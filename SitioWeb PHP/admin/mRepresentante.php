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
        $tabla = "Representante";
    
        $db = new  Operaciones();
        $camposT = new CamposTab($tabla);
    
        $accion=null;
		
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
	<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
	<script src="../js/jquery-1.11.1.min.js"></script>
	<script src="../js/jquery.maskedinput.js"></script>
	<script type="text/javascript" src="../js/editcheck.js"></script>
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

                                      $sql = "SELECT * FROM $tabla WHERE `cNom_Rep` LIKE '%$buscar%' ORDER BY `cNom_Rep` ASC";
									  
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY cNom_Rep ASC";
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
									  <div class="titulos">
        							
									</div>
									<div id="busqueda">
									<form ACTION='mRepresentante.php' METHOD='POST'>				
									<input type='image' id='boton' src="imagenes/Buscar2.png" />
									<input type='search' placeholder='Buscar...' id='b' name="b" />
									<input type="submit" value="Buscar" name="busqueda">
									</form>
									</div>
									<div class="clear-div"></div>
									<div id="grid" >
                                    <table class="grid-box">
									<thead>
									<tr>
                                      <th width="20%" class="alinear"><a href="mRepresentante.php?accion=<?php echo $db->Codificar("1");?>" title="Agregar Representante">
                                       <img src="../imag/agregar.png" title='Agregar'/></a></th>
                                      <th width="20%" style="">Nombre</th>
									  <th width="20%" style="">Apellido</th>
									  <th width="15%" style="">Telefono</th>
									  <th width="15%" style="">Telefono Movil</th>
									  <th width="15%" style="">DUI</th>
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
										  <a href="mRepresentante.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Rep"]);?>" title='Eliminar'> <img src="../imag/borrar.png" title='Eliminar' ></a>
                                          <a href="mRepresentante.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Rep"]);?>" title="Editar"> <img src="../imag/editar.png" title='Editar'></a>
                                        </td>
										<td><?php echo $db->RecortaString($fila[$i]["cNom_Rep"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cApe_Rep"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cTel_Rep"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cMovil_Rep"],15); ?></td>										
										<td><?php echo $db->RecortaString($fila[$i]["cDui"],15); ?></td>
                                        
                                        
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
                                    <tr class=""><td colspan="7" align="center">
                                        <?php 
                                        //si la pagina no es la primera se imprime anterior
                                        if ($p>1){
											
												?><a class='' href='javascript:void(0);' onClick="window.location='mRepresentante.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
												//echo "<a class='ant' href='javascript:void(0);' onclick="."window.location=\'grados.php?re=$l&p=" . ($p-1) . "'\"><< Anterior</a>&nbsp";
											
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong>$i</strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mRepresentante.php?p=$i\">$i</a>";
												
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a class='' href=\"mRepresentante.php?p=" . ($p+1) . "\">Siguiente >></a>";
											
										}
                                    ?>
                                    </td> </tr>
									</tfoot>
                                    </table>
									<script> $( "tr:odd" ).css( "background-color", "#f9f9f9" ); </script>
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
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mRepresentante.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
									
                    
									<FORM NAME='datos' autocomplete="off" onsubmit="return valforms(this)" ACTION='mRepresentante.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST'>
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
										<td><label>Nombre<label></td>
										<td> <input type='text' name='cNom_Rep' required="required" placeholder="Digite el Nombre" editcheck="cvt=UTC"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Apellido<label></td>
										<td> <input type='text' name='cApe_Rep' required="required" placeholder="Digite el Apellido" editcheck="cvt=UTC"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Telefono<label></td>
										<td> <input type='text' id="phone" name='cTel_Rep' required="required" placeholder="Digite su telefono"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Telefono Movil<label></td>
										<td> <input type='text' id="fax" name='cMovil_Rep' required="required" placeholder="Digite su telefono movil"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Email<label></td>
										<td> <input type='text' name='cEmail_Rep' size="50" maxlength="80"  editcheck="type=email;cvt=T" required="required" placeholder="Digite su email"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>DUI<label></td>
										<td> <input type='text'id='dui' name='cDui' required="required" placeholder="Digite el numero de DUI"></td>
										<td></td>
									</tr>
									<tr>
									<td colspan="4" class="centrar"><input  type='submit' value='Aceptar'>							
									<input  type='button' value='Cancelar' onclick="javascript:location.href='mRepresentante.php'">
									</td>
									</tr>
									<tfoot>
                                	<tr><td colspan="4"></td></tr>
                                </tfoot>
									</table>                    
									</FORM>

                                <?php
                                //final de la accion 1
                                break;
                                // insertar
								case "2":
																    
									$cNom_Rep = $_REQUEST["cNom_Rep"];
								    $cApe_Rep = $_REQUEST["cApe_Rep"];
									$cTel_Rep = $_REQUEST["cTel_Rep"];
									$cMovil_Rep = $_REQUEST["cMovil_Rep"];
									$cEmail_Rep = $_REQUEST["cEmail_Rep"];
									$cDui = $_REQUEST["cDui"];
									$dui="";
									$email="";
									//$clave = MD5($clave);
									
									
								//Prueba evitar duplicidad
								$sqlR = "SELECT * FROM representante WHERE cDui = '$cDui'";
										$db->Consultar($sqlR);
										if($res = $db->Primero())
										{										
										$dui = $res ["cDui"];
										$email = $res ["cEmail_Rep"];
										}
									 if (($dui == $cDui and $email == $cEmail_Rep ) or ($dui == $cDui and $email != $cEmail_Rep) or ($dui != $cDui and $email == $cEmail_Rep)){
										echo "<h4>ERROR DEL SISTEMA, YA EXISTEN LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mRepresentante.php';" value="Aceptar" /> </h4> <?php
									 
									 }
								else {
									
																
									$valores = array($cNom_Rep, $cApe_Rep, $cTel_Rep, $cMovil_Rep, $cEmail_Rep, $cDui);
									$campos = $camposT->getCamposInsert($valores);
									
									if($db->Insertar($tabla, $campos))
									{
										echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mRepresentante.php';" value="Aceptar" /> </h4> <?php
									}
									else
									{
										echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mRepresentante.php';" value="Aceptar" /> </h4> <?php
									}
								}

                                //final de la accion 2    
                                break;
								
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Rep` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
											
											
											<FORM NAME='datos' onsubmit="return valforms(this)" ACTION='mRepresentante.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST'>
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
												<td><label>Nombre<label></td><td> 
												<input type='text' value="<?php echo $registro['cNom_Rep'];?>" name='cNom_Rep' required="required" placeholder="Digite el nombre" editcheck="cvt=UTC"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Apellido<label></td>
												<td> <input type='text' value="<?php echo $registro['cApe_Rep'];?>" name='cApe_Rep' required="required" placeholder="Digite el apellido" editcheck="cvt=UTC"></td>
												<td></td>
											</tr>
									        <tr>
												<td></td>
												<td><label>Telefono<label></td>
												<td> <input type='text' id="phone" value="<?php echo $registro['cTel_Rep'];?>" name='cTel_Rep' required="required" placeholder="Digite su telefono"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Telefono Movil<label></td>
												<td> <input type='text' id="fax" value="<?php echo $registro['cMovil_Rep'];?>" name='cMovil_Rep' required="required" placeholder="Digite su telefono movil"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Email<label></td>
												<td> <input type='text' value="<?php echo $registro['cEmail_Rep'];?>" name='cEmail_Rep' size="50" maxlength="80"  editcheck="type=email;cvt=T"required="required" placeholder="Digite su Email"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>DUI<label></td>
												<td> <input type='text' type='text' id='dui' value="<?php echo $registro['cDui'];?>" name='cDui' required="required" placeholder="Digite el numero de dui"></td>
												<td></td>
											</tr>
									        <tr>
											<td></td>
											<td colspan="2" class="centrar">
											<input  type='submit' value='Aceptar' >
											
											<input  type='button' value='Cancelar' onclick="javascript:location.href='mRepresentante.php'">
											</td>
											<td></td>
											</tr>
											<tfoot>
											<tr><td colspan="4"></td></tr>
											</tfoot>
											</table>  
											
											</FORM>
     
                                <?php
										}
									}
                                break;
                                //actualizar
                                case "4":
									$cod_rep = $db->decodificar($_REQUEST["C"]);
									$cNom_Rep = $_REQUEST["cNom_Rep"];
								    $cApe_Rep= $_REQUEST["cApe_Rep"];
									$cTel_Rep= $_REQUEST["cTel_Rep"];
									$cMovil_Rep= $_REQUEST["cMovil_Rep"];
									$cEmail_Rep= $_REQUEST["cEmail_Rep"];		
									$cDui= $_REQUEST["cDui"];
									$idrep="";
									$dui="";
									$email="";
									
										$sqlR = "SELECT * FROM representante WHERE cDui = '$cDui'";
										$db->Consultar($sqlR);
										if($res = $db->Primero())
										{										
										$dui = $res ["cDui"];
										$email = $res ["cEmail_Rep"];
										$idRR= $res["Id_Rep"];
										}
									 if (($dui == $cDui and $email == $cEmail_Rep ) or ($dui == $cDui and $email != $cEmail_Rep) or ($dui != $cDui and $email == $cEmail_Rep)){
										if($cod_rep != $idRR){
											echo "<h4>ERROR DEL SISTEMA, YA EXISTEN LOS DATOS &nbsp &nbsp";
											?> <input name='btn' type='button' onClick="window.location='mRepresentante.php';" value="Aceptar" /> </h4> <?php
										}
										else{
											$sqlR = "SELECT * FROM representante WHERE cEmail_Rep = '$cEmail_Rep'";
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
												$cod2= $res["Id_Rep"];
												if($cod2 != $cod_rep){
													echo "<h4>2 Ya existe el correo  &nbsp; &nbsp; &nbsp;";
													?> <input name='btn' type='button' onClick="window.location='mRepresentante.php';" value="aceptar" /></h4> <?php
												}
												else
												{
													goto Aqui;
												}
											}
											else{
													goto Aqui;
											}
										}
									 }
								else {
                                        
										if ($idrep == $cod_rep and $email == $cEmail_Rep){
											////modificar
											if($cNom_Rep!="" or $cod_rep!="")
												{
													goto Aqui;
												}
												else{
													echo "<h4>No deje el Campo Vacio";
													echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
												}
											//termina de modificar
										}
										else {
											$sqlR = "SELECT * FROM representante WHERE cEmail_Rep = '$cEmail_Rep'";
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
												$cod2= $res["Id_Rep"];
												if($cod2 != $cod_rep){
													echo "<h4>Ya existe el correo  &nbsp; &nbsp; &nbsp;";
													?> <input name='btn' type='button' onClick="window.location='mRepresentante.php';" value="aceptar" /></h4> <?php
												}
												else
												{
													goto Aqui;
												}
											}
											else {
											////modificar
												if($cNom_Rep!="" or $cod_rep!="")
												{	
													Aqui:
													$db->ValidarDatos($cNom_Rep);
													$db->ValidarDatos($cApe_Rep);
													$db->ValidarDatos($cTel_Rep);
													$db->ValidarDatos($cMovil_Rep);
													$valores = array($cNom_Rep, $cApe_Rep, $cTel_Rep, $cMovil_Rep, $cEmail_Rep, $cDui);
													$campos = $camposT->getCamposUpdate($valores);
													if($db->Actualizar($tabla,$campos,"`Id_Rep`=$cod_rep"))
													{
														echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
														?> <input name='btn' type='button' onClick="window.location='mRepresentante.php';" value="aceptar" /></h4> <?php
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
											//termina de modificar
											}
										}       
								}				
                                break;
								
								case "5":
								    $cod = $db->decodificar($_REQUEST["cod"]);
									
                                    $campo = $camposT->getCamposDelete($cod);
                                    if($db->Eliminar($tabla, $campo))
                                    {
                                        echo "<h4>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mRepresentante.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mRepresentante.php';" value="Aceptar" /> </h4> <?php
                                    }
								break;
								
                            }
                        }catch(Exception $e){
        			echo "error" . $e->getMessage();
			}
			//Desconectar
			$db->Desconectar();
                    ?>
					               <script>
 $("#phone").mask("9999-9999");
 $("#fax").mask("9999-9999");
$("#dui").mask("99999999-9"); 
</script>
        </div>
        
    </div>
</body>
</html>
<?php } ?>

