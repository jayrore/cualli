<?php

	$tiempo=$_REQUEST['tiempo'];
	
	$conexion=mysql_connect("127.0.0.1","root","123") or
		die("Problemas en la conexion");

	mysql_select_db("gaslight",$conexion) or
		die("Problemas en la selección de la base de datos");
	
	//obtenemos el email
	$sql = "select email from clientes where id_cliente = ".$_REQUEST[id_cliente];
	
	if ($_REQUEST[correo] == '')
	{
		$registro=mysql_query($sql,$conexion) or
			die("Error:".mysql_error());
	  
		if ($reg=mysql_fetch_array($registro))
		{
			$v_email=$reg['email'];
		}	
		else
		{
			$v_email="";
		}
	}
	else
	{
		$v_email=$_REQUEST[correo];
	}
	
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
<title>Ticket</title>
<link href="style/style_principal.css" rel="stylesheet" type="text/css" />
</head>

<script type="text/javascript" src="funciones.js"></script>

<body>
<form action="valida_ticket.php" method="post">

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
                <table id="subtitulo">
                    <tr></tr>
                    <tr>
                        <td id="subtitulo">
                            <?php echo $_REQUEST[boton]; ?>
                        </td>
                        <td id="subtitulo">
                           
                                Ingrese el número de ticket:<br>
                                <input name="ticket" type="text" class="input_login" /><br><br>
                                Ingrese el monto del ticket:<br>
                                <input name="monto" type="text" class="input_login" /><br><br>
                                Método de Pago:<br>
                                <select name="forma_pago">
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Transferencia electronica">Transferencia electronica</option>
                                    <option value="Tarjeta de credito">Tarjeta de credito</option>
                                    <option value="Tarjeta de debito">Tarjeta de debito</option>
                                    <option value="Tarjeta de servicio">Tarjeta de servicio</option>
                                    <option value="Monedero electronico">Monedero electronico</option>
                                    <option value="Cheques">Cheques</option>
                                    <option value="No identificado">No identificado</option>
                                </select> <br><br>
                                Correo Electrónico:<br>
                                <input name="correo" type="text" class="input_login" value="<?php echo $v_email ?>" size="40"/><br><br>
                                <input name="boton" type="submit" id="Enviar" value="Enviar" />
                                <input name="boton" type="submit" id="Añadir" value="Añadir" /><br><br> 
                                <input type="hidden" name="id_cliente" value="<?php echo $_REQUEST[id_cliente]; ?>" >
                            
                        </td>
                        <td id="subtitulo"><br> <br>
                            <textarea readonly name="lista_tickets" id="lista_tickets" rows="5" cols="20" ><?php echo $_REQUEST[lista_tickets]; ?></textarea> 
                            <br> <br> 
                            <input type='button' name='BReset' onClick='lista_tickets.value=""' value='Vaciar' id='BReset'>
                            <br> <br> 
                        </td>
                            
                    </tr>
                    <tr></tr>
                </table>            
        	
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






</form>
</body>
</html>
