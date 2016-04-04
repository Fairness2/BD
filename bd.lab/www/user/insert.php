<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
	if ($_SESSION["newsurname"] != "") 
	{
		$surname = $_SESSION["newsurname"];		
		$years = $_SESSION["years"];
		$basic_diagnosis = $_SESSION["basic_diagnosis"];
		$concomitant_diagnosis = $_SESSION["concomitant_diagnosis"];
		$privileges = $_SESSION["privileges"];
		$erorn = $_SESSION["erorn"];
		$_SESSION["newsurname"] = "";
		$_SESSION["years"] = "";
		$_SESSION["basic_diagnosis"] = "";
		$_SESSION["concomitant_diagnosis"] = "";
		$_SESSION["privileges"] = "";
		$_SESSION["erorn"] = "";
	}
	$key = $_SESSION["key"];
	require_once "insert.html";
?>