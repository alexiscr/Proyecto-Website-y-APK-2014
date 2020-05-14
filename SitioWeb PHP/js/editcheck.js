<!--
// editcheck.js - Form validation
//
// Written by Dan Bailey - Data Design Group, Inc.  2004
// www.ddginc-usa.com
// See www.ddginc-usa.com/editcheckdoc.htm for documentation.
//
// frm - optional, edit check specific frm, or all forms
// itm - optional, edit check the specific item, or all items.
function valforms(frm,itm)
{
var i
var j
for (j=0;j < document.forms.length; j++ ) { // for each form
  if (frm && document.forms[j]!=frm) continue; // if frm specified, only look at it
  for (i=0;i < document.forms[j].length; i++ ) {  // for each field
     if (itm && document.forms[j].elements[i] != itm) continue; // if itm specified, only look at it
     var editstr = document.forms[j].elements[i].getAttribute('editcheck');
     if ((editstr) && (editstr.length>0)) {

         // break up edit string into command
         var cmds = editstr.split(";");
         var fld = document.forms[j].elements[i];

         for (var k=0;k< cmds.length; k++ ) {
             var cmdarr = cmds[k].split("=");
             cmdarr[0] = cmdarr[0].toLowerCase();
             var msg=""
             if (cmdarr.length>2) 
                msg=cmdarr[2];

             switch (cmdarr[0]) {
                case "req" : 
                  if ((cmdarr.length==1)||(cmdarr.length>1 && cmdarr[1].toUpperCase().charAt(0)=="Y")) {
                     if (fld.value.replace(/\s/g,"")==""){
                        if (!msg || !msg.length)
                               msg=fld.name.toUpperCase().replace(/_/g," ") + " is required!";
                        window.alert(msg);
                        fld.focus();
                        return false;
                     }
                   }
                   break;

               case "type" : 
                   switch (cmdarr[1].toLowerCase()) {

                       case "integer" :
                       case "int" : {
                           if (fld.value.length>0 && (isNaN(fld.value))||(fld.value.indexOf(".")>0)){
                              if (!msg || !msg.length)
                                 msg = fld.name.toUpperCase().replace(/_/g," ") + "&iexcl;Debe ser un n&uacute;mero entero!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                           } // if
                           break;
                        } // int

                       case "num" :
                       case "number" :
                        case "float" : 
                           if (fld.value.length>0 && isNaN(fld.value)) {
                              if (!msg || !msg.length)
                                 msg = fld.name.toUpperCase().replace(/_/g," ") + "&iexcl;Debe ser num&eacute;rico!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                           } // if
                           break; // int

                       case "date" : 
                           if (!msg || !msg.length) 
                              msg = fld.name.toUpperCase().replace(/_/g," ") + " &iexcl;Debe una fecha en formato MM/DD/AA" + ((fld.size>=10 || fld.maxlength>=10)?"YY":"") + "!";
                           var aDateParts = fld.value.split('../index.html');
	                   if (aDateParts.length != 3)  aDateParts = fld.value.split('-');
	                   if (aDateParts.length == 3 && !isNaN(aDateParts[0]) && !isNaN(aDateParts[1]) && !isNaN(aDateParts[2])
                               && aDateParts[0] && aDateParts[1] && aDateParts[2]) {
                              if (aDateParts[2].length == 2)  {
                                  if (parseInt(aDateParts[2],10)< 20)
                                     aDateParts[2] = "20" + aDateParts[2];
                                  else
                                     aDateParts[2] = "19" + aDateParts[2];
                                  if (fld.size>=10 || fld.maxlength>=10)
                                     fld.value =  aDateParts[0] + "/" + aDateParts[1] + "/" + aDateParts[2];
                              }
		              if (    parseInt(aDateParts[0],10) < 1 || parseInt(aDateParts[0],10) > 12
                                   || parseInt(aDateParts[1],10) < 1 || parseInt(aDateParts[1],10) > 31
                                   || (parseInt(aDateParts[0],10) ==4 && parseInt(aDateParts[1],10) >30 )
                                   || (parseInt(aDateParts[0],10) ==6 && parseInt(aDateParts[1],10) >30 )
                                   || (parseInt(aDateParts[0],10) ==9 && parseInt(aDateParts[1],10) >30 )
                                   || (parseInt(aDateParts[0],10) ==11 && parseInt(aDateParts[1],10) >30 )
                                   || parseInt(aDateParts[2],10) < 1000 || parseInt(aDateParts[2],10) > 2200
                                   || (parseInt(aDateParts[0],10)==2 && parseInt(aDateParts[1],10)>(((parseInt(aDateParts[2],10) % 4 == 0) && ((!(parseInt(aDateParts[2],10) % 100==0))||(parseInt(aDateParts[2],10)%400==0))) ? 29:28))
                                 ) 
                              {
                                   window.alert(msg);
                                   fld.focus();
                                   fld.select();
                                   return false;
                              }
	                   }
	                   else	if (fld.value.length>0) {
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                           }
                           break; // date


                       case "alphabetic": 
                       case "alpha":  
                         if(fld.value.length>0 && fld.value.search("[^A-Z a-z]") >= 0) { 
                              if (!msg || !msg.length)
                                 msg = "&iexcl;S&oacute;lo est&aacute;n permitidos los caracteres alfab&eacute;ticos!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                         }//if                             

                        break; //alpha 

                       case "alphanumeric": 
                       case "alphanum":  { 
                         if(fld.value.length>0 && fld.value.search("[^A-Z a-z0-9]") >= 0) { 
                              if (!msg || !msg.length)
                                 msg = "&iexcl;S&oacute;lo est&aacute;n permitidos los caracteres alfab&eacute;ticos!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                         }//if                             

                        break; 
                       }//alphabetic

                       case "email" : 
                           var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;
                           if (fld.value.length>0 && re.test(fld.value) == false) {
                              if (!msg || !msg.length)
                                 msg = "La direccion de correo electrnico no es valida!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                           }
                           break; // case email

                       case "zipcode" : 
                       case "zip" : 
                           var re = /(^\d{5}$)|(^\d{5}-*\d{4}$)/;
                           if (fld.value.length>0 && re.test(fld.value) == false) {
                              if (!msg || !msg.length)
                                 msg = "&iexcl;El c&oacute;digo postal no es v&aacute;lido!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                           }
                           break; // case zip

                       case "phonenum": 
                       case "phone": 
                            if(fld.value.length>0 && !(fld.value.match(/^[ ]*[(]{0,1}[ ]*[0-9]{3,3}[ ]*[)]{0,1}[-]{0,1}[ ]*[0-9]{3,3}[ ]*[-]{0,1}[ ]*[0-9]{4,4}[ ]*$/))) {
                              if (!msg || !msg.length)
                                 msg = "&iexcl;El n&uacute;mero de tel&eacute;fono no es v&aacute;lido!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                             }
                         break; // phone

                       case "ssn": 
                            if(fld.value.length>0 && !(fld.value.match(/^\d{3}-?\d{2}-?\d{4}$/))) {
                              if (!msg || !msg.length)
                                 msg = "&iexcl;El n&uacute;mero de Seguro Social no es v&aacute;lido!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                             }
                         break; // phone

                       case "state": 
                            if (fld.value.length>0 && !(fld.value.toUpperCase().match(/^AL|AK|AS|AZ|AR|CA|CO|CT|DE|DC|FM|FL|GA|GU|HI|ID|IL|IN|IA|KS|KY|LA|ME|MH|MD|MA|MI|MN|MS|MO|MT|NE|NV|NH|NJ|NM|NY|NC|ND|MP|OH|OK|OR|PW|PA|PR|RI|SC|SD|TN|TX|UT|VT|VI|VA|WA|WV|WI|WY|AE|AA|AE|AE|AP$/))) {
                              if (!msg || !msg.length)
                                 msg = "&iexcl;El estado estadounidense no es v&aacute;lido!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                             }
                         break; // state   

                       default:  // regular expression
                         var re = new RegExp(cmdarr[1],'gi');
                         if (fld.value.length>0 && re.test(fld.value) == false) {
                              if (!msg || !msg.length)
                                 msg = fld.name.toUpperCase().replace(/_/g," ") + " &iexcl;no cumple con los criterios de validaci&oacute;n!";
                              window.alert(msg);
                              fld.focus();
                              fld.select();
                              return false;
                         }
                         break; // default case, custom type via regular expression

                   } // switch
                   break; // case "type"

               case "maxlength" : 
               case "maxlen" : 
                   if(fld.value.length && fld.value.length>0 && (fld.value.length > eval(cmdarr[1]))) {
                         if (!msg || !msg.length)
                             msg = "&iexcl; La extensi&oacute;n m&aacute;xima de " + fld.name.toUpperCase().replace(/_/g," ") + " es " + cmdarr[1] + " caracteres!";
                         window.alert(msg);
                         fld.focus();
                         fld.select();
                         return false;
                   }
                   break;

               case "minlenth" :                
               case "minlen" : 
                   if(fld.value.length && fld.value.length>0 && (fld.value.length < eval(cmdarr[1]))) {
                         if (!msg || !msg.length)
                             msg = "&iexcl; La extensi&oacute;n m&iacute;nima de " + fld.name.toUpperCase().replace(/_/g," ") + " es " + cmdarr[1] + " carecteres!";
                         window.alert(msg);
                         fld.focus();
                         fld.select();
                         return false;
                   }
                   break;

               case "eval" : 
                   if(!(eval(cmdarr[1]))) {
                         if (!msg || !msg.length)
                             msg = fld.name.toUpperCase().replace(/_/g," ") + " &iexcl;no cumple con los criterios de validaci&oacute;n!";
                         window.alert(msg);
                         fld.focus();
                         fld.select();
                         return false;
                   }
                   break; // minval

               case "minvalue" :           
               case "minval" : 
                   if(fld.value.length && fld.value.length>0 && (eval(fld.value) < eval(cmdarr[1]))) {
                         if (!msg || !msg.length)
                             msg = "&iexcl;El valor m&iacute;nimo de " + fld.name.toUpperCase().replace(/_/g," ") + " es " + cmdarr[1] + "!";
                         window.alert(msg);
                         fld.focus();
                         fld.select();
                         return false;
                   }
                   break; // minval

               case "maxvalue" : 
               case "maxval" : 
                   if(fld.value.length && fld.value.length>0 && (eval(fld.value) > eval(cmdarr[1]))) {
                         if (!msg || !msg.length)
                             msg = "&iexcl;El valor m&aacute;ximo de " + fld.name.toUpperCase().replace(/_/g," ") + " es " + cmdarr[1] + "!";
                         window.alert(msg);
                         fld.focus();
                         fld.select();
                         return false;
                   }
                   break; // maxval


               case "cvt" : 
                   var cmd
                   if (fld.value.length && fld.value.length>0) {
                       for (var n=0;n<cmdarr[1].length;n++) {
                          cmd=cmdarr[1].toUpperCase().charAt(n);  // one-letter command codes
                          switch (cmd) {
                              case "U" : // uppercase
                                 if (fld.value!=fld.value.toUpperCase())
                                     fld.value=fld.value.toUpperCase();
                                 break;
                              case "L" : // lowercase
                                 if (fld.value!=fld.value.toLowerCase())
                                     fld.value=fld.value.toLowerCase();
                                 break;
                              case "T" :  // trim spaces leading and trailing
                                 if (fld.value != fld.value.replace(/^\s+/g,'').replace(/\s+$/g,''))
                                    fld.value = fld.value.replace(/^\s+/g,'').replace(/\s+$/g,'');
                                 break;
                              case "]" :  // trim trailing spaces
                                 if (fld.value != fld.value.replace(/\s+$/g,''))
                                    fld.value = fld.value.replace(/\s+$/g,'') ;
                                 break;
                              case "[" : // trim leading spaces 
                                 if (fld.value != fld.value.replace(/^\s+/g,'')) 
                                    fld.value = fld.value.replace(/^\s+/g,'') ;
                                 break;
                              case "C" : // crunch multiple spaces into one space
                                 if (fld.value != fld.value.replace(/\s+/g,' '))
                                    fld.value = fld.value.replace(/\s+/g,' ') ;
                                 break;
                              case "~" : // remove punctuation
                                 if (fld.value != fld.value.replace(/[\W_]/g,''))
                                    fld.value = fld.value.replace(/[\W_]/g,'') ;
                                 break;

                          } // switch command character
                      } // for n each command character
                   } // if
                   break;  // case cvt

             } // switch
         } // for k
     } // if editcheck
  } // for i each field
}// for j each form
return true;
}

//-->
