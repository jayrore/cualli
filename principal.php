<?php

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
<title>Facturación</title>
<link href="style/style_principal.css" rel="stylesheet" type="text/css" />
</head>

<script type="text/javascript" src="funciones.js"></script>

<body>
<form action="valida_rfc.php" method="post">
<table id="header">
</table>

<table id="logos">
    <tr>
        <td rowspan="4" id="header_cualli"><img src="img/cualli.png"/></td>
        <td id="header_top"><img src="img/header.jpg"/></td>
        <td rowspan="4" id="header_pemex"><img src="img/pemex.png"/></td>
    </tr>
	<tr></tr>
    <tr>
        <td id="subtitulo">
            Ingrese su RFC o Id Cliente:<br><br>
            <input name="rfc" type="text" class="input_login" /><br><br>
            <input name="enviar" type="submit" value="Enviar" /><br><br> 
        
        </td>
     </tr>
     <tr></tr>
     <tr>
        <td colspan="1" id="header_fecha"><?php $fecha = time();  echo FechaFormateada($fecha); ?></td>
        <td id="header_mas">Año Fiscal <?php echo($tiempo); ?></td>
        <td colspan="1" id="header_fecha"></td>
     </tr>
</table>

<table id="footer">
</table>

</form>
</body>
</html>
