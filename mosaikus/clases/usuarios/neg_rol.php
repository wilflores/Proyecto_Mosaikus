<?php

class neg_rol
{
	public $id_rol;
	public $descripcion;
	public $strHtml;
	private $dbl;
  
	
public function ResetRol(){
    $this->descripcion='';
	$this->id_rol='';

}
// contructor
function __construct()
   {
    $this->dbl = new Mysql();
    //$this->dbl->conectar();
    $this->strHtml ='nada';
   }
// destructor de conexion y la class
function __destruct() {
         //$this->dbl->cerrar();
         $this->dbl=null;
     }   
	 
//listar los roles para un combo
public function ListarRolArray( &$arrval, &$arropt){
	$nombre_sql = "sp_rol_con";
	$this->dbl->exe($nombre_sql,'');
	$lista = $this->dbl->data;
	$i =0;
	$arrval[$i] = 0;
	$arropt[$i] = '[Seleccione]';
	if ($this->dbl->nreg > 0)
		while ($i < $this->dbl->nreg ){
			$arrval[$i+1] = $lista[$i][0];
			$arropt[$i+1] = $lista[$i][1];
		$i++;
		}

}
//listar los roles
public function ListarRoles(&$lista){
	$nombre_sql = "sp_rol_con";
	$sql = array();
	$this->dbl->exe($nombre_sql,'');
	if ($this->dbl->nreg > 0)
        $lista = $this->dbl->data;
    return $this->dbl->nreg;
}
// Insertar un rol
public function InsertRol(&$mensaje){
	$nombre_sql = "sp_rol_ins";
	$sql = array();
    $sql["@descripcion"]=utf8_encode($this->descripcion);
	$this->dbl->exe($nombre_sql,$sql);
	if ($this->dbl->nreg>0)
		$mensaje="alert('Se ingreso el rol con exito');";
	else	
		$mensaje="alert('No se pudo ingresar el registro');";
}
// Editar un usuario
public function ModRol(&$mensaje){
	$nombre_sql = "sp_rol_upd";
	$sql = array();
	$sql["@id_rol"]=$this->id_rol;
	$sql["@descripcion"]=utf8_encode($this->descripcion);
	$this->dbl->exe($nombre_sql,$sql);
	if ($this->dbl->nreg>0)
		$mensaje="alert('Se modifico el Rol con exito');";
	else	
		$mensaje="alert('No se pudo modificar el registro');";
}	 
// Eliminar un usuario
public function DelRol(&$mensaje){
	$nombre_sql = "sp_rol_del";
	$sql = array();
	$sql["@id_rol"]=$this->id_rol;
	$this->dbl->exe($nombre_sql,$sql);
	if ($this->dbl->nreg>0)
		$mensaje="alert('Se elimino Rol con exito');";
	else	
		$mensaje="alert('No se pudo eliminar el registro');";
}

//listar los usuarios con permisos en una opcion
public function ReporteRol(){
	$i =0;
	$this->strHtml = "<table width='160' align ='left' border='0' class='borde_table' cellspacing='0' cellpadding='0' id='mainTableone'>\n ";
	$this->strHtml .= "<tr><td class='td-titulo-tabla-row' nowrap align='left' width='50'>Id</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' width='100' nowrap>Descripci&oacute;n</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='50'>Acci&oacute;n</td></tr>\n ";
    $reg= $this->ListarRoles($lista);
	if ($reg > 0)
		while ($i < $reg ){
			$this->strHtml .="<tr>  <td class='td-table-data' nowrap>".$lista[$i][0]."</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][1] ."</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap><img style='cursor:pointer' onclick=";
			$this->strHtml .="\"VerPermisos(".$lista[$i][0].")\" src='diseno/images/users.png' alt='Ver Rol'>&nbsp;";
			$this->strHtml .="<img style='cursor:pointer' onclick=";
			$this->strHtml .="\"EditarRol(".$lista[$i][0].")\" src='diseno/images/edit.png' alt='Editar Rol'>&nbsp;";
			$this->strHtml .="<img style='cursor:pointer' onclick=";
			$this->strHtml .="\"EliminarRol(".$lista[$i][0].")\" src='diseno/images/remove.png' alt='Eliminar Rol'>&nbsp;";
			$this->strHtml .="</td></tr>\n";
		$i++;
		}
		
	$this->strHtml .= "</table>";
	
		
}
// consultar un usuario por id
public function ConsRol($id_rol){
	$nombre_sql = "sp_rol_con_id";
	$sql = array();
	$sql["@id_rol"]=$id_rol;
	$this->dbl->exe($nombre_sql,$sql);
	$lista = $this->dbl->data;
	$i=0;
	if ($this->dbl->nreg>0){
		$this->id_rol = $lista[$i][0];
		$this->descripcion = $lista[$i][1];
	}	
}
// consultar un usuario por Login
public function ConsUsuarioLogin($login){
	$nombre_sql = "util_sp_usuario_con_login";
	$sql = array();
	$sql["@login"]=$login;
	$this->dbl->exe($nombre_sql,$sql);
	$lista = $this->dbl->data;
	$i=0;
	if ($this->dbl->nreg>0){
		$this->id = $lista[$i][0];
		$this->login = $lista[$i][1];
		$this->nombre = $lista[$i][2];
		$this->estado = $lista[$i][3];
		$this->numempl = $lista[$i][4];
		$this->perfil = $lista[$i][5];
	}	
}
public function DatosEmpleadoVentana(){
		$combo = new ut_Tool();
		$nombre_sql = "util_sp_listado_nombre_empleado";
		$this->dbl->exe($nombre_sql,'');
		$lista = $this->dbl->data;
		$i =0;
	
		$this->strHtml = "<table align ='center' border='0' cellspacing='0' cellpadding='0' id='mainTableone'>\n ";
	$this->strHtml .= "<tr><td class='td-titulo-tabla-row' align='center' nowrap width='30'>Accion</td>\n ";	
	$this->strHtml .= "<td class='td-titulo-tabla-row' nowrap align='center' width='40'>Num.Empl</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' width='50' nowrap>Cedula</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' width='200' nowrap>Nombre&nbsp;Empleado</td></tr>";
	
	if ($this->dbl->nreg > 0)
		while ($i < $this->dbl->nreg ){
			$this->strHtml .="<tr>  <td class='td-table-data' nowrap align='center'><img style='cursor:hand' onclick="; 
			$this->strHtml .="\"Cargardatos(".$lista[$i][0].",";//num trab
			$this->strHtml .="'".$lista[$i][2]."',";//cedula
			$this->strHtml .="'".$lista[$i][3]."',";//fecha ingreso
			$this->strHtml .="'".$lista[$i][4]."',";//apellido
			$this->strHtml .="'".$lista[$i][5]."',";//nombres
			$this->strHtml .="'".$lista[$i][7]."',";//fecha_nacimiento
			$this->strHtml .="'".$lista[$i][8]."',";//nacionalidad
			$this->strHtml .="'".$lista[$i][9]."',";//sexo
			$this->strHtml .="'".$lista[$i][10]."',";//edo_civil
			$this->strHtml .="'".$lista[$i][11]."',";//desc_puesto
			$this->strHtml .="".$lista[$i][12].",";//cod_puesto
			$this->strHtml .="'".$lista[$i][13]."',";//domicilio
			$this->strHtml .="'".$lista[$i][14]."',";//estado_provincia
			$this->strHtml .="'".$lista[$i][15]."',";//poblacion
			$this->strHtml .="'".$lista[$i][16]."',";//telefono	
			$this->strHtml .="'".$lista[$i][17]."',";//correo
			$this->strHtml .="'".$lista[$i][18]."'";//login			
			$this->strHtml .=")\" src='../../images/interfaz/user_into.png' alt='Seleccionar trabajador'>&nbsp;";
			$this->strHtml .="</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>".$lista[$i][0]."</td>\n"; 
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][2] ."</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][1] ."</td></tr>\n";
			
		$i++;
		}
		
	$this->strHtml .= "</table>";
	}	
/////end class


}
