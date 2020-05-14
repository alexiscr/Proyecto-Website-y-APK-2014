<?php
    require("Operaciones.php");
    require("CamposTab.php");	
    require_once("Sesion.php");
   $sesion = new sesion();
    $usuario = $sesion->get("usuario");
	$inicio = $sesion->get("tiempo");
	$actual = time();
	$sesion->inactivo($inicio,$actual);
    if( $usuario == false )
    {
        header("Location: index.php");		
    }
    else 
    {
        $tabla = "Oferta";
        $db = new  Operaciones();
        $camposT = new CamposTab($tabla);
        $accion=null;
		$subida=null;
		
?>      
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Administracion de Ofertas</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
	<script src="../js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
	
	<!--Hoja de estilos del calendario --> 
	<link rel="stylesheet" type="text/css" media="all" href="../css/calendar-green.css" title="win2k-cold-1" /> 
	<!-- librería principal del calendario --> 
	<script src="../js/calendar.js"></script> 
	<!-- librería para cargar el lenguaje deseado --> 
	<script src="../lang/calendar-es.js"></script> 
	<!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código --> 
	<script src="../js/calendar-setup.js"></script> 
	</head>
<body>

	<div id="contenedor-admin">
    	<div id="sidebar-top-admin">
        	<div id="nav-admin">
            	<ul>
                	<li class="off-buttom"><a href="">Bienvenid@ , Admin</a></li>
                </ul>
        	</div>
        </div> 
       <div id="sidebar-left-admin">
        	<div id='nav-izq'>
            	<ul id='menu-izq'>
                	<li class="post-icon">
                    	<a href='#'>Mantenimiento</a>
                        	<ul>
                            	<li><a href='mEmpresa.php'>Empresa</a></li>
                                <li><a href='mEdificio.php'>Edificio</a></li>
								<li><a href='mLocales.php'>Local</a></li>
								<li><a href='mRepresentante.php'>Representante</a></li>
								<li><a href='mPatrocinador.php'>Patrocinador</a></li>
								<li><a href='mProducto.php'>Producto</a></li>
								<li><a href='mCategoriap.php'>Categoria Producto</a></li>
								<li><a href='mCategoria.php'>Categoria Post</a></li>
								<li><a href='mTip_Ofe.php'>Tipo Oferta</a></li>
                            </ul>
                    </li>
                    <li class="category-icon">
                    	<a href='mPagina.php'>Registro Información</a>
                    </li>
                    <li class="product-icon">
                    	<a href='mUsuario.php'>Gestión de Usuarios</a>
                    </li>
                    <li class="user-icon">
                    	<a href='#'>Promoción y Publicidad</a>
						<ul>
                            	<li><a href='mOferta.php'>Ofertas</a></li>
                                <li><a href='mEvento.php'>Eventos</a></li>
                            </ul>
                    </li>
                    <li class="ofert-icon">
                    	<a href='#'>Reportes</a>
						<ul>
                            	<li><a href='mReporteEm.php'>Empresa</a></li>
                                <li><a href='mReporteEv.php'>Eventos</a></li>
								<li><a href='mReporteOf.php'>Ofertas</a></li>
                                <li><a href='mReportesU.php'>Usuarios</a></li>
                            </ul>
                    </li>
                 </ul>
              </div>
        </div>
        <div id="pagewrap-admin">
                <?php
					try{
                            $db->Conectar();
                            $db->SetUTF8();
                            //captura del valor de la accion
                            $accion = $db->decodificar(@$_REQUEST["accion"]);
                            //comparacion del valor de la accion
                            switch ($accion){
                                // MOSTRAR REGISTROS DE LA TABLA
                                case null:
                                    //captura de la busqueda
                                    $buscar = Trim(@$_REQUEST["b"]);
									
                                    //validar datos de la busqueda
                                    $db->ValidarDatos($buscar);
        
									if($buscar != null){

                                    $sql = "SELECT * FROM $tabla WHERE cNomb_Ofe LIKE '%$buscar%' ORDER BY cNomb_Ofe ASC";
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY cNomb_Ofe ASC";
                                    }
                                    //realizacion de la consulta
                                    $db->Consultar($sql);
                                    //verificacion que los registros sean mayor a cero
									if($db->numrows > 0)
                                    {                         
                                        //LIMITE
                                        $l = 10;
                                        //PAGINA, SI LA PAGINA EXISTE SE LE ASIGNA
                                        //EL VALOR QUE TRAE
                                        if (isset($_GET["p"])){
                                            $p = $_GET["p"];
                                        }
                                        else{
                                            //SI NO  EXISTE SE LE ASIGNA 1
                                            $p=1;
                                        }
                                        //llenado del array
                                        while($r = $db->Siguente())
                                        {   
                                            $fila[] = $r;
                                        }
                                        // DEFINIMOS LA CANTIDAD DE PAGINAS
                                        $paginas = ceil(count($fila) / $l);
                                        // CONDICION DE INICIO
                                        $inicio = ($p-1)*$l;
                                        // CONDICION DE FINAL
                                        $final = $p*$l;
                                    ?>
									 <div id="busqueda">
                <form>
                   <!--<input type='image' id='boton' src="imagenes/Buscar2.png" />-->
                    <input type='search' placeholder='Buscar...' id='b' name="b" />
					<input type='button' id='boton' value="Buscar" title="Buscar" />
                </form>
			</div>
			<div class="clear-div">
			</div>
									<div id="grid">
                                    <table class="grid-box">
									<thead>
									<tr>
                                      <th  width="10%" class="alinear"><a href="mOferta.php?accion=<?php echo $db->Codificar("1");?>" title="Agregar Usuario">
                                       <img src="../imag/agregar.png" /></a></th>
                                      <th  width="20%">Nombre Oferta</th>
									  <th  width="15%">Fecha Inicio</th>
									  <th  width="15%">Fecha Fin</th>
									  <th  width="20%">Producto</th>
									  <th  width="10%">Local</th>
									  <th  width="10%">Tipo de Oferta</th>
									 </tr>
									 </thead>
                                    <?php
                                    //verificacion que la pagina exista
                                    if($p > @$paginas)
                                    {
                                        echo "<tr><td colspan='3'><p>no existe la pagina $p</p></td></tr>";
                                    }
                                    //impresion de los resultados 
                                    for ($i=$inicio; $i<$final; $i++)
                                    {   
                                        //verificacion que el numero de fila exista
                                        if (isset($fila[$i])){
                                        ?>
                                        <!--<tr class="fila_<?php //echo $i%2; ?>">-->
										<tbody>
										<tr>
										<td class="opciones-datos">
										  
                                        <a href="mOferta.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Oferta"]);?>"> <img src="../imag/editar.png" title="Editar"/></a>
                                        <a href="mOferta.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Oferta"]);?>"> <img src="../imag/borrar.png" title='Eliminar'/></a>
										</td>
										<td><?php echo $db->RecortaString($fila[$i]["cNomb_Ofe"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["fFecha_Ini"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["fFecha_Fin"],15); ?></td>
										<td>
											<?php  
											$sec = $fila[$i]["Id_Prod"];
											$sql_sec = "SELECT cNom_Prod FROM producto WHERE Id_Prod='$sec'";
											$db->Consultar($sql_sec);
											if($reg_sec = $db->Primero()){
											 echo $db->RecortaString($reg_sec["cNom_Prod"],10); 
											}
										?>
										</td>
										<td>
											<?php  
											$sec = $fila[$i]["Id_Local"];
											$sql_sec = "SELECT cNum_Local FROM Locales WHERE Id_Local='$sec'";
											$db->Consultar($sql_sec);
											if($reg_sec = $db->Primero()){
											 echo $db->RecortaString($reg_sec["cNum_Local"],10); 
											}
										?>
                                        <td>
										<?php  
											$sec = $fila[$i]["Id_TipOf"];
											$sql_sec = "SELECT cClase_Of FROM tip_ofe WHERE Id_TipOf='$sec'";
											$db->Consultar($sql_sec);
											if($reg_sec = $db->Primero()){
											 echo $db->RecortaString($reg_sec["cClase_Of"],10); 
											}
											?>
										</td>
                                        </tr>
                                        </tbody>
                                        <?php   
                                        }
                                        else{
                                            break;
                                        }
                                    }
                                    ?>
                                    <tfoot>
									<tr>
									<td colspan="7" align="center">
                                        <?php 
                                        //si la pagina no es la primera se imprime anterior
                                        if ($p>1){
											
												?><a href='javascript:void(0);' onClick="window.location='mOferta.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php	
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong>$i</strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mOferta.php?p=$i\">$i</a>";
												
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a href=\"mOferta.php?p=" . ($p+1) . "\">Siguiente >></a>";
											
										}
                                    ?>
                                    </td> </tr>
									</tfoot>
                                    </table>
									<script> $( "tr:odd" ).css( "background-color", "#f9f9f9" );</script>
									</div>
                                <?php
                                }
                                else
                                {   //si en numero de registros es igual a cero
                                    //verificacion que la variable buscar no este vacia
                                    if($buscar != null){
                                        //si se realizo una busqueda y no hay resultados
                                        echo "<h4>NO EXISTE $buscar EN LA TABLA &nbsp; &nbsp; &nbsp;";
                                        echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
										
                                    }
                                    else{
                                        //si la tabla esta vacia
                                        echo "<p>la tabla esta vacia</p>";
										?>
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mOferta.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
                    
									<FORM NAME='datos' ACTION='mOferta.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST' autocomplete="off" enctype="multipart/form-data">
									<table class="formularios">
									<thead>
                            	<tr>
                                	<td width="150px;"></td>
                                    <td colspan="2"></td>
                                    <td width="150px;"></td>
                                </tr>
                                </thead>
									<tr>
									<td></td>
									<td><label>Nombre Oferta<label></td>
									<td> <input type='text' name='cNomb_Ofe' required="required" placeholder="Digite el Nombre de Oferta" MAXLENGTH="50"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Imagen Oferta<label></td>
									<td> <input name="archivo" type="file" size="35" /></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Precio de Oferta<label></td>
									<td> <input type='text' name='cPrecio' MAXLENGTH="8" required="required" placeholder="Digite el precio de la oferta"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Descripcion Oferta<label></td>
									<td> <input type='text' name='cDesc_Ofe' MAXLENGTH="150" required="required" placeholder="Digite la descripcion de Oferta"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Fecha Inicio<label></td>
									<td> <input type='text' name='fFecha_Ini'  placeholder="YYYY/MM/DD" id="campo_fecha" readonly>
									<img src="../imag/calendario.gif" width="16" height="16" border="0" title="Fecha Inicial" id="lanzador"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Fecha Fin<label></td>
									<td> <input type='text' name='fFecha_Fin'  placeholder="YYYY/MM/DD" id="campo_fecha2" readonly>
									<img src="../imag/calendario.gif" width="16" height="16" border="0" title="Fecha Final" id="lanzador2"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Producto<label></td>
									<td><input onKeyUp="BuscarE('Id_Prod','resultado','busquedaP');" name="Id_Prod" type="text" id="Id_Prod" placeholder="Nombre de Producto" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									</td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Local<label></td>
									<td><input onKeyUp="BuscarE('Id_Local','resultado','busquedaL');" name="Id_Local" type="text" id="Id_Local" placeholder="Numero de Local" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									</td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Tipo Oferta<label></td>
									<td><input onKeyUp="BuscarE('Id_TipOf','resultado','busquedaO');" name="Id_TipOf" type="text" id="Id_TipOf" placeholder="Clase de Oferta" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									</td>
									<td></td>
									</tr>
						            <tr>
									<td colspan="4" class="centrar">  
									<input name="enviar"  type='submit' value='Aceptar'>
									<input name="action" type="hidden" value="upload" /> 
									<input  type='button' value='Cancelar' onclick="javascript:location.href='mOferta.php'">
									</td>
									</tr>
									<tfoot>
                                	<tr><td colspan="4"></td></tr>
                                </tfoot>
									</table>  
							<!-- script que define y configura el calendario--> 
									<script type="text/javascript"> 
									   Calendar.setup({ 
										inputField     :    "campo_fecha",     // id del campo de texto 
										 ifFormat     :     "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto 
										 button     :    "lanzador"     // el id del botón que lanzará el calendario 
									}); 
									</script> 

									<script type="text/javascript"> 
									   Calendar.setup({ 
										inputField     :    "campo_fecha2",     // id del campo de texto 
										 ifFormat     :     "%Y-%m-%d",     // formato de la fecha que se escriba en el campo de texto 
										 button     :    "lanzador2"     // el id del botón que lanzará el calendario 
									}); 
									</script>					
									</FORM>
										
										
                                <?php
                                //final de la accion 1
                                break;
                                // insertar
								case "2":
									$status = ""; 
									($_POST["action"] == "upload") or die ();
								 	  // obtenemos los datos del archivo 
										  $tamano = $_FILES["archivo"]['size']; 
										  $tipo = $_FILES["archivo"]['type']; 
										  $archivo = $_FILES["archivo"]['name']; 
									 $prefijo = substr(md5(uniqid(rand())),0,6); 
									 ($archivo != "") or die ("Error al subir la imagen ".$archivo); 
											  // guardamos el archivo a la carpeta "capturas" 
											($tipo == "image/jpeg" || $tipo == "image/jpg") or die ("Sólo se admiten imágenes en <b>.jpg</b> y <b>.jpeg</b>"); 
											  $destino =  "captura/".$archivo; 
											(copy($_FILES['archivo']['tmp_name'],$destino)) or die ("Error al subir la imagen ".$archivo); 
											  $status = "La imagen <b>".$archivo."</b> se a subido correctamente !"; 
											  echo $destino;
											  echo $status; 
										  
										//aquí empieza el código de creación del thumbnail 
										  $source=$destino; // archivo de origen 
										  $dest= $_SERVER['DOCUMENT_ROOT']."tesis/admin/captura/thumb/tb_".$archivo; // archivo de destino 
										  $width_d=117; // ancho de salida 
										  $height_d=82; // alto de salida 

											list($width_s, $height_s, $type, $attr) = getimagesize($source, $info2); // obtengo información del archivo 
										  $gd_s = imagecreatefromjpeg($source); // crea el recurso gd para el origen 
										  $gd_d = imagecreatetruecolor($width_d, $height_d); // crea el recurso gd para la salida 

											imagecopyresampled($gd_d, $gd_s, 0, 0, 0, 0, $width_d, $height_d, $width_s, $height_s); // redimensiona 
											imagejpeg($gd_d, $dest); // graba 
										 
										// Se liberan recursos 
										imagedestroy($gd_s); 
										imagedestroy($gd_d); 


								    
									$nombre = $_REQUEST["cNomb_Ofe"];
								    $imagen = $_SERVER['DOCUMENT_ROOT']."tesis/admin/captura/".$archivo;
									$descripcion = $_REQUEST["cDesc_Ofe"];
									$fecha_inicio = $_REQUEST["fFecha_Ini"];
									$fecha_fin = $_REQUEST["fFecha_Fin"];
									$producto = $_REQUEST["Id_Prod"];
					                $local = $_REQUEST["Id_Local"]; 
									$tipo_oferta = $_REQUEST["Id_TipOf"];
									$Img_tb = $dest;
									$fecha_actual = date("Y-m-d");
									$precio= $_REQUEST["cPrecio"];
									
									$valor_array = explode(',',$producto); 
									$nombrec = $valor_array[0];
									
									$sqlR = "SELECT Id_Prod FROM Producto WHERE cNom_Prod = '$nombrec'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$producto = $res["Id_Prod"];								
									}
									
									$valor_array = explode(',',$local); 
									$nombrel = $valor_array[0];
									
									$sqlR = "SELECT Id_Local FROM Locales WHERE Id_Local = '$nombrel'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$local = $res["Id_Local"];								
									}
									
									$valor_array = explode(',',$tipo_oferta); 
									$nombreo = $valor_array[0];
									
									$sqlR = "SELECT Id_TipOf FROM Tip_Ofe WHERE cClase_Of = '$nombreo'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$tipo_oferta = $res["Id_TipOf"];								
									}
									
									
									
									if (($fecha_inicio > $fecha_fin) or ($fecha_inicio < $fecha_actual)){
										echo "<h4>FECHA INCORRECTA POR FAVOR CORREJIRLA &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mOferta.php';" value="Aceptar" /> </h4> <?php
									}
									else{
										$valores = array($nombre, $imagen, $descripcion, $fecha_inicio, $fecha_fin, $producto, $local, $tipo_oferta,$precio,$Img_tb);
										$campos = $camposT->getCamposInsert($valores);
										if($db->Insertar($tabla, $campos))
										{
											echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
											?> <input name='btn' type='button' onClick="window.location='mOferta.php';" value="Aceptar" /> </h4><?php
										}
										else
										{
											echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
											?> <input name='btn' type='button' onClick="window.location='mOferta.php';" value="Aceptar" /> </h4> <?php
										}
									}
									
									
								
								
                                //final de la accion 2    
                                break;
								
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Oferta` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
											
											<FORM NAME='datos' ACTION='mOferta.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST' autocomplete="off" enctype="multipart/form-data">
											<table class="formularios">
											<input type='hidden' value="<?php echo $db->Codificar($id);?>" name='C' >
											<thead>
												<tr>
													<td width="150px;"></td>
													<td colspan="2"></td>
													<td width="150px;"></td>
												</tr>
											</thead>
											<tr>
											<td></td>
											<td><label>Nombre de Oferta<label></td>
											<td> <input type='text' MAXLENGTH="50" value="<?php echo $registro['cNomb_Ofe'];?>" name='cNomb_Ofe' required="required" placeholder="Digite el nombre"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
									        <td><label>Imagen<label></td>
											<td> <input type='text' value="<?php echo $registro['cImg_Ofe'];?>" name='cImg_Ofe' readonly>
											<input name="archivo" type="file" size="35" /></td>
									        <td></td>
											</tr>
											<tr>
											<td></td>
									        <td><label>Imagen Thumb<label></td>
											<td> <input type='text' value="<?php echo $registro['cImg_tb'];?>" name='cImg_tb' readonly>
									        <td></td>
											</tr>
											<tr>
											<td></td>
											<td><label>Precio de Oferta<label></td>
											<td> <input type='text'  value="<?php echo $registro['Precio_Oferta'];?>" name='cPrecio' MAXLENGTH="8" required="required" placeholder="Digite el precio de la oferta"></td>
											<td></td>
											</tr>
									        <tr>
											<td></td>
											<td><label>Descripcion de Oferta<label></td>
											<td> <input type='text' MAXLENGTH="150" value="<?php echo $registro['cDesc_Ofe'];?>" name='cDesc_Ofe' required="required" placeholder="Descripcion de Oferta"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
											<td><label>Fecha de Inicio<label></td>
											<td> <input type='text' value="<?php echo $registro['fFecha_Ini'];?>" name='fFecha_Ini' placeholder="YYYY/MM/DD" id="campo_fecha3" readonly>
											<img src="../imag/calendario.gif" width="16" height="16" border="0" title="Fecha Inicial" id="lanzador3"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
											<td><label>Fecha Fin<label></td>
											<td> <input type='text' value="<?php echo $registro['fFecha_Fin'];?>" name='fFecha_Fin'  placeholder="YYYY/MM/DD" id="campo_fecha4" readonly>
											<img src="../imag/calendario.gif" width="16" height="16" border="0" title="Fecha Final" id="lanzador4"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
											<td><label>Producto<label></td>
									<td>
									<?php  
											$Id_Prod=$registro['Id_Prod'];
											$sqlR = "SELECT cNom_Prod FROM producto WHERE Id_Prod = '$Id_Prod'";											
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
												$nom = $res["cNom_Prod"];	
											} 
											?>
									<input value= "<?php echo "$nom";?>" onKeyUp="BuscarE('Id_Prod','resultado','busquedaP');" name="Id_Prod" type="text" id="Id_Prod" placeholder="Nombre de Producto" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									</td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Local<label></td>
									<td>
									<?php  
											$Id_Local=$registro['Id_Local'];
											$sqlR = "SELECT cNum_Local FROM Locales WHERE Id_Local = '$Id_Local'";											
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
												$nom = $res["cNum_Local"];	
											} 
											?>
											<input value= "<?php echo "$nom";?>" onKeyUp="BuscarE('Id_Local','resultado','busquedaL');" name="Id_Local" type="text" id="Id_Local" required="required" placeholder="Numero de Local" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
												<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
											</td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Tipo Oferta<label></td>
									<td>
									<?php  
											$Id_TipOf=$registro['Id_TipOf'];
											$sqlR = "SELECT cClase_Of FROM tip_ofe WHERE Id_TipOf = '$Id_TipOf'";											
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
												$nom = $res["cClase_Of"];	
											} 
											?>
									<input  value= "<?php echo "$nom";?>" onKeyUp="BuscarE('Id_TipOf','resultado','busquedaO');" name="Id_TipOf" type="text" id="Id_TipOf" placeholder="Clase de Oferta" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									</td>
									<td></td>
											</tr>
									        <tr>
											<td></td>
											<td colspan="2" class="centrar">  
											<input name="enviar"  type='submit' value='Aceptar'>
											<input name="action" type="hidden" value="upload" /> 
											<input  type='button' value='Cancelar' onclick="javascript:location.href='mOferta.php'">
											</td>
											</tr>
											<tfoot>
											<tr><td colspan="4"></td></tr>
											</tfoot>
											</table>  
							<!-- script que define y configura el calendario--> 
									<script type="text/javascript"> 
									   Calendar.setup({ 
										inputField     :    "campo_fecha3",     // id del campo de texto 
										 ifFormat     :     "%Y/%m/%d",     // formato de la fecha que se escriba en el campo de texto 
										 button     :    "lanzador3"     // el id del botón que lanzará el calendario 
									}); 
									</script> 

									<script type="text/javascript"> 
									   Calendar.setup({ 
										inputField     :    "campo_fecha4",     // id del campo de texto 
										 ifFormat     :     "%Y/%m/%d",     // formato de la fecha que se escriba en el campo de texto 
										 button     :    "lanzador4"     // el id del botón que lanzará el calendario 
									}); 
									</script> 											
											</FORM>
                    								
										
                                <?php
										}
									}
                                break;
                                //actualizar
                                case "4":
							
								
								if ($_FILES["archivo"]['name'] == ""){
									$imagen = $_REQUEST["cImg_Ofe"];
									$Img_tb = $_REQUEST["cImg_tb"];
								} else{
									$status = ""; 
									($_POST["action"] == "upload") or die ();
								 	  // obtenemos los datos del archivo 
										  $tamano = $_FILES["archivo"]['size']; 
										  $tipo = $_FILES["archivo"]['type']; 
										  $archivo = $_FILES["archivo"]['name']; 
									 $prefijo = substr(md5(uniqid(rand())),0,6); 
									 ($archivo != "") or die ("Error al subir la imagen ".$archivo); 
											  // guardamos el archivo a la carpeta "capturas" 
											($tipo == "image/jpeg" || $tipo == "image/jpg") or die ("Sólo se admiten imágenes en <b>.jpg</b> y <b>.jpeg</b>"); 
											  $destino =  "captura/".$archivo; 
											(copy($_FILES['archivo']['tmp_name'],$destino)) or die ("Error al subir la imagen ".$archivo); 
											  $status = "La imagen <b>".$archivo."</b> se a subido correctamente !"; 
											  echo $destino;
											  echo $status; 
										  
										//aquí empieza el código de creación del thumbnail 
										  $source=$destino; // archivo de origen 
										  $dest= $_SERVER['DOCUMENT_ROOT']."tesis/admin/captura/thumb/tb_".$archivo; // archivo de destino 
										  $width_d=117; // ancho de salida 
										  $height_d=82; // alto de salida 

											list($width_s, $height_s, $type, $attr) = getimagesize($source, $info2); // obtengo información del archivo 
										  $gd_s = imagecreatefromjpeg($source); // crea el recurso gd para el origen 
										  $gd_d = imagecreatetruecolor($width_d, $height_d); // crea el recurso gd para la salida 

											imagecopyresampled($gd_d, $gd_s, 0, 0, 0, 0, $width_d, $height_d, $width_s, $height_s); // redimensiona 
											imagejpeg($gd_d, $dest); // graba 
										 
										// Se liberan recursos 
										imagedestroy($gd_s); 
										imagedestroy($gd_d); 
										$imagen = $_SERVER['DOCUMENT_ROOT']."tesis/admin/captura/".$archivo; 
										$Img_tb =  $dest;

								}

	
									$cod_oferta = $db->decodificar($_REQUEST["C"]);
									$nombre = $_REQUEST["cNomb_Ofe"];																
									$descripcion = $_REQUEST["cDesc_Ofe"];
									$fecha_inicio = $_REQUEST["fFecha_Ini"];
									$fecha_fin = $_REQUEST["fFecha_Fin"];
									$producto = $_REQUEST["Id_Prod"];
					                $local = $_REQUEST["Id_Local"]; 
									$tipo_oferta = $_REQUEST["Id_TipOf"];
									$fecha_actual = date("Y-m-d");
									$precio = $_REQUEST["cPrecio"];

									$valor_array = explode(',',$producto); 
									$nombrec = $valor_array[0];
									
									$sqlR = "SELECT Id_Prod FROM producto WHERE cNom_Prod = '$nombrec'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$producto = $res["Id_Prod"];								
									}
									
									$valor_array = explode(',',$local); 
									$nombrel = $valor_array[0];
									
									$sqlR = "SELECT Id_Local FROM Locales WHERE cNum_Local = '$nombrel'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$local = $res["Id_Local"];								
									}
									
									$valor_array = explode(',',$tipo_oferta); 
									$nombreo = $valor_array[0];
									
									$sqlR = "SELECT Id_TipOf FROM tip_ofe WHERE cClase_Of = '$nombreo'";
										$db->Consultar($sqlR);
										if($res = $db->Primero())
									{
										$tipo_oferta = $res["Id_TipOf"];								
									}
									
									if (($fecha_inicio > $fecha_fin) or ($fecha_inicio < $fecha_actual)){
										?> <script>;
										 alert("Fechas Incorrectas");
										 window.history. go( -1); 
										 //location.href ="../admin/mOferta.php";
										 </script>;
										<?php
									}
									else{
											 if($nombre!="" or $cod_oferta!="")
										   {
																			
																					
													$valores = array($nombre, $imagen, $descripcion, $fecha_inicio, $fecha_fin, $producto, $local, $tipo_oferta,$precio,$Img_tb);
														$campos = $camposT->getCamposUpdate($valores);
														if($db->Actualizar($tabla,$campos,"`Id_Oferta`=$cod_oferta"))
													  {
														echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
													  ?> <input name='btn' type='button' onClick="window.location='mOferta.php';" value="aceptar" /></h4> <?php
															
														}
														else
														{
															echo "<h4>Error: No se Guardaron los Cambios";
															echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
														} 
											}
											else{
												echo "<h4>No deje el Campo Vacio";
												echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
										   }
										  
									}
                                break;			
								case "5":
								    $cod = $db->decodificar($_REQUEST["cod"]);							
                                    $campo = $camposT->getCamposDelete($cod);
                                    if($db->Eliminar($tabla, $campo))
                                    {
                                        echo "<h4>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mOferta.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mOferta.php';" value="Aceptar" /> </h4> <?php
                                    }
								break;		
                            }

						
							
                        }catch(Exception $e){
        			echo "error" . $e->getMessage();
			}
			//Desconectar
			$db->Desconectar();
                    ?>

					</div>
               
        </div>

</body>
</html>
<?php } ?>


