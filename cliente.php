<?php
error_reporting(E_ALL);

echo "TCP/IP Coneccion\n";

/* Obtener el puerto */
$port = 10000; /*Cambie a port de service_port*/

/* Obtener la dirección IP para el host objetivo. */
$address = gethostbyname('127.0.0.1');

/* Crear un socket TCP/IP. */
//param: dominio, tipo, protocolo
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

//Si da errores para la creacion del socket
if ($socket === false) {
	//socket_sterror() devuelve un error 
    echo "socket_create() falló: razón: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Intentando conectar a '$address' en el puerto '$port'...";
//Iniciamos la conexion de un socket
$result = socket_connect($socket, $address, $port);
//Si no se pudo conectar imprimir fallo result, caso contrario OK
if ($result === false) {
    echo "socket_connect() falló.\nRazón: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

$in = "Soy el cliente y te envio esto\r\n";
$in .= "Hola\n\r\n";
$out = '';

//Escribimos en el socket con socket_write()
echo "Enviando petición HTTP HEAD ...";
socket_write($socket, $in, strlen($in));
echo "OK.\n";

//Leemos un maximo de longitud de bytes desde un sockets
echo "Leyendo respuesta:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Cerrando socket...";
socket_close($socket);
echo "OK.\n\n";
?>

