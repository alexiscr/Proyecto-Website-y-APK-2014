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
		$sql = "SELECT * FROM producto WHERE cNom_Prod LIKE '%$q%'";
		$db->Consultar($sql);
		if ($db->numrows > 0) {
			while($fila = $db->Siguente()) {
				$consulta[] = $fila;
			} 
			?><select id="SelectE" onmousemove="elFocus();" onfocus="elFocus();" onchange = "Colocar('Id_Prod',this);" multiple="multiple" size="6" style="width: 310px;" ><?php
			for($i=0; $i<sizeof($consulta); $i++)
			{	
				?>
				<option value="<?php echo $consulta[$i]["Id_Prod"]; ?>" ><?php echo $consulta[$i]["cNom_Prod"];?></option>
				<?php
			}
			?></select><?php
		} else {
			echo "NO EXISTE EL PRODUCTO $q .<br />";
		}
		$db->Desconectar();
	}
?>
