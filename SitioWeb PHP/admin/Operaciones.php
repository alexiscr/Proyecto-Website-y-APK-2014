<?php
include("Conexion.php");
//include("phpqrcode/qrlib.php");    
class Operaciones extends Conexion
{      
    /***************************
    * ATRIBUTOS DE LA CLASE
    ***************************/
    var $numrows = 0;
	var $affectedrows = 0;
	var $result = NULL;
	var $last_id = 0;
	var $lastsql = "";
    var $imgDir; 
	var $Respaldo;
	
	function Consultar($sql)
    {
		$this->numrows = 0;
		$this->result = mysql_query($sql,$this->link); 
		$this->lastsql = $sql;
			 if (!$this->CheckError())
            {	
                if (!is_bool($this->result))
                {
                    $this->numrows = mysql_num_rows($this->result);
                }
                else
                {
                    $this->affectedrows = mysql_affected_rows($this->link);
                }
            }
        return $this->result;
	}
	
	function Actualizar($tabla, $lista, $where = "")
    {
        $this->affectedrows = -1;
	
        if (!is_array($lista))
        {
            $this->error = true;
			$this->errno = -1;
			$this->errmsg = "Segundo parametro no es array ('campo'=>'valor')";
                return false;
        }
        else
        {
            $sql = "UPDATE `".$tabla."` SET";
            foreach ($lista as $key => $value)
            {
				$sql .= " `".$key."` = '".$value."',";
			}
                $sql = substr($sql, 0, -1); // Quita la ultima coma.
			$where = trim($where);
            if (!empty($where))
            {
                $sql .= " WHERE ".$where; 
            }
            $sql .= ";";
			$this->lastsql = $sql;
			$this->result = mysql_query($sql,$this->link);
            if (!$this->CheckError())
            {
                $this->affectedrows = mysql_affected_rows($this->link);
                return true;
			}
            else
            {
                return false;
			}
        }
	}
	
	function Insertar($tabla, $lista)
    {
            $this->affectedrows = -1;
            if (!is_array($lista))
            {
                $this->error = true;
		$this->errno = -1;
                $this->errmsg = "Segundo parametro no es array ('campo'=>'valor')";
		return false;
            }
            else
            {
                $sql = "INSERT INTO `".$tabla."` (`".implode("`, `",array_keys($lista))."`) VALUES ('".implode("', '",array_values($lista))."');";
                $this->lastsql = $sql;
                $this->result = mysql_query($sql,$this->link);
			
                if (!$this->CheckError())
                {
                    $this->last_id = mysql_insert_id($this->link);
                    $this->affectedrows = mysql_affected_rows($this->link);
                    return true;
                }else
                {
                    return false; 
                }
            }
	}
	
	function Eliminar($tabla, $where)
        {
            $sql = "DELETE FROM `".$tabla."` WHERE ".$where.";";
            $this->lastsql = $sql;
            $this->result = mysql_query($sql);
	
            if (!$this->CheckError())
            {
		$this->affectedrows = mysql_affected_rows($this->link);
		return true;
            }
            else
            {
                return false;
            }
	}

	function Primero($res = NULL)
        {
            if ($res == NULL)
            {
                $res = $this->result;
            }
            if (mysql_num_rows($res) > 0)
            {
                mysql_data_seek($res,0);
		return mysql_fetch_assoc($res);
            }
            else
            {
                return false;
            }
	}
	
	function Siguente($res = NULL)
        {
            if ($res == NULL)
            {
		$res = $this->result;
            }
            if (mysql_num_rows($res) > 0)
            {
		return mysql_fetch_assoc($res);
            }
            else
            {
                return false;  
            }
	}

	function Ultimo($res = NULL)
        {
            if ($res == NULL)
            {
				$res = $this->result;
            }
            if (mysql_num_rows($res) > 0)
            {
				mysql_data_seek($res,mysql_num_rows($res)-1);
				return mysql_fetch_assoc($res);
            }
            else
            {
                return false;
            }
	}

	function Buscar($num, $res = NULL)
    {
        $result = false;
        if($res == NULL)
		{
			$res = $this->result;
        }
            if(is_int($num))
            {
                $num = (int)$num;
        	if((mysql_num_rows($res) > 0) and ($num < mysql_num_rows($res)))
                {
                    mysql_data_seek($res,$num);
                    $result = mysql_fetch_assoc($res);
		}
            }
            return $result;
	}
	
