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
				$STHCou = $DBH->prepare("SELECT COUNT(id) FROM purchase WHERE id_patient = ?");
				$STHCou->bindParam(1, $_SESSION["id"]);
				$STHCou->execute();
				$STHCou->setFetchMode(PDO::FETCH_ASSOC);
				$arrcount = $STHCou->fetch();
				$page = $arrcount["count"] / 5;

				$STHSel = $DBH->prepare("SELECT data, price, id FROM purchase WHERE id_patient = ? ORDER BY data DESC LIMIT 5 OFFSET ?");
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
			catch (PDOException $e) 
			{
				$errorconect = 1;
				file_put_contents ("errorlist.txt", $e, FILE_APPEND);
			}
			if ($errorconect != 1) 
			{
				$key = $_SESSION["key"];
				$error = 1;
				while($row = $STHSel->fetch())
				{
					$id = $row["id"];
					$data = $row["data"];
					$price = $row["price"];
					try
					{
						$STHdr = $DBH->prepare("SELECT drugstore.name AS store, drug.name AS drug FROM availability_of_drugs, drug, drugstore WHERE id_purchase = ? AND id_drug = drug.id AND id_drugstore = drugstore.id ORDER BY drug.name");
						$STHdr->bindParam(1, $id);
						$STHdr->execute();
						$STHdr->setFetchMode(PDO::FETCH_ASSOC);				
					}
					catch (PDOException $e) 
					{
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
					}
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
	}	
?>