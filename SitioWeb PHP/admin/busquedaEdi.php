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
		$sql = "SELECT * FROM edificios WHERE cNom_Edi LIKE '%$q%'";
		$db->Consultar($sql);
		if ($db->numrows > 0) {
			while($fila = $db->Siguente()) {
				$consulta[] = $fila;
			} 
			?><select id="SelectEdi" onmousemove="elFocus();" onfocus="elFocus();" onchange = "Colocar('Id_Edificio',this);" multiple="multiple" size="6" style="width: 310px;" ><?php
			for($i=0; $i<sizeof($consulta); $i++)
			{	
				?>
				<option value="<?php echo $consulta[$i]["Id_Edificio"]; ?>" ><?php echo $consulta[$i]["cNom_Edi"];?></option>
				<?php
			}
			?></select><?php
		} else {
			echo "NO EXISTE EL EDIFICIO $q .<br />";
		}
		$db->Desconectar();
	}
?>
