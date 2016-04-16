<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
	$id = clean ($_GET["id"]);

	if (isset($_SESSION["id"]) && check_length($id, 1, 6)) 
	{	
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{				
			try 
			{
				$STHP = $DBH->prepare("SELECT count(id) FROM recipe WHERE delete = false AND id_patient = ? AND id = ?");
				$STHP->bindParam(1, $_SESSION["id"]); 
				$STHP->bindParam(2, $id); 
				$STHP->execute();
				$STHP->setFetchMode(PDO::FETCH_ASSOC);
				$rowp = $STHP->fetch();
			} 
			catch (PDOException $e) 
			{
				$errorconect = 1;
				file_put_contents ("errorlist.txt", $e, FILE_APPEND);		
			}	
			if ($rowp["count"] == 1) 
			{				
				try{
					$STHSel = $DBH->prepare("SELECT date FROM recipe, patient WHERE recipe.delete = false AND patient.id = recipe.id_patient AND patient.delete = false AND patient.id = ? AND recipe.id = ?");
					$STHSel->bindParam(1, $_SESSION["id"]); 
					$STHSel->bindParam(2, $id);  
					$STHSel->execute();
					$STHSel->setFetchMode(PDO::FETCH_ASSOC);

					$STHlist = $DBH->prepare("SELECT list_of_drugs.id AS id, drug.name AS drug FROM recipe, list_of_drugs, drug WHERE recipe.delete = false AND list_of_drugs.delete = false AND drug.delete = false AND recipe.id = list_of_drugs.id_recipe AND list_of_drugs.id_drug = drug.id AND recipe.id = ? ORDER BY drug.name");
					$STHlist->bindParam(1, $id);  
					$STHlist->execute();
					$STHlist->setFetchMode(PDO::FETCH_ASSOC);

					$STHdrug = $DBH->prepare("SELECT name FROM drug WHERE delete = false ORDER BY name");
					$STHdrug->execute();
					$STHdrug->setFetchMode(PDO::FETCH_ASSOC);
					}
				catch (PDOException $e) {
					$errorconect = 1;
					file_put_contents ("errorlist.txt", $e, FILE_APPEND);
				}
				if ($errorconect != 1) 
				{
					$error = 1;
					if($row = $STHSel->fetch())
					{
						$date = htmlspecialchars ($row["date"]);
						$key = $_SESSION["key"];
						require "midl.html";
						$error = 2;
					}
					
					if ($error == 1)
						require "error.html";
					
				}
				else
					require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errorconect.html";
			}
			else
				require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errorconect.html";
		}
		else
			require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errorconect.html";
		$DBH = null;
	}	
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";

?>