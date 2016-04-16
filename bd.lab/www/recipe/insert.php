<?	
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	
	if (isset($_SESSION["name"]) && $_SESSION["name"] != "") 
	{
		$date = $_SESSION["date"];
		$erorn = $_SESSION["erorn"];
		$_SESSION["date"] = "";
	}
	$key = $_SESSION["key"];
	require_once "insert.html";

	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
	
?>