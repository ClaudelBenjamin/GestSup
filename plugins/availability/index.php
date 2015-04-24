<?php
################################################################################
# @Name : /plugin/availability/index.php
# @Desc : display availability
# @call : /menu.php
# @paramters : 
# @Author : Flox
# @Create : 18/04/2014
# @Update : 23/09/2014
# @Version : 3.0.10
################################################################################

//initialize variables 
if(!isset($_GET['previous'])) $_GET['previous']='';
if(!isset($_GET['next'])) $_GET['next']='';
if(!isset($dependancy_time)) $dependancy_time='';
if(!isset($hourdependancy)) $hourdependancy='';
if(!isset($mindependancy)) $mindependancy='';
if(!isset($hour_planned)) $hour_planned='';
if(!isset($min_planned )) $min_planned ='';

//default settings
if(!isset($_GET['year']))$year=date('Y'); else $year=$_GET['year'];

//calc selected date
$year=$year-$_GET['previous']+$_GET['next'];

//display head
echo '
<div class="page-header position-relative">
	<h1>
		<i class="icon-time"></i> Disponibilité
	</h1>
</div>
<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
	<button title="Année précédente" onclick=\'window.location.href="./index.php?page=plugins/availability/index&year='.$year.'&previous=1";\' class="btn btn-info">
		<i class="icon-arrow-left"></i>
	</button>
	<button title="Année séléctionnée" class="btn btn-info">
		'.$year.'
	</button>
	<button title="Année suivante" onclick=\'window.location.href="./index.php?page=plugins/availability/index&year='.$year.'&next=1";\' class="btn btn-info">
		<i class="icon-arrow-right"></i>
	</button>

</div>
<br />

<script type="text/javascript" src="./template/assets/js/jquery-2.0.3.min.js"></script>
<script src="./components/Highcharts-2.3.3/js/highcharts.js"></script>
<script src="./components/Highcharts-2.3.3/js/modules/exporting.js"></script>

<table border="0">
';

//find ticket with condition availibility value
//$query= "SELECT tincidents.* FROM tincidents,tcriticality WHERE tincidents.criticality=tcriticality.id AND tcriticality.id=$rparameters[availability_condition_value] group by ";
if ($rparameters['availability_all_cat']==1)
{
    //case for all categories
    $query= "
    	SELECT tincidents.id, tincidents.category, tincidents.subcat, tincidents.start_availability, tincidents.end_availability,
    	SUM(hour(TIMEDIFF(end_availability, start_availability))) AS hourdiff,
    	SUM(minute(TIMEDIFF(end_availability, start_availability))) AS minutediff
    	FROM tincidents,tcriticality
    	WHERE
    	tincidents.criticality=tcriticality.id AND
    	tincidents.disable=0 AND
    	tcriticality.id=$rparameters[availability_condition_value] AND
    	tincidents.date_create LIKE '$year%'
    	GROUP BY tincidents.subcat
    ";
} else {
    //case for selected categories
    $query= "
    	SELECT tincidents.id, tincidents.category, tincidents.subcat, tincidents.start_availability, tincidents.end_availability,
    	SUM(hour(TIMEDIFF(end_availability, start_availability))) AS hourdiff,
    	SUM(minute(TIMEDIFF(end_availability, start_availability))) AS minutediff
    	FROM tincidents,tcriticality,tavailability
    	WHERE
    	tincidents.criticality=tcriticality.id AND
    	tincidents.subcat=tavailability.subcat AND
    	tincidents.disable=0 AND
    	tincidents.date_create LIKE '$year%'
    	GROUP BY tincidents.subcat
    ";
}


