<?	
	session_start();

	function inser($id) 
	{
	    require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$key = $_SESSION["key"];
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{	
			try{
					$STHD = $DBH->prepare("SELECT name, address FROM drugstore WHERE delete = false");
					$STHD->execute();
					$STHD->setFetchMode(PDO::FETCH_ASSOC);
					$erorn = $_SESSION["erorn"];
					$snadress = $_SESSION["drugstore"];
					$data = $_SESSION["data"];
					$prise = $_SESSION["prise"];
					$_SESSION["erorn"] = "";
					$_SESSION["drugstore"] = "";
					$_SESSION["data"] = "";
					$_SESSION["prise"] = "";
					$_SESSION["drugid"] = "";						
					require_once "insert.html";			
				}
				catch (PDOException $e) 
				{
					echo "2";
					require_once $_SERVER['DOCUMENT_ROOT']."/error.html";
					file_put_contents ("errorlist.txt", $e, FILE_APPEND);
				}
		}	
		else
		{
			echo "3";
			require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
		}
		$DBH = null;
	}

	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	

	if (isset($_SESSION["role"]) && $_SESSION["role"] !=0) 
	{
		$key = $_SESSION["key"];
		if ((isset($_POST["id"])) && ($key == $_POST["key"])) 
		{
			inser ($_POST["id"]);
		}
		elseif ((isset($_SESSION["drugid"])) && ($_SESSION["drugid"] != "")) 
		{
			inser ($_SESSION["drugid"]);
		}
		else
			require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
	}
	else
		include $_SERVER['DOCUMENT_ROOT']."/forms_and_control/erorr404.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
		
?>