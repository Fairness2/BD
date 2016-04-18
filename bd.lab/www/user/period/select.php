<?	
	session_start();	
	$key = $_SESSION["key"];
	if ($_POST["key"] == $key && $_POST["patient"] != "" && $_POST["st_date"] != "" && $_POST["fn_date"] != "")
	{
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{	
			try
			{
				$s_date = $_POST["st_date"];
				$f_date = $_POST["fn_date"];
				$st_date = $_POST["st_date"]." 00:00:00";
				$fn_date = $_POST["fn_date"]." 00:00:00";
				$surname = $_POST["patient"];
			
				$STHCou = $DBH->prepare("SELECT COUNT(availability_of_drugs.id) 
					FROM purchase, patient, availability_of_drugs
					WHERE purchase.data >= ? AND purchase.data < ? AND purchase.id_patient = patient.id
					AND patient.surname = ? AND patient.delete = false AND availability_of_drugs.delete = false AND availability_of_drugs.status = true AND availability_of_drugs.id_purchase = purchase.id");
				$STHCou->bindParam(1, $st_date);
				$STHCou->bindParam(2, $fn_date);
				$STHCou->bindParam(3, $surname);
				$STHCou->execute();
				$STHCou->setFetchMode(PDO::FETCH_ASSOC);
				if ($arr = $STHCou->fetch()) 
				{
					$count = $arr["count"];					
					echo "<p>Фамилия покупателя: $surname. Кол-во: $count. Дата с $s_date по $f_date</p>";
				}
				else {
					echo "<p>Сдесь ничего нет</p>";
				}
			}
			catch (PDOException $e) 
			{
				echo "Problems?";
				file_put_contents ("errorlist.txt", $e, FILE_APPEND);
			}
		}
		$DBH = null;
	}
	else {
		echo "<p>Сдесь ничего нет</p>";
	}
?>
