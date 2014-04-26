<?php

	session_start();

	$conexion=mysql_connect("127.0.0.1","root","123") or
		die("Problemas en la conexion");

	mysql_select_db("gaslight",$conexion) or
		die("Problemas en la selección de la base de datos");

	
	
	$tiempo=$_REQUEST['tiempo'];
	
	function FechaFormateada($FechaStamp){
		$ano = date('Y',$FechaStamp); //<-- Año
		$mes = date('m',$FechaStamp); //<-- número de mes (01-31)
		$dia = date('d',$FechaStamp); //<-- Día del mes (1-31)
		$dialetra = date('w',$FechaStamp);  //Día de la semana(0-7)
		switch($dialetra){
		case 0: $dialetra="Domingo"; break;
		case 1: $dialetra="Lunes"; break;
		case 2: $dialetra="Martes"; break;
		case 3: $dialetra="Miércoles"; break;
		case 4: $dialetra="Jueves"; break;
		case 5: $dialetra="Viernes"; break;
		case 6: $dialetra="Sábado"; break;
		}
		switch($mes) {
		case '01': $mesletra="Enero"; break;
		case '02': $mesletra="Febrero"; break;
		case '03': $mesletra="Marzo"; break;
		case '04': $mesletra="Abril"; break;
		case '05': $mesletra="Mayo"; break;
		case '06': $mesletra="Junio"; break;
		case '07': $mesletra="Julio"; break;
		case '08': $mesletra="Agosto"; break;
		case '09': $mesletra="Septiembre"; break;
		case '10': $mesletra="Octubre"; break;
		case '11': $mesletra="Noviembre"; break;
		case '12': $mesletra="Diciembre"; break;
		}    
		return "$dialetra, $dia de $mesletra de $ano";
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Validación de RFC</title>
<link href="style/style_principal.css" rel="stylesheet" type="text/css" />
</head>

<script type="text/javascript" src="funciones.js"></script>

<body>
<table id="logos">
    <tr>
        <td rowspan="4" id="header_cualli"><img src="img/cualli.png"/></td>
        <td id="header_top"><img src="img/header.jpg"/></td>
        <td rowspan="4" id="header_pemex"><img src="img/pemex.png"/></td>
    </tr>
	<tr></tr>
    <tr>
        <td id="subtitulo">
            <div id="menu">
        	<?php $registro=mysql_query("select id_cliente from clientes where rfc='$_REQUEST[rfc]' or id_cliente='$_REQUEST[rfc]' ",$conexion) or
				die("Error:".mysql_error());
		  
				if ($reg=mysql_fetch_array($registro))
				{
					$id_cliente=$reg['id_cliente'];
					echo ("id_cliente:".$id_cliente);
					header("location:ticket.php?id_cliente=$id_cliente");
				}	
				else
				{
					echo "<br> No se encuentra registrado el RFC o Id del Cliente introducido: <br> ".$_REQUEST[rfc]."<br><br>";
					
				}
			?>
            <a href="principal.php">Intentar Nuevamente</a><br><br>
        </div>
        
        </td>
     </tr>
     <tr></tr>
     <tr>
        <td colspan="1" id="header_fecha"><?php $fecha = time();  echo FechaFormateada($fecha); ?></td>
        <td id="header_mas">Año Fiscal <?php echo($tiempo); ?></td>
        <td colspan="1" id="header_fecha"></td>
     </tr>
</table>



</body>
</html>