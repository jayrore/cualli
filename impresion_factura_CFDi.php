<?php	
	
	session_start();
	
	require('fpdf.php');
	include "phpqrcode/qrlib.php";    
	
	function datos_qr($conexion)
	{
		$info="ERROR";
		$sql = "select e_rfc, r_rfc, total, uuid from facturas where id_factura=".$_REQUEST[id_factura];
		$registro=mysql_query($sql,$conexion) or
		die("Error:".mysql_error());
						
		while($reg=mysql_fetch_array($registro))
		{
			$info="?re=".$reg["e_rfc"]."&rr=".$reg["r_rfc"]."&tt=".$reg["total"]."&id=".$reg["uuid"];
		}
		
		return $info;
	}
	
	function listaventas($conexion)
	{
		$lista="";
		$sql = "select id_venta from facturas_ventas where id_factura=".$_REQUEST[id_factura];
		$registro=mysql_query($sql,$conexion) or
		die("Error:".mysql_error());
						
		while($reg=mysql_fetch_array($registro))
		{
			$lista= $lista.$reg["id_venta"]." ";
		}
		
		return $lista;
	}
	
	
	class PDF extends FPDF
	{
		
		function reporte($conexion)
		{
			$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
			$filename = $PNG_TEMP_DIR.$_REQUEST[id_factura].'.png';
						
			$sql ="select  ";
			$sql .="a.e_regimen_fiscal e_regimen_fiscal, ";
			$sql .="a.r_cuenta r_cuenta,  ";
			$sql .="a.codigo_barras codigo_barras,  ";
			$sql .="ifnull(a.codigo_barras_size,0) codigo_barras_size,  ";
			$sql .="a.serie serie,  ";
			$sql .="a.folio folio, ";
			$sql .="a.fecha_expedicion fecha_expedicion,  ";
			$sql .="a.sello sello,  ";
			$sql .="a.aprobacion_año aprobacion_ano, ";
			$sql .="a.aprobacion_numero aprobacion_numero,  ";
			$sql .="a.certificado_numero certificado_numero, ";
			$sql .="a.pago_forma pago_forma, ";
			$sql .="a.pago_metodo pago_metodo, ";
			$sql .="a.comprobante_tipo comprobante_tipo, ";
			$sql .="a.cliente_pemex cliente_pemex, ";
			$sql .="a.id_emisor id_emisor, ";
			$sql .="a.e_razon_social e_razon_social, ";
			$sql .="a.e_rfc e_rfc, ";
			$sql .="a.id_receptor id_receptor,  ";
			$sql .="a.r_razon_social r_razon_social, ";
			$sql .="a.r_rfc r_rfc, ";
			$sql .="a.sub_total sub_total,  ";
			$sql .="a.total total,  ";
			$sql .="a.total_letra total_letra, ";
			$sql .="a.impuestos_retenidos impuestos_retenidos,  ";
			$sql .="a.impuestos_trasladados impuestos_trasladados, ";
			$sql .="a.cadena_original cadena_original,  ";
			$sql .="a.cadena_timbre cadena_timbre, ";
			$sql .="b.id_detalle id_detalle, ";
			$sql .="b.cantidad cantidad,  ";
			$sql .="b.unidad unidad,  ";
			$sql .="b.descripcion descripcion, ";
			$sql .="b.valor_unitario valor_unitario, ";
			$sql .="b.monto monto, ";
			$sql .="b.codigo_pemex codigo_pemex, ";
			$sql .="c.calle e_calle,  ";
			$sql .="c.numero_exterior e_numero_exterior, ";
			$sql .="c.numero_interior e_numero_interior, ";
			$sql .="c.colonia e_colonia, ";
			$sql .="c.municipio e_municipio,  ";
			$sql .="c.estado e_estado,  ";
			$sql .="c.pais e_pais, ";
			$sql .="c.codigo_postal e_codigo_postal,  ";
			$sql .="c.telefono e_telefono,  ";
			$sql .="f.calle ep_calle,  ";
			$sql .="f.numero_exterior ep_numero_exterior,  ";
			$sql .="f.numero_interior ep_numero_interior, ";
			$sql .="f.colonia ep_colonia,  ";
			$sql .="f.municipio ep_municipio, ";
			$sql .="f.estado ep_estado, ";
			$sql .="f.pais ep_pais,  ";
			$sql .="f.codigo_postal ep_codigo_postal,  ";
			$sql .="f.telefono ep_telefono,  ";
			$sql .="d.calle r_calle,  ";
			$sql .="d.numero_exterior r_numero_exterior, ";
			$sql .="d.numero_interior r_numero_interior, ";
			$sql .="d.colonia r_colonia,  ";
			$sql .="d.municipio r_municipio,  ";
			$sql .="d.estado r_estado,  ";
			$sql .="d.pais r_pais, ";
			$sql .="d.codigo_postal r_codigo_postal,  ";
			$sql .="ifnull(a.impuestos_trasladados-a.iva,0) impuesto_ieps, ";
			$sql .="a.iva iva, ";
			$sql .="a.certificado_sat,  ";
			$sql .="a.fecha_timbrado,  ";
			$sql .="a.uuid,  ";
			$sql .="a.sello_sat, ";
			$sql .="if(ifnull(a.comprobante_tipo,'ingreso')='egreso','Nota de Credito','') as nota_credito,  ";
			$sql .="a.observaciones ";
			$sql .="from (((( facturas as a inner join  facturas_detalle as b on a.id_factura=b.id_factura ) inner join   ";
			$sql .="factura_direccion as c on a.id_factura=c.id_factura and c.id_tipo=1 ) inner join factura_direccion as d on a.id_factura=d.id_factura and d.id_tipo=2 )  ";
			$sql .="inner join factura_direccion as f on a.id_factura=f.id_factura and f.id_tipo=11 ) where a.id_factura=".$_REQUEST[id_factura];
		
			
			$registro=mysql_query($sql,$conexion) or
			die("Error:".mysql_error());
			
			$cont =0;
						
			while($reg=mysql_fetch_array($registro))
			{
				if ($cont==0) ///solo se imprime la primera vez
				{
					//////////////////////////////HEADER//////////////////////////////////
					 $cont=1;
					 $sub_total=$reg["sub_total"];
					 $iva=$reg["iva"];
					 $total_letra=$reg["total_letra"];
					 $total=$reg["total"];
					 $observaciones=$reg["observaciones"];
					 $certificado_numero=$reg["certificado_numero"];
					 $certificado_sat=$reg["certificado_sat"];
					 $fecha_timbrado= $reg["fecha_timbrado"];
					 $uuid=$reg["uuid"];
					 $fecha_timbrado= $reg["fecha_timbrado"];
					 $sello=$reg["sello"];
					 $sello_sat=$reg["sello_sat"];
					 $cadena_timbre=$reg["cadena_timbre"];
					 
					//Colores, ancho de línea y fuente en negrita
					 $this->SetTextColor(0);
					 $this->SetDrawColor(128,0,0);
					 $this->SetLineWidth(.3);
					 //Colores Titulo
					 $this->SetFillColor(220,220,220);
					 $this->SetFont('Arial','',12);
					///// 	
					//Logos
					 $this->Image("img/cualli.jpg" , 171 ,10, 15 , 10 , "JPG" ,"");
					 $this->Image("img/pemex.jpg" , 186 ,10, 15 , 10 , "JPG" ,"");
					//QR
					 $this->Image("$filename" , 171 ,25, 30 , 30 , "PNG" ,"");
					 //$this->Ln(2);
					 $this->Cell(160,7,$reg["e_razon_social"],0,0,'L',1);
					 $this->Ln(9);
					 //Colores detalle
					 $this->SetFillColor(255,255,255);
					 $this->SetFont('Arial','',8);
					 //////id_emisor
					 $this->Cell(53,3,utf8_decode("Estación de servicio No.  ".$reg[id_emisor]),0,0,'L',1);
					 $this->Cell(53,3,utf8_decode("RFC: ".$reg[e_rfc]),0,0,'L',1);
					 $this->Cell(53,3,utf8_decode("SIC: ".$reg[cliente_pemex]),0,0,'L',1);
					 $this->Ln(4);
					 
					 $this->Cell(160,3,utf8_decode("Régimen Fiscal: ".$reg[e_regimen_fiscal]),0,0,'L',1);
					 //$this->Cell(160,3,"Regimen Fiscal: ".$reg[e_regimen_fiscal],0,0,'L',1);
					 $this->Ln(4);
					 
					 $this->Cell(80,3,"Domicilio Fiscal ",0,0,'L',1);
					 $this->Cell(80,3,"Expedido En ",0,0,'L',1);
					 $this->Ln(4);
					 
					 $calle = $reg["e_calle"];
					 if (strlen($reg["e_numero_exterior"] > 0)) 
					 {
						 $calle = $calle." N° ".$reg["e_numero_exterior"];
					 }
					 if (strlen($reg["e_numero_interior"] > 0))
					 {
						$calle = $calle." Int ".$reg["e_numero_interior"];	
					 }
						
					 $this->Cell(80,6,utf8_decode("Calle ".$calle),0,0,'L',1);
					 
					 $calle = $reg["ep_calle"];
					 if (strlen($reg["ep_numero_exterior"] > 0))
						$calle = $calle." N° ".$reg["ep_numero_exterior"];
					 if (strlen($reg["ep_numero_interior"] > 0))
						$calle = $calle." Int ".$reg["ep_numero_interior"];	
					
					 $this->Cell(80,6,utf8_decode("Calle ".$calle),0,0,'L',1);
					 $this->Ln(8);
					 
					 $this->Cell(80,3,utf8_decode("Colonia ".$reg["e_colonia"]),0,0,'L',1);
					 $this->Cell(80,3,utf8_decode("Colonia ".$reg["ep_colonia"]),0,0,'L',1);
					 $this->Ln(4);
					 
					 $this->Cell(80,3,utf8_decode("Municipio ".$reg["e_municipio"]),0,0,'L',1);
					 $this->Cell(80,3,utf8_decode("Municipio ".$reg["ep_municipio"]),0,0,'L',1);
					 $this->Ln(4);
					 $this->Cell(80,3,utf8_decode("Estado ".$reg["e_estado"]),0,0,'L',1);
					 $this->Cell(80,3,utf8_decode("Estado ".$reg["ep_estado"]),0,0,'L',1);
					 $this->Ln(4);
					 $this->Cell(80,3,utf8_decode("CP ".$reg["e_codigo_postal"]),0,0,'L',1);
					 $this->Cell(80,3,utf8_decode("CP ".$reg["ep_codigo_postal"]),0,0,'L',1);
					 $this->Ln(4);
					 $this->Cell(80,3,utf8_decode("Telefono ".$reg["e_telefono"]),0,0,'L',1);
					 $this->Cell(80,3,utf8_decode("Telefono ".$reg["ep_telefono"]),0,0,'L',1);
					 $this->Ln(4);
					
					//Colores Titulo
					 $this->SetFillColor(220,220,220);
					 $this->SetFont('Arial','',12);
					 ////
					 $this->Cell(190,7,"Cliente",0,0,'L',1);
					 $this->Ln(9);
					 //Colores detalle
					 $this->SetFillColor(255,255,255);
					 $this->SetFont('Arial','',10);
					 
					 $this->MultiCell(190,4,utf8_decode($reg["id_receptor"]." ".$reg["r_razon_social"]),0,'L');
					 $this->Ln(5);
					 
					 $this->Cell(150,4,utf8_decode("RFC: ".$reg["r_rfc"]),0,0,'L',1);
					 $this->SetFont('Arial','',8); $this->Cell(40,4,utf8_decode("Factura: ".$reg["serie"]." ".$reg["folio"]),0,0,'L',1);
					 $this->Ln(5);
					 
					 $calle = $reg["r_calle"];
					 if (strlen($reg["r_numero_exterior"] > 0))
						$calle = $calle." N° ".$reg["r_numero_exterior"];
					 if (strlen($reg["r_numero_interior"] > 0))
						$calle = $calle." Int ".$reg["r_numero_interior"];	
						
					 $this->SetFont('Arial','',10);$this->Cell(150,4,utf8_decode("Calle: ".$calle),0,0,'L',1);
					 $this->SetFont('Arial','',8); $this->Cell(40,4,utf8_decode("Fecha: ".$reg["fecha_expedicion"]),0,0,'L',1);
					 $this->Ln(5);
					 
					 $this->SetFont('Arial','',10);$this->Cell(150,4,utf8_decode("Colonia: ".$reg["r_colonia"]),0,0,'L',1);
					 //$this->SetFont('Arial','',8); $this->Cell(40,4,"Metodo de pago:","RLTB",0,'L',1);
					 $this->SetFont('Arial','',8); $this->Cell(40,4,utf8_decode($reg["pago_metodo"]),0,0,'L',1);
					 $this->Ln(5);
					 
					 $this->SetFont('Arial','',10);$this->Cell(150,4,utf8_decode("Municipio: ".$reg["r_municipio"]),0,0,'L',1);
					 $this->SetFont('Arial','',8); $this->Cell(40,4,utf8_decode("Forma de Pago:"),0,0,'L',1);
					 $this->Ln(5);
					 
					 $this->SetFont('Arial','',10);$this->Cell(150,4,utf8_decode("Estado: ".$reg["r_estado"]),0,0,'L',1);
					 $this->SetFont('Arial','',8); $this->Cell(40,4,utf8_decode($reg["pago_forma"]),0,0,'L',1);
					 $this->Ln(5);
					 
					 $this->SetFont('Arial','',10); $this->Cell(150,4,utf8_decode("CP: ".$reg["r_codigo_postal"]),0,0,'L',1);
					 $this->SetFont('Arial','',8); $this->Cell(40,4,utf8_decode("Cuenta:".$reg["r_cuenta"]),0,0,'L',1);
					 $this->Ln(5);
		
					//Colores Titulo
					 $this->SetFillColor(220,220,220);
					 $this->SetFont('Arial','',8);
					 $this->Cell(20,3,"Cantidad",0,0,'R',1);
					 $this->Cell(20,3,"Unidad",0,0,'L',1);
					 $this->Cell(20,3,utf8_decode("Código Pemex"),0,0,'L',1);
					 $this->Cell(90,3,utf8_decode("Descripción"),0,0,'L',1);
					 $this->Cell(20,3,"Precio",0,0,'R',1);
					 $this->Cell(20,3,"Monto",0,0,'R',1);
					 $this->SetFillColor(255,255,255);
					 $this->Ln(4);
					 //////////////////////////////HEADER//////////////////////////////////
				}
				 //////////////////////////////////////////////////////////////////////////
				 //////////////DETALLE////////////////////////////////////////////////////
				 /////////////////////////////////////////////////////////////////////////
				 $this->Cell(20,3,utf8_decode($reg["cantidad"]),0,0,'R',1);
				 $this->Cell(20,3,utf8_decode($reg["unidad"]),0,0,'L',1);
				 $this->Cell(20,3,utf8_decode($reg["codigo_pemex"]),0,0,'L',1);
				 $this->Cell(90,3,utf8_decode($reg["descripcion"]),0,0,'L',1);
				 $this->Cell(20,3,utf8_decode($reg["valor_unitario"]),0,0,'R',1);
				 $this->Cell(20,3,utf8_decode($reg["monto"]),0,0,'R',1);
				 $this->Ln(4);
				 //////////////////////////////////////////////////////////////////////////
				 //////////////DETALLE////////////////////////////////////////////////////
				 /////////////////////////////////////////////////////////////////////////
				 
			}
			
			//////////////////////////////FOOTER//////////////////////////////////
			$this->Ln(4);
			$this->Cell(170,3,"Subtotal",0,0,'R',1);
			$this->Cell(20,3,$sub_total,0,0,'R',1);
			$this->Ln(4);
			$this->SetFont('Arial','',6); $this->Cell(150,3,"Importe con letra",0,0,'L',1);
			$this->SetFont('Arial','',8);$this->Cell(20,3,"IVA 16%",0,0,'R',1);
			$this->Cell(20,3,$iva,0,0,'R',1);
			$this->Ln(4);
			$this->SetFont('Arial','',6);$this->Cell(150,3,utf8_decode($total_letra),0,0,'L',1);
			$this->SetFont('Arial','',8);$this->Cell(20,3,"Total",0,0,'R',1);
			$this->Cell(20,3,$total,0,0,'R',1);
			$this->Ln(4);
			$this->SetFont('Arial','',6);$this->Cell(190,3,utf8_decode("Observaciones: ".$observaciones),0,0,'L',1);
			$this->Ln(4);
			
			//Colores Titulo
			$this->SetFillColor(220,220,220);
			$this->SetFont('Arial','',12);
			////
			$this->Cell(190,7,"Timbre Fiscal Digital",0,0,'L',1);
			$this->Ln(9);
			//Colores detalle
			$this->SetFillColor(255,255,255);
			$this->SetFont('Arial','',6);
			
			$this->Cell(190,3,utf8_decode("No. De Serie del Certificado del CSD  ".$certificado_numero),0,0,'L',1);
			$this->Ln(4);
			$this->Cell(190,3,utf8_decode("No. De Serie del Certificado SAT ".$certificado_sat),0,0,'L',1);
			$this->Ln(4);
			$this->Cell(190,3,utf8_decode("Fecha y hora de Certificación  ".$fecha_timbrado),0,0,'L',1);
			$this->Ln(4);
			$this->Cell(190,3,utf8_decode("Folio Fiscal  ".$uuid),0,0,'L',1);
			$this->Ln(4);
			$this->Cell(190,3,utf8_decode("Fecha y hora de Certificación  ".$fecha_timbrado),0,0,'L',1);
			$this->Ln(4);
			$this->Cell(190,3,utf8_decode("Sello Digital  "),0,0,'L',1);
			$this->Ln(4);
			$this->MultiCell(190,3,utf8_decode($sello),0,'L');
			$this->Ln(4);
			$this->Cell(190,3,"Sello Sat  ",0,0,'L',1);
			$this->Ln(4);
			$this->MultiCell(190,3,utf8_decode($sello_sat),0,'L');
			$this->Ln(4);
			$this->Cell(190,3,utf8_decode("Cadena Original del Complemento De Certificación Digital Del SAT"),0,0,'L',1);
			$this->Ln(4);
			$this->MultiCell(190,3,utf8_decode($cadena_timbre),0,'L');
			$this->Ln(4);
			$this->Cell(190,3,utf8_decode("ESTE DOCUMENTO ES UNA REPRESENTACIÓN IMPRESA DE UN CFDi"),0,0,'C',1);
			$this->Ln(4);
			$this->Cell(190,3,"Ventas: ".listaventas($conexion),0,0,'L',1);
			$this->Ln(4);
			//////////////////////////////FOOTER//////////////////////////////////
		}
	}
	
	$conexion=mysql_connect("127.0.0.1","root","123") or
	die("Problemas en la conexion");

	mysql_select_db("gaslight",$conexion) or
	die("Problemas en la selección de la base de datos");
	
	mysql_query("SET NAMES 'utf8'");
	
	$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    $filename = $PNG_TEMP_DIR.$_REQUEST[id_factura].'.png';
	
	$matrixPointSize = 4;
	$errorCorrectionLevel = 'L';   //'L','M','Q','H'
	$data= datos_qr($conexion);
	
	QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
	
	$pdf = new PDF();		
	$pdf->AddPage();
	$pdf->reporte($conexion);
	$pdf->Output();
	
	mysql_close($conexion);	

?>