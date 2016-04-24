<?	
	//$key = $_SESSION["key"];
	//if ($_POST["key"] == $key)
	//{
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{	
			try
			{
				require $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
				$month = substr($_POST["month"], -2);
				$start = $_POST["month"]."-01";
				$year = substr($_POST["month"], 0, 4);
				$finish = $start;
				if ($month == 12) 
				{
					$year = substr($_POST["month"], 0, 4);
					$year = $year + 1;
					$finish = $year."-01-01";
				}
				else
				{
					$month = $month + 1;
					if (check_length ($month, 1, 1)) 
					{
						$month = "0".$month;
					}
					$finish = $year."-".$month."-01";
				}

				$STHCou = $DBH->prepare("SELECT recipe.date, patient.surname, drug.name 
					FROM list_of_drugs, drug, patient, recipe 
					WHERE recipe.date >= ? AND recipe.date < ? AND drug.id = list_of_drugs.id_drug 
					AND list_of_drugs.id_recipe = recipe.id AND recipe.id_patient = patient.id AND list_of_drugs.delete = false 
					AND drug.delete = false AND patient.delete = false AND recipe.delete = false");
				$STHCou->bindParam(1, $start);
				$STHCou->bindParam(2, $finish);
				$STHCou->execute();
				$STHCou->setFetchMode(PDO::FETCH_ASSOC);
				//$arrcount = $STHCou->fetch();
				//var_dump ($arrcount);
				$ed = 0;
				include "select.html";
				if ($er == 0) {
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
	//}
?>
