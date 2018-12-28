<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;
use App\User;
use App\StoreUserRequest;
use Hash;
use App\Http\Controllers\VerifyController;
use Carbon\Carbon;

class UserControllerAPI extends Controller {
    protected $verifyEmail;
    public function __construct(VerifyController $verifyEmail) {
        $this->verifyEmail = $verifyEmail;
    }

    public function index(Request $request) {
        return (UserResource::collection(User::orderBy('created_at', 'desc')->withTrashed()->paginate(5)))->response()->setStatusCode(200);
    }

    public function show($id) {
        return new UserResource(User::find($id));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'username'=> 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'type' => 'required|in:cook,waiter,cashier,manager'
        ]);
        $user = new User();
        $user->fill($request->all() + ['remember_token' => str_random(10)]);
        $user->password = Hash::make($user->password);
        $user->save();
        $user->sendVerificationEmail();
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'username'=> 'required|string|max:50|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'photo_url' => 'nullable',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ]);
        $user = User::findOrFail($id);
        $request->merge(['password' => Hash::make($request->get('password'))]);
        if (strpos($request->input('photo_url'), 'data:image/') !== false) {
            $exploded = explode(',', $request->photo_url);
            $decoded = base64_decode($exploded[1]);
            if (str_contains($exploded[0], 'jpeg') || str_contains($exploded[0], 'jpg')) {
                $extention = 'jpg';
            } else {
                $extention = 'png';
            }
            $fileName = str_random().'.'.$extention;
            $path = storage_path('app/public/profiles/').$fileName;
            file_put_contents($path, $decoded);
            $user->update($request->except('photo_url') + [
                'photo_url' => $fileName,
            ]);
        } else {
            $user->fill($request->all());
        }
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(200);
    }

    public function updateAsManager(Request $request, $id) {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'username'=> 'required|string|max:50|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'type' => 'required|in:cook,waiter,cashier,manager'
        ]);
        $user = User::findOrFail($id);
        $user->fill($request->all());
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(200);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return (new UserResource($user))->response()->setStatusCode(204);
    }

    public function emailAvailable(Request $request) {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0, 200);
    }

    public function myProfile(Request $request) {
        return (new UserResource($request->user()))->response()->setStatusCode(200);
    }

    public function setNewPassword(Request $request) {
        $request->validate([
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ]);
        
        $prevUrl = $request->headers->get('referer');
        $exploded = explode('/', $prevUrl);
        $remember_token = $exploded[4];
        $user = User::where('remember_token', $remember_token)->firstOrFail();
        $this->verifyEmail->verify($remember_token);
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user->fill($data);
        $user->save();
        return redirect()->route('mainPage')->setStatusCode(200);
    }

    public function getManagers(Request $request) {
        $managers = DB::table('users')
            ->where('users.type', '=', 'manager')
            ->get();
        return $managers;
    }

    public function startEndShift(Request $request, $id) {
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
        ]);
        $user = User::findOrFail($id);
        $user->fill($request->all());
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(200);
    }

    public function getBlocked(Request $request, $status) {    
        return (UserResource::collection(User::where('blocked', '=', $status)->orderBy('created_at', 'desc')->withTrashed()->paginate(5)))->response()->setStatusCode(200);
    }

    public function getDeleted(Request $request, $status) {    
        return $status ? 
            (UserResource::collection(User::where('deleted_at', '!=', NULL)->orderBy('created_at', 'desc')->withTrashed()->paginate(5)))->response()->setStatusCode(200): 
            (UserResource::collection(User::where('deleted_at', '=', NULL)->orderBy('created_at', 'desc')->withTrashed()->paginate(5)))->response()->setStatusCode(200);
    }

    public function block(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->blocked = 1;
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(200);
    }

    public function unblock(Request $request, $id) {    
        $user = User::findOrFail($id);
        $user->blocked = 0;
        $user->save();
        return (new UserResource($user))->response()->setStatusCode(200);
    }
}
