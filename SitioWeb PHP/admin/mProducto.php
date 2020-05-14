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
        $tabla = "Producto";
        $db = new  Operaciones();
        $camposT = new CamposTab($tabla);
        $accion=null;
?>      
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Administracion de Productos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
	<script src="../js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
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

                                       $sql = "SELECT * FROM $tabla WHERE cNom_Prod LIKE '%$buscar%' ORDER BY `cNom_Prod` ASC";
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY cNom_Prod ASC";
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
										<form ACTION='mProducto.php' METHOD='POST'>
											  <input type='search' placeholder='Buscar...' id='b' name="b" />
											<input type="submit" value="busqueda" name="busqueda">
										</form>
									</div>
									<div class="clear-div"></div>
									<div id="grid">
                                    <table class="grid-box">
									<thead>
									<tr>
                                      <th width="10%" class="alinear"><a href="mProducto.php?accion=<?php echo $db->Codificar("1");?>">
                                       <img src="../imag/agregar.png"title="Agregar Producto" /></a></th>
                                      <th width="25%">Nombre Producto</th>
									  <th width="65%">Categoria de Producto</th>
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
										  <a href="mProducto.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Prod"]);?>" > <img src="../imag/editar.png" title="Editar"/></a>
										  <a href="mProducto.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Prod"]);?>" > <img src="../imag/borrar.png" title='Eliminar'/></a>
                                          
                                        </td>
										<td><?php echo $db->RecortaString($fila[$i]["cNom_Prod"],15); ?></td>
										<td>
											<?php  
											$sec = $fila[$i]["Id_Catp"];
											$sql_sec = "SELECT cNom_CatP FROM categoriap WHERE Id_Catp='$sec'";
											$db->Consultar($sql_sec);
											if($reg_sec = $db->Primero()){
											 echo $db->RecortaString($reg_sec["cNom_CatP"],25); 
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
											
												?><a href='javascript:void(0);' onClick="window.location='mProducto.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
											
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong>$i</strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mProducto.php?p=$i\">$i</a>";
												
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a href=\"mProducto.php?p=" . ($p+1) . "\">Siguiente >></a>";
											
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
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mProducto.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
                    
									<FORM NAME='datos' ACTION='mProducto.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST' autocomplete="off">
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
									<td><label>Nombre Producto<label></td><td> 
									<input type='text' name='cNom_Prod' required="required" placeholder="Digite el Nombre de Producto" MAXLENGTH="30"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Categoria de Producto<label></td>
									<td><input onKeyUp="BuscarE('Id_Catp','resultado','busquedaCP');" name="Id_Catp" type="text" id="Id_Catp" placeholder="Categoria de Producto" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									</td>
									<td></td>
									</tr>
						            <tr>
									<td colspan="4" class="centrar">  
									<input  type='submit' value='Aceptar' title="Aceptar">
									<input  type='button' value='Cancelar' onclick="javascript:location.href='mProducto.php'" title="Cancelar">
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
								    
									$nombre = $_REQUEST["cNom_Prod"];
								    $categoria = $_REQUEST["Id_Catp"];
									
									$valor_array = explode(',',$categoria); 
									$nombrec = $valor_array[0];
									
									$sqlR = "SELECT Id_Catp FROM categoriap WHERE cNom_CatP = '$nombrec'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$categoria = $res["Id_Catp"];								
									}
									
									$sqlR = "SELECT cNom_Prod FROM producto WHERE cNom_Prod = '$nombre'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										echo "<h4>YA EXISTE ESTE PRODUCTO &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mProducto.php';" value="Aceptar" /></h4><?php
									}
									else {
										$valores = array($nombre, $categoria);
										$campos = $camposT->getCamposInsert($valores);
										if($db->Insertar($tabla, $campos))
										{
											echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
											?> <input name='btn' type='button' onClick="window.location='mProducto.php';" value="Aceptar" /> </h4> <?php
										}
										else
										{
											echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
											?> <input name='btn' type='button' onClick="window.location='mProducto.php';" value="Aceptar" /> </h4> <?php
										}
									}
									
									
						
                                //final de la accion 2    
                                break;
								
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Prod` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
										
											<FORM NAME='datos' ACTION='mProducto.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST' autocomplete="off">
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
											<td><label>Nombre de Producto<label></td>
											<td> <input type='text' MAXLENGTH="30" value="<?php echo $registro['cNom_Prod'];?>" name='cNom_Prod' required="required" placeholder="Digite el nombre"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
									        <td><label>Categoria de Producto<label></td>
											<td>
											<?php  
											$Id_Catp=$registro['Id_Catp'];
											$sqlR = "SELECT cNom_CatP FROM categoriap WHERE Id_Catp = '$Id_Catp'";											
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
												$nom = $res["cNom_CatP"];	
											} 
											?>
											<input value= "<?php echo "$nom";?>" onKeyUp="BuscarE('Id_Catp','resultado','busquedaCP');" name="Id_Catp" type="text" id="Id_Catp" required="required" placeholder="Categoria de Producto" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
												<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
											</td>
											<td></td>
									        </tr>
									        <tr>
											<td></td>
											<td colspan="2" class="centrar">  
											<input  type='submit' value='Aceptar' >
											<input  type='button' value='Cancelar' onclick="javascript:location.href='mProducto.php'">
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
									$cod_producto = $db->decodificar($_REQUEST["C"]);
									$nombre = $_REQUEST["cNom_Prod"];
									$categoria = $_REQUEST["Id_Catp"];
									$idprod ="";
									$nomprod="";
								   
									$valor_array = explode(',',$categoria); 
									$nombrec = $valor_array[0];
									
									$sqlR = "SELECT Id_Catp FROM categoriap WHERE cNom_CatP = '$nombrec'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$categoria = $res["Id_Catp"];								
									}
			// Codigo Duplicidad
				//Extraer valores para comparacion
									$sqlR = "SELECT * FROM producto WHERE Id_Prod = '$cod_producto'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$idprod = $res["Id_Prod"];
										$nomprod = $res["cNom_Prod"];
									}
				//Condicionar
									if($cod_producto == $idprod and $nombre == $nomprod)
									{
									 if($nombre!="" or $cod_producto!="")
                                   {
                                       $db->ValidarDatos($nombre);
                                       $db->ValidarDatos($categoria);
                                            
                                                $valores = array($nombre, $categoria);
                                                $campos = $camposT->getCamposUpdate($valores);
                                                if($db->Actualizar($tabla,$campos,"`Id_Prod`=$cod_producto"))
                                                {
                                                    echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mProducto.php';" value="aceptar" /></h4> <?php
                                                    
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
									else
									{
									$sqlR = "SELECT cNom_Prod FROM producto WHERE cNom_Prod = '$nombre'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										echo "<h4>YA EXISTE ESTE PRODUCTO &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mProducto.php';" value="Aceptar" /></h4><?php
									}
									else {
									 if($nombre!="" or $cod_producto!="")
                                   {
                                       $db->ValidarDatos($nombre);
                                       $db->ValidarDatos($categoria);
                                            
                                                $valores = array($nombre, $categoria);
                                                $campos = $camposT->getCamposUpdate($valores);
                                                if($db->Actualizar($tabla,$campos,"`Id_Prod`=$cod_producto"))
                                                {
                                                    echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mProducto.php';" value="aceptar" /></h4> <?php
                                                    
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
									}
									
									
							
//FIN CONDICION
                                   
                                break;			
								case "5":
								    $cod = $db->decodificar($_REQUEST["cod"]);							
                                    $campo = $camposT->getCamposDelete($cod);
                                    if($db->Eliminar($tabla, $campo))
                                    {
                                        echo "<h4>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mProducto.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mProducto.php';" value="Aceptar" /> </h4> <?php
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
        


