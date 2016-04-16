<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
	if (isset($_SESSION["id"])) 
	{	
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{			
			try{
				$STHCou = $DBH->prepare("SELECT COUNT(recipe.id) FROM recipe, patient WHERE patient.id = ? AND patient.id = recipe.id_patient AND patient.delete = false AND recipe.delete = false");
				$STHCou->bindParam(1, $_SESSION["id"]);
				$STHCou->execute();
				$STHCou->setFetchMode(PDO::FETCH_ASSOC);
				$arrcount = $STHCou->fetch();
				$page = $arrcount["count"] / 5;
				$STHSel = $DBH->prepare("SELECT recipe.id, date FROM recipe, patient WHERE recipe.delete = false AND patient.id = recipe.id_patient AND patient.delete = false AND patient.id = ? ORDER BY date DESC LIMIT 5 OFFSET ?");
				if (isset($_GET["page"]))
				{	
					$page1 = clean ($_GET["page"]);
					$moment = ($page1 - 1) * 5;
				}
				else
					$moment = 0;
				$STHSel->bindParam(1, $_SESSION["id"]); 
				$STHSel->bindParam(2, $moment);  
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
					$date = htmlspecialchars ($row["date"]);
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
		$DBH = null;
	}	
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";

?>