	function BuscarPor($tabla, $campo, $valor, $altorden = null)
        {
            $result = false;
            if(!empty($tabla) and !empty($campo))
            {
                $sql = "DESCRIBE `".$tabla."` `".$campo."`";
                $this->lastsql = $sql;
				$this->result = mysql_query($sql, $this->link);
                if(!$this->CheckError())
                {
                    $result = mysql_fetch_assoc($this->result);
                    if($result === FALSE)
                    {
                        $this->error = true;
						$this->errno = 1054;
						$this->errmsg = "Unknown column '".$campo."' in table '".$tabla."'";
                    }
                    else
                    {
                        $sql = "SELECT * FROM `".$tabla."` WHERE ";
                        if((stripos($result['Type'],"varchar(") == 0) or (stripos($result['Type'],"text") == 0))
                        {
                            $sql .= "LOWER(`".$campo."`) LIKE LOWER('".$valor."')";
						}
                        else
                        {
                            $sql .= "`".$campo."` = '".$valor."' ";
						}
                        if(!empty($altorden))
                        {
                            $sql .= " ORDER BY `".$altorden."`";
						}
                        $this->Consultar($sql);
						$this->lastsql = $sql;
                        if(!$this->error and $this->numrows > 0)
                        {
                            $result = $this->Primero();
			}
                        else{
                            $result = false;
			}
                     }
                }
	}
		return $result;
    }
	
    function GetNumRows($res)
    {
        return mysql_num_rows($res);
    }

    function ShowLastError()
    {
        if ($this->error)
        {
            echo $this->errno.": ".$this->errmsg."<br />";
	}
    }
	
    function SetUTF8($value=true)
    {
        $sql = ($value)?"SET NAMES 'utf8'":"SET NAMES 'latin1'";
		$this->result = mysql_query($sql,$this->link); 
		return $this->CheckError();
    }
    
    function Codificar($string)
    {
        $control = "Todo/lo/que/se/nos/pueda";
        $tmp_string = $string;
        $string = $control.$tmp_string.$control;
        $string = base64_encode($string);
        return($string);
    }
    
    function decodificar($string)
    {
        $string = base64_decode($string);
        $control = "Todo/lo/que/se/nos/pueda";
        $string = str_replace($control, "", "$string");
        return $string;
    }    
    
