<?php
	//crea_factura.php?id_venta=688972&id_cliente=6&email=rocio.merchant@live.cl&opc_mail=1&opc_imprimir=1
	$lista_ventas = $_REQUEST[id_venta];
	$id_venta = "";
	$id_cliente = $_REQUEST[id_cliente];
	//$email =$_REQUEST[email];
	$opc_email =$_REQUEST[correo];
	$opc_imprimir =$_REQUEST[opc_imprimir];
	$email=$_REQUEST[email];
	$forma_pago = $_REQUEST[forma_pago];
					 
	
	///////Variables a utilizar
	$v_id_producto=0;
	$v_monto=0;
	$v_volumen=0;
	$v_precio=0;
	$v_factor_iva = 0;
	$v_factor_ieps = 0;  //tabla de productos
	
	$v_precio_sin_ieps_local = 0;
	$v_pecio_sin_iva_local =0;
	$v_subtotales_local = 0;
	$v_ivas_local = 0;
	$v_ieps_local = 0;
	//////////////////////
	
	//echo "id_venta".$id_venta."<br>";
	//echo "id_cliente:".$id_cliente."<br>";
	//echo "email:".$email."<br>";
	//echo "opc_email:".$opc_email."<br>";
	//echo "opc_imprimir:".$opc_imprimir."<br>";
		
	$tiempo=$_REQUEST['tiempo'];
	
	session_start();

	$conexion=mysql_connect("127.0.0.1","root","123") or
		die("Problemas en la conexion");

	mysql_select_db("gaslight",$conexion) or
		die("Problemas en la selección de la base de datos");
		
	$retorno = "@retorno";
	$v_id_factura = "@v_id_factura";
   
	//creamos la listas a enviar al stored
	$id_ventas = "";
	$v_cantidades = "";
	$v_id_productos = "";
	$v_subtotales = "";
	$v_ivas = "";
	$v_precio_sin_ivas = "";
	$v_precio_sin_ieps = "";
	$v_ieps = "";
	$v_totales = "";
	$v_transacciones=""; //vacio no se facturan transacciones
	//totales de la factura
	
	$v_subtotal_factura=0;
	$v_iva_factura=0;
	$v_total_factura=0;
	
	
		
	function stored()
	{
		/////////////////////////////////////////////
		////////////STORED PROCEDURE////////////////
		///////////////////////////////////////////
		global $retorno, $v_id_factura, $id_ventas,$v_transacciones, $id_cliente,$serie,$opc_email,$email,$tipo_facturacion,
									 $v_id_productos, $v_cantidades,$v_subtotales,$v_ivas,$v_precio_sin_ivas,$v_precio_sin_ieps,
									 $v_totales, $v_ieps, $v_subtotal_factura, $v_iva_factura, $v_ieps_total_factura, $v_total_factura,$forma_pago, $lista_ventas;
		global $conexion;
		

		$sql = "Select ";
		$sql .= "serie, ";
		$sql .= "tipo ";
		$sql .= "from ";
		$sql .= "factura_folios ";
		$sql .= "where ";
		$sql .= "web = 1 ";
		$sql .= "and ";
		$sql .= "habilitado = 1 ";
		
		//echo ($sql);
		
		$registro=mysql_query($sql,$conexion) or
		die("Error:".mysql_error());
  
		if ($reg=mysql_fetch_array($registro))
		{
			$serie=$reg['serie'];
			$tipo_facturacion=$reg['tipo'];
		}	
		else
		{
			echo "No se encontraron folios para generar la factura <br> \n";
			echo "<a href=\"ticket.php?id_cliente=$_REQUEST[id_cliente]&lista_tickets=$_REQUEST[id_venta]&correo=$email\">Intentar Nuevamente</a><br><br>";
			return true;
		}
		
		//$serie ="EE";
		//$tipo_facturacion=3;
		
		$sql ="Call sp_fac_principal($retorno, '$id_ventas','$v_transacciones', $id_cliente, '$serie', '$forma_pago', $opc_email, $v_id_factura, '$tipo_facturacion', '$v_id_productos', '$v_cantidades', '', '$v_subtotales', '$v_ivas', '$v_precio_sin_ivas', '$v_precio_sin_ieps', '$v_totales', '$v_ieps', $v_subtotal_factura, $v_iva_factura, $v_ieps_total_factura, $v_total_factura, '','$email','0')";
			
		//echo $sql;
		//return;
		
		$registro=mysql_query($sql,$conexion) or
		die("Error:".mysql_error());
  
		if ($reg=mysql_fetch_array($registro))
		{
			$retorno=$reg['retorno'];
			$v_id_factura=$reg['v_id_factura'];
		}	
		else
		{
			echo "Ocurrió un error con el stored procedure de facturas <br>";
			echo "<a href=\"ticket.php?id_cliente=$_REQUEST[id_cliente]&lista_tickets=$_REQUEST[id_venta]&correo=$email\">Intentar Nuevamente</a><br><br>";
			return true;
		}
		/////marcar ventas como facturadas y esperar respuesta
		//echo "<br>regreso===>".$retorno."<br>";
		//echo "v_id_factura===>".$v_id_factura."<br>";
		header("location:valida_factura.php?id_factura=$v_id_factura&tipo=$tipo_facturacion");
		//header("location:principal.php");

	}


	function crea_lista()
	{
		global $id_venta, $conexion;
		global $v_id_producto, $v_monto, $v_volumen, $v_precio, $v_factor_iva, $v_factor_ieps;  
		
		global $id_ventas, $id_cliente, $v_id_productos, $v_cantidades,$v_subtotales,$v_ivas,$v_precio_sin_ivas,$v_precio_sin_ieps,
									 $v_totales, $v_ieps, $v_subtotal_factura, $v_iva_factura, $v_ieps_total_factura, $v_total_factura;
								 
		
		$sql = "Select ";
		$sql = $sql."id_producto, ";
		$sql = $sql."precio, ";
		$sql = $sql."monto, ";
		$sql = $sql."volumen, ";
		$sql = $sql."iva_porcentaje ";
		$sql = $sql."from ";
		$sql = $sql."ventas ";
		$sql = $sql."where ";
		$sql = $sql."id_venta =".$id_venta;
		
		//echo $sql."<br>";
		
		$registro=mysql_query($sql,$conexion) or
		die("Error en tabla ventas:".mysql_error());
  
		if ($reg=mysql_fetch_array($registro))
		{
			$v_id_producto=$reg['id_producto'];
			$v_precio=$reg['precio'];
			$v_monto=$reg['monto'];
			$v_volumen=$reg['volumen'];
			$v_factor_iva=$reg['iva_porcentaje'];
		}	
		else
		{
			echo "No se encuentra registrado el la venta:$id_venta <br>";
			return true;
		}
		
		
		//obtenemos datos de la tabla de productos
		$sql = "Select ";
		$sql = $sql."ieps ";
		$sql = $sql."from ";
		$sql = $sql."productos ";
		$sql = $sql."where ";
		$sql = $sql."id_producto =".$v_id_producto;
		
		//echo $sql."<br>";
		
		$registro=mysql_query($sql,$conexion) or
		die("Error:".mysql_error());
  
		if ($reg=mysql_fetch_array($registro))
		{
			$v_factor_ieps=$reg['ieps'];
		}	
		else
		{
			echo "No se encuentra registrado el producto: $v_id_producto <br>";
			return true;
		}
		
		/////////////////////////////////////////////Calculamos los datos
		//obtencion de cantidades
		//$v_subtotal_factura, $v_iva_factura, $v_ieps_total_factura, $v_total_factura
		
		global $v_precio_sin_ieps_local, $v_pecio_sin_iva_local, $v_subtotales_local, $v_ivas_local, $v_ieps_local;

		$v_precio_sin_ieps_local = ($v_precio - $v_factor_ieps) / (1.0 + $v_factor_iva);
		$v_precio_sin_ieps = $v_precio_sin_ieps.$v_precio_sin_ieps_local.",";
		//sel_prod_precio_sin_ieps = (sel_prod_precio_con_iva - sel_prod_factor_ieps) / (1.0 + sel_prod_factor_iva);

		//sel_prod_precio_sin_iva = sel_prod_precio_sin_ieps + sel_prod_factor_ieps;
		$v_pecio_sin_iva_local = $v_precio_sin_ieps_local + $v_factor_ieps;
		$v_precio_sin_ivas = $v_precio_sin_ivas.$v_pecio_sin_iva_local.",";
		//sel_prod_precio_sin_iva = sel_prod_precio_sin_ieps + sel_prod_factor_ieps;
		
		//sel_prod_subtotal = sel_prod_cantidad * sel_prod_precio_sin_iva;
		$v_subtotales_local = $v_volumen * $v_pecio_sin_iva_local;
		$v_subtotales = $v_subtotales.$v_subtotales_local.",";
		//sel_prod_subtotal = sel_prod_cantidad * sel_prod_precio_sin_iva;

		$v_ivas_local = $v_volumen * $v_precio_sin_ieps_local * $v_factor_iva;
		$v_ivas = $v_ivas.$v_ivas_local.",";
		//sel_prod_iva = sel_prod_cantidad * sel_prod_precio_sin_ieps * sel_prod_factor_iva;
		////////////////////////////////

		$v_ieps_local = $v_subtotales_local - $v_ivas_local / $v_factor_iva;
		$v_ieps = $v_ieps.$v_ieps_local.",";
		//concepto2.ieps = sel_prod_clase == 1 ? sel_prod_subtotal - sel_prod_iva / sel_prod_factor_iva : 0;

		$id_ventas = $id_ventas.$id_venta.",";
		$v_cantidades = $v_cantidades.$v_volumen.",";
		$v_id_productos = $v_id_productos.$v_id_producto.",";
		$v_totales = $v_totales.$v_monto.",";

		//totales de la factura
		$v_subtotal_factura = $v_subtotal_factura+$v_subtotales_local;
		$v_iva_factura = $v_iva_factura+$v_ivas_local;
		$v_total_factura = $v_total_factura+$v_monto;
		$v_ieps_total_factura = $v_ieps_total_factura+$v_ieps_local;

		//////////////////////////////////////////////
		return false;
	}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Facturación</title>
