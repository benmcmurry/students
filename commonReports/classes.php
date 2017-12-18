<?php
include_once("../../../connectFiles/connect_e.php");
$fileName = basename(__FILE__, '.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');
fputcsv($output, array('semester','track','class','teacher','teacher_nps','total_ratings','class_nps','total_ratings'));

$query = $elc_db->prepare("Select e.semester, c.track, c.course_name, teacher_id, e.course_id, t.teacher_first_name, t.teacher_last_name from Evaluations e natural join Courses c Natural join Teachers t group by e.semester, c.track, c.course_id, e.teacher_id order by `semester` ASC, `track` ASC, c.course_name ASC");
$query->execute();
$result = $query->get_result();
while ($courses = $result->fetch_assoc()) {

    
    $query2 = $elc_db->prepare("Select teacher_np_des, class_np_des from Evaluations where course_id=?");
    $query2->bind_param('s',$courses['course_id']);
    $query2->execute();
    $result2 = $query2->get_result();
    $tCount=0;
    $cCount=0;
    $tSum=0;
    $cSum=0;
    while ($np = $result2->fetch_assoc()) {
        if ($np['teacher_np_des'] !== -99) { //this is for missing data
            $tSum+=$np['teacher_np_des'];
            $tCount++;
            
        }
        if ($np['class_np_des'] !== -99) { //this is for missing data
            $cSum+=$np['class_np_des'];
            $cCount++;
        }
    }
    $tNPS = $tSum/$tCount*100;
    $cNPS = $cSum/$cCount*100;
  

    $query3 = $elc_db->prepare("Update Evaluations set teacher_nps=?, class_nps=? where course_id=?");
    $query3->bind_param('sss', $tNPS, $cNPS, $courses['course_id']);
    $query3->execute();
    $result3 = $query3->get_result();
    
    fputcsv($output, array($courses['semester'],$courses['track'],$courses['course_name'],$courses['teacher_first_name']." ".$courses['teacher_last_name'],$tNPS,$tCount,$cNPS,$cCount));
}
