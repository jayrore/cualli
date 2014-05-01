<?php
	session_start();
	
	$msg = array("found"=> false);

/*	$conexion=mysql_connect("127.0.0.1","root","123");
	if (!$conexion) {
    	echo "hubo un problema en la conexion";
    	exit;
	}

	$bd = mysql_select_db("gaslight",$conexion);
	if (!$bd) {
    	echo "no se puedo conectar a la base de datos";
    	exit;
    }	

	$query=mysql_query("select id_cliente from clientes where rfc='$_REQUEST[rfc]' or id_cliente='$_REQUEST[rfc]' ",$conexion);
	if (!$query) {
    	echo "no se puedo conectar a la base de datos";
    	exit;
    }	
	if ($row=mysql_fetch_array($query))
		{			
		$msg['id_cliente'] = $row['id_cliente'];
		$msg['found'] = true;
		}*/	

		
		$msg['id_cliente'] = "dfkjhfjshfk";
		$msg['found'] = true;
$jsonMsg = json_encode($msg, true);
echo $jsonMsg;
