<?php
    require("Operaciones.php");
    require("CamposTab.php");	
	require_once("Sesion.php");
	//
	//falta mostrar los datos modificados del patrocinador
	//
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
		
        $tabla = "Evento";
		$tabla2 = "Patrocinador";
		$tabla3 = "Relacion_Eve";
		$db = new  Operaciones();
		$camposT = new CamposTab($tabla);
		$camposT2 = new CamposTab($tabla3);
        $accion=null;
		$subida=null;
		//if (isset($_POST["action"])){
			//$accion = $_POST["action"];
		//} else {
			//$accion = "";
		//}
		
		
		
		
?>      
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Administracion de Eventos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
	<script src="../js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="../js/editcheck.js"></script>
	<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
	<script src="../js/jquery.maskedinput.js"></script>
		
	
	<!--Hoja de estilos del calendario --> 
	<link rel="stylesheet" type="text/css" media="all" href="../css/calendar-blue2.css" title="win2k-cold-1" /> 
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

                                     $sql = "SELECT * FROM $tabla WHERE cNom_Eve LIKE '%$buscar%' ORDER BY cNom_Eve ASC";
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY cNom_Eve ASC";
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
										<input type='submit' id='boton' value="Buscar" title="Buscar" />
									</form>
								</div>
						<div class="clear-div">
						</div>
								<div id="grid">
                                    <table class="grid-box">
									<thead>
									<tr>
                                      <th width="10%" class="alinear"><a href="mEvento.php?accion=<?php echo $db->Codificar("1");?>" >
									  <img src="../imag/agregar.png" title="Agregar Evento" /></a></th>
									  <th width="15%">Evento</th>
                                      <th width="10%">Fecha</th>
									  <th width="8%">Hora</th>
									  <th width="40%">Descripcion</th>
									 <!-- <th width="8%">Imagen</th> -->
									  <th width="15%">Representante</th>
									  <!--<th width="10%">Patrocinador</th>-->
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
										  <a href="mEvento.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Eve"]);?>"> <img src="../imag/editar.png" title="Editar"/></a>
										  <a href="mEvento.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Eve"]);?>"> <img src="../imag/borrar.png" title='Eliminar'/></a> &nbsp;
                                        </td>
										<td><?php echo $db->RecortaString($fila[$i]["cNom_Eve"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["fFecha_Eve"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["nHora_Eve"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cDescr_Eve"],50); ?></td>
										<!-- <td><?php //echo $db->RecortaString($fila[$i]["cImg_Eve"],15); ?></td>-->
										<td>
											<?php  
											$sec = $fila[$i]["Id_Rep"];
											$sql_sec = "SELECT cNom_Rep FROM representante WHERE Id_Rep='$sec'";
											$db->Consultar($sql_sec);
											if($reg_sec = $db->Primero()){
											 echo $db->RecortaString($reg_sec["cNom_Rep"],10); 
											}
										?>
										</td>
										<td>
											<?php  
											//$sec = $fila[$i]["Id_Pat"];
											//$sql_sec = "SELECT cNom_Pat FROM patrocinador WHERE Id_Pat='$sec'";
											//$db->Consultar($sql_sec);
											//if($reg_sec = $db->Primero()){
											 //echo $db->RecortaString($reg_sec["cNom_Pat"],10); 
											//}
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
										<td colspan="8" align="center">
                                        <?php 
                                        //si la pagina no es la primera se imprime anterior
                                        if ($p>1){
											
												?><a href='javascript:void(0);' onClick="window.location='mEvento.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong>$i</strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mEvento.php?p=$i\">$i</a>";
												
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a href=\"mEvento.php?p=" . ($p+1) . "\">Siguiente >></a>";
											
										}
                                    ?>
									</td></tr>
									</tfoot>
                                    </table>
									<script> $( "tr:odd" ) .css( "background-color", "#f9f9f9" ); </script>
								</div>
                                <?php
                                }
                                else
                                { 	//si en numero de registros es igual a cero
                                    //verificacion que la variable buscar no este vacia
                                    if($buscar != null){
									//si se realizo una busqueda y no hay resultados
                                        echo "<h4>NO EXISTE $buscar EN LA TABLA &nbsp; &nbsp; &nbsp;";
                                        echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
										
                                    }
                                    else{
                                        //si la tabla esta vacia
                                        echo "<p>La tabla esta vacia</p>";
										?>
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mEvento.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
									
										<FORM NAME='datos' autocomplete="off" onsubmit="return valforms(this)" ACTION='mEvento.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST' enctype="multipart/form-data">
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
										<td><label>Nombre del evento</label></td>
										<td> <input type='text' name='cNom_Eve' required="required" placeholder="Digite el nombre " editcheck="cvt=UTC"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Fecha del evento</label></td>
										<td> <input type="text" name='fFecha_Eve' required="required" placeholder="YYYY/MM/DD"  id="campo_fecha" readonly >
										<img src="../imag/calendario.gif" width="16" height="16" border="0" title="Fecha de evento" id="lanzador"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Hora</label></td>
										<td> <input type='text' name='nHora_Eve' id='hora' size="5" maxlength="5" onchange="return valforms(this.form,this)" editcheck="type=^([01]\d|20|21|22|23):[0-5]\d$=Digite hora valida con formato HH:MM" required="required" placeholder="Digite la hora"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Descripcion</label></td>
										<td> <input type='text' name='cDescr_Eve' required="required" placeholder="Descripcion del evento" editcheck="cvt=UTC"></td>
										<td></td>
		
									</tr>
									<tr>
										<td></td>
										<td><label>Imagen</label></td>
										<td><input name="archivo" type="file" required size="35" />
									</tr>
									<tr>
										<td></td>
										<td><label>Representante</label></td>
										<td><input onKeyUp="BuscarE('Id_Rep','resultado','busquedaRep');" name="Id_Rep" type="text" id="Id_Rep" placeholder="Nombre del Representante" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Patrocinador</label></td>
										<td> 
										<?php 
											$sql = "SELECT * FROM $tabla2 ORDER BY cNom_Pat ASC";
											$db->Consultar($sql);
											if($db->numrows > 0){                         
												while($r = $db->Siguente()){   
													$fila[] = $r;
												}
												$total = count($fila);
												 for ($i=0; $i<$total; $i++){   
													if (isset($fila[$i])){
														echo "<input type='checkbox' name='patrocinador[]'  value='".$fila[$i]["Id_Pat"]."'> ".ucwords($db->RecortaString($fila[$i]["cNom_Pat"],15))."<br />"; 
													}else{
														break;
													}
												}
											} else {
												echo "No Existen Patrocinador";
										}?>
										
										</td>
										<td></td>
									</tr>
						            <tr>
										<td colspan="4" class="centrar">
										<input name="enviar" type='submit' value='Aceptar'">
										<input name="action" type="hidden" value="upload" />
										<input  type='button' value='Cancelar' onclick="javascript:location.href='mEvento.php'"></td>
									</tr>
									<tfoot>
										<tr><td colspan="4"></td></tr>
									</tfoot>
								</table>         
								<!-- poner marcara para la hora -->
										<script>
										 $("#hora").mask("99:99");
										</script>
									<!-- script que define y configura el calendario--> 
									<script type="text/javascript"> 
									   Calendar.setup({ 
										inputField     :    "campo_fecha",     // id del campo de texto 
										ifFormat     :     "%Y/%m/%d",     // formato de la fecha que se escriba en el campo de texto 
										button     :    "lanzador"     // el id del botón que lanzará el calendario 
									}); 
									</script> 
							</FORM>
														
					<!--Fin Form-->		
                                <?php
                                //final de la accion 1
                                break;
                                // insertar
								case "2":
								
							///////aqui comienza el jale de la foto -->
									
								  	$status = ""; 
									($_POST["action"] == "upload") or die ();
								 	  // obtenemos los datos del archivo 
										  $tamano = $_FILES["archivo"]['size']; 
										  $tipo = $_FILES["archivo"]['type']; 
										  $archivo = $_FILES["archivo"]['name']; 
									 // $prefijo = substr(md5(uniqid(rand())),0,6); 
									 ($archivo != "") or die ("Error al subir la imagen ".$archivo); 
											// guardamos el archivo a la carpeta "capturas" 
											($tipo == "image/jpeg" || $tipo == "image/jpg") or die ("Sólo se admiten imágenes en <b>.jpg</b> y <b>.jpeg</b>"); 
											 $destino =  "../imag/".$archivo; 
												(copy($_FILES['archivo']['tmp_name'],$destino)) or die ("Error al subir la imagen ".$archivo); 
											 $status = "La imagen <b>".$archivo."</b> se a subido correctamente !"; 
											 // echo $destino;
											  //echo $status; 
										  
										//aquí empieza el código de creación del thumbnail 
										  $source=$destino; // archivo de origen 
										  $dest=$_SERVER['DOCUMENT_ROOT']."/tesis/imag/thumb/tb_".$archivo; // archivo de destino 
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
							//////////aqui termina el php de imagen
							
									$fecha = $_REQUEST["fFecha_Eve"];
								    $descripcion = $_REQUEST["cDescr_Eve"];
									$hora = $_REQUEST["nHora_Eve"];
									$imagen =$_SERVER['DOCUMENT_ROOT']."/tesis/imag/".$archivo;
									$nombre = $_REQUEST["cNom_Eve"];
									$rep = $_REQUEST["Id_Rep"];
									$tb = $dest;
									
									
									$valor_array = explode(',',$rep); 
                                    $nombrec = $valor_array[0];
									                                    
                                    $sqlR = "SELECT Id_Rep FROM representante WHERE cNom_Rep = '$nombrec'";
                                    $db->Consultar($sqlR);
                                    if($res = $db->Primero())
                                    {
                                        $rep= $res["Id_Rep"];                               
                                    }
									
									/////empieza a guardar
									if (!isset($_POST['patrocinador'])){ 
										?> <script>;
										alert("No hay patrocinador seleccionado");
										window.history. go( -1); 
										</script>;
										<?php
									} 
									else {
											$valores = array($fecha, $descripcion , $hora, $imagen, $nombre, $rep, $tb);
											$campos = $camposT->getCamposInsert($valores);
																			
											if($db->Insertar($tabla, $campos))
											{
												$ultimo_id = mysql_insert_id();
												foreach ($_POST['patrocinador'] as $Id_Pat)
												{
													$valores2 = array($Id_Pat,$ultimo_id);
													$campos2 = $camposT2->getCamposInsert($valores2);
													$db->Insertar($tabla3,$campos2);
												}
												echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
												?> <input name='btn' type='button' onClick="window.location='mEvento.php';" value="Aceptar" />  </h4><?php
											}
											else
											{
												echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
												?> <input name='btn' type='button' onClick="window.location='mEvento.php';" value="Aceptar" /> </h4> <?php
											}
											//echo $tb;
										//////termina de guardar
									}	
                                //final de la accion 2    
                                break;
								
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Eve` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
						<FORM NAME='datos' autocomplete="off" onsubmit="return valforms(this)" ACTION='mEvento.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST' enctype="multipart/form-data">
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
												<td><label>Nombre del evento</label></td>
												<td> <input type='text' value="<?php echo $registro['cNom_Eve'];?>" name='cNom_Eve' required="required" placeholder="Digite nuevo nombre del evento"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Fecha del evento</label></td>
												<td> <input type='text' value="<?php echo $registro['fFecha_Eve'];?>" name='fFecha_Eve' required="required" placeholder="YYYY/MM/DD" id="campo_fecha2" readonly>
												<img src="../imag/calendario.gif" width="16" height="16" border="0" title="Fecha Inicial" id="lanzador2"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Hora</label></td>
												<td> <input type='text' value="<?php echo $registro['nHora_Eve'];?>" name='nHora_Eve' id="hora" size="5" maxlength="5" onchange="return valforms(this.form,this)" editcheck="type=^([01]\d|20|21|22|23):[0-5]\d$=Digite hora valida con formato HH:MM" required="required" placeholder="Digite la hora"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Descripcion</label></td>
												<td> <input type='text' value="<?php echo $registro['cDescr_Eve'];?>" name='cDescr_Eve' required="required" placeholder="Digite la nueva Descripcion"></td>
												<td></td>
									        </tr>
											<tr>
												<td></td>
												<td><label>Imagen</label></td>
												<td><input name="cImg_Eve" type="text" value="<?php echo $registro['cImg_Eve'];?>" readonly />
												<input type="file" name="archivo" size="35" /></td>
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
												<td><label>Representante</label></td>
												<td> 
												<?php  
												$Id_Rep=$registro['Id_Rep'];
												$sqlR = "SELECT cNom_Rep, cApe_Rep FROM representante WHERE Id_Rep = '$Id_Rep'";											
												$db->Consultar($sqlR);
												if($res = $db->Primero())
												{
													$nom = $res["cNom_Rep"];	
													$ape = $res["cApe_Rep"];
												} 
												?>
												<input value= "<?php echo "$nom, $ape";?>" onKeyUp="BuscarE('Id_Rep','resultado','busquedaRep');" name="Id_Rep" type="text" id="Id_Rep" placeholder="nombre de responsable" style="width:45%; display: inline-block;" required="required"  onFocus="elFocus();" />
												<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
												</td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Patrocinador</label></td>
												<td> 
												<?php
													$fila2="";
													$idpat = $db->decodificar($_GET["cod"]); 
													$sql = "SELECT Id_Pat,cNom_Pat FROM $tabla2 ORDER BY cNom_Pat ASC";
													$db->Consultar($sql);
													if($db->numrows > 0){                         
														while($r = $db->Siguente()){   
															$fila[] = $r;
														}
														$total = count($fila);
														for ($i=0; $i<$total; $i++){
															$sql2 = "SELECT Id_Pat, Id_Eve FROM $tabla3 WHERE Id_Eve='".$db->decodificar($_GET["cod"])."' And Id_Pat='".$fila[$i]["Id_Pat"]."'";   
															$db->Consultar($sql2);
															if($db->numrows > 0){                         
																while($r = $db->Siguente()){   
																	$fila2[] = $r;
															}
																$total2 = count($fila2);
															
																echo "<input type='checkbox'  name='patrocinador[]' value='".$fila[$i]["Id_Pat"]."' checked='checked'> ".ucwords($db->RecortaString($fila[$i]["cNom_Pat"],15))."<br />";
													   } else {
																echo "<input type='checkbox'   name='patrocinador[]' value='".$fila[$i]["Id_Pat"]."' > ".ucwords($db->RecortaString($fila[$i]["cNom_Pat"],15))."<br />";
													   }
															/*if (isset($fila[$i])){
															} else{
																break;
															}*/
														}
													} else {
														echo "No Existen patrocinador";
													}
												?>
												</td>
												<td></td>
											</tr>
									        <tr>
												<td></td>
												<td colspan="2" class="centrar">  
													<input name="enviar" type='submit' value='Aceptar' >
													<input name="action" type="hidden" value="upload" /> 
													<input  type='button' value='Cancelar' onclick="javascript:location.href='mEvento.php'">
											</td>
											<td></td>
											</tr>
											<tfoot>
												<tr><td colspan="4"></td></tr>
											 </tfoot>
										</table> 
										<!-- poner marcara para la hora -->
										<script>
										 $("#hora").mask("99:99");
										</script>
										<!-- script que define y configura el calendario--> 
										<script type="text/javascript"> 
										Calendar.setup({ 
										inputField     :    "campo_fecha2",     // id del campo de texto 
										ifFormat     :     "%Y/%m/%d",     // formato de la fecha que se escriba en el campo de texto 
										button     :    "lanzador2"     // el id del botón que lanzará el calendario 
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
									$imagen = $_REQUEST["cImg_Eve"];
									$Img_tb = $_REQUEST["cImg_tb"];
								} else{
								///////aqui comienza el jale de la foto -->
									
								  	$status = ""; 
									($_POST["action"] == "upload") or die ();
								 	  // obtenemos los datos del archivo 
										  $tamano = $_FILES["archivo"]['size']; 
										  $tipo = $_FILES["archivo"]['type']; 
										  $archivo = $_FILES["archivo"]['name']; 
									 // $prefijo = substr(md5(uniqid(rand())),0,6); 
									 ($archivo != "") or die ("Error al subir la imagen ".$archivo); 
											// guardamos el archivo a la carpeta "capturas" 
											($tipo == "image/jpeg" || $tipo == "image/jpg") or die ("Sólo se admiten imágenes en <b>.jpg</b> y <b>.jpeg</b>"); 
											 $destino =  "../imag/".$archivo; 
												(copy($_FILES['archivo']['tmp_name'],$destino)) or die ("Error al subir la imagen ".$archivo); 
											 $status = "La imagen <b>".$archivo."</b> se a subido correctamente !"; 
											  //echo $destino;
											  //echo $status; 
										  
										//aquí empieza el código de creación del thumbnail 
										  $source=$destino; // archivo de origen 
										  $dest=$_SERVER['DOCUMENT_ROOT']."/tesis/imag/thumb/tb_".$archivo; // archivo de destino 
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
										$imagen = $_SERVER['DOCUMENT_ROOT']."tesis/imag/".$archivo; 
										$Img_tb =  $dest;
							//////////aqui termina el php de imagen
								}
									$cod_evento = $db->decodificar($_REQUEST["C"]);
									
									$fecha = $_REQUEST["fFecha_Eve"];
								    $descripcion = $_REQUEST["cDescr_Eve"];
									$hora = $_REQUEST["nHora_Eve"];
									$nombre = $_REQUEST["cNom_Eve"];
									$rep = $_REQUEST["Id_Rep"];
						    ///
							 	$valor_array = explode(',',$rep); 
                                    $nombrec = $valor_array[0];
                                   									
                                    $sqlR = "SELECT Id_Rep FROM representante WHERE cNom_Rep = '$nombrec'";
                                    $db->Consultar($sqlR);
                                    if($res = $db->Primero())
                                    {
                                        $rep= $res["Id_Rep"];                               
                                    }

								if($fecha!="" or $cod_evento!="")
                                    {
                                        $db->ValidarDatos($fecha);
                                        $db->ValidarDatos($descripcion);
										$db->ValidarDatos($hora);
										$db->ValidarDatos($nombre);
										$db->ValidarDatos($rep);
                                        
                                                $valores = array($fecha, $descripcion, $hora, $imagen, $nombre, $rep, $Img_tb);
                                                $campos = $camposT->getCamposUpdate($valores);
                                                if($db->Actualizar($tabla,$campos,"`Id_Eve`=$cod_evento"))
                                                {
                                                   /*Eliminamos la categorias previas*/
													$campo2 = $camposT2->getCamposDelete($cod_evento);
													$db->Eliminar($tabla3, $campo2);
													
													/*Agregamos las nuevas categorias*/
													
													$ultimo_id = $cod_evento;
													foreach ($_POST['patrocinador'] as $Id_Pat)
													{
														$valores2 = array($Id_Pat,$ultimo_id);
														$campo3 = $camposT2->getCamposInsert($valores2);
														$db->Insertar($tabla3,$campo3);
													}
													
													echo " <h4> CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
													?> <input name='btn' type='button' onClick="window.location='mEvento.php';" value="aceptar" /></h4><?php    
                                                }
                                                else
                                                {
                                                    echo "<<h4>ERROR: NO SE GUARDARON LOS CAMBIOS";
                                                    echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
                                                }
                                    }
                                    else{
                                        echo "<h4>No deje el campo vacio";
                                        echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
                                    }
								
                                break;
	
								case "5":
								    $cod = $db->decodificar($_REQUEST["cod"]);
                                    $campo = $camposT->getCamposDelete($cod);
                                    if($db->Eliminar($tabla, $campo))
                                    {
										$campos2 =  $camposT2->getCamposDelete($cod);
										$db->Eliminar($tabla3, $campos2);
                                        echo "<h4>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mEvento.php';" value="Aceptar" /> </h4><?php
                                    }
                                    else
                                    {
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mEvento.php';" value="Aceptar" /> </h4><?php
                                    }
								break;	
                            }
                        }catch(Exception $e){
        			echo "error" . $e->getMessage();
			}
			$db->Desconectar();
                    ?>
                 </div>
    </div>
</body>
</html>
<?php }  ?>