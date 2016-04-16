<?	
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	
	if (isset($_POST["id"]))
	{
		$id = htmlspecialchars ($_POST["id"]);
		$date = htmlspecialchars ($_POST["date"]);
		$key = $_SESSION["key"];
		require_once "upd.html";
	}
	elseif (isset($_SESSION["rid"]) && $_SESSION["rid"] != "") 
	{
		$id = $_SESSION["rid"];
		$date = $_SESSION["date"];
		$erorn = $_SESSION["erorn"];
		$_SESSION["rid"] = "";
		$_SESSION["date"] = "";
		$key = $_SESSION["key"];
		require_once "upd.html";
	}
	else
		require_once "error.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
	
?>