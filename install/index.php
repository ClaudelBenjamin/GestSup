<?php
################################################################################
# @Name : ./install/index.php 
# @Desc : page to install gestsup
# @call : /
# @Autor : Flox
# @Version : 3.0
# @Update : 10/11/2013
################################################################################

//initialize variables 
if(!isset($_POST['refresh'])) $_POST['refresh'] = '';
if(!isset($_POST['step'])) $_POST['step'] = '';
if(!isset($_POST['serveur'])) $_POST['serveur'] = '';
if(!isset($_POST['user'])) $_POST['user'] = '';
if(!isset($_POST['password'])) $_POST['password'] = '';
if(!isset($requetes)) $requetes= '';
if(!isset($valid)) $valid = '';
if(!isset($vphp)) $vphp = '';
if(!isset($i)) $i = '';
if(!isset($textension[$i])) $textension[$i] = '';
if(!isset($openssl)) $openssl = '';
if(!isset($phpinfo)) $phpinfo = '';
if(!isset($match)) $match = '';
if(!isset($ldap)) $ldap = '';
if(!isset($zip)) $zip = '';
if(!isset($imap)) $imap = '';
if(!isset($error)) $error = '';


//default value
if(!isset($step)) $step=1;

//Extraction des paramètres du phpinfo
ob_start();
phpinfo();
$phpinfo = array('phpinfo' => array());
if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
    foreach($matches as $match)
        if(strlen($match[1]))
            $phpinfo[$match[1]] = array();
        elseif(isset($match[3])) {
			$ak=array_keys($phpinfo);
            $phpinfo[end($ak)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			}
        else {
			$ak=array_keys($phpinfo);
            $phpinfo[end($ak)][] = $match[2];
		}
			
			
//case for old version php, php info tab is PHP CORE 			
if (isset($phpinfo['Core'])!='') $vphp='Core'; else $vphp='HTTP Headers Information';

//initialize variables 
if(!isset($phpinfo[$vphp]['register_globals'][0])) $phpinfo[$vphp]['register_globals'][0] = '';
if(!isset($phpinfo[$vphp]['magic_quotes_gpc'][0])) $phpinfo[$vphp]['magic_quotes_gpc'][0] = '';
if(!isset($phpinfo[$vphp]['file_uploads'][0])) $phpinfo[$vphp]['file_uploads'][0] = '';
if(!isset($phpinfo[$vphp]['memory_limit'][0])) $phpinfo[$vphp]['memory_limit'][0] = '';
if(!isset($phpinfo[$vphp]['upload_max_filesize'][0])) $phpinfo[$vphp]['upload_max_filesize'][0] = '';

////actions on submit

//step1
if($_POST['step']==1)
{
	//write connect.php file with parameter
	$fichier = fopen('../connect.php','w+');
	fputs($fichier,"<?php\r\n");
	fputs($fichier,"\$serveur=\"$_POST[serveur]\";//nom du serveur\r\n");
	fputs($fichier,"\$user=\"$_POST[user]\";//votre nom utilisateur\r\n");
	fputs($fichier,"\$password=\"$_POST[password]\";//mot de passe\r\n");
	fputs($fichier,"\$base=\"$_POST[dbname]\";//nom de la base de donnée\r\n");
	fputs($fichier,"\$connexion = mysql_connect(\$serveur,\$user,\$password) or die(\"impossible de se connecter : \". mysql_error());\r\n");
	fputs($fichier,"\$db = mysql_select_db(\$base, \$connexion)  or die(\"impossible de sélectionner la base : \". mysql_error());\r\n");
	fputs($fichier,"?>");
	fclose($fichier);

	//create and connect to database
	$link=mysql_connect($_POST['serveur'],$_POST['user'],$_POST['password']);
	mysql_query("create database $_POST[dbname];");
	mysql_select_db("$_POST[dbname]", $link);
	
	//modify database encoding
	mysql_query("SET NAMES 'utf8'"); 
	
	if (mysql_errno()) { 
	$error = "<b>MySQL error ".mysql_errno().":</b> ".mysql_error()."\n <br />Vérifier vos paramètres de connexion"; 
	} else {
		//import sql skeleton
		$sql_file=file_get_contents('../_SQL/skeleton.sql');
		$sql_file=explode(";", $sql_file);
		foreach ($sql_file as $value) {
			mysql_query($value);
		}
		$step=2;
	}
}
//step2
if($_POST['step']==2)
{
	if($_POST['refresh']) $step=2; else $step=3;
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<title>GestSup | Installation</title>
		<link rel="shortcut icon" type="image/ico" href="./images/favicon.ico" />
		<meta name="description" content="gestsup" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="../template/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="../template/assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="../template/assets/css/jquery-ui-1.10.3.full.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="../template/assets/css/ace-skins.min.css" />
		<script src="../template/assets/js/ace-extra.min.js"></script>
	</head>
	<body>
		<div class="navbar navbar-default" id="navbar">
			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<i class="icon-ticket "></i>
						GestSup
					</a><!--/.brand-->
				</div><!-- /.navbar-header -->
			</div><!--/.navbar-inner-->
		</div>
		<div class="main-container" id="main-container">
			<div class="row-fluid">
				<div class="span12">
					<div class="widget-box">
						<div class="widget-header widget-header-blue widget-header-flat">
							<h4 class="lighter">Installation de l'application</h4>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div id="fuelux-wizard" class="row-fluid" data-target="#step-container">
									<ul class="wizard-steps">
										<li data-target="#step1" <?php if ($step==1) echo 'class="active"'; ?>>
											<span class="step">1</span>
											<span class="title">Base de données</span>
										</li>

										<li data-target="#step2"  <?php if ($step==2) echo 'class="active"'; ?>>
											<span class="step">2</span>
											<span class="title">Vérification de la configuration serveur</span>
										</li>

										<li data-target="#step3"  <?php if ($step==3) echo 'class="active"'; ?>>
											<span class="step">3</span>
											<span class="title">Fin</span>
										</li>
									</ul>
								</div>
								<hr>
								<form method="post" id="form" action="" class="form-horizontal" id="sample-form" >
									<div class="step-content row-fluid position-relative" id="step-container">
										<div class="step-pane active" id="step1">
											<?php
												//display error box
												if ($error) echo '<div class="alert alert-block alert-danger"><center><i class="icon-remove red"></i> '.$error.'.</center></div>';
												//display STEP 1 form
												if ($step=='1')
												{
												echo '
													<h3 class="lighter block green">Entrer les paramètres de connexion à votre base de données:</h3>
													<div class="space-8"></div>
													<input type="hidden" name="step" value="1">
													<div class="form-group has-info">
														<label for="serveur" class="col-xs-12 col-sm-3 control-label no-padding-right">Serveur de base données:</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="text" name="serveur" value="localhost" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline"> <i>Nom netbios ou adresse IP du serveur de base de données</i> </div>
													</div>
													<div class="form-group has-info">	
														<label for="dbname" class="col-xs-12 col-sm-3 control-label no-padding-right">Nom de la base données:</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="text" name="dbname" value="bsup" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline"> </div>
													</div>
													<div class="form-group has-info">		
														
														<label for="user" class="col-xs-12 col-sm-3 control-label no-padding-right">Utilisateur de la base données:</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="text" name="user" value="root" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline">  </div>
													</div>
													<div class="form-group has-info">		
														<label for="password" class="col-xs-12 col-sm-3 control-label no-padding-right">Mot de passe de la base données:</label>
														<div class="col-xs-12 col-sm-5">
															<span class="block input-icon input-icon-right">
																<input type="password" name="password" value="" class="width-100">
																<i class="icon-info-sign"></i>
															</span>
														</div>
														<div class="help-block col-xs-12 col-sm-reset inline"> <i>Pour WAMP laisser vide</i> </div>
													</div>
												';
												}
												if ($step=='2')
												{	
													echo '
													<input type="hidden" name="step" value="2">
													<h3 class="lighter block green">Vérification de la configuration serveur:</h3>
													<div class="space-8"></div>
													';
													include('../system.php');
												}
												if ($step=='3')
												{	
													echo '
													<input type="hidden" name="step" value="3">
													<h3 class="lighter block green">Installation terminée:</h3>
													<div class="space-8"></div>';
													//find server url
													$url=$_SERVER['HTTP_REFERER'];
													$url=(parse_url($url));
													$path=$url['path'];
													$path=explode("/",$path);
													$path=$path[1];
													$url='http://'.$url['host'].'/'.$path.'';
													echo '
													L\'application à été installée avec succès.<br />
													Les identifiants initiaux sont <b>admin</b> / <b>admin</b> <br />
													Vous pouvez vous connecter via l\'url: <a href="'.$url.'">'.$url.'</a>. <br /><br />
													<font color="red">!!! Attention pour des raisons de sécurité nous vous conseillons de supprimer le répertoire /install .</font>
													';
													
												
												}
											?>
										</div>
										<hr>
										<div class="row-fluid wizard-actions">
											<?php
												if ($step==2)
												{
													echo '
													
													<button type="submit" name="refresh" id="refresh" value="refresh" class="btn btn-primary btn-next">
														<i class="icon-refresh"></i>
														Actualiser
													</button>';
												}
												if ($step!=3)
												{
													echo '
													<button type="submit" class="btn btn-success btn-next" data-last="Finish ">
														Suivant
														<i class="icon-arrow-right icon-on-right"></i>
													</button>';
												}
											?>
										</div>
									</div>
								</form>
							</div><!-- /widget-main -->
						</div><!-- /widget-body -->
					</div>
				</div>
			</div>
		</div>
	</body>
</html>