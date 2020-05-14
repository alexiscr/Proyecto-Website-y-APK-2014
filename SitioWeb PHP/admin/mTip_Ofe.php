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
        $tabla = "Tip_Ofe";
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
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/editcheck.js"></script>
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
                                         $sql = "SELECT * FROM $tabla WHERE `cClase_Of` LIKE '%$buscar%' ORDER BY `cClase_Of` ASC";
                                    }
                                    else
                                    {
                                    $sql = "SELECT * FROM $tabla ORDER BY cClase_Of ASC";
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
                                        <!--<input type='image' id='boton' src="imagenes/Buscar2.png" />-->
                                        <input type='search' placeholder='Buscar...' id='b' name="b" />
                                         <input type='submit' id='boton' value="Buscar" title="Buscar" />
                                    </form>
                                      </div>
                                    <div class="clear-div"></div>
                                    <div id="grid">
                                    <table class="grid-box">
                                    <thead>
                                    <tr>
									<th width=10% class="alinear"><a href="mTip_Ofe.php?accion=<?php echo $db->Codificar("1");?>" title="Agregar Local">
                                       <img src="../imag/agregar.png" title="Agregar"/></a></th>
                                      <th th width=30%>Clase de Oferta</th>
                                      <th th width=30%>Detalle de Oferta</th>
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
										  <a href="mTip_Ofe.php?accion=<?php echo $db->Codificar("3");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_TipOf"]);?>" title='Eliminar'> <img src="../imag/editar.png" title="Editar"/></a>
                                          <a href="mTip_Ofe.php?accion=<?php echo $db->Codificar("5");?>&cod=<?php echo $db->Codificar($fila[$i]["Id_TipOf"]);?>" title="Editar"> <img src="../imag/borrar.png" title='Eliminar'/></a>
                                        </td>
										<td><?php echo $db->RecortaString($fila[$i]["cClase_Of"],25); ?></td>
										<td><?php echo $db->RecortaString($fila[$i]["cDet_TOf"],50); ?></td>
                                        <td>
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
											
												?><a class='ant' href='javascript:void(0);' onClick="window.location='mTip_Ofe.php?p=<?php echo ($p-1); ?>'"><< Anterior</a>&nbsp;<?php
												//echo "<a class='ant' href='javascript:void(0);' onclick="."window.location=\'grados.php?re=$l&p=" . ($p-1) . "'\"><< Anterior</a>&nbsp";
										}
                                        //impresion del numero de las paginas
                                        for ($i=1; $i<=$paginas; $i++)
                                        {   //si se esta en la pagina actual se le quita el hipervinculo
                                            if ($i == $p){
                                                echo "<strong class='act'>$i</strong>";
                                            }
                                            else
                                            { 
												
													echo "<a href=\"mTip_Ofe.php?p=$i\">$i</a>";	
											}
                                        }
                                        //si la pagina no es la ultima se imprime siguiente
                                        if ($p<$paginas){
											
												echo "<a class='sig' href=\"mTip_Ofe.php?p=" . ($p+1) . "\">Siguiente >></a>";
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
                                        echo "<h4 class='alert_warning'>NO EXISTE $buscar EN LA TABLA &nbsp; &nbsp; &nbsp;";
                                        echo "<input type='button' value='Atras' onClick='history.go(-1);'></h4>";
                                    }
                                    else{
                                        //si la tabla esta vacia
                                        echo "<p class='alert_info'>La tabla esta vacia</p>";
										?>
										<input  type='button' value='Insertar un nuevo registro' onclick='javascript:location.href="mTip_Ofe.php?accion=<?php echo $db->Codificar("1");?>"'>
										<?php
                                    }
                                }       
                                //final de la accion null
                                break;
                                //formulario de insercion
                                case "1":
                                    ?>
									<FORM NAME='datos' ACTION='mTip_Ofe.php?accion=<?php echo $db->Codificar("2");?>' METHOD='POST' autocomplete="off" onsubmit="return valforms(this)" >
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
									<td><label>Clase de Oferta<label></td>
                                    <td> <input type='text' name='cClase_Of' required="required" editcheck="cvt=UTC" placeholder="Digite el tipo de oferta"></td>
									<td></td>
                                    </tr>
									<tr>
                                    <td></td>
                                    <td><label>Detalle de Oferta<label></td>
                                    <td><input type='text' name='cDet_TOf' required="required"  editcheck="cvt=UTC" placeholder="Digite el Detalle de Oferta"></td>
									<td></td>
                                    </tr>
                                    <tr>
                                    <td colspan="4" class="centrar">  
                                    <input  type='submit' value='Aceptar'>
									<input  type='button' value='Cancelar' onclick="javascript:location.href='mTip_Ofe.php'">
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
									$Clase= $_REQUEST["cClase_Of"];
									$Detof= $_REQUEST["cDet_TOf"];
                                    //Duplicidad
                                        $sqlR = "SELECT cClase_Of FROM tip_ofe WHERE cClase_Of = '$Clase'";
                                        $db->Consultar($sqlR);
                                        if($res = $db->Primero())
                                        {
                                            echo "<h4>YA EXISTE ESTE TIPO DE OFERTA &nbsp &nbsp";
                                            ?> <input name='btn' type='button' onClick="window.location='mTip_Ofe.php';" value="Aceptar" /></h4><?php
                                        }
                                        else {
                                    //Duplicidad
									$valores = array($Clase,$Detof);
									$campos = $camposT->getCamposInsert($valores);
									if($db->Insertar($tabla,$campos))
									{
										echo "<h4 class='alert_success'>EL REGISTRO SE INGRESO CON EXITO &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mTip_Ofe.php';" value="Aceptar" /> </h4> <?php
									}
									else
									{
										echo "<h4 class='alert_error'>ERROR DEL SISTEMA, NO SE INGRESARON LOS DATOS &nbsp &nbsp";
										?> <input name='btn' type='button' onClick="window.location='mTip_Ofe.php';" value="Aceptar" /> </h4> <?php
									}
                                }
						
                                //final de la accion 2    
                                break;
                                //formulario de modificar
                                case "3":
									$id =  $db->decodificar($_REQUEST["cod"]);
									$sqlU = "SELECT * FROM $tabla WHERE `Id_TipOf` = '$id'";
									$db->Consultar($sqlU);
									//verificacion que los registros sean mayor a cero
									if($db->numrows > 0){
										if($registro = $db->Primero()){
								?>
											<FORM NAME='datos' ACTION='mTip_Ofe.php?accion=<?php echo $db->Codificar("4");?>' METHOD='POST' autocomplete="off"  onsubmit="return valforms(this)">
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
											<td><label>Clase de Oferta<label></td>
                                            <td><input type='text' value="<?php echo $registro['cClase_Of'];?>" name='cClase_Of' required="required"  editcheck="cvt=UTC" placeholder="Digite la Clase de Oferta"></td>
											<td></td>
                                            </tr>
									        <tr>
                                            <td></td>
											<td><label>Detalle de Oferta<label></td>
                                            <td><input type='text' value="<?php echo $registro['cDet_TOf'];?>" name='cDet_TOf' required="required"  editcheck="cvt=UTC" placeholder="Digite el Detalle de Oferta"></td>
											<td></td>
                                            </tr>
									        <tr>
											<td></td>
                                            <td colspan="2" class="centrar">  
											<input  type='submit' value='Aceptar' >
											<input  type='button' value='Cancelar' onclick="javascript:location.href='mTip_Ofe.php'">
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
									$Clase = $_REQUEST["cClase_Of"];
                                    $Detof= $_REQUEST["cDet_TOf"];
                                    $IdTipOf ="";
                                    $cCof="";
                                    //Duplicidad
                                    $sqlR = "SELECT * FROM tip_ofe WHERE Id_TipOf = '$cod_usuario'";
                                    $db->Consultar($sqlR);
                                    if($res = $db->Primero())
                                    {
                                        $IdTipOf = $res["Id_TipOf"];
                                        $Tipof = $res["cClase_Of"];
                                    }
                                    //Inicia Codigo de Actualizacion duplicidad
                                    if($cod_usuario == $IdTipOf and $Clase == $cCof)
                                    {
                                     if($cod_usuario!="") //Comprobar si uno o mas campos estan vacios duda?
                                    {
                                            $db->ValidarDatos($Clase);
                                            $db->ValidarDatos($Detof);
                                                    $valores = array($Clase,$Detof);
                                                    $campos = $camposT->getCamposUpdate($valores);
                                                    if($db->Actualizar($tabla,$campos,"`Id_TipOf`=$cod_usuario"))
                                                    {
                                                        echo "<h4 >CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                        ?> <input name='btn' type='button' onClick="window.location='mTip_Ofe.php';" value="Aceptar" /></h4> <?php
                                                        
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
                                    $sqlR = "SELECT cClase_Of FROM tip_ofe WHERE cClase_Of = '$Clase'";
                                    $db->Consultar($sqlR);
                                    if($res = $db->Primero())
                                    {
                                        echo "<h4>YA EXISTE ESTE TIPO DE OFERTA &nbsp &nbsp";
                                        ?> <input name='btn' type='button' onClick="window.location='mTip_Ofe.php';" value="Aceptar" /></h4><?php
                                    }
                                    else
                                    {
                                        if($cod_usuario!="") //Comprobar si uno o mas campos estan vacios duda?
                                    {
                                         $db->ValidarDatos($Clase);
                                            $db->ValidarDatos($Detof);
                                                    $valores = array($Clase,$Detof);
                                                    $campos = $camposT->getCamposUpdate($valores);
                                                    if($db->Actualizar($tabla,$campos,"`Id_TipOf`=$cod_usuario"))
                                                {
                                                    echo "<h4 >CAMBIOS GUARDADOS &nbsp; &nbsp; &nbsp;";
                                                    ?> <input name='btn' type='button' onClick="window.location='mTip_Ofe.php';" value="Aceptar" /></h4> <?php
                                                    
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
                                        echo "<h4 class='alert_success'>SE ELIMINO EL REGISTRO";
										?> <input name='btn' type='button' onclick="window.location='mTip_Ofe.php';" value="Aceptar" /> </h4> <?php
                                    }
                                    else
                                    {
                                        echo "<h4 class='alert_error'>NO SE PUDO ELIMINAR";
										?> <input name='btn' type='button' onclick="window.location='mTip_Ofe.php';" value="Aceptar" /> </h4> <?php
                                    }
								break;
                            }
                        }catch(Exception $e){
        			echo "error" . $e->getMessage();
			}
                    ?>
                </div>
                
            </div>
        
        </div>
                </div>
                </div>
</body>
</html>
<?php } ?>

