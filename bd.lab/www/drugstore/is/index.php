<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";	
	
	if ((isset($_GET["name"])) && (isset($_GET["id"])) && (isset($_GET["address"]))) 
	{
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$name = clean ($_GET["name"]);
		$id = clean ($_GET["id"]);
		$address = clean ($_GET["address"]);

		if (!empty($name) && !empty($id) && !empty($address) && check_length($name, 1, 50) && check_length($address, 1, 255) && check_length($id, 1, 6)) 
		{

			require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
			if ($errorconect != 2) 
			{			
				try{
					$STHCou = $DBH->prepare("SELECT COUNT(id) FROM drugstore WHERE delete = false AND id = ? AND name = ? AND address = ?");
					$STHCou->bindParam(1, $id);  
					$STHCou->bindParam(2, $name);  
					$STHCou->bindParam(3, $address);  
					$STHCou->execute();
					$STHCou->setFetchMode(PDO::FETCH_ASSOC);
					$arrcount = $STHCou->fetch();
					$count = $arrcount["count"];

					if ($count == 1) 
					{						
						$STHSel = $DBH->prepare("SELECT name, COUNT(availability_of_drugs.id) 
							FROM drug, availability_of_drugs 
							WHERE availability_of_drugs.id_drugstore = ? AND availability_of_drugs.id_drug = drug.id
							AND drug.delete = false AND availability_of_drugs.delete = false AND availability_of_drugs.status = false GROUP BY name ORDER BY name");
						$STHSel->bindParam(1, $id);  
						$STHSel->execute();
						$STHSel->setFetchMode(PDO::FETCH_ASSOC);		
					}
					else 
						$ercoun = 1;
				}
				catch (PDOException $e) {
					$errorconect = 1;
					file_put_contents ("errorlist.txt", $e, FILE_APPEND);
				}
				if ($errorconect != 1 && $ercoun != 1) 
				{
					$error = 1;
					require "midl.html";				
				}
				else
					require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errprconect.html";
			}
			else
				require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errorconect.html";
			require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
			$DBH = null;	
		}
		else
		{
			//echo "4";
			require_once "error.html";	
		}
	}
	else
	{
		//echo "5";
		require_once "error.html";	
	}
?>