if ($rparameters['debug']==1) echo "$query <br /><br />";
$query = mysql_query("$query");
while ($row=mysql_fetch_array($query)) 
{
	//var init
	$hourdependancy=0;
	$mindependancy=0;
	$dependancy_time=0;
	
	//calc dependancy if exist
	if ($rparameters['availability_dep']==1)
	{
	   //calc dependancy time
	   $querydependancy= "
    	SELECT tincidents.id, tincidents.category, tincidents.subcat, tincidents.start_availability, tincidents.end_availability,
    	SUM(hour(TIMEDIFF(end_availability, start_availability))) AS hourdiff,
    	SUM(minute(TIMEDIFF(end_availability, start_availability))) AS minutediff
    	FROM tincidents,tcriticality,tavailability_dep
    	WHERE
    	tincidents.criticality=tcriticality.id AND
    	tincidents.subcat=tavailability_dep.subcat AND
    	tincidents.disable=0 AND
    	tcriticality.id=$rparameters[availability_condition_value] AND
    	tincidents.date_create LIKE '$year%'
    	GROUP BY tincidents.subcat";
    
    	$querydependancy = mysql_query("$querydependancy");
        while ($rowdependancy=mysql_fetch_array($querydependancy)) 
        { 
             $dependancy_time=($rowdependancy['hourdiff']*60)+($rowdependancy['minutediff'])+$dependancy_time;
             $hourdependancy=$rowdependancy['hourdiff']+$hourdependancy;
             $mindependancy=$rowdependancy['minutediff']+$mindependancy;
        }
	} else $dependancy_time=0;

    //calc availabilty percentage
    $total_minute=($row['hourdiff']*60)+$row['minutediff']+$dependancy_time;
    $tx=100-(100*($total_minute/(365*24*60)));
    $tx=number_format($tx,2); 
    
    //calc availabilty percentage whitout planned ticket
    if ($rparameters['availability_all_cat']==1)
    {
        //case for all categories whitout planned ticket 
        $query_planned= "
        	SELECT tincidents.id, tincidents.category, tincidents.subcat, tincidents.start_availability, tincidents.end_availability,
        	SUM(hour(TIMEDIFF(end_availability, start_availability))) AS hourdiff,
        	SUM(minute(TIMEDIFF(end_availability, start_availability))) AS minutediff
        	FROM tincidents,tcriticality
        	WHERE
        	tincidents.criticality=tcriticality.id AND
        	tincidents.disable=0 AND
        	tincidents.subcat=$row[subcat] AND
        	tincidents.availability_planned=0 AND
        	tcriticality.id=$rparameters[availability_condition_value] AND
        	tincidents.date_create LIKE '$year%'
        	GROUP BY tincidents.subcat
        ";
    } else {
        //case for selected categories whitout planned ticket
        $query_planned= "
        	SELECT tincidents.id, tincidents.category, tincidents.subcat, tincidents.start_availability, tincidents.end_availability,
        	SUM(hour(TIMEDIFF(end_availability, start_availability))) AS hourdiff,
        	SUM(minute(TIMEDIFF(end_availability, start_availability))) AS minutediff
        	FROM tincidents,tcriticality,tavailability
        	WHERE
        	tincidents.criticality=tcriticality.id AND
        	tincidents.subcat=tavailability.subcat AND
        	tincidents.availability_planned=0 AND
        	tincidents.subcat=$row[subcat] AND
        	tincidents.disable=0 AND
        	tincidents.date_create LIKE '$year%'
        	GROUP BY tincidents.subcat
        ";
    }
    $query_planned = mysql_query($query_planned);
    $row_planned = mysql_fetch_array($query_planned);
    
    //calc dependancy if exist whitout planned ticket
	if ($rparameters['availability_dep']==1)
	{
       	//var init
        $hourdependancy_planned=0;
        $mindependancy_planned=0;
        $dependancy_time_planned=0;
        //calc dependancy time whitout planned ticket
	    $querydependancy_planned= "
    	SELECT tincidents.id, tincidents.category, tincidents.subcat, tincidents.start_availability, tincidents.end_availability,
    	SUM(hour(TIMEDIFF(end_availability, start_availability))) AS hourdiff,
    	SUM(minute(TIMEDIFF(end_availability, start_availability))) AS minutediff
    	FROM tincidents,tcriticality,tavailability_dep
    	WHERE
    	tincidents.criticality=tcriticality.id AND
    	tincidents.subcat=tavailability_dep.subcat AND
    	tincidents.disable=0 AND
    	tincidents.availability_planned=0 AND
    	tcriticality.id=$rparameters[availability_condition_value] AND
    	tincidents.date_create LIKE '$year%'
    	GROUP BY tincidents.subcat";
    	
    	$querydependancy_planned = mysql_query("$querydependancy_planned");
        while ($rowdependancy_planned=mysql_fetch_array($querydependancy_planned)) 
        { 
             $dependancy_time_planned=($rowdependancy_planned['hourdiff']*60)+($rowdependancy_planned['minutediff'])+$dependancy_time_planned;
             $hourdependancy_planned=$rowdependancy_planned['hourdiff']+$hourdependancy_planned;
             $mindependancy_planned=$rowdependancy_planned['minutediff']+$mindependancy_planned;
        }
	} else $dependancy_time_planned=0;
    
    //calc tx for ticket whithout planned
    $total_minute_planned=($row_planned['hourdiff']*60)+$row_planned['minutediff']+$dependancy_time_planned;
    $tx_planned=100-(100*($total_minute_planned/(365*24*60)));
    $tx_planned=number_format($tx_planned,2); 
   
    //unavailability time for ticket whithout planned
	if($row_planned['hourdiff']||$hourdependancy_planned)
	{
		$min_planned=explode(".", ($row_planned['minutediff']+$mindependancy_planned)/60);
		$hour_planned=($row_planned['hourdiff']+$hourdependancy_planned)+$min_planned[0];
		$min_planned=(60*"0.$min_planned[1]");
		$min_planned=round($min_planned);
	}
	
    //calc unavailabity percent
    $tx2=100-$tx;
    $tx2=number_format($tx2,2);
    
    //find cat name
    $querycat= mysql_query("SELECT name FROM `tcategory` WHERE id=$row[category] ");
	$rowcat=mysql_fetch_array($querycat);
	
	//find subcat name
	$querysubcat= mysql_query("SELECT name FROM `tsubcat` WHERE id=$row[subcat] ");
	$rowsubcat=mysql_fetch_array($querysubcat);
	
	//convert in hours and minutes 
	if($row['hourdiff']||$hourdependancy)
	{
		$min=explode(".", ($row['minutediff']+$mindependancy)/60);
		$hour=($row['hourdiff']+$hourdependancy)+$min[0];
		$min=(60*"0.$min[1]");
		$min=round($min);
	} else {$min=0; $hour=0;}
	
    //display table 
   	echo "
   	<tr>
   		<td colspan=\"3\" align=\"center\">
   			<font size=\"5px\"> $rowcat[0] - $rowsubcat[0]</font>
   		</td>
   	</tr>
   	<tr>
   	    <td>
   	    	";
			$container="container$row[id]";
			include('./stat_bar_stacked.php');
			echo "<div id=\"$container\" style=\"min-width: 400px; height: 400px; margin: 0 auto\"></div>";
   	    	echo "
   	    </td>
   	    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   	    <td> 
   	    	<font size=\"4px\"><b>$tx%</b></font> de disponibilité pour $year.<br />
   	    	<font size=\"3px\"><b>$hour h $min m</b></font> d'indisponiblité pour $year.<br />
   	        Taux de disponibilité en dehors des interventions planifiées: $tx_planned% ($hour_planned h $min_planned m)<br /> 
   	    	<u>Tickets de la catégorie concernés:</u><br />
   	    	";
   	    		$queryticket = "SELECT tincidents.* FROM tincidents, tcriticality WHERE subcat=$row[subcat] AND tincidents.criticality=tcriticality.id AND tcriticality.id=$rparameters[availability_condition_value] AND tincidents.disable=0 AND tincidents.date_create LIKE '$year%' ORDER BY tincidents.start_availability";
				$queryticket = mysql_query("$queryticket");
				while ($rowticket=mysql_fetch_array($queryticket)) 
				{
					//calc time by ticket
					$t1 =strtotime($rowticket['start_availability']) ;
                    $t2 =strtotime($rowticket['end_availability']) ;
                   	$time=(($t2-$t1)/60)/60;
                   	$time=number_format($time,2);
                   	$time_hour=explode(".", $time);
                   	$time_min=60*"0.$time_hour[1]";
					$time_min=round($time_min);
                   	$time_hour=$time_hour[0];
                   	$dateticket=date("d/m/Y",strtotime($rowticket['start_availability']));
                   	//check planned unavailability
                    if($rowticket['availability_planned']==1) $planned="planifié"; else $planned="non planifié";
					echo "&nbsp;&nbsp;&nbsp;&nbsp; - <a target=\"about_blank\" href=\"./index.php?page=ticket&id=$rowticket[id]\">n°$rowticket[id]</a>: $rowticket[title] $planned le $dateticket ($time_hour h $time_min m)<br />";
				}
				//display dependancy if exist
            	if ($rparameters['availability_dep']==1)
            	{
            	    echo "<u>Tickets dépendant d'autres catégories:</u><br />";
            	    $queryticket = "SELECT tincidents.* FROM tincidents, tcriticality, tavailability_dep WHERE tincidents.criticality=tcriticality.id AND tincidents.subcat=tavailability_dep.subcat AND tcriticality.id=$rparameters[availability_condition_value] AND tincidents.disable=0 AND tincidents.date_create LIKE '$year%' ORDER BY tincidents.start_availability";
    				$queryticket = mysql_query("$queryticket");
    				while ($rowticket=mysql_fetch_array($queryticket)) 
    				{
    					//calc time by ticket
    					$t1 =strtotime($rowticket['start_availability']) ;
                        $t2 =strtotime($rowticket['end_availability']) ;
                       	$time=(($t2-$t1)/60)/60;
                       	$time=number_format($time,2);
                       	$time_hour=explode(".", $time);
	                   	$time_min=60*"0.$time_hour[1]";
						$time_min=round($time_min);
	                   	$time_hour=$time_hour[0];
                       	$dateticket=date("d/m/Y",strtotime($rowticket['start_availability']));
                       	//check planned unavailability
                       	if($rowticket['availability_planned']==1) $planned="planifié"; else $planned="non planifié";
    					echo "&nbsp;&nbsp;&nbsp;&nbsp; - <a target=\"about_blank\" href=\"./index.php?page=ticket&id=$rowticket[id]\">n°$rowticket[id]</a>: $rowticket[title] $planned le $dateticket ($time_hour h $time_min m)<br />";
    				}
            	}
   	    	echo "
   	    	
   	    </td>
   	</tr>
   	<tr><td colspan=\"3\"><br /><hr /><br /></td></tr>
   	";
} 
echo'</table>';
?>