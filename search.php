<?php
header("Content-Type: application/json; charset=UTF-8");
include_once("../../connectFiles/connect_e.php");
include_once("cas-go.php");


if (isset($_GET['term'])){
    $term = "%".$_GET['term']."%";
    $return_arr = array();
    $query = $elc_db->prepare("Select student_id, student_first_name, student_last_name from Students where student_id like ? or student_first_name like ? or student_last_name like ? or student_netID like ?");
    $query->bind_param("ssss",$term,$term,$term,$term);
    $query->execute();
    $result = $query->get_result();
    $a_json = array();
    $a_json_row = array();
    while ($row = $result->fetch_assoc()){
        // $return_arr[] = $row['student_first_name']." ".$row['student_last_name']." (".$row['student_id'].")";
       $value = $row['student_id'];
       $label = $row['student_first_name']." ".$row['student_last_name']." (".$row['student_id'].")";
       $a_json_row["value"] = $value;
       $a_json_row["label"] = $label;
       array_push($a_json, $a_json_row);
    }
    // echo json_encode($return_arr);
echo json_encode($a_json);

}