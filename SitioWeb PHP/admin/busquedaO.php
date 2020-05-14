<?php
	require("Operaciones.php");
	$db = new Operaciones();
	$q = $_GET['bus'];
	
	if($q == null){
		echo" <script >
				sinFocus();
			  </script>";
	}
	else{
		$db->Conectar();
		$sql = "SELECT * FROM tip_ofe WHERE cClase_Of LIKE '%$q%'";
		$db->Consultar($sql);
		if ($db->numrows > 0) {
			while($fila = $db->Siguente()) {
				$consulta[] = $fila;
			} 
			?><select id="SelectE" onmousemove="elFocus();" onfocus="elFocus();" onchange = "Colocar('Id_TipOf',this);" multiple="multiple" size="6" style="width: 310px;" ><?php
			for($i=0; $i<sizeof($consulta); $i++)
			{	
				?>
				<option value="<?php echo $consulta[$i]["Id_TipOf"]; ?>" ><?php echo $consulta[$i]["cClase_Of"];?></option>
				<?php
			}
			?></select><?php
		} else {
			echo "NO EXISTE EL LOCAL BUSCADO $q .<br />";
		}
		$db->Desconectar();
	}
?>
