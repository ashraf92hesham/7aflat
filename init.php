<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "testdb";
$table = "data";
// $serevr = $_SERVER;
// $post = $_POST;
// $get = $_GET;

$conn = new mysqli($host, $user, $pass);

if (mysqli_connect_errno()){
	    printf("Connect failed: %s\n", mysqli_connect_error());
	    exit();
}

$selected_db = $conn->select_db($db);



if ($_SERVER['REQUEST_METHOD'] == "POST"){
	$name = isset($_POST['name'])? $conn->real_escape_string($_POST['name']) : "";
	$id = isset($_POST['id'])? $conn->real_escape_string($_POST['id'])  : 0;
	$job = isset($_POST['job'])? $conn->real_escape_string($_POST['job']): "";

	$insert_query = "INSERT INTO $table (name, id, job) VALUES ('$name', '$id', '$job')";
	$query = $conn->query($insert_query);

	if($query){
		$json = array("status" => 1, "msg" => "Done! data added!");
	}else{
		$json = array("status" => 0, "msg" => "ERROR ADDING DATA..", "query" => $query, "error" => $conn->error);
	}
}


if ($_SERVER['REQUEST_METHOD'] == "GET"){
	$id = isset($_GET['id'])? $conn->real_escape_string($_GET['id']) : "" ;

	if (!empty($id)){
		$select_query = "SELECT name, job FROM $table where id='$id'";
		$get_query = $conn->query($select_query);
		$result = array();

		while ($r = mysqli_fetch_array($get_query)){
			extract($r);
			$result[] = array("name"=>$name, "job"=>$job);
		}
		
		$json = array("status"=>1, "info"=>$result[0]);
	}else{
		$json = array("status"=>0, "msg"=>$id);
	}
}

$conn->close();

header('Content-type: application/json');
echo json_encode($json);

?>