/*jshint esversion: 6 */

var app = require('http').createServer();

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base) 

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor, 
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require('socket.io')(app);

var LoggedUsers = require('./loggedusers.js');

app.listen(8080, function(){
    console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

// loggedUsers = the list (map) of logged users. 
// Each list element has the information about the user and the socket id
// Check loggedusers.js file

let loggedUsers = new LoggedUsers();

io.on('connection', function (socket) {
    console.log('client has connected (socket ID = '+socket.id+')' );
	// login
	socket.on('user_enter', function (user) {
		if (user !== undefined && user !== null) {
            socket.join('type_' + user.type);
            if (user.shift_active)
                loggedUsers.addUserInfo(user, socket.id);
        }
	});
	// logout
	socket.on('user_exit', function (user) {
		if (user !== undefined && user !== null) {
			socket.leave('type_' + user.type);            
			loggedUsers.removeUserInfoByID(user.id);
		}
	});
	// --- template start
	socket.on('msg_from_client_type_manager', function (msg, userInfo) {
		if (userInfo !== undefined) {
			io.sockets.to('type_manager').emit('msg_from_server_type', userInfo.name,  msg);
		}
	});
	socket.on('msg_from_client_type_waiter', function (msg, userInfo) {
		if (userInfo !== undefined) {
			io.sockets.to('type_waiter').emit('msg_from_server_type', userInfo.name,  msg);
		}
    });
    socket.on('msg_from_client_type_cook', function (msg, userInfo) {
		if (userInfo !== undefined) {
			io.sockets.to('type_cook').emit('msg_from_server_type', userInfo.name,  msg);
		}
    });
    socket.on('msg_from_client_type_cashier', function (msg, userInfo) {
		if (userInfo !== undefined) {
			io.sockets.to('type_cashier').emit('msg_from_server_type', userInfo.name,  msg);
		}
    });
	// --- template end
    socket.on('msg_from_client_type_waiter', function (msg) {
		io.sockets.to('type_cook').emit('msg_from_server_type_cook',  msg);
	});
	socket.on('message_responsable_waiter', function (msg, sourceUser, destUser_id) {
		let userInfo = loggedUsers.userInfoByID(destUser_id);
		let socket_id = userInfo !== undefined ? userInfo.socketID : null;
		if (socket_id === null) {
			socket.emit('responsableWaiterMessage_unavailable', destUser_id);
		} else {
			io.to(socket_id).emit('responsableWaiterMessage', msg, sourceUser);
			socket.emit('responsableWaiterMessage_sent', msg);
		}
	});
	socket.on('order_assignment_update', data => {
		io.sockets.emit('refresh_orders_assignment_update', data);
		io.sockets.emit('refresh_waiter_confirmed_orders');
    });

	socket.on('order_prepared', data => {
		io.sockets.emit('refresh_prepared_orders', data);
		io.sockets.emit('refresh_waiter_prepared_orders');
	});

	socket.on('order_delivered', () => {
		io.sockets.emit('refresh_waiter_prepared_orders');
		io.sockets.emit('refresh_invoices');
	});
	
	socket.on('invoice_paid', () => {
        io.sockets.emit('refresh_invoices');
	});

	socket.on('invoice_not_paid', () => {
        io.sockets.emit('refresh_invoices');
	});

	socket.on('meal_terminated', () => {
		io.sockets.emit('refresh_invoices');
		let msg = "New Invoice!"
		io.sockets.to('type_cashier').emit('new_invoice',  msg)
		io.sockets.to('type_manager').emit('new_invoice_manager', msg)
	});
	socket.on('meal_paid', () => {
		let msg = "Invoice Paid!"
		io.sockets.to('type_manager').emit('invoice_paid_manager', msg)
	});
	socket.on('order_confirmed', () => {
		io.sockets.emit('refresh_waiter_confirmed_orders');
		io.sockets.emit('refresh_cook_orders');
	});
});
