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
	//Pegar aqui lo de sesion son 11 lineas y al final en cerrar el de php solo se pone enmedio un }
        $tabla = "Locales";
        $db = new  Operaciones();
        $camposT = new CamposTab($tabla);
        $accion=null;
?>      
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<script type="text/javascript" language="javascript" src="../js/funciones.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
	<script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/jquery.maskedinput.js"></script>
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
                                     $sql = "SELECT * FROM $tabla WHERE `cNum_Local` LIKE '%$buscar%' ORDER BY `cNum_Local` ASC";
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY cNum_Local ASC";
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
                                    <form autocomplete="off">
                                       <div id="busqueda"> 
                                        <form ACTION='mLocales.php' METHOD='POST' autocomplete="off">  
                                        <input type='image' id='boton' src="imagenes/Buscar2.png" />
                                       <input type='search' placeholder='Buscar...' id='b' name="b" />
                                        <input type="submit" value="busqueda" name="busquedax">
                                    </form>
                                    </div>
                                    <div class="clear-div"></div>


									<div class="clear-div"></div>
									<div id="grid">
                                    <table class="grid-box">
									<thead>
									<tr>
                                      <th width=10% class="alinear"><a href="mLocales.php?accion=<?php echo $db->Codificar("1");?>" title="Agregar Local">
                                        <img src="../imag/agregar.png" title="Agregar"/></a></th>
                                      <th width=14%>N° Local</th>
                                      <th width=14%>N° Pasillo</th>
                                      <th width=16%>Telefono</th>
									  <th width=16%>Hora Apertura</th>
									  <th width=16%>Empresa</th>
									  <th width=16%>Representante</th>
									  <th width=16%>Edificio</th>
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
										<tbody>
										<tr>
										<td class="opciones-datos">
										<a href="mLocales.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Local"]);?>"> <img src="../imag/editar.png" title="Editar"/></a>
										<a href="mLocales.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_Local"]);?>"> <img src="../imag/borrar.png" title='Eliminar'/></a>
                                        </td>
                                        <td><?php echo $db->RecortaString($fila[$i]["cNum_Local"],20); ?></td>
                                        <td><?php echo $db->RecortaString($fila[$i]["cNum_Pas"],20); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cLocal_Tel"],20); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cLocal_Hora"],20); ?></td>
										<td>
											<?php  
											$sec = $fila[$i]["Id_Emp"];
											$sql_sec = "SELECT cNom_Emp FROM empresa WHERE Id_Emp='$sec'";
											$db->Consultar($sql_sec);
											if($reg_sec = $db->Primero()){
											 echo $db->RecortaString($reg_sec["cNom_Emp"],20); 
											}
										?>
										</td>
                                        <td>
                                        <?php  
                                            $sec = $fila[$i]["Id_Rep"];
                                            $sql_sec = "SELECT cNom_Rep FROM representante WHERE Id_Rep='$sec'";
                                            $db->Consultar($sql_sec);
                                            if($reg_sec = $db->Primero()){
                                             echo $db->RecortaString($reg_sec["cNom_Rep"],20); 
                                            }
                                        ?>
                                        </td>
                                        <td>
                                            <?php  
                                            $sec = $fila[$i]["Id_Edificio"];
                                            $sql_sec = "SELECT cNom_Edi FROM edificios WHERE Id_Edificio='$sec'";
                                            $db->Consultar($sql_sec);
                                            if($reg_sec = $db->Primero()){
                                             echo $db->RecortaString($reg_sec["cNom_Edi"],20); 
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
                                    <tr><td colspan="7" align="center">
                                        <?php 
                                        //si la pagina no es la primera se imprime anterior
                                        if ($p>1){
											
												?><a href='javascript:void(0);' onClick="window.location='mLocales.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong>$i</strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mLocales.php?p=$i\">$i</a>";	
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a href=\"mLocales.php?p=" . ($p+1) . "\">Siguiente >></a>";
										}
                                    ?>
                                    </td> </tr>
									</tfoot>
                                    </table>
									<script> $( "tr:odd" ).css( "background-color" , "#f9f9f9" ); </script>
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
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mLocales.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
									<FORM NAME='datos' ACTION='mLocales.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST' autocomplete="off">
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
                                    <td><label>Número Local<label></td>
									<td> <input  type='text' id="NumL" name='cNum_Local' required="required"  placeholder="Digite El Número de Local"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
                                    <td><label>Número Pasillo<label></td>
									<td> <input  type='text' id="NumP" name='cNum_Pas' required="required"  placeholder="Digite El Número de Pasillo"></td>
									<td></td>
									</tr>
                               		<tr>
									<td></td>
                                    <td><label>Telefono Local<label></td>
									<td> <input  type='text' id="phone" name='cLocal_Tel' required="required" placeholder="Digite El Número de Telefono"></td>
									<td></td>
									</tr>
							     	<tr>
									<td></td>
									<td><label>Hora Local<label></td>
									<td> <input type='text' id="hora" name='cLocal_Hora' required="required" placeholder="Digite la Hora Local"></td>
									<td></td>
									</tr>
									<tr>
									<td></td>
									<td><label>Empresa<label></td><td> 									
									<input onKeyUp="BuscarE('Id_Emp','resultado','busquedaE');" name="Id_Emp" type="text" id="Id_Emp"  placeholder="Nombre de Empresa" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									<td></td>									
									<tr>
									<td></td>
                                    <td><label>Representante<label></td><td>
									<!--<td> <input type='text' name='Id_Rep' required="required" placeholder="Digite el Id Representante"></td>-->
									<input onKeyUp="BuscarE('Id_Rep','resultado','busquedaR');" name="Id_Rep" type="text" id="Id_Rep" placeholder="Nombre de Representante" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
                                    <td></td>
									</tr>
									<tr>
									<td></td>
                                    <td><label>Edificio<label></td><td>
									<input onKeyUp="BuscarE('Id_Edificio','resultado','busquedaEdi');" name="Id_Edificio" type="text" id="Id_Edificio" placeholder="Nombre de Edificio" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
									<td></td>
									</tr>
						            <tr>
									<td colspan="4" class="centrar">  
									<input  type='submit' value='Aceptar'>
									<input  type='button' value='Cancelar' onclick="javascript:location.href='mLocales.php'">
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
									//$Local= $_REQUEST["Id_Local"];
									$NumLocal= $_REQUEST["cNum_Local"];
									$NumPas= $_REQUEST["cNum_Pas"];
									$Telefono= $_REQUEST["cLocal_Tel"];
								    $Hora = $_REQUEST["cLocal_Hora"];  
									$Empresa= $_REQUEST["Id_Emp"];
									$Representante = $_REQUEST["Id_Rep"];
									$Edificio=$_REQUEST["Id_Edificio"];
									

									//Prueba evitar duplicidad                                                                      
		                                //$sqlR = "SELECT cNum_Local FROM locales WHERE cNum_Local = LIKE '%$nombre%'";
		                                //$db->Consultar($sqlR);
		                                //if($res = $db->Primero())
		                                //{
		                               // 	$NumLocal1 = $res["cNum_Local"];                               
		                               // 	echo $NumLocal1;										
										//}
										//else 
									//	{
									//		echo "No hay nada";
									//	}
                                    //Prueba evitar duplicidad

									//Empresa
							
									$valor_array = explode(',',$Empresa); 
									$nombre = $valor_array[0];
									
									$sqlR = "SELECT Id_Emp FROM empresa WHERE cNom_Emp = '$nombre'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$Id_Emp = $res["Id_Emp"];								
									}

									//Representante
									$valor_array = explode(',',$Representante); 
									$nombre = $valor_array[0];
									
									$sqlR = "SELECT Id_Rep FROM representante WHERE cNom_Rep = '$nombre'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$Representante = $res["Id_Rep"];								
									}

									//Edificio
									$valor_array = explode(',',$Edificio); 
									$nombre = $valor_array[0];
									
									$sqlR = "SELECT Id_Edificio FROM edificios WHERE cNom_Edi = '$nombre'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$Edificio = $res["Id_Edificio"];								
									}
									//Duplicidad
									$NumLocal= $_REQUEST["cNum_Local"];
									$sqlR = "SELECT cNum_Local FROM locales WHERE cNum_Local = '$NumLocal'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										echo "<h4>YA EXISTE ESTE NUMERO DE LOCAL &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mLocales.php';" value="Aceptar" /></h4><?php
									}
									else {
									//

									
									$valores = array($NumLocal,$NumPas, $Telefono, $Hora, $Id_Emp, $Representante, $Edificio);
									$campos = $camposT->getCamposInsert($valores);
									
									if($db->Insertar($tabla,$campos))
									{
										echo "<h4>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mLocales.php';" value="Aceptar" /> </h4> <?php
									}
									else
									{
										echo "<h4>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mLocales.php';" value="Aceptar" /> </h4> <?php
									}
									}
                                //final de la accion 2    
                                break;
                                //formulario de modificar
                                 case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_Local` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
											<FORM NAME='datos' ACTION='mLocales.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST' autocomplete="off">
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
											<td><label>Número Local<label></td>
											<td><input type='text' id="NumL" value="<?php echo $registro['cNum_Local'];?>" name='cNum_Local' required="required" placeholder="Digite El Número de Local"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
											<td><label>Número Pasillo<label></td>
											<td><input type='text' id="NumP" value="<?php echo $registro['cNum_Pas'];?>" name='cNum_Pas' required="required" placeholder="Digite El Número de Local"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
											<td><label>Telefono Local<label></td>
											<td><input type='text' id="phone" value="<?php echo $registro['cLocal_Tel'];?>" name='cLocal_Tel' required="required" placeholder="Digite El Número de Telefono"></td>
											<td></td>
											</tr>
											<tr>
											<td></td>
									        <td><label>Hora Local<label></td>
									        <td><input type='text' id="hora" value="<?php echo $registro['cLocal_Hora'];?>" name='cLocal_Hora' required="required" placeholder="Digite Hora Local"></td>
									        <td></td>
									        </tr>
									        <tr>
									        <td></td>
											<td><label>Empresa<label></td>
											 <td> 
												<?php  
												$Id_Emp=$registro['Id_Emp'];
												$sqlR = "SELECT cNom_Emp FROM empresa WHERE Id_Emp = '$Id_Emp'";											
												$db->Consultar($sqlR);
												if($res = $db->Primero())
												{
													$nom = $res["cNom_Emp"];
												} 
												?>
											<input value= "<?php echo "$nom";?>" onKeyUp="BuscarE('Id_Emp','resultado','busquedaE');" name="Id_Emp" type="text" id="Id_Emp" placeholder="Nombre de la empresa" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
											</td>
											<td></td>
											</tr>
											<tr>
											<td></td>
                                            <td><label>Representante<label></td>
                                            <td> 
												<?php  
												$Id_Rep=$registro['Id_Rep'];
												$sqlR = "SELECT cNom_Rep FROM representante WHERE Id_Rep = '$Id_Rep'";											
												$db->Consultar($sqlR);
												if($res = $db->Primero())
												{
													$nom = $res["cNom_Rep"];
												} 
												?>
											<input value= "<?php echo "$nom";?>" onKeyUp="BuscarE('Id_Rep','resultado','busquedaR');" name="Id_Rep" type="text" id="Id_Rep" placeholder="Nombre del Representante" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
											</td>
											<td></td>
											</tr>
                                            <tr>
                                            <td></td>
                                            <td><label>Edificio<label></td>
                                             <td> 
												<?php  
												$Id_Edificio=$registro['Id_Edificio'];
												$sqlR = "SELECT cNom_Edi FROM edificios WHERE Id_Edificio = '$Id_Edificio'";											
												$db->Consultar($sqlR);
												if($res = $db->Primero())
												{
													$nom = $res["cNom_Edi"];
												} 
												?>
											<input value= "<?php echo "$nom";?>" onKeyUp="BuscarE('Id_Edificio','resultado','busquedaEdi');" name="Id_Edificio" type="text" id="Id_Edificio" placeholder="Nombre del Edificio" style="width:45%; display: inline-block;"   onFocus="elFocus();" />
											<div class="resultado" id="resultado" onMouseOut="sinFocus();"  onMouseOver="elFocus();" style="z-index:10;" ></div>
											</td>
                                            <td></td>
                                            </tr>
									        <tr>
									        <td></td>
											<td colspan="2" class="centrar">  
											<input  type='submit' value='Aceptar' >
											<input  type='button' value='Cancelar' onclick="javascript:location.href='mLocales.php'">
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
									$cod_usuario = $db->decodificar($_REQUEST["C"]);
									$NumLocal= $_REQUEST["cNum_Local"];
									$NumPas= $_REQUEST["cNum_Pas"];
									$Telefono = $_REQUEST["cLocal_Tel"];
								    $Hora= $_REQUEST["cLocal_Hora"];
                                    $Empresa= $_REQUEST["Id_Emp"];
                                    $Representante= $_REQUEST["Id_Rep"];
                                    $Edificio=$_REQUEST["Id_Edificio"];
                                    $Id_Local="";
                                    $Num_Local="";
                                    //
                                    //Empresa
                            
                                    $valor_array = explode(',',$Empresa); 
                                    $nombre = $valor_array[0];
                                    
                                    $sqlR = "SELECT Id_Emp FROM empresa WHERE cNom_Emp = '$nombre'";
                                    $db->Consultar($sqlR);
                                    if($res = $db->Primero())
                                    {
                                        $Id_Emp = $res["Id_Emp"];                               
                                    }

                                    //Representante
                                    $valor_array = explode(',',$Representante); 
                                    $nombre = $valor_array[0];
                                    
                                    $sqlR = "SELECT Id_Rep FROM representante WHERE cNom_Rep = '$nombre'";
                                    $db->Consultar($sqlR);
                                    if($res = $db->Primero())
                                    {
                                        $Representante = $res["Id_Rep"];                                
                                    }

                                    //Edificio
                                    $valor_array = explode(',',$Edificio); 
                                    $nombre = $valor_array[0];
                                    
                                    $sqlR = "SELECT Id_Edificio FROM edificios WHERE cNom_Edi = '$nombre'";
                                    $db->Consultar($sqlR);
                                    if($res = $db->Primero())
                                    {
                                        $Edificio = $res["Id_Edificio"];                                
                                    }
									//

//
                                    $sqlR = "SELECT * FROM locales WHERE Id_Local = '$cod_usuario'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										$Id_Local = $res["Id_Local"];
										$Num_Local = $res["cNum_Local"];
									}
                                    //
                                    //Condicionar
									if($cod_usuario == $Id_Local and $NumLocal == $Num_Local)
									{
									 if($cod_usuario!="") //Comprobar si uno o mas campos estan vacios duda?
                                    {
	                                    	$db->ValidarDatos($NumLocal);
	                                    	$db->ValidarDatos($NumPas);
	                                        $db->ValidarDatos($Telefono);
											$db->ValidarDatos($Hora);
											$db->ValidarDatos($Id_Emp);
	                                        $db->ValidarDatos($Representante);
	                                        $db->ValidarDatos($Edificio);
	                                                $valores = array($NumLocal,$NumPas,$Telefono,$Hora,$Id_Emp,$Representante,$Edificio);
	                                                $campos = $camposT->getCamposUpdate($valores);
	                                                if($db->Actualizar($tabla,$campos,"`Id_Local`=$cod_usuario"))
	                                                {
	                                                    echo "<h4 >CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
	                                                    ?> <input name='btn' type='button' onClick="window.location='mLocales.php';" value="Aceptar" /></h4> <?php
	                                                    
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
                                   else{
//Insertar aqui
                                   	$sqlR = "SELECT cNum_Local FROM locales WHERE cNum_Local = '$NumLocal'";
									$db->Consultar($sqlR);
									if($res = $db->Primero())
									{
										echo "<h4>YA EXISTE ESTE NUMERO DE LOCAL &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mLocales.php';" value="Aceptar" /></h4><?php
									}
									else
									{
										if($cod_usuario!="" or $Telefono!="") //Comprobar si uno o mas campos estan vacios duda?
                                    {
                                    	$db->ValidarDatos($NumLocal);
                                    	$db->ValidarDatos($NumPas);
                                        $db->ValidarDatos($Telefono);
										$db->ValidarDatos($Hora);
										$db->ValidarDatos($Id_Emp);
                                        $db->ValidarDatos($Representante);
                                        $db->ValidarDatos($Edificio);
                                                $valores = array($NumLocal,$NumPas,$Telefono,$Hora,$Id_Emp,$Representante,$Edificio);
                                                $campos = $camposT->getCamposUpdate($valores);
                                                if($db->Actualizar($tabla,$campos,"`Id_Local`=$cod_usuario"))
                                                {
                                                    echo "<h4 >CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mLocales.php';" value="aceptar" /></h4> <?php
                                                    
                                                }
                                                else
                                                {
                                                    echo "<h4>Error: No se Guardaron los Cambios";
                                                    echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
                                                }
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
										?> <input name='btn' type='button' onclick="window.location='mLocales.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
                                        echo "<h4>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mLocales.php';" value="Aceptar" /> </h4> <?php
                                    }
								break;
                            }
                        }catch(Exception $e){
        			echo "error" . $e->getMessage();
			}
                    ?>
                </div>
                 <script>
                       $("#phone").mask("9999-9999");
                       $("#hora").mask("99:99");
                       $("#NumL").mask("999");
                       $("#NumP").mask("99");
                </script>
            </div>
        
        </div>
       </div>
    </div>
</body>
</html>
<?php } ?>

