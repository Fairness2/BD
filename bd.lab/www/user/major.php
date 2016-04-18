<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	//if (isset($_SESSION["id"])) 
	//{
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{			
			try
			{
				$STHCo = $DBH->prepare("SELECT surname, privileges, name, purchase.price AS pu_price,  availability_of_drugs.price AS dr_prise 
					FROM drug, patient, purchase, availability_of_drugs 
					WHERE availability_of_drugs.price = (SELECT MAX(price) FROM availability_of_drugs) 
					AND  purchase.id_patient = patient.id AND availability_of_drugs.status = true AND availability_of_drugs.delete = false
					AND availability_of_drugs.id_drug = drug.id AND availability_of_drugs.id_purchase = purchase.id
					AND patient.delete = false AND drug.delete = false");  
				$STHCo->execute();
				$STHCo->setFetchMode(PDO::FETCH_ASSOC);				
			}
			catch (PDOException $e) {
				$errorconect = 1;
				file_put_contents ("errorlist.txt", $e, FILE_APPEND);
			}
			if ($errorconect != 1) 
			{
				$error = 1;				
				require "major.html";
				

				if ($error == 1)
					require "error.html";
				require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
			}
			else
				require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errprconect.html";
		}
		else
			require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errorconect.html";
	//}
	$DBH = null;	
?>