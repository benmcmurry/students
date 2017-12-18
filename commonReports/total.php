<?php
include_once("../../../connectFiles/connect_e.php");
$fileName = basename(__FILE__, '.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
fputcsv($output, array('elc_nps','total_ratings','teacher_nps','total_ratings','class_nps','total_ratings'));


    $query2 = $elc_db->prepare("Select elc_np_des, teacher_np_des, class_np_des from Evaluations");
    $query2->execute();
    $result2 = $query2->get_result();
    $eCount=0;
    $tCount=0;
    $cCount=0;
   $eSum=0;
   $tSum=0;
   $cSum=0;
        while($np = $result2->fetch_assoc()){
            if ($np['elc_np_des'] !== -99) {$eSum+=$np['elc_np_des']; $eCount++;}
            if ($np['teacher_np_des'] !== -99) {$tSum+=$np['teacher_np_des'];$tCount++;}
            if ($np['class_np_des'] !== -99) {$cSum+=$np['class_np_des'];$cCount++;}
        }
    $eNPS = $eSum/$eCount*100;
    $tNPS = $tSum/$tCount*100;
    $cNPS = $cSum/$cCount*100;
    $eCount = $eCount/4;
  

    
    fputcsv($output, array($eNPS,$eCount, $tNPS, $tCount, $cNPS, $cCount));
  

?>