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
        $tabla = "Patrocinador";
        $db = new  Operaciones();
        $camposT = new CamposTab($tabla);
        $accion=null;
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Administracion de patrocinadores</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
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
                                    $buscar = Trim(@$_REQUEST["busqueda"]);
									
                                    //validar datos de la busqueda
                                    $db->ValidarDatos($buscar);
        
									//if($buscar != null){

                                      // $sql = "SELECT `id_usr`,`nombres`, `apellidos`, `login`, `email_usr`, `id_empresa` FROM $tabla WHERE `$campo` LIKE '%$buscar%' ORDER BY `nombres` ASC";
                                    //}
                                    //else
                                    //{
                                    $sql = "SELECT * FROM $tabla ORDER BY cNom_Pat ASC";
                                    //}
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
                                    	<h1>Administracion de patrocinadores</h1>
                                    </div>
                                    <div id="busqueda">
                                    <form>
                                        <!--<input type='image' id='boton' src="imagenes/Buscar2.png" />-->
                                        <input type='search' placeholder='Buscar...' id='b' />
                                        <input type='button' id='boton' value="Buscar" />
                                    </form>
                                    </div>
                                    <div class="clear-div"></div>
									<div id="grid">
                                    <table class="grid-box">
                                    <thead>
									<tr>
                                      <th width="10%" class="alinear"><a href="mPatrocinador.php?accion=<?php echo $db->Codificar("1");?>" title="Agregar Patrocinador"><img src="../imag/agregar.png" title="Agregar Patrocinador" /></a></th>
                                      <th width="25%">Patrocinador</th>
                                      <th width="10%">Telefono</th>
									  <th width="15%">Email</th>
                                      <th width="20%">Contacto</th>
                                      <th width="20%">Detalle</th>
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
                                          <a href="mPatrocinador.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Pat"]);?>" title="Editar"><img src="../imag/editar.png" title="Editar" /></a>
                                          <a href="mPatrocinador.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Pat"]);?>" title='Eliminar'><img src="../imag/borrar.png" title="Eliminar" /></a>
                                        </td>
										<td><?php echo $db->RecortaString($fila[$i]["cNom_Pat"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cTel_Pat"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cEmail_Pat"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cNomC_Pat"],15); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cDet_Pat"],15); ?></td>
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
											
												?><a href='javascript:void(0);' onClick="window.location='mPatrocinador.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
												
											
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong>$i</strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mPatrocinador.php?p=$i\">$i</a>";
												
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a href=\"mPatrocinador.php?p=" . ($p+1) . "\">Siguiente >></a>";
											
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
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mPatrocinador.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>

									<FORM NAME='datos'  onsubmit="return valforms(this)"  ACTION='mPatrocinador.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST'>
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
											<td><label>Patrocinador</label></td>
                                            <td> <input type='text' name='cNom_Pat' required="required" placeholder="Digite el Patrocinador"></td>
                                            <td></td>
										</tr>
										<tr>
                                        	<td></td>
											<td><label>Telefono</label></td>
                                            <td> <input type='text' id="phone" name='cTel_Pat' required="required" placeholder="Digite Telefono"></td>
                                            <td></td>
										</tr>
										<tr>
                                        	<td></td>
											<td><label>Email</label></td>
                                            <td> <input type='text' name='cEmail_Pat' size="50" maxlength="80"  editcheck="type=email;cvt=T"required="required" placeholder="Digite Email"></td>
                                            <td></td>
										</tr>
										<tr>
                                        	<td></td>
											<td><label>Contacto</label></td>
                                            <td> <input type='text' name='cNomC_Pat' required="required" placeholder="Digite Nombre de Contacto"></td>
                                            <td></td>
										</tr>
										<tr>
                                        	<td></td>
											<td><label>Detalle</label></td>
                                            <td> <input type='text' name='cDet_Pat' required="required" placeholder="Digite Detalle"></td>
                                            <td></td>
										</tr>
						           		<tr>
											<td colspan="4" class="centrar">  
												<input  type='submit' value='Aceptar'>
												<input  type='button' value='Cancelar' onclick="javascript:location.href='mPatrocinador.php'">
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
						


								    
									$nompat = $_REQUEST["cNom_Pat"];
								    $telpat = $_REQUEST["cTel_Pat"];
									$emailpat = $_REQUEST["cEmail_Pat"];
								    $contactopat = $_REQUEST["cNomC_Pat"];
									$detpat = $_REQUEST["cDet_Pat"];
									
									
						//Prueba evitar duplicidad
									 $sqlR = "SELECT cNom_Pat FROM patrocinador WHERE cNom_Pat = '$nompat'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										///
										echo "<h4>ERROR DEL SISTEMA, YA EXISTEN LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mPatrocinador.php';" value="Aceptar" /> </h4> <?php
										
										///										
									}
									else {
																	
										$valores = array($nompat,$telpat,$emailpat,$contactopat,$detpat);
										$campos = $camposT->getCamposInsert($valores);
										if($db->Insertar($tabla, $campos))
										{
											echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
											?> <input name='btn' type='button' onClick="window.location='mPatrocinador.php';" value="Aceptar" /> </h4> <?php
										}
										else
										{
											echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
											?> <input name='btn' type='button' onClick="window.location='mPatrocinador.php';" value="Aceptar" /> </h4> <?php
									}
									}

									
                                //final de la accion 2    
                                break;
								
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Pat` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
                                    <FORM NAME='datos' onsubmit="return valforms(this)" ACTION='mPatrocinador.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST'>
                                    	<input type='hidden' value="<?php echo $db->Codificar($id);?>" name='C' >
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
                                    	<td><label>Patrocinador</label></td>
                                        <td> <input type='text' value="<?php echo $registro['cNom_Pat'];?>" name='cNom_Pat' required="required" placeholder="Digite Patrocinador"></td>
                                    	<td></td>
                                    </tr>
                                    <tr>
                                    	<td></td>
                                    	<td><label>Telefono</label></td>
                                        <td> <input type='text' id="phone" value="<?php echo $registro['cTel_Pat'];?>" name='cTel_Pat' required="required" placeholder="Digite el numero telefonico"></td>
                                    	<td></td>
                                    </tr>
                                    <tr>
                                    	<td></td>
                                    	<td><label>Email</label></td>
                                        <td> <input type='text' value="<?php echo $registro['cEmail_Pat'];?>" name='cEmail_Pat' size="50" maxlength="80"  editcheck="type=email;cvt=T"required="required" placeholder="Digite Email"></td>
                                    	<td></td>
                                    </tr>
                                    <tr>
                                    	<td></td>
                                    	<td><label>Contacto</label></td>
                                        <td> <input type='text' value="<?php echo $registro['cNomC_Pat'];?>" name='cNomC_Pat' required="required" placeholder="Digite Contacto"></td>
                                    	<td></td>
                                    </tr>
                                    <tr>
                                    	<td></td>
                                    	<td><label>Detalle</label></td>
                                        <td> <input type='text' value="<?php echo $registro['cDet_Pat'];?>" name='cDet_Pat' required="required" placeholder="Detalle de patrocinador"></td>
                                    	<td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="centrar">  
                                            <input  type='submit' value='Aceptar' >
                                            <input  type='button' value='Cancelar' onclick="javascript:location.href='mPatrocinador.php'">
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
									$cod_pat = $db->decodificar($_REQUEST["C"]);
									$nompat = $_REQUEST["cNom_Pat"];
								    $telpat = $_REQUEST["cTel_Pat"];
									$emailpat = $_REQUEST["cEmail_Pat"];
								    $contactopat = $_REQUEST["cNomC_Pat"];
									$detpat = $_REQUEST["cDet_Pat"];
									
									$sqlR = "SELECT * FROM patrocinador WHERE cEmail_Pat = '$emailpat'";
										$db->Consultar($sqlR);
										if($res = $db->Primero())
										{										
										$Id_Pat = $res["Id_Pat"];
										$cEmail_Pat = $res["cEmail_Pat"];
										
										}
										if (!$Id_Pat == $cod_pat and $cEmail_Pat== $cEmail_Pat){
											////modificar
									
                                    if($nompat!="" or $Email_pat!="")
                                   {
								   
                                       $db->ValidarDatos($nompat);
                                       $db->ValidarDatos($telpat);
									   
									   $db->ValidarDatos($contactopat);
									   $db->ValidarDatos($detpat);
                                            
                                                $valores = array($nompat,$telpat,$Email_Pat,$contactopat,$detpat);
                                                $campos = $camposT->getCamposUpdate($valores);
                                                if($db->Actualizar($tabla,$campos,"`Id_Pat`=$cod_pat"))
                                                {
                                                    echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mPatrocinador.php';" value="aceptar" /></h4> <?php
                                                    
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
										else {
											$sqlR = "SELECT * FROM patrocinador WHERE cEmail_Pat = '$cEmail_Pat'";
											$db->Consultar($sqlR);
											if($res = $db->Primero())
											{
											
											echo "<h4>Ya existe  &nbsp; &nbsp; &nbsp;";
                                            ?> <input name='btn' type='button' onClick="window.location='mPatrocinador.php';" value="aceptar" /></h4> <?php
                                                    
											
											}
											else {
											////modificar
									
                                    if($nompat!="" or $Email_pat!="")
                                   {
								   
                                       $db->ValidarDatos($nompat);
                                       $db->ValidarDatos($telpat);
									   
									   $db->ValidarDatos($contactopat);
									   $db->ValidarDatos($detpat);
                                            
                                                $valores = array($nompat,$telpat,$Email_Pat,$contactopat,$detpat);
                                                $campos = $camposT->getCamposUpdate($valores);
                                                if($db->Actualizar($tabla,$campos,"`Id_Pat`=$cod_pat"))
                                                {
                                                    echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mPatrocinador.php';" value="aceptar" /></h4> <?php
                                                    
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
                                break;			
								case "5":
								    $cod = $db->decodificar($_REQUEST["cod"]);							
                                    $campo = $camposT->getCamposDelete($cod);
                                    if($db->Eliminar($tabla, $campo))
                                    {
                                        echo "<h4>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mPatrocinador.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mPatrocinador.php';" value="Aceptar" /> </h4> <?php
                                    }
								break;		
                            }
                        }catch(Exception $e){
        			echo "error" . $e->getMessage();
			
			}
                    ?>
					                    <script>
 $("#phone").mask("9999-9999");
</script>
        </div>
    </div>
</body>
</html>
<?php } ?>
        



