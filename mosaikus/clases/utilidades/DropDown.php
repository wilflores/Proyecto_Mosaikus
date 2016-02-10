<?php
/** Clase DropDown
**************************************************************************************************
Proyecto: SACSERHI-CVG		Ruta: librería/clases/componentes/DropDown.php
Propósito: Crea un Elemento SELECT de html a partir de un arreglo o recurso (resultado de una consulta) 
	   del manejador de Base de Datos PostgreSQL
Version:  0.4 		PHP Version >=5			
Fuente consultada:  Maik (www.escasala.com)
Fecha: 25 Oct 2006	Actualizada: Agosto 2007
Requirimientos:
	Clase Template
	PHP Version >= 4									*/
/**************************************************************************************************/
//include_once(dirname(dirname(__FILE__))."/Template.php" );
class DropDown
{
	//Constants Properties
	const MSG_ERR_PATH_NOT_VALID = '[ERROR] Debes indicar un ruta correcta a $DropDown->PATH';
	const MSG_ERR_KEYS_MISSED = '[Error] Tu debes asignar llaves label y value en un array o una fuente de datos de SQL';
	
	//Privates Properties 
	private $selecteds;
	private $Template;
	private $isEmpty	=	false;
	private $Data		=	array();
	
	//Public Properties
	public $readOnly;
	public $PATH		=	"diseno/Templates/usuario/";
	public $fileData	=	"DropDown";
	public $multiple;
	public $name		=	"mySelect";
	public $type		=	"data";
	public $onChange	=	"";
	public function __construct()
	{
		$this->readOnly 		=	false;
		$this->multiple 		=	false;
		$this->Template			=	new Template();
		$this->PATH         = PATH_TO_TEMPLATES.'usuario/';
		$this->clearSelecteds();
	}

	public function resourceToArray ( $Resource = NULL ) 
	{
		if (!is_resource ($Resource))
		{
			return false;
		}		
		$Data =	array();
		if (@pg_num_rows($Resource)>0)
		{
			while ( $row = @pg_fetch_array($Resource, $i))
			{
			array_push($Data, $row);
			}
			$this->isEmpty		=	false;
		}
		else
		{
				$total		=	pg_num_fields($Resource);
				$fieldNames	=	array();
				for ($i=0 ; $i < $total ; $i++ )
				{
					$name	=	pg_field_name($Resource,$i);
					$fieldNames[$name]= NULL;
				}
				array_push($Data,$fieldNames);
				$this->isEmpty	=	true;
		}		
		return $Data;
	}	
	
	public function setFunction($FieldName, $FunctionName)
	{
		$this->Functions[$FieldName]	=	$FunctionName;
	}

	private function printField($field, $value)
	{
		if ( (isset($this->Functions[$field]))  OR ($this->Functions[$this->Fields[$field]]) )
		{
			$function	=	(isset($this->Functions[$field]))?$this->Functions[$field]:$this->Functions[$this->Fields[$field]] ;
			@eval ( " \$value = \$function (\$value);");
		}
		return $value;
	}	
	
	private function setOption	( $Option = NULL, $isSelected=false )
	{
		$this->Template->setData($this->Data["option"]);
		if ($isSelected)
		{
			$selected	= "selected=\"selected\"";
		}
		else
		{
			$selected	=	"";
		}
		/* invalido hoy 09/08/07 por mostrar error OJO
		if ( !isset($Option["value"],$Option["label"]))
		{
			$Option["label"]	=	self::MSG_ERR_KEYS_MISSED;
			$Option["value"]	=	-1;
		}*/
		$this->Template->setVars( array(
			"value"	=>	$this->printField("value",$Option["value"]),
			"label"	=>	$this->printField("label",Utils::acentos($Option["label"])),
			"selected" 	=>	$selected
		)
	);
		return $this->Template->show()."\n";
	}
	
	public function addSelected($value)
	{
		if (!is_array($this->selecteds))
		{
			$this->selecteds	=	array($value);
		}
		else
		{
			array_push($this->selecteds,$value);
		}
	}
	
	public function clearSelecteds ()
	{
		$this->selecteds	=	array();
	}
	
	public function setEvent( $Event, $Action)
	{
		if (!isset($this->Events))
		{
			$this->Events	= 	array();
		}
		array_push($this->Events, array("Event" => $Event, "Action"=>$Action));
	}
	private function getEvents ()
	{
		$Events	= "";
		if (isset($this->Events))
		{
			foreach ($this->Events as $Event)
			{
				$Events 	.=	$Event["Event"] . "= \"" . $Event["Action"]."\" ";
			}
		}
		return $Events;
	}
	public function setID ( $id = "" ) 
	{
		$this->ID	=	"id=\"$id\" ";
	}
	
	public function setSize ( $size)
	{
		$this->Size	=	"size=\"$size\"";
	}
	
	public function initAll()
	{
		$this->Events	=	$this->Functions	=	$this->selecteds = array();
		$this->Size		=	$this->ID	=	"";
		$this->multiple	=	false;
	}
	public function show ( $Data = NULL )
	{
		$this->Template->PATH	=	$this->PATH;
		if ( !$this->Data = $this->Template->getTemplates($this->fileData))
		{
			return self::MSG_ERR_PATH_NOT_VALID;
		}	
		if ( !is_array ($Data))
		{
			if (is_resource($Data))
			{
				$Data	= 	$this->resourceToArray($Data);				
				if ($this->isEmpty)
				{
					$this->Template->setData($this->Data["noData"]);
					$vars["text"]	=	$this->type;
					$this->Template->setVars($vars);
					$Options = $this->setOption(array("label"=>$this->Template->show(), "value"=>$this->Data["noDataValue"]),true);
				}
			}
			else
			{
				// provoked error intentionally
				$Options = $this->setOption();
				$this->isEmpty = true;
			}
		}
		else
		{
			if (count($Data) == 0)
			{
				$this->Template->setData($this->Data["noData"]);
				$vars["text"]	=	$this->type;
				$this->Template->setVars($vars);
				$Options = $this->setOption(array("label"=>$this->Template->show(), "value"=>$this->Data["noDataValue"]),true);
				$this->isEmpty	=	true;
			}
		}
		$Events		=	$this->getEvents();
		if (!$this->isEmpty)
		{
			
			$Options	=	"";
			foreach ($Data as $option)
			{
				$Options 	.=	$this->setOption( $option, in_array($option["value"], $this->selecteds ));
				
			}
		}
		if ($this->multiple)
		{
			$multiple	=	"multiple=\"multiple\" ";
			if (!isset($this->Size))
			{
				$this->setSize(count($Data));
			}
		}
		else
		{
			$multiple	=	"";
		}
		if ($this->readOnly)
		{
			$readOnly	=	" disabled=\"disabled\" ";
		}
		else
		{
			$readOnly	=	"";
		}
		if ($this->onChange)
		{
			$onChange	=	" onChange=\"onChange\" ";
		}
		else
		{
			$onChange	=	"";
		}
		$this->Template->setData($this->Data["select"]);
		$this->Template->setVars(
		array(	"id"		=>	$this->ID,
			"name"		=>	$this->name,
			"options"	=>	$Options,
			"events"	=>	$Events,
			"multiple"	=>	$multiple,
			"size"		=>	$this->Size,
			"readonly"	=>	$readOnly,
			"onChange"	=>	$this->onChange
			)
		);
		
		return $this->Template->show();
	}
}
?>