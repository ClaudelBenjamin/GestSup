<?php
################################################################################
# @Name : ticket.php 
# @Desc : page to edit ticket
# @call : /ticket.php
# @Autor : Flox
# @Version : 3.0.6
# @Update : 09/02/2014
################################################################################

//connexion script with database parameters
require "connect.php";

//initialize variables 
if(!isset($userreg)) $userreg = ''; 
if(!isset($u_group)) $u_group = ''; 
if(!isset($globalrow['u_group'])) $globalrow['u_group'] = ''; 
if(!isset($_POST['user'])) $_POST['user'] = ''; 
if(!isset($_POST['technician'])) $_POST['technician'] = ''; 

//master query
$globalquery = mysql_query("SELECT * FROM tincidents WHERE id LIKE '$_GET[id]'");
$globalrow=mysql_fetch_array($globalquery); 

echo '<body onload="window.print();"> </body>';

echo 'Impression du ticket n°'.$_GET['id'].':  '.$globalrow['title'].'';
?>
<br /><br />
<u>Demandeur:</u>
<?php
	if (($globalrow['u_group']==0 && $u_group=='') || $_POST['user']!="")
	{
		if ($_POST['user'])	{$user=$_POST['user'];}	else {$user=$globalrow['user'];}
		$query = mysql_query("SELECT * FROM tusers WHERE id LIKE $user");
		$row = mysql_fetch_array($query);
		echo "$row[lastname] $row[firstname]";
	} else {
		if (($globalrow['u_group']!=$u_group) && $u_group!=''){$group=$u_group;}else {$group=$globalrow['u_group'];}
		$query = mysql_query("SELECT * FROM `tgroups` WHERE id=$group");
		$row = mysql_fetch_array($query);
		echo "[G] $row[name]";
	}
?>
<br />
<u>Technicien:</u>
<?php
//selected value
if ($globalrow['t_group']!=0)
{
	$query = mysql_query("SELECT * FROM `tgroups` WHERE id=$globalrow[t_group]");
	$row = mysql_fetch_array($query);
	echo "[G] $row[name]";
} else {
	if ($_POST['technician'])
	{
		$querytech = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$_POST[technician]' ");
	} else {
		$querytech = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$globalrow[technician]' ");		
	}
	$row=mysql_fetch_array($querytech);
	echo "$row[lastname] $row[firstname]";
}
?>
<br />
<u>Catégorie:</u>
<?php
	$query= mysql_query("SELECT * FROM `tcategory` WHERE id=$globalrow[category] ");
	$row=mysql_fetch_array($query);
	echo "$row[name]";	
	$query= mysql_query("SELECT * FROM `tsubcat` WHERE id=$globalrow[subcat] ");
	$row=mysql_fetch_array($query);
	echo " - $row[name]";	
?>		
<br />
<u>Titre:</u>
<?php echo $globalrow['title']; ?>
<br />
<u>Déscription:</u><br />
<?php echo $globalrow['description']; ?>
<br />
<u>Résolution:</u><br />
<?php
	$query = mysql_query("SELECT * FROM tthreads WHERE ticket='$_GET[id]' and type='0' ORDER BY date");
	while ($row=mysql_fetch_array($query))
	{
		echo "- $row[text]<br />";
	}
?>
<?php
if ($globalrow['date_res']!='0000-00-00 00:00:00')
{
    echo '<u>Date de résolution:</u><br />'.$globalrow['date_res'].'';
}

?>

<?php 	mysql_close($connexion);  ?>