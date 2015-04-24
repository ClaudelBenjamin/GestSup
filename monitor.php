<?php
################################################################################
# @Name : monitor.php
# @Desc : display newticket current ticket for monitoring screen
# @call : /stat.php
# @parameters : 
# @Author : Flox
# @Create : 15/02/2014
# @Update : 22/10/2014
# @Version : 3.0.10
################################################################################

//connexion script with database parameters
require "connect.php";

$daydate=date('Y-m-d');

$reqnb = mysql_query("SELECT count(*) FROM `tincidents` WHERE date_create LIKE '$daydate%' AND disable='0'"); 
$nbday = mysql_fetch_array($reqnb);

$cnt5 = mysql_query("SELECT count(*) FROM `tincidents` WHERE technician='0' and disable='0'"); 
$cnt5= mysql_fetch_array($cnt5);

$reqdayres = mysql_query("SELECT count(*) FROM `tincidents` WHERE date_res LIKE '$daydate%' AND state=3 AND disable='0'"); 
$nbdayres = mysql_fetch_array($reqdayres);

session_start();

//ring for new ticket
if($_SESSION['current_ticket']<$cnt5[0]) {echo'<audio hidden="false" autoplay="true" src="./sounds/notify.ogg" controls="controls"></audio>';}

//update current counter
if($_SESSION['current_ticket']!=$cnt5[0]) {$_SESSION['current_ticket']=$cnt5[0];}

?>
<!DOCTYPE html>
<html lang="fr">
	<head>
	    <?php header('x-ua-compatible: ie=edge'); //disable ie compatibility mode ?>
		<meta charset="UTF-8" />
		<title>GestSup | Gestion de Support</title>
		<link rel="shortcut icon" type="image/ico" href="./images/favicon.ico" />
		<meta name="description" content="gestsup" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link href="./template/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="./template/assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="./template/assets/css/jquery-ui-1.10.3.full.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="./template/assets/css/ace-skins.min.css" />
		<script src="./template/assets/js/ace-extra.min.js"></script>
		<meta http-equiv="Refresh" content="60">

	</head>
	<body>
	    <?php
	    //generate color
	    if($cnt5[0]>0) $color='danger'; else $color='success';
	    
	    //add pluriel
	    if($cnt5[0]>1) $new='Nouveaux'; else $new='Nouveau';
	    if($nbday[0]>1) $open='Ouverts'; else $open='Ouvert';
	    if($nbdayres[0]>1) $res='Résolus'; else $res='Résolu';
	    
	    ?>
        <a href="#" class="btn btn-<?php echo $color; ?> btn-app radius-4">
            <?php echo $new; ?> <br /><br /><br />
			<i class="icon-ticket bigger-230"><br /><br /><?php echo $cnt5[0]; ?></i>
			<br />
        </a>
        <a href="#" class="btn btn-info btn-app radius-4">
            <?php echo $open; ?><br />du jour
            <br /><br />
			<i class="icon-plus bigger-230"><br /><br /><?php echo $nbday[0]; ?></i>
			<br />
        </a>
         <a href="#" class="btn btn-purple btn-app radius-4">
            <?php echo $res; ?><br />du jour
            <br /><br />
			<i class="icon-ok bigger-230"><br /><br /><?php echo $nbdayres[0]; ?></i>
			<br />
        </a>
        
    </body>
</html>
<?php
	// Close database access
		mysql_close($connexion); 
?>
