<?php
abstract class Conexion
{
    /****************
    * ATRIBUTOS DE LA
    * CLASE
    *****************/
    var $link = NULL;
    var $error = false;
    var $errno = 0;
    var $errmsg = "";

    /*******************************
    * CONSTRUCTOR QUE INCLUYE
    * LOS PARAMETROS DE CONFIGURACION
    * DEL HOST, USUARIO, PASSWORD Y 
    * BASE DE DATOS DE MySQL
    ********************************/
    function __construct()
    {
		/*Inclusion que genera la ubicacion del config.php*/
        include_once $_SERVER['DOCUMENT_ROOT']."/tesis/config.php";   
    }
    
    /*******************************
    * METODO DE LA CLASE QUE PERMITE
    * REALIZAR LA CONEXION CON EL
    * SERVIDOR DE MySQL
    *******************************/
   function Conectar()
   {
       $this->link = @mysql_connect(host, user, password, true);

       if (!$this->CheckError())
       {
           @mysql_select_db(db);
       }
        $this->CheckError();
        return $this->error;
    }

    /**********************************
    * METODO DE LA CLASE QUE CIERRA
    * LA CONEXION DEL SERVIDOR DE MySQL
    **********************************/
    function Desconectar()
    {
        if ($this->link !== NULL)
        {
            mysql_close($this->link);
            $this->link = NULL;
        }
    }

    /**********************************
    * METODO DE LA CLASE QUE DETERMINA
    * LA CONEXION DEL SERVIDOR DE MySQL
    **********************************/
    function EstaConectado()
    {
	if ($this->link)
        {
            return true;
	}
        else
        {
            return false;
	}
    }

    /*******************************
    * METODO DE LA CLASE QUE
    * CAPTURA LOS ERRORES
    *******************************/
    function CheckError()
    {
    	$this->errno = mysql_errno();
    	$this->error = $this->errno != 0;
    	$this->errmsg = $this->errno.": ".mysql_error();
    	return $this->error;
    }
    /*********************************************
    * METODO QUE DEVUELVE LA CONEXION AL SERVIDOR
    * NULL EN CASO DE QUE NO ESTE CONECTADO
    *********************************************/
    function GetLink()
    {
		return $this->link;
    }    
}
?>