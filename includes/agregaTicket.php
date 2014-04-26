<?php 
var_dump($_POST);
exit;
$conexion=mysql_connect("127.0.0.1","root","123");
	if (!$conexion) {
    	echo "hubo un problema en la conexion";
    	exit;
	}

$bd = mysql_select_db("gaslight",$conexion);
	if (!$bd) {
    	echo "no se puedo conectar a la base de datos";
    	exit;
    }
$msg = array('found' => false);

$registro=mysql_query("select id_venta from ventas where id_venta='".$_POST['ticket']."' and factura=0 and monto=".$_POST['monto']." and YEAR(fecha_venta)= YEAR(now()) and MONTH(fecha_venta)= MONTH(now()) ",$conexion);

if ($reg=mysql_fetch_array($registro))
	{
	$msg['found'] = true;
	echo json_encode($msg, true);
	}	
	else
	{
	echo json_encode($msg, true);	
	}

?>