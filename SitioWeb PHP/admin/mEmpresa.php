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
        $tabla = "Empresa";
    
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

                                       $sql = "SELECT * FROM $tabla WHERE `cEmp_Email` LIKE '%$buscar%' ORDER BY `cEmp_Email` ASC";
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY `cEmp_Email` ASC";
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
                                    <form>
                                       <div id="busqueda"> 
									<form ACTION='mEmpresa.php' METHOD='POST'>	

										<input type='image' id='boton' src="imagenes/Buscar2.png" />
										<input type='search' placeholder='Buscar...' id='b' name="b" />
										<input type="submit" value="Buscar" name="busqueda">
									</form>
									</div>
									<div class="clear-div"></div>
									 
                                    </form>
									</div>	
									<div class="clear-div"></div>
									<div id="grid" >
                                    <table class="grid-box">
									<thead>
									<tr>
                                      <th width="10%" class="alinear" ><a href="mEmpresa.php?accion=<?php echo $db->Codificar("1");?>" title="Agregar Empresa">
                                       <img src="../imag/agregar.png" title='Agregar'/></a></th>
                                      <th width="10%" >Telefono</th>
									  <th width="10%">Fax</th>
									  <th width="20%">Email</th>
									  <th width="10%">Empresa</th>
									  <th width="20%">Responsable</th>
									  <th width="20%">Pagina Web</th>
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
										  <a href="mEmpresa.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Emp"]);?>" > <img src="../imag/editar.png" title='Editar' ></a>
                                          <a href="mEmpresa.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Emp"]);?>" > <img src="../imag/borrar.png" title='Eliminar' ></a>
                                        </td>
										<td><?php echo $db->RecortaString($fila[$i]["cEmp_Tel"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cEmp_Fax"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cEmp_Email"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cNom_Emp"],15); ?></td>
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
										<td><?php echo $db->RecortaString($fila[$i]["cWeb_Emp"],15); ?></td>
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
                                    <tr><td colspan="7" align="center">
                                        <?php 
                                        //si la pagina no es la primera se imprime anterior
                                        if ($p>1){
											
												?><a href='javascript:void(0);' onClick="window.location='mEmpresa.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
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
												
													echo "<a href=\"mEmpresa.php?p=$i\">$i</a>";
												
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a href=\"mEmpresa.php?p=" . ($p+1) . "\">Siguiente >></a>";
											
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
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mEmpresa.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
									
                    
									<FORM NAME='datos' onsubmit="return valforms(this)"  ACTION='mEmpresa.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST'>
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
										<td><label>Telefono<label></td>
										<td> <input type='text' id="phone" name='cEmp_Tel' required="required" placeholder="Digite el Telefono"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Fax<label></td>
										<td> <input type='text' id="fax" name='cEmp_Fax' required="required" placeholder="Digite el Fax"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Email<label></td>
										<td> <input type='text' name='cEmp_Email' size="50" maxlength="80"  editcheck="type=email;cvt=T" required="required" placeholder="Digite su email"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Empresa<label></td>
										<td> <input type='text' name='cNom_Emp' required="required" placeholder="Digite el nombre de la empresa"></td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Representante</label></td>
										<td> 
										<input onKeyUp="BuscarE('Id_Rep','resultado','busquedaR');" name="Id_Rep" type="text" id="Id_Rep" placeholder="nombre de representante" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
										</td>
										<td></td>
									</tr>
									<tr>
										<td></td>
										<td><label>Pagina Web<label></td>
										<td> <input type='text' name='cWeb_Emp' required="required" placeholder="Pagina Web"></td>
										<td></td>
									</tr>
									<tr>
									<td colspan="4" class="centrar"><input  type='submit' value='Aceptar'>
									<input  type='button' value='Cancelar' onclick="javascript:location.href='mEmpresa.php'">
									
									
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
																    
									$cEmp_Tel = $_REQUEST["cEmp_Tel"];
								    $cEmp_Fax = $_REQUEST["cEmp_Fax"];
									$cEmp_Email = $_REQUEST["cEmp_Email"];
									$cNom_Emp = $_REQUEST["cNom_Emp"];
									$Id_Rep = $_REQUEST["Id_Rep"];
									$cWeb_Emp = $_REQUEST["cWeb_Emp"];
									
									$valor_array = explode(',',$Id_Rep); 
									$nombre = $valor_array[0];
									@$ape = Trim($valor_array[1]);
									
									$sqlR = "SELECT Id_Rep FROM representante WHERE cNom_Rep = '$nombre' AND cApe_Rep = '$ape'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$Id_Rep = $res["Id_Rep"];								
									}
									else{
										///
										echo "<h4>ERROR DEL SISTEMA, NO EXISTE EL REPRESENTANTE &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="Aceptar" /> </h4> <?php
										exit;
										///									
									}
									
									
									
						//Prueba evitar duplicidad
									$sqlR = "SELECT cEmp_Email FROM empresa WHERE cEmp_Email = '$cEmp_Email'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										///
										echo "<h4>ERROR DEL SISTEMA, YA EXISTEN LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="Aceptar" /> </h4> <?php
										
										///												
									}
									else {
									
									
																	
									$valores = array($cEmp_Tel, $cEmp_Fax, $cEmp_Email, $cNom_Emp, $Id_Rep, $cWeb_Emp);
									$campos = $camposT->getCamposInsert($valores);
									
									if($db->Insertar($tabla, $campos))
									{
										echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="Aceptar" /> </h4> <?php
									}
									else
									{
										echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="Aceptar" /> </h4> <?php
									}
									}

									
                               
							   //final de la accion 2    
                                break;						
					
					
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Emp` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
											
											
											<FORM NAME='datos' onsubmit="return valforms(this)"  ACTION='mEmpresa.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST'>
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
												<td><label>Telefono<label></td>
												<td> <input type='text' id="phone" value="<?php echo $registro['cEmp_Tel'];?>" name='cEmp_Tel' required="required" placeholder="Digite el telefono"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Fax<label></td>
												<td> <input type='text' id="fax" value="<?php echo $registro['cEmp_Fax'];?>" name='cEmp_Fax' required="required" placeholder="Digite el fax"></td>
												<td></td>
											</tr>
									        <tr>
												<td></td>
												<td><label>Email<label></td>
												<td> <input type='text' size="50" maxlength="80"  editcheck="type=email;cvt=T" value="<?php echo $registro['cEmp_Email'];?>" name='cEmp_Email' required="required" placeholder="Digite el email"></td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td><label>Empresa<label></td>
												<td> <input type='text' value="<?php echo $registro['cNom_Emp'];?>" name='cNom_Emp' required="required" placeholder="Digite el nombre de la empresa"></td>
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
									<input value= "<?php echo "$nom, $ape";?>" onKeyUp="BuscarE('Id_Rep','resultado','busquedaR');" name="Id_Rep" type="text" id="Id_Rep" placeholder="nombre de responsable" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>

									</td>
									<td></td>
											</tr>
											 <tr>
												<td></td>
												<td><label>Pagina Web<label></td>
												<td> <input type='text' value="<?php echo $registro['cWeb_Emp'];?>" name='cWeb_Emp' required="required" placeholder="Digite pagina web"></td>
												<td></td>
											</tr>
									        <tr>
											<td></td> 
											<td colspan="2" class="centrar">
											<input  type='submit' value='Aceptar' >
											<input  type='button' value='Cancelar' onclick="javascript:location.href='mEmpresa.php'">
											</td>
											<td>
											<td></td> 
											</td>
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
									$cod_empr = $db->decodificar($_REQUEST["C"]);
									$cEmp_Tel = $_REQUEST["cEmp_Tel"];
								    $cEmp_Fax= $_REQUEST["cEmp_Fax"];
									$cEmp_Email= $_REQUEST["cEmp_Email"];
									$cNom_Emp= $_REQUEST["cNom_Emp"];
									$Id_Rep= $_REQUEST["Id_Rep"];
									$cWeb_Emp= $_REQUEST["cWeb_Emp"];
									
									$valor_array = explode(',',$Id_Rep); 
									$nombre = $valor_array[0];
									@$ape = Trim($valor_array[1]);
									
									$sqlR = "SELECT * FROM representante WHERE cNom_Rep = '$nombre' AND cApe_Rep = '$ape'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$Id_Rep = $res["Id_Rep"];							
									}
									else{
										///
										echo "<h4>ERROR DEL SISTEMA, NO EXISTE EL REPRESENTANTE &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="Aceptar" /> </h4> <?php
										exit;
										///									
									}																
									
									$sqlR = "SELECT * FROM empresa WHERE cEmp_Email = '$cEmp_Email'";
									$db->Consultar($sqlR);
									$IdEmp=0;
									$email="";
										if($res = $db->Primero())
										{
											$IdEmp = $res ["Id_Emp"];
											$email = $res ["cEmp_Email"];
										}
										if (!$IdEmp == $cod_empr and $email == $cEmp_Email){
											////modificar
											if($cNom_Emp!="" or $cod_empr!="")
											{
												$db->ValidarDatos($cEmp_Fax);
												$db->ValidarDatos($cNom_Emp);
												$db->ValidarDatos($IdEmp);		
												
									
                                                $valores = array($cEmp_Tel, $cEmp_Fax, $cEmp_Email, $cNom_Emp, $Id_Rep, $cWeb_Emp);
                                                $campos = $camposT->getCamposUpdate($valores);	
												print_r ($campos);												
                                                if($db->Actualizar($tabla,$campos,"`Id_Emp`=$IdEmp"))
                                                {
                                                    echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="aceptar" /></h4> <?php
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
                                    else{
									$sqlR = "SELECT * FROM empresa WHERE cEmp_Email = '$cEmp_Email'";
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
											
											echo "<h4>Ya existe  &nbsp; &nbsp; &nbsp;";
                                            ?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="aceptar" /></h4> <?php
                                                    
											}											
											else {
											////modificar
											if($cNom_Emp!="" or $cod_empr!="")
											
												{
												$db->ValidarDatos($cEmp_Fax);                                        
												$db->ValidarDatos($cNom_Emp);
												$db->ValidarDatos($IdEmp);								
                                                                                   
                                                $valores = array($cEmp_Tel, $cEmp_Fax, $cEmp_Email, $cNom_Emp, $Id_Rep, $cWeb_Emp);
                                                $campos = $camposT->getCamposUpdate($valores);
												
                                                if($db->Actualizar($tabla,$campos,"`Id_Emp`=$IdEmp"))
                                                {
                                                    echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mEmpresa.php';" value="aceptar" /></h4> <?php
                                                    
                                                }
												
                                                else
                                                {
                                                    echo "<h4>Error: No se Guardaron los Cambios";
                                                    echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
                                                
                                          }
										  }
										  
                                        //termina de modificar
										}
									   }
									   
									   break;
									
                                   						
								case "5":
								    $cod = $db->decodificar($_REQUEST["cod"]);
									
                                    $campo = $camposT->getCamposDelete($cod);
                                    if($db->Eliminar($tabla, $campo))
                                    {
                                        echo "<h4>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mEmpresa.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mEmpresa.php';" value="Aceptar" /> </h4> <?php
                                    }
								break;
								
                            }
                        }catch(Exception $e){
        			echo "error" . $e->getMessage();
			}
                    ?>
					                  <script>
 $("#phone").mask("9999-9999");
  $("#fax").mask("9999-9999");
</script>
                </div>
                
            </div>
        
        </div>
        
        
    
                
        </div>
    </div>
</body>
</html>
<?php } ?>

