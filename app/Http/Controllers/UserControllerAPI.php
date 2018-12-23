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

class UserControllerAPI extends Controller
{
    protected $verifyEmail;
    public function __construct(VerifyController $verifyEmail)
    {
        $this->verifyEmail = $verifyEmail;
    }

    public function index(Request $request)
    {
        return UserResource::collection(User::paginate(5));
        

        /*Caso não se pretenda fazer uso de Eloquent API Resources (https://laravel.com/docs/5.5/eloquent-resources), é possível implementar com esta abordagem:
        if ($request->has('page')) {
            return User::with('department')->paginate(5);;
        } else {
            return User::with('department')->get();;
        }*/
    }

    public function show($id)
    {
        return new UserResource(User::find($id));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'username'=> 'required|string|max:50|unique:users,username',
            'email' => 'required|email|unique:users,email',
            //'photo_url' => 'nullable',
            //'password' => 'required|same:password',
            //'password_confirmation' => 'required|same:password',   
        ]);
        $user = new User();
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

            //$user->fill($request->all());
            $user->fill($request->all() + ['remember_token' => str_random(10)]);

        }

        $user->password = Hash::make($user->password);

        $user->save();

        $user->sendVerificationEmail();

        return response()->json(new UserResource($user), 201);
    }

    public function update(Request $request, $id)
    {
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
            //dd($request->all());
            $user->fill($request->all());
        }
        //dd(new UserResource($user));
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }

    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

    public function myProfile(Request $request)
    {
        return new UserResource($request->user());
    }

    // public function setPassword(Request $request)
    // {
    //     $user = Auth::user();

    //     $curPassword = $request->input['curPassword'];
    //     $newPassword = $request->input['newPassword'];

    //     if (Hash::check($curPassword, $user->password)) {
    //         $user_id = $user->id;
    //         $obj_user = User::find($user_id)->first();
    //         $obj_user->password = Hash::make($newPassword);
    //         $obj_user->save();

    //         return response()->json(["result"=>true]);
    //     }
    //     else
    //     {
    //         return response()->json(["result"=>false]);
    //     }
    // }

    public function setNewPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ]);
        
        $prevUrl = $request->headers->get('referer');
        
        $exploded = explode('/', $prevUrl);
        
        $remember_token = $exploded[4];

        $user = User::where('remember_token', $remember_token)->firstOrFail();


        //dd($request->all());

        $this->verifyEmail->verify($remember_token);
        
        //$data = $request->validated();
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user->fill($data);
        //dd($user);
        $user->save();
        //return new UserResource($user);
        return redirect()->route('mainPage');
    }
    public function getManagers(Request $request) {
        $managers = DB::table('users')
            ->where('users.type', '=', 'manager')
            ->get();
        return $managers;
    }

    public function startEndShift(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
        ]);
        $user = User::findOrFail($id);
        $user->fill($request->all());
        return new UserResource($user);
    }
}
