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
        $tabla = "post";
		$tabla2 = "categorias";
		$tabla3 = "relacion_cat";
        $db = new  Operaciones();
        $camposT = new CamposTab($tabla);
		$camposT2 = new CamposTab($tabla3);
        $accion=null;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Administracion de usuarios</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../apps/ckeditor.js"></script>
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

                                      $sql = "SELECT * FROM $tabla WHERE `cTitulo` LIKE '%$buscar%' ORDER BY `cTitulo` ASC";
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY Id_Post ASC";
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
        							<h1>P&aacute;ginas</h1>
                                </div>
            					<div id="busqueda">
                                <?php /* echo strftime("%F %T"); */?>
                                    <form>
                                        <!--<input type='image' id='boton' src="imagenes/Buscar2.png" />-->
                                        <input type='search' placeholder='Buscar...' id='b' name="b"/>
                                         <input type='submit' id='boton' value="Buscar" title="Buscar" />
                                    </form>
                                </div>
           						<div class="clear-div"></div>
								<div id="grid">
                                    <table class="grid-box">
                                    <thead>
									<tr>
                                      <th width="10%" class="alinear" ><a href="mPagina.php?accion=<?php echo $db->Codificar("1");?>" ><img src="../imag/agregar.png" title="Agregar Usuario" /></a></th>
                                      <th width="90%">Titulo</th>
                                      
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
   				                            <a href="mPagina.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Post"]);?>" ><img src="../imag/editar.png" title="Editar" /></a>
                                          	<a href="mPagina.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Post"]);?>" ><img src="../imag/borrar.png" title="Eliminar" /></a>
                                        </td>
										<td><?php echo $db->RecortaString($fila[$i]["cTitulo"],15); ?></td>
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
                                    	<td colspan="6" align="center">
                                        <?php 
                                        //si la pagina no es la primera se imprime anterior
                                        if ($p>1){
											
												?><a href='javascript:void(0);' onClick="window.location='mPagina.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
												
											
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong> $i </strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mPagina.php?p=$i\">$i</a>";
												
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a href=\"mPagina.php?p=" . ($p+1) . "\"> Siguiente >></a>";
											
										}
                                    ?>
                                    	</td> 
                                    </tr>
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
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mPagina.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
                            <div class="titulos">
        						<h1>Agregar Nueva P&aacute;gina</h1>
                            </div>
                            <div class="clear-div"></div>
            				<FORM NAME='datos' ACTION='mPagina.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST'>
                        	<div class="box">
			                   	<!-- Titulo y TextArea-->
                                <div class="title"><input type="text" placeholder="Ingrese titulo" name="cTitulo" /></div>
                                <textarea class="ckeditor" cols="80" id="editor1" name="cCuerpo" rows="10">
                                </textarea>
                    			<input  type='submit' value='Aceptar' title="Aceptar">
                                <input  type='button' value='Cancelar' onclick="javascript:location.href='mPagina.php'" title="Cancelar"></td>
			
                            </div>
                            <div class="small-box-title">
                                	Tipo De P&aacute;gina
                            </div>
                            <div class="small-box">
                        			<center><select name='cTipo' class="opcion-select" style="width:150px;">
                                		<option value="Page">P&aacute;gina</option>
                                    	<option value="Post">Post</option>
                               		</select></center>	
                        	</div>
                            <div class="small-box-title">
                                	Categorias
                                </div>
                            	<div class="small-box">
                                <?php 
                                $sql = "SELECT * FROM $tabla2 ORDER BY cValor ASC";
                                $db->Consultar($sql);
                                if($db->numrows > 0){                         
                                    while($r = $db->Siguente()){   
                                        $fila[] = $r;
                                    }
                                    $total = count($fila);
                                     for ($i=0; $i<$total; $i++){   
                                        if (isset($fila[$i])){
                                            echo "<input type='checkbox' name='categoria[]' value='".$fila[$i]["Id_Categoria"]."'> ".ucwords($db->RecortaString($fila[$i]["cValor"],15))."<br />"; 
                                        }else{
                                            break;
                                        }
                                    }
                                } else {
									echo "No Existen Categorias";
								}?>
                                </div>
                                </FORM>
                                
                            
				<!--Fin Form-->		
                                <?php
                                //final de la accion 1
                                break;
                                // insertar
								case "2":
								    
									$titulo = $_REQUEST["cTitulo"];
								    $cuerpo = $_REQUEST["cCuerpo"];
									$user = 1;
									$fechapub = strftime("%F %T");
									$tipo = $_REQUEST["cTipo"];
																									
									$valores = array($titulo, "", $cuerpo, $user, $fechapub, "",$tipo);
									$campos = $camposT->getCamposInsert($valores);
									
									if($db->Insertar($tabla, $campos))
									{
										$ultimo_id = mysql_insert_id();
										foreach ($_POST['categoria'] as $idcat)
										{
										 	$valores2 = array($idcat,$ultimo_id);
											$campos2 = $camposT2->getCamposInsert($valores2);
											$db->Insertar($tabla3,$campos2);
										}
										echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mPagina.php';" value="Aceptar" /> </h4> <?php
									}
									else
									{
										echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mPagina.php';" value="Aceptar" /> </h4> <?php
									}
						
                                //final de la accion 2    
                                break;
								
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Post` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
                        <FORM NAME='datos' ACTION='mPagina.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST'>
                        	<input type='hidden' value="<?php echo $db->Codificar($id);?>" name='C' >
                            <!--Titulo y TextArea-->
                            <div class="box">
                            	<div class="title"><input type="text" placeholder="Titulo" name="cTitulo" value="<?php echo $registro['cTitulo'];?>" /></div>
                            <textarea class="ckeditor" cols="80" id="editor1" name="cCuerpo" rows="10">
                            	<?php echo $registro['cCuerpo'];?>
                            </textarea>
                            <input  type='submit' value='Aceptar' >
                            <input  type='button' value='Cancelar' onclick="javascript:location.href='mPagina.php'" />
                            </div>
                            <div class="small-box-title">
                                	Tipo De P&aacute;gina
                            </div>
                            <div class="small-box">
                        			<center><select name='cTipo' class="opcion-select" style="width:150px;">
                                    	<?php if ($registro['cTipo'] == "Page"){
											echo "<option value='Page' selected='selected'>P&aacute;gina</option>";
                                    		echo "<option value='Post'>Post</option>";
										} else {
											echo "<option value='Page'>P&aacute;gina</option>";
                                    		echo "<option value='Post' selected='selected'>Post</option>";
										}
										?>
                                		</select></center>	
                        	</div>
                            <div class="small-box-title">
                                	Categorias
                                </div>
                            	<div class="small-box">
                                <?php
								$fila2="";
								$idpost = $db->decodificar($_GET["cod"]); 
                                $sql = "SELECT Id_Categoria, cValor FROM $tabla2 ORDER BY cValor ASC";
                                $db->Consultar($sql);
                                if($db->numrows > 0){                         
                                    while($r = $db->Siguente()){   
                                        $fila[] = $r;
                                    }
                                    $total = count($fila);
                                    for ($i=0; $i<$total; $i++){
										$sql2 = "SELECT Id_Categoria, Id_Post FROM $tabla3 WHERE Id_Post='".$db->decodificar($_GET["cod"])."' And Id_Categoria='".$fila[$i]["Id_Categoria"]."'";   
                                        $db->Consultar($sql2);
										if($db->numrows > 0){                         
											while($r = $db->Siguente()){   
												$fila2[] = $r;
										}
											$total2 = count($fila2);
										
											echo "<input type='checkbox' name='categoria[]' value='".$fila[$i]["Id_Categoria"]."' checked='checked'> ".ucwords($db->RecortaString($fila[$i]["cValor"],15))."<br />";
								   } else {
											echo "<input type='checkbox' name='categoria[]' value='".$fila[$i]["Id_Categoria"]."' > ".ucwords($db->RecortaString($fila[$i]["cValor"],15))."<br />";
								   }
										/*if (isset($fila[$i])){
                                        } else{
                                            break;
                                        }*/
                                    }
                                } else {
									echo "No Existen Categorias";
								}?>
                                </div>
   		                </FORM>
                    	
                                <?php
										}
									}
                                break;
                                //actualizar
                                case "4":
									$cod_post = $db->decodificar($_REQUEST["C"]);
									$titulo = $_REQUEST["cTitulo"];
								    $cuerpo = $_REQUEST["cCuerpo"];
									$user = 1;
									$tipo = $_REQUEST["cTipo"];
                                    if($titulo!="" or $cod_post!="")
                                   {
                                                $valores = array($titulo, "", $cuerpo, $user, "", "", $tipo);
                                                $campos = $camposT->getCamposUpdate($valores);
                                                if($db->Actualizar($tabla,$campos,"`Id_Post`=$cod_post"))
                                                {
													/*Eliminamos la categorias previas*/
													$campo2 = $camposT2->getCamposDelete($cod_post);
													$db->Eliminar($tabla3, $campo2);
													
													/*Agregamos las nuevas categorias*/
													
													$ultimo_id = $cod_post;
													foreach ($_POST['categoria'] as $idcat)
													{
														$valores2 = array($idcat,$ultimo_id);
														$campo3 = $camposT2->getCamposInsert($valores2);
														$db->Insertar($tabla3,$campo3);
													}
													
                                                    echo "<h4>CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mPagina.php';" value="aceptar" /></h4> <?php
                                                    
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
                                break;			
								case "5":
									$cod = $db->decodificar($_REQUEST["cod"]);	
                                    $campo = $camposT->getCamposDelete($cod);

									if($db->Eliminar($tabla, $campo))
                                    {	
										$campo2 = $camposT2->getCamposDelete($cod);
										$db->Eliminar($tabla3, $campo2);
										echo "<h4>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mPagina.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
										
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mPagina.php';" value="Aceptar" /> </h4> <?php
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
<?php } ?>
        



