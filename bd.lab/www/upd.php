<?	
	session_start();
	require_once "/navigation_and_head/head.html";
	require_once "/navigation_and_head/navigation.php";
	
	if (isset($_POST["id"]))
	{
		$id = htmlspecialchars ($_POST["id"]);
		$name = htmlspecialchars ($_POST["name"]);
		$alias_1 = htmlspecialchars ($_POST["alias_1"]);
		$alias_2 = htmlspecialchars ($_POST["alias_2"]);
		$alias_3 = htmlspecialchars ($_POST["alias_3"]);
		$shelf_life = htmlspecialchars ($_POST["shelf_life"]);
		$composition = htmlspecialchars ($_POST["composition"]);
	}
	elseif (isset($_SESSION["did"]) && $_SESSION["did"] != "")
	{		
		$id = $_SESSION["did"];
		$name = $_SESSION["dname"];
		$alias_1 = $_SESSION["dalias_1"];
		$alias_2 = $_SESSION["dalias_2"];
		$alias_3 = $_SESSION["dalias_3"];
		$shelf_life = $_SESSION["dshelf_life"];
		$composition = $_SESSION["dcomposition"];
		$erorn = $_SESSION["erorn"];
		$_SESSION["dname"] = "";
		$_SESSION["dalias_1"] = "";
		$_SESSION["dalias_2"] = "";
		$_SESSION["dalias_3"] = "";
		$_SESSION["dshelf_life"] = "";
		$_SESSION["dcomposition"] = "";
		$_SESSION["did"] = "";
		$_SESSION["erorn"] = "";
	}
	$key = $_SESSION["key"];
	require_once "upd.html";
	require_once "/navigation_and_head/foot.html";
?>