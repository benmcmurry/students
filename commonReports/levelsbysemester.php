<?php
include_once("../../../connectFiles/connect_e.php");
$fileName = basename(__FILE__, '.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
fputcsv($output, array('semester','level','overall teacher nps','total_ratings','overall class nps','total_ratings'));
$query = $elc_db->prepare("Select c.level_number, semester from Evaluations Natural Join Courses c group by c.level_number, semester order by `semester` ASC, c.level_number ASC");
$query->execute();
$result = $query->get_result();
while ($level = $result->fetch_assoc()) {

    $query2 = $elc_db->prepare("Select teacher_np_des, class_np_des from Evaluations Natural Join Courses c where semester=? and c.level_number=?");
    $query2->bind_param('ss', $level['semester'], $level['level_number']);
    $query2->execute();
    $result2 = $query2->get_result();
    $tCount=0;
    $cCount=0;
    $tSum=0;
    $cSum=0;
    while ($np = $result2->fetch_assoc()) {
        if ($np['teacher_np_des'] !== -99) {
            $tSum+=$np['teacher_np_des'];
            $tCount++;
        }
        if ($np['class_np_des'] !== -99) {
            $cSum+=$np['class_np_des'];
            $cCount++;
        }
    }
        $tNPS = $tSum/$tCount*100;
        $cNPS = $cSum/$cCount*100;
    
        fputcsv($output, array($level['semester'], $level['level_number'],$tNPS,$tCount,$cNPS,$cCount));
    }