</head>
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
                           <?php
						  		$error=0;
	
								while ($error==0)
								{
									if (strpos($lista_ventas,',') ===false)
									{
										break;
									}
									
									$id_venta= substr($lista_ventas, 0, strpos( $lista_ventas,',')); //obtenemos un id_venta en v_id_venta
									
									if (crea_lista())
									{
										$error=1;
										//echo ("Ocurrió un problema al obtener datos de la factura.");
										break;
									}
									
									$lista_ventas =substr($lista_ventas,strpos($lista_ventas,',')+1, strlen($lista_ventas));//nueva lista
								}	
								
								if ($error==1)
								{
									//echo ("Ocurrió un problema al obtener datos de la factura.");
								}
								else
								{
									//echo "Se crearon listas:"." <br>";
									//echo "id_ventas:".$id_ventas." <br>";
									//echo " v_id_productos:".$v_id_productos." <br>";
									//echo " v_cantidades:".$v_cantidades." <br>";
									//echo " v_subtotales:".$v_subtotales." <br>";
									//echo " v_ivas:".$v_ivas." <br>";
									//echo " v_precio_sin_ivas:".$v_precio_sin_ivas." <br>";
									//echo " v_precio_sin_ieps:".$v_precio_sin_ieps." <br>";
									//echo " v_totales:".$v_totales." <br>";
									//echo " v_totales:".$v_ieps." <br>";
									
									//echo "v_subtotal_factura:".$v_subtotal_factura." <br>";
									//echo "v_iva_factura:".$v_iva_factura." <br>";
									//echo "v_ieps_total_factura:".$v_ieps_total_factura." <br>";
									//echo "v_total_factura:".$v_total_factura." <br>";
									
									//echo (stored());
									stored();
								}
						   ?>
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

<body>
</body>
</html>