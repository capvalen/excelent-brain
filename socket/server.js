//------------- EJEMPLO SIN EXPRESS y ES JS (modulos)
// Agregar al package.json el tipo: "type": "module"
import http from 'http';
import { Server } from 'socket.io';
import moment from 'moment'

const options = {
	cors: true,
	origin: ['http://localhost:3000']
}

// Crea un servidor HTTP básico
const server = http.createServer((req, res) => {
	res.writeHead(200, { 'Content-Type': 'text/html' });
	res.end('<h1>Servidor Socket.IO sin Express</h1>');
});

const hoy = moment().format('YYYY-MM-DD')


// Crea una instancia de Socket.IO y la adjunta al servidor HTTP
const io = new Server(server, options);



async function busquedaApi (){
	const response = await fetch('http://127.0.0.1:8000/api/listarAvisosAhora/'+hoy);
	const result = await response.json();
	if(result.avisos.length>=0) {console.log('Mensajes para enviar hay', result.avisos.length, 'a las', moment().format('HH:mm:ss a') ); io.emit('update', result.avisos);}
	else console.log('No hay eventos en el bloque de 5 minutos a las', moment().format('HH:mm:ss a'));
}

io.on('connection', (socket) => {
	console.log('Un cliente se ha conectado con id: '+socket.id, 'a las', moment().format('HH:mm a') );

	socket.on('mensaje', (variable) => { console.log(variable); });
	socket.on('pedirAvisosDeAhora', () => busquedaApi() );

	//Enviar mensaje
	//socket.emit('respuesta','Bienvenido cliente '+socket.id)

});
setInterval(async () => { busquedaApi()}, 60000*5); //1min *5

busquedaApi()

// Escucha en el puerto 3000
server.listen(3001, () => {
	console.log('Servidor escuchando en el puerto 3001 start at:', moment().format('HH:mm a'));
});

