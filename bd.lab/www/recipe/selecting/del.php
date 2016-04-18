<?
	session_start();
	$key = $_SESSION["key"];	
	if ((isset($_POST["select"])) && ($_POST["key"] == $key)) 
	{
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		require $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		try
		{
			//$month = substr($_POST["month"], -2);
			//$start = $_POST["month"]."-01";
			//$year = substr($_POST["month"], 0, 4);
			//$finish = $start;
			//if ($month == 12) 
			//{
			//	$year = substr($_POST["month"], 0, 4);
			//	$year = $year + 1;
			//	$finish = $year."-01-01";
			//}
			//else
			//{
			//	$month = $month + 1;
			//	if (check_length ($month, 1, 1)) 
			//	{
			//		$month = "0".$month;
			//	}
			//	$finish = $year."-".$month."-01";
			//}
			////$start = clean ($start);
			////$finish = clean ($finish);
			$st = "2016-04-01";
			$fi = "2016-05-01";
			$STHrt = $DBH->query("SELECT 1 as d");
			//$STHrt->bindParam(1, $st);
			//$STHrt->bindParam(2, $fi);
			//$STHrt->execute();
			$STHrt->setFetchMode(PDO::FETCH_ASSOC);
			$DBH = null;
			echo "<br />";
			//var_dump($STHrt->execute($arr));
			echo "<br />";
			echo "<br />";
			var_dump($STHrt);
			//header("Location: http://bd.lab/recipe/select");
		}
		catch (PDOException $e) 
		{
			echo "Problems?";
			file_put_contents ("errorlist.txt", $e, FILE_APPEND);
		}
		$DBH = null;
	}
	//SELECT recipe.date, patient.surname, drug.name FROM list_of_drugs, drug, patient, recipe WHERE recipe.date >= '2016-04-01' AND recipe.date < '2016-05-01' AND drug.id = list_of_drugs.id_drug AND list_of_drugs.id_recipe = recipe.id AND recipe.id_patient = patient.id AND list_of_drugs.delete = false AND drug.delete = false AND patient.delete = false AND recipe.delete = false
?>
