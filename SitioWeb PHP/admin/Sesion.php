<?php
class Sesion
{
    function __construct()
    {
        @session_start ();
    }
  
    public function set($nombre, $valor)
    {
        $_SESSION [$nombre] = $valor;
    }
    
    public function get($nombre) {
        if (isset ($_SESSION [$nombre]))
        {
            return $_SESSION [$nombre];
        }
        else
        {
            return false;
        }
    }
  
    public function elimina_variable($nombre)
    {
        unset ( $_SESSION [$nombre] );
    }
  
    public function termina_sesion()
    {
        $_SESSION = array();
        session_destroy ();
    }
 
    function validarUsuario($password, $pass)
    {
            if( strcmp($password,$pass) == 0)
            {
                    return true;						
            }
            else
            {					
                return false;
            }
    }
	
	function inactivo($inicio,$actual)
	{
		$segundos = 900 ;//si pasa este tiempo se detecta la inactividad del usuario en el sitio
		
		if(($inicio + $segundos) < time())
		{
			echo'<script type="text/javascript">alert("SU SESION HA EXPIRADO POR INACTIVIDAD';
			echo', \n\n VUELVA A LOGEARSE PARA CONTINUAR");window.location.href="cerrarsesion.php";</script>';    
		}
		else{
			$this->set("tiempo",time());
		}
	}
}
?>