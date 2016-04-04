<?
	session_start();
	$_SESSION["surname"] = $_GET["surname"];
	$_SESSION["id"] = $_GET["id"];
	$_SESSION["key"] = md5($_GET["surname"].$_GET["id"].$_SERVER["REMOTE_ADDR"]);
	header("Location: http://bd.lab");
?>