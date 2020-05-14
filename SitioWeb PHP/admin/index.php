<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Administracion de usuarios</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
    <script src="../js/jquery-1.11.1.min.js"></script>
</head>
<body>
<?php 
	require("Operaciones.php");
	require("CamposTab.php");
	require("Sesion.php");
	$sesion = new Sesion();
	if( isset($_POST["iniciar"]) )
	{
		$op = new Operaciones();
		$usuario = @$_POST["usuario"];
		$password = MD5(@$_POST["password"]);
		$op->ValidarDatos($usuario);
		$op->ValidarDatos($password);
		$op->Conectar();
		$sql = "select `cPassword` from `usuarios` where `cUsuario`='$usuario'";
		$op->Consultar($sql);
		if($op->numrows > 0){
			if($fila = $op->Primero()){
			$pass = $fila["cPassword"];
		}
		else{
			header("location: index.php");
		}
		if($sesion->validarUsuario($password,$pass) == true){			
			$sesion->set("usuario",$usuario);
			$sesion->set("tiempo",time());
			header("location: index.php");
		}
		else {
			echo $password;
			echo "<br>";
			echo $pass;
			echo "<script>alert('Verifica contraseña'); window.location='index.php'; </script>";
			}
		}
		else{
			echo "<script>alert('Verifica nombre de usuario');window.location='index.php';</script>";
		}
		$op->Desconectar();
	}
	if($sesion->get("usuario") != false){
		header("location: administracion.php");
	}
	else{
?>
	<div id="login">
    <form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> 
    	<label>Usuario</label>
        <input id="usuario" name="usuario" required type="text" placeholder="Usuario"/>
        <label>Password</label>
        <input id="password" name="password" required type="password" placeholder="Password" />
        <input type="submit" id="button" value="Login" name="iniciar" />
    </form>
 	</div>
<?php  
	}
?>
</body>
</html>
