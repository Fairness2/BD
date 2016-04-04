<?
	session_start();
	session_unregister($_SESSION['id']);
	session_unregister($_SESSION['surname']);
	session_unset();
	session_destroy();
	header("Location: http://bd.lab");
?>