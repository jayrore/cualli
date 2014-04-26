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


	$id_factura = $_REQUEST['id_factura'];
	
	$conexion=mysql_connect("127.0.0.1","root","123") or
		die("Problemas en la conexion");

	mysql_select_db("gaslight",$conexion) or
		die("Problemas en la selección de la base de datos");
		
	$estatus=0;
	$archivo_pdf="";
	$archivo_xml="";
	$descripcion_estatus="";
	
	$sql ="select estatus_proceso, archivo_pdf, archivo_xml, descripcion_estatus from facturas where id_factura=".$id_factura;

    	
		$registro=mysql_query($sql,$conexion) or
		die("Error:".mysql_error());
	
		if ($reg=mysql_fetch_array($registro))
		{
			$estatus=$reg['estatus_proceso'];
			$archivo_pdf=$reg['archivo_pdf'];
			$archivo_xml=$reg['archivo_xml'];
			$descripcion_estatus=$reg['descripcion_estatus'];
		}	
		else
		{
			echo "Ocurrió un error al validar la información de la factura ";
		}
		
		


?>

<?php
	$id_factura = $_REQUEST[id_factura];
	//$url_actual = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	$url_actual = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	// echo "<b>$url_actual</b>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="style/style_principal.css" rel="stylesheet" type="text/css" />
<head>
		<?php 
			if ($estatus ==0)
			{
				echo "<meta http-equiv=\"refresh\" content=\"5;url=\"".$url_actual."\" /> ";
			}
		?>
		<title>Facturacion</title>
</head>
    <body>
    
    
    
<table id="logos">
    <tr>
        <td rowspan="3" id="header_cualli"><img src="img/cualli.png"/></td>
        <td id="header_top"><img src="img/header.jpg"/></td>
        <td rowspan="3" id="header_pemex"><img src="img/pemex.png"/></td>
    </tr>
	<tr></tr>
    <tr>
        <td id="subtitulo">
            <div id="menu">
        	
				<?php  
                //proceso();
                
                if ($estatus ==0)
                {
                    //echo "Esperando que se cree factura..... ";
                }
                
                if ($estatus ==0)
                {
                        //echo "Procesando factura: ".$id_factura;
                        
                        echo "<br><br>";
                        echo("<table id=\"info_cliente\">\n");
                        
                        echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\">Factura   </td>\n");	
                        echo("<td id=\"info_cliente_n\">$id_factura</td>\n");	
                        echo("</tr>\n");
                        
                        echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\">Estatus  </td>\n");	
                        echo("<td id=\"info_cliente_tl\">Procesando factura</td>\n");	
                        echo("</tr>\n");
                                    
               
                                    
                        echo("</table>\n");
            
                }
                else
                {
                    if ($estatus==1)
                    {
                       
                        echo "<br><br>";
                        echo("<table id=\"info_cliente\">\n");
                        
                        echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\">Factura</td>\n");	
                        echo("<td id=\"info_cliente_n\">$id_factura</td>\n");	
                        echo("</tr>\n");
                        
                        echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\">Estatus</td>\n");	
                        echo("<td id=\"info_cliente_tl\">PROCESADO CON EXITO</td>\n");	
                        echo("</tr>\n");
                                    
                        echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\"></td>\n");	
						if ($_REQUEST[tipo] ==3)
                        	echo("<td id=\"info_cliente_n\"><a href=\"impresion_factura_CFDi.php?id_factura=$id_factura&ventas=".$_REQUEST[ventas]."\" target=\"_blank\">Impresion</a></td>\n");	
						else
							echo("<td id=\"info_cliente_n\"><a href=\"impresion_factura_CFD.php?id_factura=$id_factura&ventas=".$_REQUEST[ventas]."\" target=\"_blank\">Impresion</a></td>\n");	
                        
                        echo("</tr>\n");
						
						echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\"></td>\n");	
                        echo("<td id=\"info_cliente_n\"><a href=\"principal.php\">Regresar</a></td>\n");	
                        
                        echo("</tr>\n");
                                    
                        echo("</table>\n");
            
                    }
                    else
                    {
                      
                        echo "<br><br>";
                        echo("<table id=\"info_cliente\">\n");
                        
                        echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\">Factura</td>\n");	
                        echo("<td id=\"info_cliente_n\">$id_factura</td>\n");	
                        echo("</tr>\n");
                        
                        echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\">Estatus</td>\n");	
                        echo("<td id=\"info_cliente_tl\">Error: $descripcion_estatus</td>\n");	
                        echo("</tr>\n");
                                    
						echo("<tr>\n");			
                        echo("<td id=\"info_cliente_h\"></td>\n");	
                        echo("<td id=\"info_cliente_n\"><a href=\"principal.php\">Regresar</a></td>\n");	
                        
                        echo("</tr>\n");
                                    
                        echo("</table>\n");
            
                    }
                    
                }
            ?>
                 <br />
                 
            <?php 
                if ($estatus ==0)
                {
                    echo "<script>";
                    echo "	var seconds = 5;";
                    echo "	setInterval";
                    echo "  (";
                    echo "	function()";
                    echo "	{";
                    echo "	  document.getElementById('seconds').innerHTML = --seconds;";
                    echo "	}, 1000 );";
                    echo "</script>";
                 }
            ?>
			
        	</div>
        </td>
     </tr>
     <tr></tr>
     <tr>
        <td colspan= "1" id="header_fecha"><?php $fecha = time();  echo FechaFormateada($fecha); ?></td>
        <td id="header_mas">Año Fiscal <?php echo($tiempo); ?></td>
        <td id="header_fecha"></td>
     </tr>
</table>
    
       
	</body>
</html>
