<?php

    	require("Operaciones.php");
    require("CamposTab.php");	
    require_once("dompdf/dompdf_config.inc.php");
	$db= new operaciones();
	$a='<html>
		<head>
			<title>
				reportes
			</title>
				<style type="text/css">
					h2,h3{ text-align:center; }
					.reportes
					{
						margin: auto;
						text-align:center;
						padding: 5px;
					}
					
					.reportes th
					{
						border-bottom:1px solid #ccc;
						border-top:1px solid #ccc;
						padding: 15px;
						
					}
					.reportes td
					{
						border-bottom:1px solid #ccc;
						padding: 15px;
					}
				</style>
		</head>
		<body>
			<h2>MEGA PLAZA SONSONATE</h2><br>
			<h3>Reporte de Ofertas</h3>
			<table class="reportes">
				<tr>
					<th>No</th><th>Nombres</th><th>Apellidos</th><th>Telefono</th>
				</tr>';
	
	$db->Conectar();
	$filtro= $_POST["name"];
	$criterio = $_POST["criterio"];
	$sql = "select * from Oferta WHERE " . $criterio. " LIKE '%" .$filtro."%' order by cNom_Ofe asc";
	$db->Consultar($sql);
	$i=0;
	while($r = $db->Siguente())
	{   
		$i++;
		$a.= '<tr>';

		$a.= '<td> ' . $i . '</td><td>' . $r['cNom_Ofe'] . ' </td><td> ' . $r['fFecha_Ini'] . ' </td><td> ' . $r['fFecha_Fin'] . '</td><td> ' . $r['cDesc_Ofe'] . '</td>';

	}
	$a.='	
				</tr>
			<table>
		<body>
	</html>
	';
	
	$dompdf = new DOMPDF();
	$dompdf->load_html($a);
	ini_set("memory_limit","32M");
	$dompdf->render();
	$dompdf->stream("reporte.pdf");
	
	
	$db->desconectar();

?>