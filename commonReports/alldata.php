<?php
include_once("../../../connectFiles/connect_e.php");

$fileName = basename(__FILE__, '.php');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$fileName.'.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');



$query = $elc_db->prepare("Select * from `Evaluations`
natural join Courses 
natural join  Students
Natural Join Teachers
Natural Join Semesters
inner join Semester_Data on Semester_Data.student_id=Evaluations.student_id and Semester_Data.semester = Evaluations.semester
join Student_Enrollments on Student_Enrollments.student_id=Evaluations.student_id and Student_Enrollments.course_id = Evaluations.course_id
");
$query->execute();
$result = $query->get_result();
$data = array();
$headersFirst = TRUE;
$headers = array();
$new_row = array();
while ($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
        if ($headersFirst){
            $headers[] = $key;
        }        
      }
      if ($headersFirst){
        fputcsv($output, $headers);
    }
      fputcsv($output, $row);
      $headersFirst=FALSE;
    }
?>