<?	
	session_start();
	include_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	include_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	
	$key = $_SESSION["key"];
	include "midl.html";
	include_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";

?>