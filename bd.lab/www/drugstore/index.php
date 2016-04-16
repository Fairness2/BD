<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
	

	require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
	if ($errorconect != 2) 
	{			
		try{
			$STHCou = $DBH->prepare("SELECT COUNT(id) FROM drugstore WHERE delete = false");
			$STHCou->execute();
			$STHCou->setFetchMode(PDO::FETCH_ASSOC);
			$arrcount = $STHCou->fetch();
			$page = $arrcount["count"] / 5;
			$STHSel = $DBH->prepare("SELECT drugstore.id AS id, drugstore.name AS name, address, type_dragstore.name AS type, number FROM drugstore, type_dragstore WHERE delete = false AND drugstore.type = type_dragstore.id ORDER BY name LIMIT 5 OFFSET ?");
			if (isset($_GET["page"]))
			{	
				$page1 = clean ($_GET["page"]);
				$moment = ($page1 - 1) * 5;
			}
			else
				$moment = 0;
			$STHSel->bindParam(1, $moment);  
			$STHSel->execute();
			$STHSel->setFetchMode(PDO::FETCH_ASSOC);		
			}
		catch (PDOException $e) {
			$errorconect = 1;
			file_put_contents ("errorlist.txt", $e, FILE_APPEND);
		}
		if ($errorconect != 1) 
		{
			$error = 1;
			while($row = $STHSel->fetch())
			{
				$id = htmlspecialchars ($row["id"]);
				$name = htmlspecialchars ($row["name"]);
				$address = htmlspecialchars ($row["address"]);
				$type = htmlspecialchars ($row["type"]);
				$number = htmlspecialchars ($row["number"]);
				$key = $_SESSION["key"];
				require "midl.html";
				$error = 2;
			}

			if ($error == 1)
				require "error.html";
			require_once "footer.html";
			
		}
		else
			require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errprconect.html";
	}
	else
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errorconect.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
	$DBH = null;	
?>