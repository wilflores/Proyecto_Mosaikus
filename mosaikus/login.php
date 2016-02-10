<?php

include_once('configuracion/configuracion.php');

session_name($GLOBALS[SESSION]);
session_start();
session_unset();

                        header("Location: ../msks/index.php");
                        
 

?>
