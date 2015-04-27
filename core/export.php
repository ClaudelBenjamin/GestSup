<?php
################################################################################
# @Name : ./core/export.php
# @Desc : dump csv files of current query
# @call : /dashboard.php
# @parameters : 
# @Autor : Flox
# @Create : 27/01/2014
# @Update : 28/01/2014
# @Version : 3.0.11
################################################################################

$daydate=date('Y-m-d');

// output headers so that the file is downloaded rather than displayed
header('Content-Encoding: UTF-8');
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"Gestsup-Export-$daydate.csv\"");
echo "\xEF\xBB\xBF"; // UTF-8 BOM

require "../connect.php"; 

//load parameters table
$qparameters = mysql_query("SELECT * FROM `tparameters`"); 
$rparameters= mysql_fetch_array($qparameters);

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('N° ticket', 'Référence', 'Type', 'Technicien', 'Demandeur', 'Createur', 'Client', 'Logiciel','Titre', 'Temps passé', 'Temps estimé', 'Date de creation','Date de resolution estime', 'Date de cloture', 'Etat', 'Criticite', 'Description', 'Résolution' ),";");

// fetch the data
$req="SELECT tincidents.id, tincidents.ref, tincidents.type, technician, tincidents.user, creator, category, subcat, title, description, time, time_hope, date_create, date_hope, date_res, state, criticality FROM tincidents WHERE disable=0";
if ($_POST['client']!= "%") $req=$req." AND tincidents.user=".$_POST['client'];
if ($_POST['date_begin']!= "" && $_POST['date_end']!= "")$req = $req." AND date_create BETWEEN \"".$_POST['date_begin']." 00:00:00\" AND \"".$_POST['date_end']." 23:59:59\"";  
$rows = mysql_query($req);
//$rows = mysql_query('SELECT * FROM tincidents WHERE disable=0');
// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) 
{
	//get data
	$querytech=mysql_query("SELECT firstname,lastname FROM tusers WHERE id LIKE '$row[technician]' "); 
	$resulttech=mysql_fetch_array($querytech);
	$row['technician']="$resulttech[firstname] $resulttech[lastname]";

	$querytype=mysql_query("SELECT name FROM ttypes WHERE id LIKE $row[type]"); 
	$resulttype=mysql_fetch_array($querytype);
	$row['type']=$resulttype['name'];
	
	$queryuser=mysql_query("SELECT firstname,lastname FROM tusers WHERE id LIKE '$row[user]'"); 
	$resultuser=mysql_fetch_array($queryuser);
	$row['user']="$resultuser[firstname] $resultuser[lastname]";
	
	$querycreator=mysql_query("SELECT firstname,lastname FROM tusers WHERE id LIKE '$row[creator]'"); 
	$resultcreator=mysql_fetch_array($querycreator);
	$row['creator']="$resultcreator[firstname] $resultcreator[lastname]";
	
	$querycat=mysql_query("SELECT * FROM tcategory WHERE id LIKE '$row[category]'"); 
	$resultcat=mysql_fetch_array($querycat);
	$row['category']=$resultcat['name'];
	
	$queryscat=mysql_query("SELECT * FROM tsubcat WHERE id LIKE '$row[subcat]'"); 
	$resultscat=mysql_fetch_array($queryscat);
	$row['subcat']=$resultscat['name'];
	
	$querystate=mysql_query("SELECT * FROM tstates WHERE id LIKE $row[state]"); 
	$resultstate=mysql_fetch_array($querystate);
	$row['state']=$resultstate['name'];
	
	$querycriticality=mysql_query("SELECT * FROM tcriticality WHERE id LIKE $row[criticality]"); 
	$resultcriticality=mysql_fetch_array($querycriticality);
	$row['criticality']=$resultcriticality['name'];

	$desc = str_replace("&nbsp;"," ",$row['description']);
	$desc = str_replace("<br>","\n",$desc);
	$desc = str_replace("<div>","\n",$desc);
	$desc = strip_tags($desc);
	$desc = trim($desc);
	$row['description'] = stripslashes($desc);
	
	$temps = $row['time'];
	$nbheure= floor($temps/60);
	$nbmin= $temps%60;
	$min = (string)$nbmin;
	$heure = (string)$nbheure;
	if (strlen($heure) == 1) $heure = "0".$heure;
	if (strlen($min) == 1) $min = "0".$min;
	$row['time']= $heure.":".$min;
	
	$temps = $row['time_hope'];
	$nbheure= floor($temps/60);
	$nbmin= $temps%60;
	$min = (string)$nbmin;
	$heure = (string)$nbheure;
	if (strlen($heure) == 1) $heure = "0".$heure;
	if (strlen($min) == 1) $min = "0".$min;
	$row['time_hope']= $heure.":".$min;
	
	$text="";
	$requete="SELECT * FROM tthreads WHERE tthreads.ticket=".$row['id'];
	$queryresol = mysql_query($requete);
	while($resultresol = mysql_fetch_assoc($queryresol)){
		if($resultresol[type]==0){
			$ttmp = str_replace("&nbsp;"," ",$resultresol['text']);
			$ttmp = str_replace("<br>","\n",$ttmp);
			$ttmp = str_replace("<div>","\n",$ttmp);
			$ttmp = strip_tags($ttmp);
			$ttmp = trim($ttmp);
			$text = $text." ".stripslashes($ttmp);
		} 
	}

  fputcsv($output, array($row['id'], $row['ref'], $row['type'], $row['technician'], $row['user'], $row['creator'], $row['category'], $row['subcat'], $row['title'], $row['time'], $row['time_hope'], $row['date_create'], $row['date_hope'], $row['date_res'], $row['state'], $row['criticality'], $row['description'], $text), ";");
}

mysql_close($connexion); 

?>