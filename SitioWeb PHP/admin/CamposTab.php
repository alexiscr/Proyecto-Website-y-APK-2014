<?php
class CamposTab
{
    private $tabla;
    function __construct($tbl)
    {
        $this->tabla = $tbl;    
    }
    function getCamposInsert($valores)
    {
        $campos;
        switch($this->tabla)
        {         
           	case "Usuarios":
				$campos = array("cUsuario" => $valores[0], "cPassword" => $valores[1], "cEmail" => $valores[2], "cNombre" => $valores[3], "cApellidos" => $valores[4], "cEstado" => $valores[5], "cNivel" => $valores[6]);
				break;
			case "Patrocinador":
				$campos = array("cNom_Pat" => $valores[0], "cTel_Pat" => $valores[1], "cEmail_Pat" => $valores[2], "cNomC_Pat" => $valores[3], "cDet_Pat" => $valores[4]);
				break;
			case "Empresa":
                $campos = array("cEmp_Tel" => $valores[0], "cEmp_Fax" => $valores[1], "cEmp_Email" => $valores[2], "cNom_Emp" => $valores[3], "Id_Rep" => $valores[4], "cWeb_Emp" => $valores[5]);
                break;
			case "Representante":
                $campos = array("cNom_Rep" => $valores[0], "cApe_Rep" => $valores[1], "cTel_Rep" => $valores[2],"cMovil_Rep" => $valores[3], "cEmail_Rep" => $valores[4], "cDui" => $valores[5]);
                break;
			case "Oferta":
                $campos = array("cNomb_Ofe" => $valores[0], "cImg_Ofe" => $valores[1], "cDesc_Ofe" => $valores[2], "fFecha_Ini" => $valores[3], "fFecha_Fin" => $valores[4], "Id_Prod" => $valores[5], "Id_Local" => $valores[6], "Id_TipOf" => $valores[7], "Precio_Oferta" => $valores[8],"cImg_tb" => $valores[9]);
                break;
            case "Producto":
                $campos = array("cNom_Prod" => $valores[0], "Id_Catp" => $valores[1]);
                break;
			case "Locales":
                $campos = array("cNum_Local" => $valores[0], "cNum_Pas" => $valores[1], "cLocal_Tel" => $valores[2], "cLocal_Hora" => $valores[3], "Id_Emp" => $valores[4],"Id_Rep" => $valores[5],"Id_Edificio" => $valores[6]);
                break;     
            case "Tip_Ofe":
                $campos = array("cClase_Of" => $valores[0], "cDet_TOf" => $valores[1]);
                break;
			case "Categoriap":
				$campos = array("cNom_CatP"=> $valores[0]);
				break;
			case "Post":
				$campos = array("cTitulo"=> $valores[0], "cCopete" => $valores[1], "cCuerpo" => $valores[2], "Id_Usr" => $valores[3], "fModificacion" => $valores[4],"fPublicacion" => $valores[5], "cTipo" => $valores[6]);
				break;
			case "Relacion_Cat":
				$campos = array("Id_Categoria"=> $valores[0], "Id_Post" => $valores[1]);
				break;
			case "Categorias":
				$campos = array("cValor"=> $valores[0]);
				break;
			case "Relacion_Eve":
				$campos = array("Id_Pat"=> $valores[0], "Id_Eve" => $valores[1]);
				break;
			case "Edificios":
				$campos = array("cNom_Edi"=> $valores[0], "cDesc_Edi" => $valores[1], "cImg_Edi" => $valores[2]);
				break;
			case "Evento":
                $campos = array("fFecha_Eve" => $valores[0], "cDescr_Eve" => $valores[1], "nHora_Eve" => $valores[2],"cImg_Eve" => $valores[3],"cNom_Eve" => $valores[4],"Id_Rep" => $valores[5],"cImg_tb" => $valores[6]);               
				break;
            default:
                echo "No existe la tabla";
    }
    return $campos;
    }
    function getCamposUpdate($valores)
    {
        $campos;
        switch($this->tabla)
        {
           case "Usuarios":
				$campos = array("cUsuario" => $valores[0], "cPassword" => $valores[1], "cEmail" => $valores[2], "cNombre" => $valores[3], "cApellidos" => $valores[4], "cEstado" => $valores[5], "cNivel" => $valores[6]);
				break;
		   case "Patrocinador":
				$campos = array("cNom_Pat" => $valores[0], "cTel_Pat" => $valores[1], "cEmail_Pat" => $valores[2], "cNomC_Pat" => $valores[3], "cDet_Pat" => $valores[4]);
				break;
			case "Empresa":
                $campos = array("cEmp_Tel" => $valores[0], "cEmp_Fax" => $valores[1], "cEmp_Email" => $valores[2], "cNom_Emp" => $valores[3], "Id_Rep" => $valores[4], "cWeb_Emp" => $valores[5]);
                break;
			case "Representante":
                $campos = array("cNom_Rep" => $valores[0], "cApe_Rep" => $valores[1], "cTel_Rep" => $valores[2],"cMovil_Rep" => $valores[3], "cEmail_Rep" => $valores[4], "cDui" => $valores[5]);
                break;
			case "Oferta":
                $campos = array("cNomb_Ofe" => $valores[0], "cImg_Ofe" => $valores[1], "cDesc_Ofe" => $valores[2], "fFecha_Ini" => $valores[3], "fFecha_Fin" => $valores[4], "Id_Prod" => $valores[5], "Id_Local" => $valores[6], "Id_TipOf" => $valores[7], "Precio_Oferta" => $valores[8],"cImg_tb" => $valores[9]);
                break;
            case "Producto":
                $campos = array("cNom_Prod" => $valores[0], "Id_Catp" => $valores[1]);
                break;
			case "Locales":
                $campos = array("cNum_Local" => $valores[0],"cNum_Pas" => $valores[1],"cLocal_Tel" => $valores[2], "cLocal_Hora" => $valores[3], "Id_Emp" => $valores[4],"Id_Rep" => $valores[5],"Id_Edificio" => $valores[6]);
                break;      
            case "Tip_Ofe":
                $campos = array("cClase_Of" => $valores[0], "cDet_TOf" => $valores[1]);
                break;
			case "Categoriap":
				$campos = array("cNom_CatP"=> $valores[0]);
				break;
			case "Post":
				$campos = array("cTitulo"=> $valores[0], "cCopete" => $valores[1], "cCuerpo" => $valores[2], "Id_Usr" => $valores[3], "fModificacion" => $valores[4],"fPublicacion" => $valores[5], "cTipo" => $valores[6]);
				break;
			case "Relacion_Cat":
				$campos = array("Id_Categoria"=> $valores[0], "Id_Post" => $valores[1]);
				break;
			case "Categorias":
				$campos = array("cValor"=> $valores[0]);
				break;
			case "Relacion_Eve":
				$campos = array("Id_Pat"=> $valores[0], "Id_Eve" => $valores[1]);
				break;
			case "Edificios":
				$campos = array("cNom_Edi"=> $valores[0], "cDesc_Edi" => $valores[1], "cImg_Edi" => $valores[2]);
				break;
			case "Evento":
                $campos = array("fFecha_Eve" => $valores[0], "cDescr_Eve" => $valores[1], "nHora_Eve" => $valores[2],"cImg_Eve" => $valores[3],"cNom_Eve" => $valores[4],"Id_Rep" => $valores[5],"cImg_tb" => $valores[6]);
                break;
            default:
                echo "No existe la tabla";
    }
    return $campos;
    }
    function getCamposDelete($valor)
    {
        $campos;
        switch($this->tabla)
        {
            case "Usuarios":
                $campos = "Id_Usr =".$valor;
                break;
			case "Patrocinador":
                $campos = "Id_Pat =".$valor;
                break;
			case "Empresa":
                $campos = "Id_Emp =".$valor;
                break;
			case "Representante":
                $campos = "Id_Rep =".$valor;
                break;
			case "Oferta":
                $campos = "Id_Oferta =".$valor;
                break;
		    case "Producto":
                $campos = "Id_Prod =".$valor;
                break;
			case "Locales":
                $campos = "Id_Local =".$valor;
                break;
            case "Tip_Ofe":
                $campos = "Id_TipOf =".$valor;
                break;
			case "Categoriap":
				$campos = "Id_Catp =".$valor;
				break;
			case "Post":
				$campos = "Id_Post =".$valor;
				break;
			case "Relacion_Cat":
				$campos = "Id_Post =".$valor;
				break;
			case "Categorias":
				$campos = "Id_Categoria =".$valor;
				break;
			case "Relacion_Eve":
				$campos = "Id_Eve =".$valor;
				break;
			case "Edificios":
				$campos = "Id_Edificio =".$valor;
				break;
			case "Evento":
                $campos = "Id_Eve =".$valor;
                break;
            default:
                echo "No existe la tabla";
    }
    return $campos;
    } 
}
?>