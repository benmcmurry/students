<?php
include_once("../../connectFiles/connect_e.php");
include_once("cas-go.php");
$student_id = $_POST['student_id'];
// $student_id = $_GET['student_id'];
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
  sd.level,
  sd.level_name,
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
 inner join Semesters sem on sd.semester = sem.semester
   where sd.student_id=? order by sem.semester ASC");
  $query->bind_param('s', $student_id);
  $query->execute();
  $result2 = $query->get_result();

 

  ?>
    <div id='data-table'>

        <div class='column'>
            <div class='header'>Semester</div>
            <div class='header'>Status</div>
            <div class='header'>Level</div>
            <div class='placeholderHeader'>LATs</div>
            <div class='header'>Combined Average</div>
            <div class='header'>Vocabulary</div>
            <div class='header'>Listening Score</div>
            <div class='header'>Listening Level</div>
            <div class='header'>Reading Score</div>
            <div class='header'>Reading Level</div>
            <div class='header'>Speaking</div>
            <div class='header'>Writing</div>
            <div class='placeholderHeader'>Citizenship Grades</div>
            <div class='header'>Grammar</div>
            <div class='header'>Listening & Speaking</div>
            <div class='header'>Reading</div>
            <div class='header'>Writing</div>
            <div class='placeholderHeader'>Proficiency Grades</div>
            <div class='header'>Grammar</div>
            <div class='header'>Listening & Speaking</div>
            <div class='header'>Reading</div>
            <div class='header'>Writing</div>
            <div class='placeholderHeader'>5-point Scale</div>
            <div class='header'>Grammar</div>
            <div class='header'>Listening & Speaking</div>
            <div class='header'>Reading</div>
            <div class='header'>Writing</div>
        </div>
        <?php
  while ($row2 = $result2->fetch_assoc()) {
    
        echo "<div class='column'>";
        foreach ($row2 as $key => $value) {
          if ($key == "lats_comb_av" || $key == "lats_vocab"){
            $value = number_format((float)$value, 2, '.', '');
          }
          if ($key == "semester"){
            echo "<div class='value'>".$row2['semester_name']." ".$row2['semester_year']."</div>";
          } elseif($key !== 'semester_name' && $key !=="semester_year"){
            if ($key == 'lats_comb_av'){echo "<div class='placeholder'>&nbsp;</div>";}
            if ($value=="-777") {$value = "-";}
            if ($key=="level") {echo "<div class='value'>$value";}
            elseif ($key=="level_name") {
              if ($value==""){
                echo "</div>";
              } else {echo " ($value)</div>";}
            }
            else {echo "<div class='value'>$value</div>"; }
          }
        }

        $query = $elc_db->prepare("select * from Student_Enrollments se where se.student_id=? and se.semester =? order by se.course_id asc");
        $query->bind_param('ss', $student_id, $row2['semester']);
        $query->execute();
        $result3 = $query->get_result();
        $numRows = $result3->num_rows;
        if ($numRows==0) { echo "<div class='placeholder'>&nbsp;</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='placeholder'>&nbsp;</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='placeholder'>&nbsp;</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div><div class='value'>-</div>"; }
        else
        {echo "<div class='placeholder'>&nbsp;</div>";
        while ($citizenshipGrades = $result3->fetch_assoc()) {
          echo "<div class='value'>".$citizenshipGrades['grades_citizenship_percentage']."</div>";
          
        }
        $result3->data_seek(0);
        echo "<div class='placeholder'>&nbsp;</div>";
        while ($proficiencyGrades = $result3->fetch_assoc()) {
          
          echo "<div class='value'>".$proficiencyGrades['grades_proficiency_percentage']."</div>";
       
          
        }
        $result3->data_seek(0);
        echo "<div class='placeholder'>&nbsp;</div>";
        while ($fivePointScale = $result3->fetch_assoc()) {
          echo "<div class='value'>".$fivePointScale['five_point_scale']."</div>";
          
        }
      }
        echo "</div>";

        
      }

  
  echo "</div>";
?>