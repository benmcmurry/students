<?php
include_once("../../connectFiles/connect_e.php");
include_once("cas-go.php");
$student_id = $_POST['student_id'];
$student_id = str_replace(' ', '', $student_id);
$student_id = preg_replace('/\s+/', '', $student_id);

if (strlen($student_id)!==9) {echo "invalid ID";exit();}
$query = $elc_db->prepare("Select * from Students s inner join Semesters sem on s.semester_first_enrolled=sem.semester where s.student_id=?");
$query->bind_param('s', $student_id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

  echo "<h1>".$row['student_first_name']." ".$row['student_last_name']."</h1>";
  echo "Student ID#: ". $row['student_id']."<br />";
  echo "netid: ". $row['student_netID']."<br />";
  echo "Birthday: ".$row['birthday_day']."/".$row['birthday_month']."/".$row['birthday_year']."<br />";
  echo "Native Language: ".$row['native_language']."<br />";
  echo "Sex: ".$row['sex']."<br />";
  echo "First Semester: ".$row['semester_name']." ".$row['semester_year'];
mysqli_free_result($result);
  echo "<h1>Semester Proficiency Data</h1>";

  $query = $elc_db->prepare("Select 
  sd.semester, 
  sd.status, 
  sd.lats_comb_av,
  sd.lats_vocab,
  sd.lats_listening_score,
  sd.lats_listening_level,
  sd.lats_reading_score,
  sd.lats_reading_level,
  sd.lats_speaking,
  sd.lats_writing,
  sem.semester_name,
  sem.semester_year
 from Semester_Data sd
  Natural Join Semesters sem
  where student_id=? order by sem.semester ASC");
  $query->bind_param('s', $student_id);
  $query->execute();
  $result = $query->get_result();

 

  ?>
 <div id='data-table'>
  <div class='column'>
    <div class='value header'>Semester</div>
    <div class='value header'>Status</div>
    <div class='value header'>LATs Combined Average</div>
    <div class='value header'>LATs Vocabulary</div>
    <div class='value header'>LATs Listening Score</div>
    <div class='value header'>LATs Listening Level</div>
    <div class='value header'>LATs Reading Score</div>
    <div class='value header'>LATs Reading Level</div> 
    <div class='value header'>LATs Speaking</div>
    <div class='value header'>LATs Writing</div>
    <div class='value header'>Grammar Citizenship</div>
    <div class='value header'>Grammar Proficiency</div>
    <div class='value header'>Grammar 5-point Scale</div>
    <div class='value header'>Listening & Speaking Citizenship</div>
    <div class='value header'>Listening & Speaking Proficiency</div>
    <div class='value header'>Listening & Speaking 5-point Scale</div>
    <div class='value header'>Reading Citizenship</div>
    <div class='value header'>Reading Proficiency</div>
    <div class='value header'>Reading 5-point Scale</div>
    <div class='value header'>Writing Citizenship</div>
    <div class='value header'>Writing Proficiency</div>
    <div class='value header'>Writing 5-point Scale</div>
  </div>
 <?php
  while ($row2 = $result->fetch_assoc()) {
    
        echo "<div class='column'>";
        foreach ($row2 as $key => $value) {
          if ($key == "lats_comb_av" || $key == "lats_vocab"){
            $value = number_format((float)$value, 2, '.', '');
          }
          if ($key == "semester"){
            echo "<div class='value'>".$row2['semester_name']." ".$row2['semester_year']."</div>";
          } elseif ($key !== 'semester_name' && $key !=="semester_year"){
            // $value = number_format((float)$value, 2, '.', '');
          echo "<div class='value'>$value</div>";
          }
        }

        $query = $elc_db->prepare("select * from Student_Enrollments se where se.student_id=? and se.semester =? order by se.course_id asc");
        $query->bind_param('ss', $student_id, $row2['semester']);
        $query->execute();
        $result2 = $query->get_result();
        $numRows = $result2->num_rows;
        if ($numRows==0) { echo "<div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div>"; }
        while ($row3 = $result2->fetch_assoc()) {
          echo "<div class='value'>".$row3['grades_citizenship_percentage']."</div>";
          echo "<div class='value'>".$row3['grades_proficiency_percentage']."</div>";
          echo "<div class='value'>".$row3['five_point_scale']."</div>";
          
        }

        echo "</div>";

        
      }

  
  echo "</div>";
?>
