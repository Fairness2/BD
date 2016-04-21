<?
	session_start();
	session_unset();
	session_destroy();
	require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
	$url = clean($_GET["url"]);
	header("Location: $url");
?>