<?php
class neg_menu
{
	public $matrizmenu;
    public $parent_id;
	public $login;
	public $strHtml;
	private $dbl;
	public  $tipo_menu;
    public  $reg;
    public  $id_rol;

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
//carga el menu vertical
public function CargaMenu()
{
	$this->matrizmenu = array();
	//$user = $this->login;

	$nombre_sql = "util_sp_menu_con";
	$sql = array();
	$sql["@id_rol"]=$this->id_rol;
	$sql["@tipo_menu"]=$this->tipo_menu;
        $sql["@parent_id"]=$this->parent_id;
	$this->dbl->exe($nombre_sql,$sql);
	$i=0;
	if  ($this->dbl->nreg > 0) {
        $this->reg = $this->dbl->nreg;
		while ($i < $this->dbl->nreg) 
			{
			$this->matrizmenu[$i][0] = $this->dbl->data[$i][0];
            $this->matrizmenu[$i][1] = $this->dbl->data[$i][1];
            $this->matrizmenu[$i][2] = $this->dbl->data[$i][2];
            $this->matrizmenu[$i][3] = $this->dbl->data[$i][3];
            $this->matrizmenu[$i][4] = $this->dbl->data[$i][4];
            $this->matrizmenu[$i][5] = $this->dbl->data[$i][5];
			$i++;
			}
	}
}

public function CargaMenuRol()
{
	$this->matrizmenu = array();
	//$user = $this->login;        
	$nombre_sql = "util_sp_menu_con_rol2";
	$sql = array();
	$sql["@tipo_menu"]="'" . $this->tipo_menu . "'" ;
        $sql["@parent_id"]=$this->parent_id;
        $sql["@id_rol"]=$this->id_rol;       
        $result = $this->dbl->exe($nombre_sql,$sql);
        //print_r($result);
        $tam = count($result); //$result->num_rows;
    //echo $this->dbl->str_exe."<br>";
	$i=0;
        $this->reg=0;
	if  ($tam > 0) {
        $this->reg = $tam;
		while ($i < $tam)
			{
			$this->matrizmenu[$i][0] = $result[$i][0];
            $this->matrizmenu[$i][1] = $result[$i][1];
            $this->matrizmenu[$i][2] = $result[$i][2];
            $this->matrizmenu[$i][3] = $result[$i][3];
            // 4 insertar, 5 mostrar, 6 modificar, 7 eliminar
            $this->matrizmenu[$i][4] = $result[$i][4];
            $this->matrizmenu[$i][5] = $result[$i][5];
            $this->matrizmenu[$i][6] = $result[$i][6];
            $this->matrizmenu[$i][7] = $result[$i][7];
			$i++;
			}
	}
        //print_r($this->matrizmenu);
}

// para cargar el menu el la matriz segun el login de la persona
public function CargaMenuXX()
{
	$this->matrizmenu = array();
	$user = $this->login;
	$site = $this->tipo_site;
	$nombre_sql = "util_sp_menu_con";
	$sql = array();
	$sql["@login"]=$user;
	$sql["@nivel_menu"]=0;
	$sql["@site"] = $site;
	$this->dbl->exe($nombre_sql,$sql);
	$i=0;
	if  ($this->dbl->nreg > 0) {
		while ($i < $this->dbl->nreg) //MENU VERTICAL IZQUIERDO
			{
			$mod = $this->dbl->data[$i][1];
			$this->matrizmenu[$mod]= array();
			$i++;
			}
		$sql = array();	
		$sql["@login"]=$user;	
		$sql["@nivel_menu"]=1;
		$sql["@site"] = $site;
		$this->dbl->exe($nombre_sql,$sql);
		$i=0;
			while ($i < $this->dbl->nreg) //LOS TABS
				{
				$mod = $this->dbl->data[$i][0];
				$tab = $this->dbl->data[$i][2];
				$this->matrizmenu[$mod][$tab]=array();
				$i++;
				}
			$sql = array();	
			$sql["@login"]=$user;	
			$sql["@nivel_menu"]=2;
			$sql["@site"] = $site;
			$this->dbl->exe($nombre_sql,$sql);
			$i=0;
				while ($i < $this->dbl->nreg) //LAS OPCIONES
					{
					$mod = $this->dbl->data[$i][0];
					$tab = $this->dbl->data[$i][1];
					$opc = $this->dbl->data[$i][3];
					$this->matrizmenu[$mod][$tab][$opc] = $this->dbl->data[$i][4];
					$i++;
					}
			
	}  
}
// para cargar el arbol de menu para la asignacion de permisos
public function CargarMenuArbol()
{ 
	$nombre_sql = "util_sp_menu_completo_con";
	$sql = array();
	$sql["@ids"]=0;
	$sql["@nivel"]=0;
	$i=0;
	$this->dbl->exe($nombre_sql,$sql);
	$mod = $this->dbl->data;
	$mod_nreg = $this->dbl->nreg;
	$this->strHtml = "<table width='400' align ='center' border='0' cellspacing='0' cellpadding='0' id='mainTableone'>\n ";
	$this->strHtml .= "<tr><td class='td-titulo-tabla-row' nowrap align='center' width='200'>Modulo&nbsp;Menu</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' colspan='2' align='center' width='100' nowrap>Estado</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='100'>Ver</td></tr>\n ";
	while ($i < $mod_nreg ) //MENU VERTICAL IZQUIERDO
		{
		$this->strHtml .="<tr><td class='td-subtitulo' nowrap>".$mod[$i][1]."</td>\n"; 
		$this->strHtml .="<td class='td-subtitulo' nowrap>". $mod[$i][2] ."</td>\n";
		$this->strHtml .="<td class='td-subtitulo' nowrap><img style='cursor:hand' onclick=\"ModPermisos(".$mod[$i][0].",'".$mod[$i][1]."',0,'".$mod[$i][2]."')\" src='diseno/images/edit.png' alt='Modificar estado de este nivel'></td>\n";
		if ($mod[$i][2]=='Privado')
			$this->strHtml .="<td class='td-subtitulo' align='center'><img style='cursor:hand' onclick='VerUserPermisos(".$mod[$i][0].",0)' src='diseno/images/users.png' alt='Ver Usuarios de esta opcion'></td></tr>\n";
		else
			$this->strHtml .="<td class='td-subtitulo'>&nbsp;</td></tr>\n";	
		$sql["@ids"]=$mod[$i][0];
		$sql["@nivel"]=1;
		$this->dbl->exe($nombre_sql,$sql);
		$tabs = $this->dbl->data;
		$tabs_nreg = $this->dbl->nreg;
		$j=0;
		while ($j < $tabs_nreg ) //TABS
			{
			$this->strHtml .="<tr><td class='td-table-data-alt' nowrap>&nbsp;&nbsp;".$tabs[$j][1]."</td>\n"; 
			$this->strHtml .="<td class='td-table-data-alt' nowrap>". $tabs[$j][2] ."</td>\n";
			$this->strHtml .="<td class='td-table-data-alt' nowrap><img style='cursor:hand' onclick=\"ModPermisos(".$tabs[$j][0].",'".$tabs[$j][1]."',1,'".$tabs[$j][2]."')\" src='diseno/images/edit.png' alt='Modificar estado de este nivel'></td>\n";
			if ($tabs[$j][2]=='Privado')
				$this->strHtml .="<td class='td-table-data-alt' align='center'><img style='cursor:hand' onclick='VerUserPermisos(".$tabs[$j][0].",1)' src='diseno/images/users.png' alt='Ver Usuarios de esta opcion'></td></tr>\n";
			else
				$this->strHtml .="<td class='td-table-data-alt'>&nbsp;</td></tr>\n";

			$sql["@ids"]=$tabs[$j][0];
			$sql["@nivel"]=2;
			$this->dbl->exe($nombre_sql,$sql);
			$opc = $this->dbl->data;
			$opc_nreg = $this->dbl->nreg;
			$k=0;
			while ($k < $opc_nreg ) //Opciones
				{
				$this->strHtml .="<tr>  <td class='td-table-data' nowrap>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$opc[$k][1]."</td>\n"; 
				$this->strHtml .="<td class='td-table-data' nowrap>". $opc[$k][2] ."</td>\n";
				$this->strHtml .="<td class='td-table-data' nowrap><img style='cursor:hand' onclick=\"ModPermisos(".$opc[$k][0].",'".$opc[$k][1]."',2,'".$opc[$k][2]."')\" src='diseno/images/edit.png' alt='Modificar estado de este nivel'></td>\n";
				if ($opc[$k][2]=='Privado')
					$this->strHtml .="<td class='td-table-data' align='center'><img style='cursor:hand' onclick='VerUserPermisos(".$opc[$k][0].",2)' src='diseno/images/users.png' alt='Ver Usuarios de esta opcion'></td></tr>\n";
				else
					$this->strHtml .="<td class='td-table-data'>&nbsp;</td></tr>\n";
				$k++;
				}		
			$j++;
			}
		
		$i++;
		}

	$this->strHtml .= "</table>";
}

//Cambiar el estado de un modulo, tab o opcion del menu
public function CambiarEstado($id, $nivel, $estado){
	$nombre_sql = "util_sp_seguridad_estados_act";
	$sql = array();
	$sql["@id"]=$id;
	$sql["@nivel"]=$nivel;
	$sql["@estado"]=$estado;
	$this->dbl->exe($nombre_sql,$sql);
}
//Asignar permisos a una opcion con el id del user
public function AsignarPermiso($id_opcion, $id_rol, $ins, $most,$mod,$del,$es_opcion){
    //printf($ins.$most.$mod.$del.$es_opcion)."<br>";
	$nombre_sql = "sp_permisos_ins";
    $ins_val=0; 
    $most_val=0;
    $mod_val=0;
    $del_val=0;
    $es=0;
    if ($ins=='true')$ins_val=1;
    if ($most=='true')$most_val=1;
    if ($mod=='true')$mod_val=1;
    if ($del=='true')$del_val=1;
    if ($es_opcion) $es=1;
    //printf($ins_val.$most_val.$mod_val.$del_val.$es_opcion_val);
	$sql = array();
	$sql["@id_rol"]=$id_rol;
	$sql["@id_opcion"]=$id_opcion;
    $sql["@ins"]= "'" . $ins_val . "'";
    $sql["@most"]="'" . $most_val . "'";
    $sql["@mod"]= "'" . $mod_val . "'";
    $sql["@del"]= "'" . $del_val . "'";
    $sql["@es_opcion"]=$es;
	$this->dbl->exe($nombre_sql,$sql);
}
//Elimiar permisos a una opcion con el id del user
public function EliminarPermiso($id_opcion, $id_rol){
	$nombre_sql = "sp_permisos_del";
	$sql = array();
	$sql["@id_rol"]=$id_rol;
	$sql["@id_opcion"]=$id_opcion;
	$sql = "DELETE FROM util_tb_seguridad_permisos WHERE id_rol = $id_rol AND id_opcion = $id_opcion";
	$this->dbl->query($sql);
}
//listar los usuarios con permisos en una opcion
public function ListarOpcionesUser($id, $nivel){
	$nombre_sql = "util_sp_opciones_usuarios_con_permisos_con";
	$sql = array();
	$sql["@id"]=$id;
	$sql["@nivel"]=$nivel;
	$this->dbl->exe($nombre_sql,$sql);
	$lista = $this->dbl->data;
	$i =0;
	$this->strHtml = "<table width='300' align ='center' border='0' cellspacing='0' cellpadding='0' id='mainTableone'>\n ";
	$this->strHtml .= "<tr><td class='td-titulo-tabla-row' nowrap align='center' width='100'>Login</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' width='100' nowrap>Nombre</td>";
	$this->strHtml .= "<td class='td-titulo-tabla-row' align='center' nowrap width='50'>Accion</td></tr>\n ";

	if ($this->dbl->nreg > 0)
		while ($i < $this->dbl->nreg ){
			$this->strHtml .="<tr>  <td class='td-table-data' nowrap>".$lista[$i][1]."</td>\n"; 
			$this->strHtml .="<td class='td-table-data' nowrap>". $lista[$i][2] ."</td>\n";
			$this->strHtml .="<td class='td-table-data' nowrap><img style='cursor:hand' onclick=\"EliminarPermiso(".$lista[$i][0].",".$id.",".$nivel.")\" src='diseno/images/remove.png' alt='Quitar permiso a esta opcion'></td></tr>\n";
		$i++;
		}
		
	$this->strHtml .= "</table>";
	
		
}

//listar los usuarios con permisos en una opcion
public function UsuariosPermisoArray( &$arrval, &$arropt){
	$nombre_sql = "util_sp_usuarios_con";
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

// para cargar el arbol de menu segun el id del rol
public function CargarMenuArbol_x_Rol($id_rol)
{ 
	$i=0;
    $menu = array();
    //$menu_array = new neg_menu();
    $this->parent_id=0;
    $this->id_rol=$id_rol;
    $this->tipo_menu='modulo';
    $this->CargaMenuRol();
    $menu = $this->matrizmenu;
    $reg1=$this->reg;
    
    $this->strHtml = "<table width='280px'  align ='center' border='1' cellspacing='0' cellpadding='0' id=''>\n ";
	//$this->strHtml .= "<tr><td colspan='2' class='td-titulo-tabla-row' nowrap align='center' width='200'>Modulo&nbsp;Menu</td></tr>\n ";

	while ($i < $reg1 ) //MENU VERTICAL IZQUIERDO
		{
		$check='';
        //echo $menu[$i][3];
		if ($menu[$i][3]!=0) $check='checked';
		$this->strHtml .="<tr><td class='td-subtitulo' width='10%' ><input ". $check ." name='permiso_". $menu[$i][0] ."' type='checkbox' value='". $menu[$i][0] ."' />&nbsp;</td>\n";
        //$this->strHtml .="<tr><td class='td-table-data' width='10%'>&nbsp;</td>\n";
		$this->strHtml .="<td  class='td-subtitulo' width='90%' colspan=5 >". $menu[$i][1] ."</td></tr>\n";

        $menu2 = array();
        //$menu_array2 = new neg_menu();
        $this->id_rol=$id_rol;
        $this->parent_id=$menu[$i][0];
        $this->tipo_menu='tab';
        //echo 'va pro'. $menu[$i][0];
        $this->CargaMenuRol();
        $menu2 = $this->matrizmenu;
        $reg2 =$this->reg ;
		$j=0;
		while ($j < $reg2 ) //TABS
			{
			$check='';
            if ($menu2[$j][3]!=0) $check='checked';
            $this->strHtml .="<tr><td class='td-table-data-alt' width='10%' ><input ". $check ." name='permiso_". $menu2[$j][0] ."' type='checkbox' value='". $menu2[$j][0] ."' />&nbsp;</td>\n";
          //  $this->strHtml .="<tr><td class='td-table-data' width='10%'>&nbsp;</td>\n";
            $this->strHtml .="<td class='td-table-data-alt' width='90%' colspan=5 >&nbsp;&nbsp;". $menu2[$j][1] ."</td></tr>\n";

            $menu3 = array();
            //$menu_array3 = new neg_menu();
            $this->id_rol=$id_rol;
            $this->parent_id=$menu2[$j][0];
            $this->tipo_menu='opcion';
            $this->CargaMenuRol();
            $menu3 = $this->matrizmenu;
            $reg3=$this->reg;
			$k=0;
			while ($k < $reg3) //Opciones
				{
				$check='';
                if ($menu3[$k][3]!=0) $check='checked';
                $this->strHtml .="<tr><td class='td-table-data' width='10%'><input ". $check ." name='permiso_". $menu3[$k][0] ."' type='checkbox' value='". $menu3[$k][0] ."' onclick=\"MarcaTodas(this.checked,'insertar_". $menu3[$k][0] ."','mostrar_". $menu3[$k][0] ."','modificar_". $menu3[$k][0] ."','eliminar_". $menu3[$k][0] ."')\" />&nbsp;</td>\n";
            //    $this->strHtml .="<tr><td class='td-table-data' width='10%'>&nbsp;</td>\n";
                $this->strHtml .="<td class='td-table-data' width='50%' nowrap>&nbsp;&nbsp;&nbsp;&nbsp;". $menu3[$k][1] ."</td>\n";
                $this->strHtml .="<td class='td-table-data' width='10%' nowrap><img src='../../diseno/images/add.png'><input ". $menu3[$k][4] ." id='insertar_". $menu3[$k][0] ."' type='checkbox' value='". $menu3[$k][4] ."' /></td>\n";
                $this->strHtml .="<td class='td-table-data' width='10%' nowrap><img src='../../diseno/images/find.png'><input ". $menu3[$k][5] ." id='mostrar_". $menu3[$k][0] ."' type='checkbox' value='". $menu3[$k][5] ."' /></td>\n";
                $this->strHtml .="<td class='td-table-data' width='10%' nowrap><img src='../../diseno/images/edit.png'><input ". $menu3[$k][6] ." id='modificar_". $menu3[$k][0] ."' type='checkbox' value='". $menu3[$k][6] ."' /></td>\n";
                $this->strHtml .="<td class='td-table-data' width='10%' nowrap><img src='../../diseno/images/remove.png'><input ". $menu3[$k][7] ." id='eliminar_". $menu3[$k][0] ."' type='checkbox' value='". $menu3[$k][7] ."' /></td></tr>\n";
                
				$k++;
				}		
			$j++;
			}
		
		$i++;
		}
        $this->strHtml .="<input type='hidden' id='total' value='".$reg1."'>";
	$this->strHtml .= "</table>";
}

}
?>