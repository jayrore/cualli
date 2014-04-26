<?php

	session_start();

	$conexion=mysql_connect("127.0.0.1","root","123") or
		die("Problemas en la conexion");

	mysql_select_db("gaslight",$conexion) or
		die("Problemas en la selección de la base de datos");

	$opcion= $_REQUEST[boton];
	
	$ticket = $_REQUEST[ticket];
	
	$lista_tickets = $_REQUEST[lista_tickets];
	
	$correo_electronico = $_REQUEST[correo];
	
	$correo = 1;
	//$email=$_REQUEST[email];
	//if ($_REQUEST[correo])
		//$correo=1;
	//else
		//$correo=0;
	
	
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
	
	function obten_ticket($codigo)
	{
		echo substr($codigo, 3, 9);
		echo ("<br>");
		return substr($codigo, 3, 9);
	}
	
	function verifica_codigo($codigo)
	{
			echo "ticket:".$codigo;
			echo ("<br>");
            $linea1 = substr($codigo, 0, 3);
			echo "linea 1:".$linea1;
			echo ("<br>");
            $linea2 = substr($codigo, 3, strlen($codigo)-3); 
			echo "linea 2:".$linea2;
			echo ("<br>");
            $codigo_completo = $linea1."89".$linea2;
			echo "codigo completo:".$codigo_completo;
			echo ("<br>");
			
			//echo "respuesta:".(((int)$codigo_completo) % 97);
			//echo "respuesta:".(1028900071281871 % 97);
			//echo ("<br>"); 
			echo "respuesta:".bcmod ( $codigo_completo , '97' );
			echo ("<br>"); 
			
            //if ((((int)$codigo_completo) % 97) == 1)
			if ((bcmod ( $codigo_completo , '97' )) == 1)
			{
				echo "SI es correcto el digito verificador";
                return true;
			}
			else
			{
				echo "No es correcto el digito verificador";
				echo ("<br>");
			}

			return false;
            //return true;
     }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Validación de Ticket</title>
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

                <table id="subtitulo">
                    <tr></tr>
                    <tr>
                    <td id="subtitulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td id="subtitulo">
                        <div id="menu">
                            <?php	
								$error=0;
								//verificamos si se validara ticket largo
								$ticket_largo = 0; //por default se valida ticket con id_venta solamente
								$registro=mysql_query("select valor from parametros where id_parametro = 'ticket_largo' ",$conexion) or
								die("Error:".mysql_error());
								if ($reg=mysql_fetch_array($registro))
								{
									$ticket_largo=$reg['valor'];
								}	
								
								if ($ticket_largo ==1)
								{
									if (strlen($ticket) <14 )
									{
										$error=1;
										$_REQUEST[monto]='';  //para que no valide nada mas
										$ticket='';
										echo ("Ticket inválido: Faltan caracteres");
										echo ("<br>");
									}
									else if (!verifica_codigo($ticket))
									{
										$error=1;
										$_REQUEST[monto]='';  //para que no valide nada mas
										$ticket='';
										echo ("Ticket inválido: Ticket incorrecto");
										echo ("<br>");
									}
									else
									{
										echo ("Entro aqui");
										echo ("<br>");
										$ticket = obten_ticket($ticket);
									}
								}
								///fin de verificacion
								
								if ($ticket != '') //verificamos que no se encuentre en la lista
								{
									$lista_ventas = $lista_tickets;
									while ($error==0)
									{
										if (strpos($lista_ventas,',') ===false) //no hay mas
										{
											break;
										}
										
										$id_venta= substr($lista_ventas, 0, strpos( $lista_ventas,',')); //obtenemos un id_venta en v_id_venta
										
										if ($ticket == $id_venta)
										{
											echo ("El Ticket ya se encuentra en la lista");
											$error=1;
											$_REQUEST[monto]='';  //para que no valide nada mas
											break;
											
										}
										
										$lista_ventas =substr($lista_ventas,strpos($lista_ventas,',')+1, strlen($lista_ventas));//nueva lista
									}	
								}
								
								if ($_REQUEST[monto]=='' && $ticket !='')
								{
									if ($error==0)
										echo ("Es necesario Introducir un Monto");
								}
								else
								{
									if ($opcion=="Enviar")
									{
										if ($lista_tickets=="" && $ticket=="" )
										{
											
										}
										else
										{
											if ($ticket=="")
											{
												//echo ("SIMULACRO DE ENVIO: ".$lista_tickets);
												if ($correo_electronico != "")
												{
													header("location:factura.php?id_venta=$lista_tickets&id_cliente=$_REQUEST[id_cliente]&correo=$correo&email=$correo_electronico&forma_pago=$_REQUEST[forma_pago]");
												}
												else
												{
													echo "Es necesario Capturar unn correo electronico";
												}
												//header("location:factura.php?id_venta=$lista_tickets&id_cliente=$_REQUEST[id_cliente]&correo=$correo");
											}
											else
											{
												if (!is_numeric($ticket) ) //deber ser numerico
												{
													echo ("Ticket inválido 2");
													echo ("<br>");
												}
												else
												{
													$registro=mysql_query("select id_venta from ventas where id_venta='$ticket' and factura=0 and monto=$_REQUEST[monto] and YEAR(fecha_venta)= YEAR(now()) and MONTH(fecha_venta)= MONTH(now()) ",$conexion) or
													die("Error:".mysql_error());
											  		
													if ($reg=mysql_fetch_array($registro))
													{
														$id_venta=$reg['id_venta'];
														echo ("id_venta=$id_venta");
														echo ("<br>");
														echo ("ticket=$ticket");
														echo ("<br>");
														
														$lista_tickets= $lista_tickets.$ticket.",";
														//echo ("SIMULACRO DE ENVIO");
														if ($correo_electronico != "")
														{
															header("location:factura.php?id_venta=$lista_tickets&id_cliente=$_REQUEST[id_cliente]&correo=$correo&email=$correo_electronico&forma_pago=$_REQUEST[forma_pago]");
														}
														else
														{
															echo "Es necesario Capturar un correo electronico";
														}
														
													}	
													else
													{
														echo "No es valido o ha expirado el ticket introducido: $ticket";
														
													}
												}
											}
										}
									}
									else
										// ADD
									{						
										if ($ticket=="")
										{
											if ($error==0)
											{
												echo ("Ticket es inválido 1");
												echo ("<br>");
											}
										}
										else if (!is_numeric($ticket) ) //deber ser numerico
										{
											echo ("Ticket inválido 2");
											echo ("<br>");
										}
										
										else
										{
											//validamos el ticket
											//if (verifica_codigo($ticket))
											//{
											//	echo ("Ticket inválido 4");
											//	echo ("<br>");
											//}
											//else
											//{
												//$ticket = obten_ticket($ticket);
											    $msg = array('found' => false);
												$registro=mysql_query("select id_venta from ventas where id_venta='$ticket' and factura=0 and monto=$_REQUEST[monto] and YEAR(fecha_venta)= YEAR(now()) and MONTH(fecha_venta)= MONTH(now()) ",$conexion) or
												die("Error:".mysql_error());
										  		//echo "select id_venta from ventas where id_venta='$ticket' and factura=0 and monto=$_REQUEST[monto] and YEAR(fecha_venta)= YEAR(now()) and MONTH(fecha_venta)= MONTH(now()) ";
												if ($reg=mysql_fetch_array($registro))
												{
												
													//$lista_tickets = $lista_tickets.$ticket.",";
													//header("location:ticket.php?lista_tickets=$lista_tickets&id_cliente=$_REQUEST[id_cliente]&correo=$correo_electronico");
													$msg['found'] = true;
													echo json_encode($msg, true);

												}	
												else
												{
													//echo "No es valido o ha expirado el ticket introducido 2:$ticket";
													echo json_encode($msg, true);	
												}
											//}
										}
									}
								}
                            ?>
                            <br /> <br />
                            <a href="ticket.php<?php echo "?id_cliente=$_REQUEST[id_cliente]&lista_tickets=$lista_tickets&correo=$correo_electronico"; ?>">Intentar Nuevamente</a><br><br>
                        </div>
                        </td>
                     </tr>
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






</body>
</html><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>
</body>
</html>