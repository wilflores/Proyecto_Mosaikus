<?php 
/* file:Mysql
*****************************************************************************
Autor   :  Jorge Rodriguez
Version :  0.1
Fecha   :  30-07-2007
Dependencias : 
  1.- Utils.php  
Jorge Rodriguez (rodriguez.jorge.mail@gmail.com)
Modificaciones : 
# 01-08-2007 : Jorge Rodriguez (rodriguez.jorge.mail@gmail.com)
Al metodo query le agregue la opcion de pasar los elementos resultantes de un SELECT
por el metodo acentos de Utils, esto es para que todos los resultado se acomoden o se 
verifiquen en un solo lugar, entonces se crea una dependencia con Utils.
*****************************************************************************
*/
class MysqlBDP{
  //::::: Atributos :::::
  var $db_name;//Base de datos
  var $db_host;//servidor de la base de datos
  var $db_user;//usuario de la base de datos 
  var $db_pass;//clave de la base de datos
  var $db_port;//puerto de la base de datos
  var $db_conn;//conexion de la base de datos
  var $db_cone;//indica si estoy o no conectado a una base de datos
  var $data;
  //var static $last_db_name;//ultima base de datos a la que me conecte
  //var static $last_db_conn;//ultima conexion a una base de datos
  //:::::Metodos :::::
  /************************************************************************************************/
  /**
    * Constructor de la clase
    * toma los datos de conexion del archivo config.php
    * si no se incluye el archivo se debera de definir las constantes
    * antes de instanciarlo
    *
    * @return null
    */
  function MysqlBDP($conex = -1){
    if(!class_exists('Utils')){
      include_once(dirname(dirname(dirname(__FILE__))).'/clases/utilidades/Utils.php');
    }
//    global $CONEXION;
//    global $BD;
//    global $HOST;
//    global $USER;
//    global $PASSWORD;
//    global $PORT;
    if($conex != -1)
     $CONEXION = $conex;
    //$this->db_name = 'grupopri_rutero';//[$CONEXION];
    //$this->db_host = 'grupoprincipal.com.ve';//[$CONEXION];
    //$this->db_user = 'grupopri_rutero';//[$CONEXION];
    //$this->db_pass = 'gfpss';//[$CONEXION];
    $this->db_name = 'mosaikus_admin';//[$CONEXION];
    //$this->db_name = 'sslma135_five';//[$CONEXION];
    $this->db_host = 'localhost';//[$CONEXION];
	//$this->db_user = 'adm_bd';//[$CONEXION];
    $this->db_user = 'root';//[$CONEXION];
    //$this->db_pass = '672312';//[$CONEXION];
	$this->db_pass = '';//[$CONEXION];
    
//    $this->db_name = 'sslma135_ssp';//[$CONEXION];
//    $this->db_host = 'localhost';//[$CONEXION];
//    $this->db_user = 'sslma135';//[$CONEXION];
//    $this->db_pass = 'gabo1982';//[$CONEXION];
    
//    $this->db_host = 'ubicarepuestobd.db.7958676.hostedresource.com';//[$CONEXION];
//    $this->db_user = 'ubicarepuestobd';//[$CONEXION];
//    $this->db_pass = 'Ubica.08072011';//[$CONEXION];
    //$this->db_port = $PORT[$CONEXION];      
      $this->db_conn=mysqli_connect($this->db_host,$this->db_user, $this->db_pass);
      //$this->db_conn=mysql_connect($this->db_host,$this->db_user, $this->db_pass);
	  $this->db_conn->select_db($this->db_name);
	  $this->db_conn->query("SET NAMES utf8");
	  //$juego_cars = mysql_client_encoding($this->db_conn);

//echo "El juego de caracteres actual es: $juego_cars\n";

    if($this->db_conn->stat()!=null){
      $this->db_cone = true;
    }
    else{
      $this->db_cone = false;
    }
  }
  /************************************************************************************************/
  /**
    * Metodo para saber si se esta conectado a
    * una base de datos o no
    *
    * @return boolean
    */
  function conectado(){
    return $this->db_cone;
  }


  
  /************************************************************************************************/
  /**
    * Ejecuta una consulta en la base de datos
    * si es un select retorna una matriz con todo
    * el resultado obtenido
    *
    * @param $sql  consulta a ejecutar
    * @return array
    */
  function query($sql,$utils = true){
       //echo $sql;
    $return = /*null*/false;
    if($this->conectado()){
        //echo $sql;
        $this->db_conn->query("SET NAMES utf8");
	  $temp = $this->db_conn->query($sql);          
//          print_r($temp);
      $res=array();
      if($temp !=false){
        if((strtoupper(substr($sql,0,6))=="SELECT") || (strtoupper(substr($sql,0,8))=="DESCRIBE") || (strtoupper(substr($sql,0,4))=="SHOW")){
          
		  $i = 0;
		  while ($row = mysqli_fetch_array($temp)) {
			$res[$i] = $row;
			$i++;
		  }                  
          return $res;
        }
        else{
          $return = true;
        }
      }
    }
    return $res;
  }
  