    function ValidarDatos($campo)
    {
        //Array con las posibles cadenas a utilizar por un hacker
		$CadenasProhibidas = array("Content-Type:",
		//evita email injection
		"MIME-Version:", "Content-Transfer-Encoding:","Return-path:","Subject:","From:","Envelope-to:","To:","bcc:","cc:",
		"UNION",
		// evita sql injection
		"DELETE","DROP","SELECT","INSERT","UPDATE","CREATE","TRUNCATE","ALTER","INTO","DISTINCT","GROUP BY","WHERE","RENAME","DEFINE","UNDEFINE","PROMPT","ACCEPT","VIEW","COUNT","UNION","HAVING","'",'"',"{","}","[","]",
		// evita introducir direcciones web
		"HOTMAIL","WWW",".COM","@","W W W",". c o m","http://",
		//variables y comodines
		"$", "&","*","'","&nbsp;","%");
		//Comprobamos que entre los datos no se encuentre alguna de
		//las cadenas del array. Si se encuentra alguna cadena se
		//dirige a la pagina anterior
		foreach($CadenasProhibidas as $valor){
        	if(strpos(strtolower($campo), strtolower($valor)) !== false)
			{
                echo("<script>
				alert('Datos Incorrectos!!!');
                ;</script>");
                exit;
			}
		}
    } 
	
    function Redireccionar($url)
    {
        header("refresh:3; url=$url");
    }
	
    function limite($lm)
    {
        $l;
        if($lm < 5 || $lm == null)
        {
            $l=5;
        }
        elseif($lm > 10)
        {
            $l=10;
        }
        else
        {
                $l=$lm;
        }
    
        return $l;    
    }
 
    
    function ComprobarLogin($login)
    {
		$this->ValidarDatos($login);
        $sql = "SELECT `login` from `usuarios` where `login`='$login';";
        $this->Consultar($sql);
        if($this->numrows > 0)
        {
            $disponible=false;
        }
        else
        {
            $disponible=true;
        }
            return $disponible;
    }
    
    function CrearCarpeta($empresa)
    {
        $direcctorio= "empresas/$empresa";
        $dir_img = "empresas/$empresa/imagenes";
        $dir_qr = "empresas/$empresa/img_qr";
        if(!is_dir($direcctorio))
        {
            if(@mkdir($direcctorio, 0755) && @mkdir($dir_img, 0755) && @mkdir($dir_qr, 0755))
            {
                chmod($direcctorio, 0755);
                chmod($dir_img, 0755);
                chmod($dir_qr, 0755);
                
                $res = true;
            }
            else
            {
                $res = false;
            }
        }
        else
        {
            $res = false;
        }
        return $res;
    }
    
    function RenombrarCarpera($actual, $nuevo)
    {
        $dir_actual = "empresas/$actual";
        $dir_nuevo = "empresas/$nuevo";
        if(@rename ($dir_actual,$dir_nuevo))
        {
            $res = true;
        }
        else
        {
            $res = false;
        }
        return $res;
    }
    
    function SubirImagen($empresa,$imagen,$nombre)
    {
        $image_ext = "";
        $dir = "empresas/".$empresa."/imagenes/";
        $tmp_name = $_FILES["$imagen"]["tmp_name"];
        $size = $_FILES["$imagen"]["size"];
        $filename = basename($_FILES["$imagen"]["name"]);
        $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
        //si hemos enviado un directorio que existe realmente y hemos subido el archivo    
        if ( is_dir($dir) && is_uploaded_file($tmp_name) && $size < 2097152)
        {   
            $img_type  = $_FILES["$imagen"]["type"];
            $time = @strtotime(date('Y-m-d H:i:s'));
            $img_file  = "img_".$empresa.""."_".$nombre."_$time.$file_ext";
            //¿es una imagen realmente?           
            if (((strpos($img_type, "gif") || strpos($img_type, "jpeg") || strpos($img_type,"jpg")) || strpos($img_type,"png") )){
                //¿Tenemos permisos para subir la imagen?
                if(move_uploaded_file($tmp_name, $dir.'/'.$img_file)){
                    $this->imgDir="$dir$img_file";
                    return true;
                }
            }
        }
        //si llegamos hasta aqui es que algo ha fallado
        return false;
    }
	
	
	function getHeight($image) {
		$size = getimagesize($image);
		$height = $size[1];
		return $height;
	}

	function getWidth($image) {
		$size = getimagesize($image);
		$width = $size[0];
		return $width;
	}
	
	function resizeImage($image,$width,$height,$scale) {
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$image); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$image,100); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$image);  
			break;
    }
	
	chmod($image, 0777);
	return $image;
}
	
    
    function Thumbnail($empresa, $producto, $dir_img, $src_x, $src_y, $src_w, $src_h){
        
        list($imagewidth, $imageheight, $imageType) = getimagesize($dir_img);
        
        $imageType = image_type_to_mime_type($imageType);
        
        $file_ext = strtolower(substr($dir_img, strrpos($dir_img, '.') + 1));
        
        $time = @strtotime(date('Y-m-d H:i:s'));
        
        $nombre_thumb = "thumb_".$empresa.""."_".$producto."_$time.$file_ext";
        $dir_thumb =  "empresas/$empresa/imagenes/$nombre_thumb";
        $targ_w = 200;
        $targ_h = 200;
	    $jpeg_quality = 100;
       //crear la nueva imagen 
	   $newImage = imagecreatetruecolor($targ_w,$targ_h);
       //se crea dependiendo del tipo de imagen
       switch($imageType) {
		  case "image/gif":
                imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
		      	$source=imagecreatefromgif($dir_img); 
                break;
	      case "image/pjpeg":
          case "image/jpeg":
		  case "image/jpg":
                $source=imagecreatefromjpeg($dir_img); 
                break;
          case "image/png":
		  case "image/x-png":
                imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $source=imagecreatefrompng($dir_img); 
                break;
  	    }
        //se copia la seleccion en la imagen
        imagecopyresampled($newImage,$source,0,0,$src_x,$src_y,$targ_w,$targ_h,$src_w,$src_h);
	    //se guarda dependiendo del tipo   
        switch($imageType) {
		  case "image/gif":
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
			imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
	  		imagegif($newImage,$dir_thumb); 
			break;
          case "image/pjpeg":
		  case "image/jpeg":
		  case "image/jpg":
	  		imagejpeg($newImage,$dir_thumb,$jpeg_quality); 
			break;
		  case "image/png":
		  case "image/x-png":
            imagecolortransparent($newImage, imagecolorallocatealpha($newImage, 0, 0, 0, 127));
			imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            imagepng($newImage,$dir_thumb);  
			break;
        }
        //se le dan permisos a la imagen 
        chmod($dir_thumb, 0777);
        return $dir_thumb;
        imagedestroy($dir_thumb);
    }
  
    function getDirimg()
    {
        return $this->imgDir;    
    }
    
    function ComprobarGrupo($user){
        $this->Conectar();
        $sql = "SELECT `usuarios`.`id_grupo`, `grupos_usr`.`grupo` FROM usuarios, grupos_usr WHERE usuarios.login='$user' AND `grupos_usr`.`id_grupo`=`usuarios`.`id_grupo`";
        $this->Consultar($sql);
        if($reg = $this->Primero()){
            $grupo=$reg["grupo"];
			$grupo=strtolower($grupo);
        }
        $this->Desconectar();
        if($grupo == "administradores" )
        {
            $res = true;
        }
        else
        {
            $res = false;
        }
        return $res;
    }
	
	function enviar_array($array){
		$tmp = serialize($array);
		$tmp = urlencode($tmp);
		return $tmp;
	}
	function recibir_array($url_array){
		$tmp = stripslashes($url_array);
		$tmp = urldecode($tmp);
		$tmp = unserialize($tmp);
		return $tmp;
	} 
	
	function CrearQr($empresa, $nombre ,$datos)
	{
		$time = @strtotime(date('Y-m-d H:i:s'));
		trim($empresa);
		trim($nombre);
		$nombre = str_replace(" ", "", "$nombre");
		$empresan = str_replace(" ", "", "$empresa");
		$filename = "empresas/$empresa/img_qr/imgQR_".$empresan."_".$nombre."_".$time.".png";
		$errorCorrectionLevel = "M";
		$matrixPointSize = 6;
		@QRcode::png($datos, $filename, $errorCorrectionLevel, $matrixPointSize, 6);
		
		return $filename;
	}
	
	function CrearQrEmp($empresa, $datos)
	{
		$time = @strtotime(date('Y-m-d H:i:s'));
		trim($empresa);
		$empresan = str_replace(" ", "", "$empresa");
		$filename = "empresas/$empresa/imgQR_".$empresan."_".$time.".png";
		$errorCorrectionLevel = "M";
		$matrixPointSize = 6;
		@QRcode::png($datos, $filename, $errorCorrectionLevel, $matrixPointSize, 6);
		
		return $filename;
	}
	
	function BorrarImg($nombre, $empresa, $tipo)
	{	
		if($tipo == "img")
		{
			$dir = "empresas/$empresa/imagenes/";
		}
		elseif( $tipo == "qr" )
		{
			$dir = "empresas/$empresa/img_qr/";
		}
		elseif($tipo == "empresa")
		{
			$dir = "empresas/$empresa/";
		}
		
		$img = "$dir".$nombre;
		
		if(!is_dir($dir))
		{
			return false;
		}
		else
		{
			@unlink($img);
			return true;
		}
	}
	
	function RecortaString($string, $limit)
	{
		//Si la cadena es menor al limite la devuelve completa
		if(strlen($string) <= $limit)
		{
			return $string;
		}
		
		$break = substr($string, $limit);
		$pad="...";
		
		if(false !== ($breakpoint = strpos($string, $break, $limit))) {
			if($breakpoint < strlen($string)-1)
			{
				$string = substr($string, 0, $breakpoint) . $pad;
			}
		}
		return $string;
	}
	
	//FUNCION PARA RESPALDAR LA BASE DE DATOS
	function Respaldar($tables = '*')
	{
		$return="";
		$this->Conectar();
		//nombre de la base de datos
		$result = mysql_query('SELECT DATABASE()');
		while($row = mysql_fetch_row($result))
		{
			$dbnombre = $row[0];
		}
		//$return .= "DROP DATABASE IF EXISTS ".$dbnombre.";";
		//$return.= "\n\n";
		$return .= "CREATE DATABASE IF NOT EXISTS ".$dbnombre.";";
		$return.= "\n\n";
		$return .= "USE ".$dbnombre.";";
		$return.= "\n\n";
		
		//OBTENER TODAS LAS TABLAS
		if($tables == '*')
		{
		  $tables = array();
		  $result = mysql_query('SHOW TABLES');
		  while($row = mysql_fetch_row($result))
		  {
			 $tables[] = $row[0];
		  }
		}
	   else
	   {  //TABLAS SELECCIONADAS
		  $tables = is_array($tables) ? $tables : explode(',',$tables);
	   }
	   
	   foreach($tables as $table)
	   {
		  $result = mysql_query('SELECT * FROM '.$table);
		  $num_fields = mysql_num_fields($result);
		  
		  $return.= 'DROP TABLE IF EXISTS '.$table.';';
		  $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		  $return.= "\n\n".$row2[1].";\n\n";
		  
			for ($i = 0; $i < $num_fields; $i++)
			  {
				 while($row = mysql_fetch_row($result))
				 {
					$return.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
					   $row[$j] = addslashes($row[$j]);
					   $row[$j] = @ereg_replace("\n","\\n",$row[$j]);
					   if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					   if ($j<($num_fields-1)) { $return.= ','; }
					}
					$return.= ");\n";
				 }
			  }
			  $return.="\n\n\n";
	   }
	   //GUARDAR ARCHIVO
	   $nombre = 'backups\backup-'.$dbnombre.'-'.time().'.sql';
	   $handle = fopen($nombre,'w+');
	   //$handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
	   fwrite($handle,$return);
	   fclose($handle);
	   return $nombre;
	}
	
	function Descargar($archivo)
	{
		if (is_file($archivo))
		{
			$nombre = basename($archivo);
			$type="";
			$size = filesize($archivo);
			
			
				if (function_exists('mime_content_type'))
				{
					$type = mime_content_type($archivo);
				}
				else if(function_exists('finfo_file'))
				{
					$info = finfo_open(FILEINFO_MIME);
					$type = finfo_file($info, $archivo);
					finfo_close($info);
				}
				if ($type == '')
				{
					$type = "application/octet-stream";
				}
				// Definir headers
				header("Content-Type: $type");
				header("Content-Length: " . $size);
				header("Content-Disposition: attachment; filename=$nombre");
				header("Content-Transfer-Encoding: binary");
				// Descargar archivo
				readfile($archivo);
			
		}
		else
		{
			exit;
		}
	}
	
	function SubirRespaldo($archivo)
    {
        $dir = "backups/";
        $tmp_name = $_FILES["$archivo"]["tmp_name"];
        $size = $_FILES["$archivo"]["size"];
        $filename = basename($_FILES["$archivo"]["name"]);
        $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
        //si hemos enviado un directorio que existe realmente y hemos subido el archivo    
        if ( is_dir($dir) && is_uploaded_file($tmp_name) && $size < 2097152)
        {   
            //¿es un archivo sql realmente?           
            if($file_ext == "sql"){
                //¿Tenemos permisos para subir el archivo?
                if(move_uploaded_file($tmp_name, $dir.$filename)){
					$this->Respaldo = "$dir$filename" ;
                    return true;
                }
            }
        }
        //si llegamos hasta aqui es que algo ha fallado
        return false;
    }
	
	function splitQueryText($query) {
    // the regex needs a trailing semicolon
    $query = trim($query);

    if (substr($query, -1) != ";")
        $query .= ";";

    // i spent 3 days figuring out this line
    preg_match_all("/(?>[^;']|(''|(?>'([^']|\\')*[^\\\]')))+;/ixU", $query, $matches, PREG_SET_ORDER);

    $querySplit = "";

    foreach ($matches as $match) {
        // get rid of the trailing semicolon
        $querySplit[] = substr($match[0], 0, -1);
    }

    return $querySplit;
}
function fecha()
{
	date_default_timezone_set("Etc/GMT+6" ) ; 
	$tiempo = getdate(time()); 
	$dia = $tiempo['wday']; 
	$dia_mes=$tiempo['mday']; 
	$mes = $tiempo['mon']; 
	$year = $tiempo['year']; 
	$hora= $tiempo['hours']; 
	$minutos = $tiempo['minutes']; 
	$segundos = $tiempo['seconds']; 
	
	switch ($dia){ 
	case "1": $dia_nombre="Lunes"; break; 
	case "2": $dia_nombre="Martes"; break; 
	case "3": $dia_nombre="Miercoles"; break; 
	case "4": $dia_nombre="Jueves"; break; 
	case "5": $dia_nombre="Viernes"; break; 
	case "6": $dia_nombre="Sabado"; break; 
	case "0": $dia_nombre="Domingo"; break; 
	} 
	switch($mes){ 
	case "1": $mes_nombre="Enero"; break; 
	case "2": $mes_nombre="Febrero"; break; 
	case "3": $mes_nombre="Marzo"; break; 
	case "4": $mes_nombre="Abril"; break; 
	case "5": $mes_nombre="Mayo"; break; 
	case "6": $mes_nombre="Junio"; break; 
	case "7": $mes_nombre="Julio"; break; 
	case "8": $mes_nombre="Agosto"; break; 
	case "9": $mes_nombre="Septiembre"; break; 
	case "10": $mes_nombre="Octubre"; break; 
	case "11": $mes_nombre="Noviembre"; break; 
	case "12": $mes_nombre="Diciembre"; break; 
	} 
	
	return $dia_nombre." ".$dia_mes." de ".$mes_nombre." de ".$year;
}
	
}  
?>
