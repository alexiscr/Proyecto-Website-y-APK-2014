<html>
<head>
<title>Administracion de Reportes</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../css/admin-style.css" />
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
<table class="formularios">
<thead>
										<tr>
											<td width="150px;"></td>
											<td colspan="2"></td>
											<td width="150px;"></td>
										</tr>									
</thead>
<!--Ofertas-->
<form NAME='datos' ACTION='reporteO.php' METHOD='POST' autocomplete="off" id="frm1">
<tr>
<td><label>Dato a Buscar:</label></td><td> <input type='text' name='name'  placeholder="Digite criterio"></td>
</tr>
<tr>
<td><label>Nombre de Oferta</label> </td><td><input type="radio" name="criterio" value="cNomb_Ofe" ></td>
<td><label>Fecha Inicio de Oferta</label> </td><td><input type="radio" name="criterio" value="fFecha_Ini" ></td>
<td><label>Fecha Finalizacion de Oferta</label> </td><td><input type="radio" name="criterio" value="fFecha_Fin" ></td>
</tr>
<tr>
<td colspan="6" class="centrar">
<input  type='submit' value='Generar Reporte'>
</td>
</tr>

</form>	
<tfoot>
<tr><td colspan="6"></td></tr>
</tfoot>						 
							 
</table>
</div>
               
        </div>		
</body>
</html>