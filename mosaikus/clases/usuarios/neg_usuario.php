<?php
class neg_usuario
{
	public $id;
	public $login;
	public $nombre;
        public $cedula;
    public $password;
    public $correo;
	public $numempl;
	public $estado;
	public $id_rol;
    public $cargo;
	public $strHtml;
	private $dbl;
    public $reg_mod;
	public $trab;
        public $es_admin_curso;
	
	
// contructor
function __construct()
   {
    $this->dbl = new Mysql();    
    $this->strHtml ='nada';
   }
// destructor de conexion y la class
function __destruct() {
         //$this->dbl->cerrar();
         $this->dbl=null;
     }   
public function ResetUsuario(){
    $this->login='';
	$this->nombre='';
         $this->cedula = '';
    $this->password='';
    $this->correo='';
	$this->estado='';
	$this->numempl='';
    $this->cargo='';
	$this->id_rol='0';
    $this->reg_mod= 0;
}
public function  ValidarUsuario($login,$pass,&$id_rol,&$nombre){
    $this->ConsUsuarioLogin($login);
    //echo $this->password;
    if ($this->id){
        if($this->password==$pass){
            return $this->id;
        }
    }
}
public function  ValidarUsuarioCorreo($login,&$id_rol,&$nombre){
    $this->ConsUsuarioCorreo($login);
    //echo $this->password;
    if ($this->id){        
            return $this->id;        
    }
}
// Insertar un usuario
public function InsertUsuario(&$mensaje){
    $nombre_sql = "sp_usuario_ins";
    $sql = array();
    $sql["@login"]=utf8_encode($this->login);
    $sql["@nombre"]=utf8_encode($this->nombre);
    $sql["@cedula"]=utf8_encode($this->cedula);
    $sql["@password"]=$this->password;
    $sql["@correo"]=utf8_encode($this->correo);
    $sql["@estado"]=$this->estado;
    $sql["@numempl"]=$this->numempl;
    $sql["@cargo"]=$this->cargo;
    $sql["@id_rol"]=$this->id_rol;
    $sql["@es_admin_curso"]=$this->es_admin_curso;
    $this->dbl->exe($nombre_sql,$sql);
    if ($this->dbl->nreg>0)
        $mensaje="alert('Se ingreso el usuario con exito');document.getElementById('FormData').reset();";
    else
        $mensaje="alert('No se pudo ingresar el registro');";
    $this->reg_mod= $this->dbl->nreg;
}
// Editar un usuario
public function ModUsuario(&$mensaje){
    $nombre_sql = "sp_usuario_upd";
    $sql = array();
    $sql["@id"]=$this->id;
    $sql["@login"]=utf8_encode($this->login);
    $sql["@nombre"]=utf8_encode($this->nombre);
    $sql["@cedula"]=utf8_encode($this->cedula);
    $sql["@estado"]=$this->estado;
    $sql["@numempl"]=$this->numempl;
    $sql["@id_rol"]=$this->id_rol;
    $sql["@cargo"]=$this->cargo;
    $sql["@correo"]=utf8_encode($this->correo);
    $sql["@password"]=$this->password;
    $sql["@es_admin_curso"]=$this->es_admin_curso;
    $this->dbl->exe($nombre_sql,$sql);
    if ($this->dbl->nreg>0)
        $mensaje="alert('Se modifico el usuario con exito');";
    else
        $mensaje="alert('No se pudo modificar el registro');";
    $this->reg_mod= $this->dbl->nreg;
}

public function ModPermisosMobile($id_user, $id_mod, $id_per){
    $nombre_sql = "sp_tb_permisos_mobile_act";
    $sql = array();
    $sql["@id_user"]=$id_user;
    $sql["@id_mod"]=$id_mod;
    $sql["@id_per"]=$id_per;
    $this->dbl->exe($nombre_sql,$sql);
}

// Eliminar un usuario
public function DelUsuario(&$mensaje){
    $nombre_sql = "sp_usuario_del";
    $sql = array();
    $sql["@id"]=$this->id;
    $this->dbl->exe($nombre_sql,$sql);
}

//listar los usuarios con permisos en una opcion
public function ListarUsuarios(&$lista){
	$nombre_sql = "sp_usuario_con";
	$sql = array();
	$this->dbl->exe($nombre_sql,'');
	if ($this->dbl->nreg > 0)
        $lista = $this->dbl->data;
    return $this->dbl->nreg;
}

public function listarModulosMobile(&$lista, $id){
    $sql = array();
    $sql["@id_user"]=$id;
    $this->dbl->exe("sp_tb_modulos_mobile",$sql);
    if ($this->dbl->nreg > 0)
        $lista = $this->dbl->data;
    return $this->dbl->nreg;
}

public function ReporteMobile($id){
    $i = 0;
    $reg= $this->listarModulosMobile($lista, $id);
    $this->strHtml = "";
    if ($reg > 0)
        while($i < $reg ){
             $this->strHtml .="<tr>";
             $this->strHtml .="<td class='td-subtitulo' width='10%' ><input  name='permiso_". $lista[$i][0] ."' id='permiso_". $lista[$i][0] ."' type='checkbox' ". $lista[$i][3] ." value='". $lista[$i][0] ."' />&nbsp;</td>";
             $this->strHtml .="<td class='td-subtitulo' width='90%' colspan=5 >". $lista[$i][1] ."&nbsp;</td>";
             $this->strHtml .="</tr>";
            $i++;
        }
}

//listar los usuarios con permisos en una opcion
public function ReporteUsuarios($opcion='usuario'){

    $i =0;
	$this->strHtml = "<table width='360' align ='left' class='borde_table' border='0' cellspacing='0' cellpadding='0' id='mainTableone'>\n ";
	$this->strHtml .= "<tr><td class='td-titulo-tabla-row' nowrap align='center' width='50'>Login</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' width='100' nowrap>Nombre</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' width='100' nowrap>Correo</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='50'>Estado</td>\n ";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='80'>Perfil</td>\n ";
        $this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='130'>Cargo</td>\n ";
        $this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='30'>Admin<br>Curso</td>\n ";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='40'>Acci&oacute;n</td></tr>\n ";
    $reg= $this->ListarUsuarios($lista);
	if ($reg > 0)
		while ($i < $reg ){
			$this->strHtml .="<tr>  <td class='td-table-data' nowrap>".$lista[$i][1]."&nbsp;</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][2] ."&nbsp;</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][3] ."&nbsp;</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][5] ."&nbsp;</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][6] ."&nbsp;</td>\n";
                        $this->strHtml .="<td class='td-table-data' >". $lista[$i][7] ."&nbsp;</td>\n";
                        $this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][10] ."&nbsp;</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>"; 
			
			if ($opcion=='usuario'){
				$this->strHtml .="<img style='cursor:hand' onclick="; 
				$this->strHtml .="\"EditarUsuario(".$lista[$i][0].")\" src='diseno/images/edit.png' alt='Editar Usuario'>&nbsp;";
				$this->strHtml .="<img style='cursor:hand' onclick="; 
				$this->strHtml .="\"EliminarUsuario(".$lista[$i][0].")\" src='diseno/images/remove.png' alt='Eliminar Usuario'>&nbsp;";
				//$this->strHtml .="<img style='cursor:hand' onclick=";
				//$this->strHtml .="\"PermisosMobile(".$lista[$i][0].")\" src='diseno/images/phone.png' alt='Asignar Permisos Mobile'>&nbsp;";
				}
			$this->strHtml .="</td></tr>\n";
		$i++;
		}
		
	$this->strHtml .= "</table>";
	
		
}
// consultar un usuario por id
public function ConsUsuario($id){
	$nombre_sql = "sp_usuario_con_id";
	$sql = array();
	$sql["@id"]=$id;
	$this->dbl->exe($nombre_sql,$sql);
	$lista = $this->dbl->data;
	$i=0;
	if ($this->dbl->nreg>0){
		$this->id = $lista[$i][0];
		$this->login = $lista[$i][1];
		$this->nombre = $lista[$i][2];
                $this->cedula = $lista[$i][10];
		$this->estado = $lista[$i][3];
        $this->correo = $lista[$i][4];
		$this->numempl = $lista[$i][5];
		$this->id_rol = $lista[$i][6];
        $this->cargo = $lista[$i][8];
        $this->password = base64_decode($lista[$i][7]);
        $this->es_admin_curso=$lista[$i][11];
	}	
}
// consultar un usuario por Login
public function ConsUsuarioLogin($login){
	$nombre_sql = "sp_usuario_con_login";
	$sql = array();
	$sql["@login"]=$login;
	//$this->dbl->exe($nombre_sql,$sql);
        $lista = $this->dbl->query("SELECT * FROM tb_usuarios WHERE login = '$login' AND estado = 1");
       
        //print_r($lista);
	//$lista = $this->dbl->data;
	$i=0;
	if (count($lista) > 0){
                //echo $lista[0]['id'];
		$this->id = $lista[0]['id'];
		$this->login = $lista[0]['login'];
		$this->nombre = $lista[0]['nombre'];
                $this->cedula = $lista[0]['cedula'];
		$this->estado = $lista[0]['estado'];
		//$this->numempl = $lista[$i][4];
		$this->id_rol = $lista[0]['id_rol'];
        $this->correo = $lista[0]['correo'];
        $this->cargo = $lista[0]['cargo'];
        $this->password = base64_decode($lista[0]['password']);
        $this->numempl = $lista[0]['numempl'];
         //$this->es_admin_curso=$lista[$i][];
	}	
}

