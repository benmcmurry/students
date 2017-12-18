<?php
include_once("../../../connectFiles/connect_e.php");
$fileName = basename(__FILE__, '.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
fputcsv($output, array('semester','class','overall teacher nps','total_ratings','overall class nps','total_ratings'));

$query = $elc_db->prepare("Select c.course_name, semester from Evaluations Natural Join Courses c group by c.course_name, semester order by `semester` ASC, c.course_name ASC");
$query->execute();
$result = $query->get_result();
while($class = $result->fetch_assoc()){
    

    $query2 = $elc_db->prepare("Select teacher_np_des, class_np_des from Evaluations natural Join Courses c where c.course_name=? and semester=?");
    $query2->bind_param('ss', $class['course_name'], $class['semester']);
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
    fputcsv($output, array($class['semester'],$class['course_name'],$tNPS,$tCount,$cNPS,$cCount));
}
?>