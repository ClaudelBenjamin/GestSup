<?php
################################################################################
# @Name : ./core/import.php
# @Desc : import and insert rows in database from EXEL file
# @call : /dashboard.php
# @parameters : 
# @Autor : Benjamin Claudel
# @Create : 27/01/2015
# @Version : 3.0.11
################################################################################

// put this at beginning of your script
$saveTimeZone = date_default_timezone_get();
date_default_timezone_set('UTC'); // PHP's date function uses this value!
$Erreur= "Insertion des tickets effectuée";
if (!isset ($_FILES['file']) && $_FILES['file']['error']!=0){
	$Erreur= "Pas de fichier importé. Séléctionnez un fichier XLS à importer.";
}else{ 
	if (!file_exists($_FILES['file']['tmp_name'])) {
		$Erreur= "Le fichier n'existe pas. Vérifiez l'emplacement de votre fichier.";
	}else{
		//move_uploaded_file($_FILES['file']['tmp_name'], $fichier);	
		// Chargement de la librairie
		require "../connect.php"; 
		Include('../components/phpExcelReader/Excel/reader.php');
		// Instanciation de la class permettant la lecture du fichier excel
		$data = new Spreadsheet_Excel_Reader();
		// Définition du type d’encodage de caractère à utiliser pour la sortie (ce qui va être affiché à l’écran)
		$data->setOutputEncoding('CP1251');
		// Chargement du fichier excel à lire
		$data->read($_FILES['file']['tmp_name']);
		error_reporting(E_ALL ^ E_NOTICE);
		$cat = '';
		$tpsmin = 0;
		$ref = '';
		$dateres='';
		//Si le fichier est lisible
		//vérification du format du fichier
		if($data->sheets[0]['numCols']!=17){
			$Erreur= "Le fichier n'est pas bien formé. Prenez le fichier modèle."; 
		}else{
			//si le fichier est bien formé
			// Parse l’intégralité du fichier excel
			for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				$query = "INSERT INTO tincidents (ref,type, technician,user,creator,category,subcat,title,time,time_hope,date_create,date_hope,date_res,state,criticality,description) VALUES (";
				for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
					switch($j){
						case 1: //ref
							$ref = $data->sheets[0]['cells'][$i][$j];
							$query = $query."'".$ref."',";	
							break;
						case 2: //type
							$reqtype=mysql_query("SELECT * FROM ttypes WHERE ttypes.name LIKE '".mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252")."'");
							$restype=mysql_fetch_array($reqtype);
							$query = $query."'".$restype[id]."',";
							break;
						case 3: //technician
							$exp = explode(" ",mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252"));
							$reqtech=mysql_query("SELECT * FROM tusers WHERE tusers.firstname LIKE '".$exp[0]."' AND tusers.lastname LIKE '".$exp[1]."'");
							$restech=mysql_fetch_array($reqtech);
							$query = $query."'".$restech[id]."',";
							break;
						case 4: //user
							$exp = explode(" ",mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252"));
							$requser=mysql_query("SELECT * FROM tusers WHERE tusers.firstname LIKE '".$exp[0]."' AND tusers.lastname LIKE '".$exp[1]."'");
							$resuser=mysql_fetch_array($requser);
							$query = $query."'".$resuser[id]."',";
							break;
						case 5: //creator
							$exp = explode(" ",mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252"));
							$reqcreator=mysql_query("SELECT * FROM tusers WHERE tusers.firstname LIKE '".$exp[0]."' AND tusers.lastname LIKE '".$exp[1]."'");
							$rescreator=mysql_fetch_array($reqcreator);
							$query = $query."'".$rescreator[id]."',";
							break;
						case 6: //category
							$category = str_replace("_", " ",$data->sheets[0]['cells'][$i][$j]);
							$reqcat=mysql_query("SELECT * FROM tcategory WHERE tcategory.name LIKE '".mb_convert_encoding($category, "UTF-8","windows-1252")."'");
							$rescat=mysql_fetch_array($reqcat);
							$query = $query."'".$rescat[id]."',";
							$cat = $rescat[id];
							break;
						case 7: //subcat
							$reqscat=mysql_query("SELECT * FROM tsubcat WHERE tsubcat.name LIKE '".mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252")."' AND tsubcat.cat LIKE '".$cat."'");
							$resscat=mysql_fetch_array($reqscat);
							$query = $query."'".$resscat[id]."',";
							break;
						case 8: //title
						if ($data->sheets[0]['cells'][$i][$j]==''){
								$query = $query."'".$ref."',";
							}else{
								$query = $query."'".mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252")."',";
							}
							break;
						case 9: //time
							$tpsglob = date('H:i:s', $data->sheets[0]['cells'][$i][$j] * 86400 + mktime(0, 0, 0));
							if($tpsglob!=""){ 
								$tps = explode(":", $tpsglob);
								$tpsmin = intval($tps[0]*60) + intval($tps[1]);
								//echo $tpsmin;
								$query = $query."'".$tpsmin."',";
							}else{
								$query = $query."'0',";
							}
							break;
						case 10: //time_hope
							$tpsglob = date('H:i:s', $data->sheets[0]['cells'][$i][$j] * 86400 + mktime(0, 0, 0));
							if($tpsglob!=""){ 
								$tps = explode(":", $tpsglob);
								$tpsmin = intval($tps[0]*60) + intval($tps[1]);
								//echo $tpsmin;
								$query = $query."'".$tpsmin."',";
							}else{
								$query = $query."'".$tpsmin."',";
							}
							break;
						case 11: //date_create
							$dateExcel = $data->sheets[0]['cells'][$i][$j] ; //Excel date
							$datecreate = date('Y-m-d H:i', ($dateExcel - 25569)*24*60*60 ); //PHP Date
							if ($datecreate != "1899-12-30 00:00"){
								$query = $query."'".$datecreate."',";
							}else{
								$query = $query."'".date("Y-m-d H:i:s")."',";
							}
							break;
						case 12: //date_hope
							$dateExcel = $data->sheets[0]['cells'][$i][$j] ; //Excel date
							$datehope = date('Y-m-d', ($dateExcel - 25569)*24*60*60 ); //PHP Date
							if ($datehope != "1899-12-30"){
								$query = $query."'".$datehope."',";
							}else{
								$query = $query."'0000-00-00',";
							}
							break;
						case 13: //date_res
							$dateExcel = $data->sheets[0]['cells'][$i][$j] ; //Excel date
							$dateres = date('Y-m-d H:i', ($dateExcel - 25569)*24*60*60 ); //PHP Date
							if ($dateres != "1899-12-30 00:00"){
								$query = $query."'".$dateres."',";
							}else{
								$dateres = '0000-00-00 00:00:00';
								$query = $query."'".$dateres."',";
							}
							break;
						case 14: //state
							if ($data->sheets[0]['cells'][$i][$j]!=""){
								$reqstate=mysql_query("SELECT * FROM tstates WHERE tstates.name LIKE '".mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252")."'");
								$resstate=mysql_fetch_array($reqstate);
								$query = $query."'".$resstate[id]."',";
							}else{
								if($dateres != '0000-00-00 00:00:00'){
									$query = $query."'3',";
								}else{
									$query = $query."'1',";	
								}
							}
							break;
						case 15: //criticality
							if ($data->sheets[0]['cells'][$i][$j]!=""){
								$reqcrit=mysql_query("SELECT * FROM tcriticality WHERE tcriticality.name LIKE '".mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252")."'");
								$rescrit=mysql_fetch_array($reqcrit);
								$query = $query."'".$rescrit[id]."',";
							}else{
									$query = $query."'4',";	
							}
							break;
						case 16: //description
							$query = $query."'".mb_convert_encoding($data->sheets[0]['cells'][$i][$j], "UTF-8","windows-1252")."'";
							break;
						case 17://résolution
							// On vera plus tard
							break;
					}
				}
				$query=$query.")";
				// echo $query;
				// echo '<br>';
				mysql_query($query) or die($Erreur = 'Erreur SQL !'.$sql.'<br>'.mysql_error()); 
			}
		}
	}
}
date_default_timezone_set($saveTimeZone);
mysql_close($connexion);
// Redirection
echo $Erreur;
$www = "../index.php";
echo '<script language="Javascript">
<!--
document.location.replace("'.$www.'");
-->
</script>';

?>