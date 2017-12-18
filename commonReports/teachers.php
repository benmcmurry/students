<?php
include_once("../../../connectFiles/connect_e.php");
$fileName = basename(__FILE__, '.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
fputcsv($output, array('teacher', 'overall teacher nps','ratings','overall class nps','ratings'));
$query = $elc_db->prepare("Select teacher_id, t.teacher_first_name, t.teacher_last_name from Evaluations e Natural Join Teachers t group by e.teacher_id order by t.teacher_first_name ASC");
$query->execute();
$result = $query->get_result();
while ($teacher = $result->fetch_assoc()) {

    $query2 = $elc_db->prepare("Select teacher_np_des, class_np_des from Evaluations where teacher_id=?");
    $query2->bind_param('s', $teacher['teacher_id']);
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
    
    fputcsv($output, array($teacher['teacher_first_name']." ".$teacher['teacher_last_name'],$tNPS,$tCount,$cNPS,$cCount));
}
?>