public function ConsUsuarioCorreo($login){
	$nombre_sql = "sp_usuario_con_login";
	$sql = array();
	$sql["@login"]=$login;
	//$this->dbl->exe($nombre_sql,$sql);
        $lista = $this->dbl->query("SELECT * FROM tb_usuarios WHERE correo = '$login' AND estado = 1");
       
        //print_r($lista);
	//$lista = $this->dbl->data;
	$i=0;
	if (count($lista) > 0){
                //echo $lista[0]['id'];
		$this->id = $lista[0]['id'];
		$this->login = $lista[0]['login'];
		$this->nombre = $lista[0]['nombre'];
                $this->cedula = $lista[0]['cedula'];
		$this->estado = $lista[0]['estado'];
		//$this->numempl = $lista[$i][4];
		$this->id_rol = $lista[0]['id_rol'];
        $this->correo = $lista[0]['correo'];
        $this->cargo = $lista[0]['cargo'];
        $this->password = base64_decode($lista[0]['password']);
        $this->numempl = $lista[0]['numempl'];
         //$this->es_admin_curso=$lista[$i][];
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
			$this->strHtml .=")\" src='diseno/images/user_into.png' alt='Seleccionar trabajador'>&nbsp;";
			$this->strHtml .="</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>".$lista[$i][0]."</td>\n"; 
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][2] ."</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][1] ."</td></tr>\n";
			
		$i++;
		}
		
	$this->strHtml .= "</table>";
	}	
/////end class


//listar los usuarios por cargos
public function ListarUsuarios_x_rol(&$lista, $rol){
	$nombre_sql = "sp_usuario_con_rol";
	$sql = array();
    $sql["@rol"]=$rol;
	$this->dbl->exe($nombre_sql,$sql);
	if ($this->dbl->nreg > 0)
        $lista = $this->dbl->data;
    return $this->dbl->nreg;
}
}