  public function corregir_parametros($params){        
      foreach($params as $key  => $value){          
          if (!is_array($params[$key]))
          $params[$key] = $this->db_conn->real_escape_string($params[$key]);
      }
      return $params;
  }

  function exe($sp, $params = array()){
      //$this->db_conn=mysqli_connect('localhost','root', '123456');
        $sql = 'CALL ' . $sp . ' ( ';
        foreach ($params as $key => $value){
            $sql .= $value . ',';
        }
        $sql = substr($sql, 0, strlen($sql) - 1) . ')';

        $con = mysqli_connect($this->db_host,$this->db_user, $this->db_pass);
        $con->select_db($this->db_name);
        $con->query("SET NAMES utf8");

 //Calling the total_price stored procedure using the @t OUT parameter
        //  echo $sql;
 $result= $con->query($sql) or die(mysql_error());
$res = array();
 $i = 0;

		  while ($row = mysqli_fetch_array($result)) {
                      
			$res[$i] = $row;
                        
			$i++;

		  }
// while($row = mysqli_fetch_array($result))
// {
//  //echo 'The total price is = '.$row[0];
//  print_r($row);
// }
//print_r($res);
mysqli_close($con);
        
    return $res;
    //return $return;
  }
	/************************************************************************************************/	
	private static function extractFields($saSql){
		$mlFields	=	array();
		preg_match ("/SELECT\s+(.+?)\s+FROM\s+.+/is", $saSql, $mlFields );
		if (empty($mlFields)){
			trigger_error("This \"$saSql\" doesn't seem a select/Esto \"$saSql\" no parece ser un select");				
			return null;
		}
		else{
			return $mlFields[1];
		}
	}
  /************************************************************************************************/	
	private static function makeSQLPager ( $saSql , $iaPage, $iaLimit){
		$slFields	=	self::extractFields($saSql);
		if (isset($slFields)){
			$slSql	= str_replace($slFields , "SQL_CALC_FOUND_ROWS $slFields " , $saSql);
			return	$slSql . " LIMIT " . ($iaPage-1)*$iaLimit . "," . $iaLimit;
		}
		else{
			return null;
		}
	}
	
  /************************************************************************************************/
  /**
    * Ejecuta una consulta en la base de datos
    * si es un select retorna una matriz con todo
    * el resultado obtenido
    *
    * @param $sql  consulta a ejecutar
    * @return array
    */
  function paginar($sql, $pagina = 1 , $num_por_pag = 4){
    //$pagina = " LIMIT " . $num_por_pag * ($pagina - 1) . " , " . $num_por_pag;
	$sql = self::makeSQLPager($sql, $pagina, $num_por_pag);
	$datos['data'] = $this->query($sql);
	$datos["totall"]	= mysql_query("SELECT FOUND_ROWS();", $this->db_conn);
	$datos["total"]	=	@mysql_result($datos["totall"],0);
	$datos["pages"]	=	ceil($datos["total"]/$num_por_pag);
    return $datos;
  }  

  /************************************************************************************************/
  /** 
    * Ejecuta un insert en la base de datos
    * No coloque los campos seriales
    *
    * @param $table Tabla en la que se va a insertar
    * @param $fields Columnas de la tabla
    * @param $values Valores de las columnas
    * @return boolean
    */
  function insert($table,$fields,$values){
    $temp1=implode(",",$fields);
    $temp2=implode("','",$values);
    return $this->query("INSERT INTO $table ($temp1) VALUES ('$temp2')");
  }
  
  function insert_update($sql){
    $return = false;
    {
        $return = $sql;        
        $res = $this->db_conn->query($sql);

        if (strlen($this->db_conn->error) > 0) {            
            throw new Exception($this->db_conn->error);
        }
    }
    return $return;
  }
  /************************************************************************************************/
  /** 
    * Ejecuta un delete en la base de datos
    *
    * @param $table Tabla en la que se va a ejecutar el delete
    * @param $where Condicion del delete
    * @return boolean
    */
  function delete($table,$where){
    $return = false;
    if($where!=""){
      $return = $this->query("DELETE FROM $table WHERE ($where)");
    }
    return $return;
  }
  /************************************************************************************************/
  /**
    * Ejecuta un update en la base de datos
    *
    * @param $table Tabla en la que se va a ejecutar el update
    * @param $fields Columnas de la tabla
    * @param $values Valores de las columnas
    * @param $where Condicion del update
    * @return boolean
    */
  function update($table,$fields,$values,$where){
    $return = false;
    print_r($values);
    if($where!=""){
      $sql="UPDATE $table SET ";
      for ($i=0;$i<count($fields);$i++){
              $sql.=$fields[$i]." = ".$values[$i]."".(($i==count($fields)-1)?"":" , ");
              echo $values[$i];
      }
      $sql.=" WHERE ($where)";
      echo $sql;
      $return = $this->query($sql);
    }
    return $return;
  }

 function __destruct(){ // destructor de la clase
  //return mysql_close($this->db_conn);
 }

}
?>