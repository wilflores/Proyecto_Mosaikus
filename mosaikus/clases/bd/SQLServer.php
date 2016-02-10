<?php
/******************************************************************
SUPER CLASE DATABASE
clase que crea todas las funciones necesarias para trabajr con bases de datos
desde su coneccion hasta cierre de la conexion
 ******************************************************************/
//nombre de la clase

 class ut_Database {

 //**********************************************************************Atributos de la clase
  private $db_HOST;  //nombre del host de base de datos
  private $db_USER;  // user del sistema
  private $db_PASS;  //  password
  private $dbase;    // base de datos
  public  $dbc;      // variable de conexion
  public  $result;   /// atributo que tiene el resultado del query
  public  $nreg;    //// cantidad de registros devueltos
  public  $ncol;    //// cantidad de columnas devueltos
  public  $retval;    //// Valor de retorno
  public  $ultimoID; /// ultimo id insertado (auto incremental)
  public  $data;     /// Array con los registros solicitados
  public  $str_exe;     /// Array con los registros solicitados
  public  $p_output;     /// Array con los registros solicitados
 /***********************************************************************metodos y propiedades*/

   /// propiedad que cambia la conexion a una nueva database o server  con solo invocarla se
   //// cambia la conexion el ultimo parametro determina si la conexion es persistente o no.

//----------------------------Ini - Sobrecarga----------------------------------------
public function __call($f, $p)
       {
        if(method_exists($this, $f.sizeof($p))) return call_user_func_array(array($this, $f.sizeof($p)), $p);
         // function does not exists~
          throw new Exception('Intentando llamar a metodo desconocido '.get_class($this).'::'.$f);
       }
//----------------------------Fin - Sobrecarga----------------------------------------

//----------------------------Ini - Conectar------------------------------------------

  public function Conectar4()
   { putenv("PAGINACION=10");// PARA LA PAGINACION
     if($this->dbc!=null)$this->cerrar();
     $this->db_HOST = 'JLDERIS-HP';
     $this->db_USER = 'sa';
     $this->db_PASS = 'blanco';
//     $this->db_HOST = 'MAVEMCPA07';
//     $this->db_USER = 'sideca_user';
//     $this->db_PASS = 'atila2010prd';
     $this->db_HOST = 'MELVIN-PC\MSSQL2008';
     $this->db_USER = 'sa';
     $this->db_PASS = 'sa12345';
//     $this->db_HOST = 'mavemcpa11';
//     $this->db_USER = 'sa';
//     $this->db_PASS = 'blanco;11';
     $this->dbase   = 'atila';
     //$this->dbase   = 'sideca_bd';
     $persiste=false;
    //conexion  persistente o no

   if($persiste)
    $this->dbc = mssql_pconnect($this->db_HOST,$this->db_USER,$this->db_PASS)  or die('<font color=#FF0000> no se puede conectar a '.$this->db_HOST.' </font>');
   else
    $this->dbc = mssql_connect($this->db_HOST,$this->db_USER,$this->db_PASS)  or die('<font color=#FF0000> no se puede conectar a '.$this->db_HOST.' </font>');

   //////////////

    $m = @mssql_select_db($this->dbase,$this->dbc);// or die('<font color=#FF0000> nombre de base de datos invalida </font>'.$db);
    return($m);


   }
   
  function Conectar0()
   {
      $this->Conectar($this->db_HOST,$this->db_USER,$this->db_PASS,$this->dbase);
   }
  
     ////autoconexion discreta segun los parametros y la base dedatos especificadas en el archivo de conf

 ///metodo para cerrar la conexion con la base de datos
  public function cerrar()
   {//cierra la conexion

    @mssql_free_result($this->result);
    if(is_resource($this->dbc))
	  mssql_close($this->dbc);

   }

  ///metodo para liberar el objeto que retorna el valor del query
   public function liberar ()
   {//cierra la conexion

     mssql_free_result($this->result);

   }


    //*****************************************************************************************ejecucion
   // metodo que ejecuta un query de creacion o modificacion o devuelve un error  el segundo parametro es para liberar el recurso de una vez
  //utilizado ideal para sentencias insert, update, delete etc

    public function query($sql,$lib=false)
   {// ejecuta un query o devuelve un error
     if(is_resource($this->dbc))
	 {
       $this->result = @mssql_query($sql,$this->dbc) or die('<font color=#FF0000> invalid query: '.$sql.' </font>')  ;
       $this->nreg = @mssql_num_rows($this->result);
	 }
	 else
	 die('<font color=#FF0000>Error: Verificar la conexi�n. '.$this->dbc.' Query: '.$sql.'</font>')  ;  

       if($lib) $this->liberar();
   }



 ///lista el numero de registros traidos en el ultimo query
  public function total_registros()
   {

     $tmp = mssql_num_rows($this->result)or die('<font color=#FF0000> error 0 rows: </font>');
     return($tmp);
   }

   private function tipo_dato_par($tipo_dato)
    {
    	$re=0;
	 //Se pasa cada parametro con su tipo respectivo, no hay que preocuparse por las ''!
     if((string)array_search($tipo_dato,array("int","bigint","bit"))!="")
       $re=56;			
     if((string)array_search($tipo_dato,array("varchar","char"))!="")
  	  $re=SQLTEXT;
     if((string)array_search($tipo_dato,array("datetime"))!="")
  	  $re=39;
     if((string)array_search($tipo_dato,array("float","double","decimal"))!="")
  	  $re=62;
	 if((string)array_search($tipo_dato,array("text"))!="")
  	  $re=35;
    return $re; 
    }
	
 public function exe($sp,$a_par_r)
   {
     //echo $sp;
    // print_r($a_par_r);
   //Ejecuta StoredProcedure de MSSQL, en $sp viene el nombre del SP,  en 
   //$a_par_r es el arreglo de parametros recibos, viene en la forma "@parametro"=>VALOR
      
	  unset($this->p_output);
      $a_par_sp= array();
      
	  //en esta funcion buscamos los parametros del SP en cuestion
	  $a_par_sp=$this->sp_param($sp);
	  //print_r($a_par_r);
	  //print_r($a_par_sp);
       // print_r($a_par_sp);
	  //Se inicializa el SP 
       $this->nreg=0;
	  $query = mssql_init($sp, $this->dbc);
       $str_exe="Exec ".$sp." ";

	   //buscamos cada valor que debe recibir el SP
	   foreach($a_par_sp as $reg_act)
	    {
		switch($reg_act['COLUMN_TYPE'])
		 {
		 case 1://parametros de entrada, buscamos las coincidencias con los parametros recibidos($a_par_r)
		   {

		  foreach(array_keys($a_par_r) as $par_act)
	        {   
			   if($reg_act['COLUMN_NAME']==$par_act)
			     { 
				// $a_par_r[$par_act]="'".$a_par_r[$par_act]."'";
				
				//echo $reg_act['COLUMN_NAME']."='".$a_par_r[$par_act]."', ";
				 $str_exe.=$reg_act['COLUMN_NAME']."='".$a_par_r[$par_act]."', ";
				 //echo $this->tipo_dato_par($reg_act['TYPE_NAME']);
                                  
				 $r=mssql_bind($query,$reg_act['COLUMN_NAME'],utf8_decode($a_par_r[$par_act]),$this->tipo_dato_par($reg_act['TYPE_NAME']));

				 }
          	}
		  break;
		   }
		 case 2://parametros de salida, los vamos guardando en el arreglo de parametros de salida($this->p_output)
		   {
		   $this->p_output[$reg_act['COLUMN_NAME']]="";
		   				 $str_exe.=$reg_act['COLUMN_NAME']." output, ";
   		    $r=mssql_bind($query,$reg_act['COLUMN_NAME'],$this->p_output[$reg_act['COLUMN_NAME']],$this->tipo_dato_par($reg_act['TYPE_NAME']),1,0);
		  break;
		   }
		 case 5://parametros de retorno
		   { 
   		    $r=mssql_bind($query, "RETVAL", $this->retval, SQLINT4);

		  break;
		   } 
		 } 
		}	
          $this->str_exe=$str_exe;  
         
          //echo $this->str_exe;
		  //Se ejecuta el SP
	     $this->nreg=0;
	     $this->ncol=0;	
		 mssql_query('SET ANSI_NULLS ON',$this->dbc);
         mssql_query('SET ANSI_WARNINGS ON',$this->dbc);
	 
         //mssql_query('SET ANSI_NULLS ON');
         //mssql_query('SET ANSI_WARNINGS ON');
         
  	      $resu = mssql_execute($query);         

		  //echo mssql_get_last_message();
		  $this->result=$resu;		  
          
		  if($this->retval==0)
		    {
  		    if(is_resource($resu))
			  {
           
			   //Si no hay errores se guarda el set de datos en un array $this->data
			   $this->data=$this->array_desde_result($resu); 				 
                
			   //esta funcion se invoca para que los parametros de salida esten disponibles
			   mssql_next_result($resu);

			  }
			else
			  unset($this->data);  
			  
			  //Se carga $this->nreg
			 if(is_array($this->p_output))
			  { if (array_key_exists('@nreg', $this->p_output)) 
                   $this->nreg=$this->p_output['@nreg'];
			    else
   	  		      if(is_resource($resu))
   	  		      {$this->nreg=mssql_num_rows($resu);}
		          else
		          {$this->nreg=mssql_rows_affected($this->dbc);}

			    //Se carga $this->ultimoID
		 	    if (array_key_exists('@id', $this->p_output)) 
                   $this->ultimoID=$this->p_output['@id'];
		        else
			       $this->ultimoID="";
			   }
			   else
			    {
   	  		      if(is_resource($resu))
			        {$this->nreg=mssql_num_rows($resu);}
		          else
				  $this->nreg=mssql_rows_affected($this->dbc);

			      $this->ultimoID="";
				}	   
		   }

		   if(is_array($this->data[0]))
		     $this->ncol=count(array_keys($this->data[0]))/2;;	
		  //print_r($this->data);
		  return  $this->retval;

   }
   
   public  function array_desde_result($resource)
    { 
	//transforma en un Array un set de Datos que viene en $resource
	$arr = array();

		   while($row = mssql_fetch_array($resource)) 
	        { 
             array_push($arr, $row);
            }
			
	if(count($arr)>0)
       mssql_data_seek($resource,0);
       return $arr;
	}


  private function sp_param($sp)
   {  
     //Buscamos cuales son los parametros del  SP en cuestion, con su nombe,tipo_dato,long, etc....
	 $sql="exec sp_sproc_columns '{$sp}'";
	// echo $sql;
      $this->query($sql);
      return $this->array_desde_result($this->result);

   }

private  function  consulta($sql)
   {
    $result = $result=mssql_query($sql, $this->dbc);
    return ($result);
  }
  
  
  public function autoconexion(){
             $this->conectar("NGSSERVER2","user_gestion","gestion2407","gestion_ngs");
    }  

};   //fin de la super clase

?>
