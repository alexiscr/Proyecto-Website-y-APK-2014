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
		$sql = "SELECT * FROM categoriap WHERE cNom_CatP LIKE '%$q%'";
		$db->Consultar($sql);
		if ($db->numrows > 0) {
			while($fila = $db->Siguente()) {
				$consulta[] = $fila;
			} 
			?><select id="SelectE" onmousemove="elFocus();" onfocus="elFocus();" onchange = "Colocar('Id_Catp',this);" multiple="multiple" size="6" style="width: 310px;" ><?php
			for($i=0; $i<sizeof($consulta); $i++)
			{	
				?>
				<option value="<?php echo $consulta[$i]["Id_Catp"]; ?>" ><?php echo $consulta[$i]["cNom_CatP"];?></option>
				<?php
			}
			?></select><?php
		} else {
			echo "NO EXISTE LA CATEGORIA DE PRODUCTO $q .<br />";
		}
		$db->Desconectar();
	}
?>
