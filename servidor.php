<?php
error_reporting(E_ALL);//definir el tiempo de ejecucion de los scripts

/* Permitir que el script se quede esperando conexiones */
set_time_limit(0);

/* activar salida para ver lo que stamos recibiendo */
ob_implicit_flush();

$address = '127.0.0.1'; /*ip*/
$port = 10000;/*puerto*/

/*Creacion del socket TCP y imprimir error en caso falle.*/
if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}
//Vincular nombre al socket
if (socket_bind($sock, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}
//EScuchar la conexion del socket
if (socket_listen($sock, 5) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}
//Una vez creado el socket( socket_create() ), se acepta 
do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        break;
    }
    /* Enviar instrucciones */
    //EScribir en el socket desde el buffer dado.
    $msg = "\nBienvenido al Servidor. \n";
    socket_write($msgsock, $msg, strlen($msg));

    do {
	    /*Para leer el socket el mensaje se guarda en buf*/
        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
        }
        if (!$buf = trim($buf)) {
            continue;
	}
	/*if ($buf == 'shutdown'){
		socket_close($msgsock);
		break 2;
	}*/
        $talkback = "SERVIDOR: Cliente usted dijo '$buf'.\n";
        socket_write($msgsock, $talkback, strlen($talkback));
        echo "$buf\n";
    } while (true);
    socket_close($msgsock);
} while (true);
//Cerramdo socket
socket_close($sock);
?>
