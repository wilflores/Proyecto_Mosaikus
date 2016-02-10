<?php 
/* file:PostgreSQL
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
class PostgreSQL{
  //::::: Atributos :::::
  var $db_name;//Base de datos
  var $db_host;//servidor de la base de datos
  var $db_user;//usuario de la base de datos 
  var $db_pass;//clave de la base de datos
  var $db_port;//puerto de la base de datos
  var $db_conn;//conexion de la base de datos
  var $db_cone;//indica si estoy o no conectado a una base de datos
  var $pagina;
  var $activar_pagina;
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
  function PostgreSQL($conex = -1){
    
      $conn_string = "host=127.0.0.1 port=5432 dbname=santa_monica user=colegio password=diosesmifuerza options='--client_encoding=UTF8'";      
      $this->db_conn=pg_connect($conn_string);
      $this->activar_pagina = false;

    if(pg_connection_status($this->db_conn)===PGSQL_CONNECTION_OK){
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
  function query($sql){
    $return = /*null*/false;
    if($this->conectado()){
      if(($temp=pg_query($this->db_conn,$sql))!==false){
        if(strtoupper(substr($sql,0,6))=="SELECT"){
          $res=pg_fetch_all($temp);
          if($res!=false){
            if(is_array($res[0])){              
              {
//                $filas = count($res);                
//                for($fila = 0; $fila<$filas; $fila++){
//                  foreach($res[$fila] as $NombreColumna => $ValorColumna){
//                    $res[$fila][$NombreColumna] = utf8_decode($ValorColumna);
//                  }
//                }
                return $res;
              }
            }
          }
        }
        else{
          $return = true;
        }
      }
    }
    return $return;
  }
  
  function exe($sql, $params){
      $return = /*null*/false;
      
    if($this->conectado()){
      
      if(($temp=pg_query($this->db_conn,$sql))!==false){
          
        if(strtoupper(substr($sql,0,6))=="SELECT"){
           
          $res=pg_fetch_all($temp);
          
          if($res!=false){
            if(is_array($res[0])){              
              {
//                $filas = count($res);                
//                for($fila = 0; $fila<$filas; $fila++){
//                  foreach($res[$fila] as $NombreColumna => $ValorColumna){
//                    $res[$fila][$NombreColumna] = utf8_decode($ValorColumna);
//                  }
//                }
                return $res;
              }
            }
          }
        }
        else{
          $return = true;
        }
      }
    }
    return $return;
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
//  function insert($table,$fields,$values){
//    $temp1=implode(",",$fields);
//    $temp2=implode("','",$values);
//    return $this->query("INSERT INTO $table ($temp1) VALUES ('$temp2')");
//  }
  function insert_update($sql){
    $return = false;
    {
        $return = $sql;
        pg_send_query($this->db_conn,$return); //or die("pg_query");
        $res1 = pg_get_result($this->db_conn);
        $error = pg_result_error($res1);
        if (strlen($error)>0) throw new Exception($error);
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
        $return = ("DELETE FROM $table WHERE ($where)");
        pg_send_query($this->db_conn,$return); //or die("pg_query");
        $res1 = pg_get_result($this->db_conn);
        $error = pg_result_error($res1);
        if (strlen($error)>0) throw new Exception($error);
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
    if($where!=""){
      $sql="UPDATE $table SET ";
      for ($i=0;$i<count($fields);$i++)
              $sql.=$fields[$i]." = '".$values[$i]."'".(($i==count($fields)-1)?"":" , ");
      $sql.=" WHERE ($where)";
      $return = $this->query($sql);
    }
    return $return;
  }

  function cerrar(){
      
      return pg_close($this->db_conn);
  }
  
 function __destruct(){ // destructor de la clase
  //return pg_close($this->db_conn);
 }

}
?>
