<?	
	session_start();

	function type_dragstore() 
	{
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{	
			try{
					global $STHD;
					$STHD = $DBH->prepare("SELECT name FROM type_dragstore");
					$STHD->execute();
					$STHD->setFetchMode(PDO::FETCH_ASSOC);	
				}
				catch (PDOException $e) 
				{
					require_once $_SERVER['DOCUMENT_ROOT']."/error.html";
					file_put_contents ("errorlist.txt", $e, FILE_APPEND);
				}
		}	
		else
		{
			require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
		}
		$DBH = null;
	}


	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
	$key = $_SESSION["key"];
	if (isset($_POST["id"]))
	{
		$id = htmlspecialchars ($_POST["id"]);
		$name = htmlspecialchars ($_POST["name"]);
		$number = htmlspecialchars ($_POST["number"]);
		$type = htmlspecialchars ($_POST["type"]);
		$address = htmlspecialchars ($_POST["address"]);
		$key = $_SESSION["key"];
		type_dragstore ();
		require_once "upd.html";
	}
	elseif (isset($_SESSION["dsid"]) && $_SESSION["dsid"] != "") 
	{
		$id = $_SESSION["dsid"];
		$name = $_SESSION["name"];
		$number = $_SESSION["number"];
		$type = $_SESSION["type"];
		$address = $_SESSION["address"];
		$erorn = $_SESSION["erorn"];
		$_SESSION["dsid"] = "";
		$_SESSION["name"] = "";
		$_SESSION["number"] = "";
		$_SESSION["type"] = "";
		$_SESSION["address"] = "";
		$_SESSION["erorn"] = "";
		$key = $_SESSION["key"];
		type_dragstore ();
		require_once "upd.html";
	}
	else
	require_once "error.html";
	
?>