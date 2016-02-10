<?php
	$MENSAJE='';
	include("../mosaikus/clases/bd/MysqlBDP.php");
        include("../mosaikus/clases/bd/Mysql.php");

        session_name('mosaikus');
        session_start();
        session_unset();

        //echo $_SERVER['SERVER_NAME'];
        switch ($_SERVER['SERVER_NAME']) {
            case 'localhost':
                $id_empresa = 11;
                //$id_empresa = 8;
                break;
            case 'www.zaldivar.mosaikus.cl':
            case 'zaldivar.mosaikus.cl':
            case 'zaldivar.mosaikus.com':
            case 'www.zaldivar.mosaikus.com':
                $id_empresa = 2;
                break;
            case 'santateresa.mosaikus.cl':
            case 'www.santateresa.mosaikus.cl':
            case 'santateresa.mosaikus.com':
            case 'www.santateresa.mosaikus.com':
                $id_empresa = 5;
                break;
            case 'www.tecnofast.mosaikus.cl':
            case 'tecnofast.mosaikus.cl':
            case 'www.tecnofast.mosaikus.com':
            case 'tecnofast.mosaikus.com':
                $id_empresa = 1;
                break;
            case 'rombuss.mosaikus.cl':
            case 'www.rombuss.mosaikus.cl':
            case 'rombuss.mosaikus.com':
            case 'www.rombuss.mosaikus.com':
                $id_empresa = 4;
                break;
            case 'rombusc.mosaikus.cl':
            case 'www.rombusc.mosaikus.cl':
            case 'rombusc.mosaikus.com':
            case 'www.rombusc.mosaikus.com':
                $id_empresa = 3;
                break;
            case 'demo.mosaikus.cl':
            case 'www.demo.mosaikus.cl':
            case 'demo.mosaikus.com':
            case 'www.demo.mosaikus.com':
                $id_empresa = 2;
                break;
            case 'masisa-cl-mc.mosaikus.cl':
            case 'www.masisa-cl-mc.mosaikus.cl':
            case 'masisa-cl-mc.mosaikus.com':
            case 'www.masisa-cl-mc.mosaikus.com':
                $id_empresa = 8;
                break;
            default:
                $id_empresa = 6;
                $id_empresa = 6;
                break;
        }
        $Opcion = $_GET[Opcion];
        
	if($Opcion=='I')
	{
                $pagina = new MysqlBDP();
                $params = $pagina->corregir_parametros($_GET);
                $TxtUsuario = $params[TxtUsuario];
                //$arr_usuario = explode(" ", $TxtUsuario);
                //$TxtUsuario = str_replace(".", "", $arr_usuario[0]) ;

                $TxtEmpresa = $params[TxtEmpresa];
                $arr_empresa = explode("-", $TxtEmpresa);
                $TxtEmpresa = str_replace(".", "", $arr_empresa[0]) ;
                $TxtPwd = $params[TxtPwd];
                
		require_once("includes/EnDecryptText.php");
		$Consulta="Select t2.businessName,t2.db,t2.loginDB,t2.passwordDB
                                From mosaikus_admin.mos_adm_empresas t2 
                            Where id_empresa='".$id_empresa."' ";
                
            //    exit();
                //$Consulta="Select t1.*,t2.businessName,t2.db,t2.loginDB,t2.passwordDB
		//						From mosaikus_admin.mos_adm_acceso t1 inner join  mosaikus_admin.mos_adm_empresas t2 on t1.id_empresa=t2.id_empresa
		//						Where t1.id_usuario='".$TxtUsuario."' and  t1.id_empresa='".$TxtEmpresa."' and (t1.password_1='".md5($TxtPwd)."' or  t1.password_1='".$TxtPwd."')";
                
                $data = $pagina->query($Consulta, array());
                
//                $Consulta="Select t1.*,t2.businessName,t2.db,t2.loginDB,t2.passwordDB
//								From mosaikus_admin.mos_adm_acceso t1 inner join  mosaikus_admin.mos_adm_empresas t2 on t1.id_empresa=t2.id_empresa
//								Where t1.id_usuario='".$TxtUsuario."' and  t1.id_empresa='".$TxtEmpresa."' and (t1.password_1='".md5($TxtPwd)."' or  t1.password_1='".$TxtPwd."')";
                //echo $Consulta;
            //    exit();
//                $data = $pagina->query($Consulta, array());
                //print_r($data) . '<br/><br/>';
                if (count($data)>0)
                
		//$Resp=mysql_query($Consulta);
		//if($Fila=mysql_fetch_assoc($Resp))
		{
			//SOLO SI EL ACCESO ES CORRECTO CREAMOS LAS VARIABLES DE SESION....
			

                        $Fila = $data[0];
//                        $_SESSION[BD]=$Fila["db"];
//			$_SESSION[Usuario]=$Fila["loginDB"];
//			$_SESSION[Pwd]=$Fila["passwordDB"];
			$EnDecryptText = new EnDecryptText();
			$_SESSION[BaseDato] = $EnDecryptText->Encrypt_Text($Fila["db"]);
			$_SESSION[LoginBD] = $EnDecryptText->Encrypt_Text($Fila["loginDB"]);
			$_SESSION[PwdBD] = $EnDecryptText->Encrypt_Text($Fila["passwordDB"]);
                        
                        $pagina2 = new Mysql($Fila["db"],$Fila["loginDB"],$Fila["passwordDB"]);

			//$CookNamUsuario=NombreUsuario($CookIdUsuario);
			$Consulta="select id_usuario,super_usuario "
                                //. ",CONCAT(UPPER(LEFT(nombres, 1)), LOWER(SUBSTRING(nombres, 2))) nombres"
                                . ",initcap(nombres) nombres"
                                . ",CONCAT(UPPER(LEFT(apellido_paterno, 1)), LOWER(SUBSTRING(apellido_paterno, 2))) apellido_paterno "
                                . ",CONCAT(UPPER(LEFT(apellido_materno, 1)), LOWER(SUBSTRING(apellido_materno, 2))) apellido_materno "
                                . " from mos_usuario where email='".$TxtUsuario."' and (password_1='".md5($TxtPwd)."')";
                        //echo $Consulta;
                        $data = $pagina2->query($Consulta, array());
                        //echo count($data);
                        if (count($data)<=0){
                            //echo 1;		
                            echo Header("Location:index.php?ingreso=XyZ3wGt4");
                            exit();
                        }
			$Fila2 = $data[0];
			$_SESSION[CookNomEmpresaGeneralIni]=$Fila["businessName"];
			$_SESSION[CookIdEmpresa]=$id_empresa;//$Fila["id_empresa"];
			$_SESSION[CookIdUsuario]=$Fila2["id_usuario"];
			$_SESSION[CookWeb]='S';

			
                        //print_r($data);
                        
//                        echo(utf8_decode($Fila2[apellido_paterno]));
//                        exit();
			//$Resp2=mysql_query($Consulta);
			//if($Fila2=mysql_fetch_assoc($Resp2))
                        
			{
				$_SESSION[SuperUser]=$Fila2[super_usuario];
                                //$_SESSION[CookNamUsuario] = (ucfirst(strtolower(utf8_decode($Fila2["nombres"])." ".utf8_decode($Fila2["apellido_paterno"])." ".utf8_decode($Fila2["apellido_materno"]))));
                                $_SESSION[CookNamUsuario] = (((($Fila2["nombres"])." ".($Fila2["apellido_paterno"])." ".($Fila2["apellido_materno"]))));
                                //echo (ucfirst(strtolower(utf8_decode($Fila2["nombres"])." ".utf8_decode($Fila2["apellido_paterno"])." ".utf8_decode($Fila2["apellido_materno"]))));
                                //echo $_SESSION[CookNamUsuario];
			}
                        //print_r($_SESSION);
			//$IngWeb='S';
			//$CookWeb=$IngWeb;
			$Consulta="Select t1.id_filial,t1.unidad,t1.descripcion,t1.nombre from mos_empresa_filial t1 inner join mos_usuario_filial t2 on t1.id_filial=t2.id_filial ";
			if($SuperUser!='S')
				$Consulta.=" where t2.id_usuario='".$_SESSION[CookIdUsuario]."'";
			$Consulta.=" group by t1.id_filial ";

                        //echo $Consulta;
                        $data = $pagina2->query($Consulta, array());
                        //print_r($data);
                        //$Fila3 = $data[0];
			//$Resp3=mysql_query($Consulta);
			$cont=0;
			//while($Fila3=mysql_fetch_assoc($Resp3)) 
                        foreach ($data as $Fila3) 
                        {
				$id_filial = $Fila3[id_filial];
				$cont++;
                        //echo $cont;
                        
                        }

			if ($cont>1) {
				echo 'Redireccionando...';
                                
				?>
				<script type="text/javascript">
				  window.location='../mosaikus/mos_inicio.php?In=S&Op=I';
				</script>
				<?php
			}
			else {
				//DIRECTOOOOO.....
				$path_redireccion = "../mosaikus/mos_redireccion.php?Op=IF&IDFILIAL=".$id_filial;

				$_SESSION[CookFilial]=$id_filial;
				$Consulta="Select id_filial,unidad,descripcion,nombre from mos_empresa_filial where id_filial='".$_SESSION[CookFilial]."'";
                                //echo $Consulta;
                                $data = $pagina2->query($Consulta, array());
                                //
				//$Resp=mysql_query($Consulta);
                                $Fila = $data[0];
				//if($Fila=mysql_fetch_assoc($Resp))
				{
					$_SESSION[CookNomUnidad]=$Fila["unidad"];
					$_SESSION[CookNomEmpresa]=$Fila["nombre"];
				}
                                $Consulta="select t2.*,ultimo_acceso from mos_usuario_filial t1 left join mos_perfil t2 on t1.cod_perfil=t2.cod_perfil  where t1.id_usuario='".$_SESSION[CookIdUsuario]."' and  t1.id_filial='".$_SESSION[CookFilial]."' group by t1.cod_perfil";
                                //$resultperfil = mysql_query($Consulta);
                                
                                $data = $pagina2->query($Consulta, array());
                                //print_r($data);
                                //while($rowperfil = mysql_fetch_assoc($resultperfil))
                                {
                                        $_SESSION[CookN]=$data[0]['nuevo'];
                                        $_SESSION[CookM]=$data[0]['modificar'];
                                        $_SESSION[CookE]=$data[0]['eliminar'];                                
                                }
                                
				//session_register("CookNomEmpresaGeneral");
				//$CookNomEmpresaGeneral=utf8_encode($Fila["nombre"]);
                                if ($data[0][ultimo_acceso] == '1')
                                    header("location:../mosaikus/index.php");
                                else 
                                    header("location:../mosaikus/portal.php");
				//echo 'Redireccionando...';

			}
			//*************ENTERMEDIABIT***************************//
			//*************ENTERMEDIABIT***************************//

			/**EL CODIGO DE ENTERMEDIABIT REEMPLAZA AL SIGUIENTE:
				echo 'Redireccionando...';
				?>
				<script type="text/javascript">
				  window.location='../mosaikus/mos_inicio.php?In=S&Op=I';
				</script>
				<?//*/

		}
		else
		{
			echo Header("Location:index.php?ingreso=XyZ3wGt4");
		}
	}
?>
