function nuevoAjax(){
	var xmlhttp = false;
 	try {
 		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
 	} catch (e) {
 		try {
 			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
 		} catch (E) {
 			xmlhttp = false;
 		}
  	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
//se usa para buscar empresas en la pagina ausuarios
function BuscarE(campo, resultado, pagina){
	document.getElementById(resultado).style.display='block';
	q = document.getElementById(campo).value;
	c = document.getElementById(resultado);
	ajax =  nuevoAjax();
	ajax.open("GET",pagina+".php?bus="+q);
	ajax.onreadystatechange = function(){
		if(ajax.readyState==4){
			c.innerHTML = ajax.responseText;
		}	
	}	
	ajax.send(null);
}
//se usa para buscar empresas en la pagina ausuarios
function Buscar(campo, resultado, pagina){
	document.getElementById(resultado).style.display='block';
	q = document.getElementById(campo).value;
	c = document.getElementById(resultado);
	ajax =  nuevoAjax();
	ajax.open("GET",pagina+".php?bus="+q);
	ajax.onreadystatechange = function(){
		if(ajax.readyState==4){
			c.innerHTML = ajax.responseText;
		}	
	}	
	ajax.send(null);
}
//se usa en la pagina ausuarios
function sinFocus(){
	document.getElementById('resultado').style.display='none';
}
function elFocus(){
	document.getElementById('resultado').style.display='block';
}
//se usa en la pagina ausuarios
function Colocar(campo, lista)
{
	var txt = document.getElementById(campo);  
	var idx = lista.selectedIndex;
	var contenido = lista.options[idx].innerHTML;
	txt.value = contenido;	
}
//se usa en el pagina de los productos
function BuscarP(urltmp){
    b = document.getElementById("busqueda").value;
	c = document.getElementById("bus").value;
    url=urltmp+'?busqueda='+ b +'&campo=' + c;
    window.location=url;
}
//funcion para buscar
function Buscar(urltmp, ls){
    b = document.getElementById("busqueda").value;
    url=urltmp+'?busqueda='+b;
    window.location=url;
}

function Click(e)
{
	var key;
	var boton = document.getElementById("buscar");

	if(window.event) // IE
	{
		key = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		key = e.which;
	}
	
	if (key == 13)
	{
		boton.click();
	}
}


function eliminar(url)
{
    if(confirm("¿Elimiar Registro?")){
        window.location=url;
    }
}
//cambia el numero de registros por pagina
function combo(lista, texto, urltmp)
{
  texto = document.getElementById(texto);  
  var idx = lista.selectedIndex;
  var contenido = lista.options[idx].innerHTML;
  texto.value = contenido;	
  urltmp = Remplazar(urltmp,"?re=5")
  urltmp = Remplazar(urltmp,"?re=10")
  urltmp = Remplazar(urltmp,"&re=10");
  urltmp = Remplazar(urltmp,"&re=5");
  var len = urltmp.length;
  var ultima = urltmp.substring(len-1)
  if(ultima == "p")
  {
	url=urltmp+'?re='+contenido;
  }
  else
  {
	url=urltmp+'&re='+contenido;
  }
  window.location=url;
}
//se usa en la funcion combo
function Remplazar(text, busca){
  var reemplaza="";
  while (text.toString().indexOf(busca) != -1)
  {
      text = text.toString().replace(busca,reemplaza);
  } 
  return text;
}
//se usa en el archivo de productos
function combo2(lista, texto)
{
  texto = document.getElementById(texto);  
  var idx = lista.selectedIndex;
  var contenido = lista.options[idx].innerHTML;
  texto.value = contenido;	
  //alert(texto.value);  
}
//se usa en el archivo de productos
function Habilitar(lista, lista2,$clave)
{
  var idx = lista.selectedIndex;
  
  var list = document.getElementById(lista2);
  
  var contenido = lista.options[idx].innerHTML;
  
  if(contenido == $clave)
  {
      list.disabled=false;
  }
  else
  {
      list.disabled=true;
  }
}

//permite introducir solo numeros
function NumCheck(e)
{
	var key;
	//var cont=0;

	if(window.event) // IE
	{
		key = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		key = e.which;
	}
	
	if (key < 48 || key > 57)
	{
		if(key == 46 || key == 8 ) // Detectar . (punto) y backspace (retroceso)
		{
			return true; 
		}
		else
		{
			return false;
		}
	}
	return true;
}
//funcion que limita los textarea y muestra los caracteres disponibles
function contador(campo, cuenta, texto ,limite){
  var cont;
 
 if(campo.value.length > limite){
    campo.value = campo.value.substring(0, limite);
  }
  else{
    cont = limite - campo.value.length;
    document.getElementById(cuenta).innerHTML = texto + " " + cont;
  }
}

function BuscarP2(urltmp){
    
    b = document.getElementById("busqueda").value;
	c = document.getElementById("bus").value;
    
    url=urltmp+'&busqueda='+ b +' &campo=' + c;
    
    window.location=url;
}
