<?php
include_once("../../../connectFiles/connect_e.php");
$fileName = basename(__FILE__, '.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
fputcsv($output, array('semester','elc_nps','total_ratings','teacher_nps','class_nps'));
$query = $elc_db->prepare("Select semester from Evaluations group by semester order by `semester` ASC");
$query->execute();
$result = $query->get_result();
while($semester = $result->fetch_assoc()){
    

    $query2 = $elc_db->prepare("Select semester, elc_np_des, teacher_np_des, class_np_des from Evaluations where semester=?");
    $query2->bind_param('s', $semester['semester']);
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
    
  

    $query3 = $elc_db->prepare("Update Evaluations set elc_nps=? where semester=?");
    $query3->bind_param('ss', $eNPS,$semester['semester']);
    $query3->execute();
    $result3 = $query3->get_result();
    
    fputcsv($output, array($semester['semester'],$eNPS,$eCount, $tNPS, $cNPS));
}
?>