<?php 
	require ("admin/Operaciones.php");
	$db = new Operaciones;
	$db->Conectar();
    $db->SetUTF8();
	$sql = "Select * From post Where Id_Post='1'";
	$db->Consultar($sql);
	if ($db->numrows > 0){
		echo "1";
		while($r = $db->Siguente()){   
			$fila[] = $r;
		}
		for ($i=0; $i<count($fila); $i++){   
			if (isset($fila[$i])){
				$titulo = $fila[$i]["cTitulo"];
				$contenido = $fila[$i]["cCuerpo"]; 
			}else{
				break;
			}
		}
	}else {
		
	}
	?>
	

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<title><?php echo $titulo; ?></title>
</head>
<?php
	include 'theme/header.php';?>
    
	<div id="contenedor">
        
        
        <div id="contenido">
        	<h1><?php echo $titulo; ?></h1>
           	<?php echo $contenido; ?>
           
        </div>
<?php
	include 'theme/footer.php';
	?>
