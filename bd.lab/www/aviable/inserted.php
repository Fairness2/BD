<?	
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$id = $_POST["id"];
		$drugstore = clean ($_POST["drugstore"]);
		$data = clean ($_POST["data"]);
		$prise = clean ($_POST["prise"]);
		if(!empty($id) && !empty($drugstore) && !empty($data) && !empty($prise))
		{
			if (check_length($drugstore, 1, 100) && check_length($data, 8, 10) && check_length($prise, 1, 6)) 
			{				
				require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
				if ($errorconect != 2) 
				{
					try
					{
						$STH = $DBH->prepare("SELECT id, name, address FROM drugstore WHERE delete = false");
						$STH->execute();
						$STH->setFetchMode(PDO::FETCH_ASSOC);
						while ($row = $STH->fetch()) 
						{
							$nadress = '"'.$row["name"].'"'.' Адрес: '.$row["address"];
							$nadress = clean ($nadress);
							if ($nadress == $drugstore) 
							{
								$did = $row["id"];
								$error = 1;
								break;
							}
						}
						if ($error == 1) 
						{							
							$STHIN = $DBH->prepare("INSERT INTO availability_of_drugs (id_drugstore, id_drug, date_of_manufacture, price) VALUES (?, ?, ?, ?)");
							$STHIN->bindParam(1, $did);
							$STHIN->bindParam(2, $id); 
							$STHIN->bindParam(3, $data); 
							$STHIN->bindParam(4, $prise);
							$STHIN->execute();
							$DBH = null;
							header("Location: http://bd.lab");	
						}
						else
						{
							$_SESSION["drugid"] = $id;
							$_SESSION["drugstore"] = $drugstore;
							$_SESSION["data"] = $data;
							$_SESSION["prise"] = $prise;
							$erorn = "Опаньки, что-то пошло не так";
							$_SESSION["erorn"] = $erorn;
							header("Location: http://bd.lab/aviable/insert.php");	
						}
					}
					catch (PDOException $e) 
					{
						$_SESSION["drugid"] = $id;
						$_SESSION["drugstore"] = $drugstore;
						$_SESSION["data"] = $data;
						$_SESSION["prise"] = $prise;
						$erorn = "Опаньки, что-то пошло не так";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/aviable/insert.php");
					}
				}
				else
				{
					$_SESSION["drugid"] = $id;
					$_SESSION["drugstore"] = $drugstore;
					$_SESSION["data"] = $data;
					$_SESSION["prise"] = $prise;
					$erorn = "Опаньки, что-то пошло не так";
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/aviable/insert.php");
				}
				$DBH = null;
			}
			else 
			{
				$_SESSION["drugid"] = $id;
				$_SESSION["drugstore"] = $drugstore;
				$_SESSION["data"] = $data;
				$_SESSION["prise"] = $prise;
				$erorn = "Превышено кол-во символов";
				$_SESSION["erorn"] = $erorn;
				header("Location: http://bd.lab/aviable/insert.php");
				
			}	
		}
		else 
		{
			$_SESSION["drugid"] = $id;
			$_SESSION["drugstore"] = $drugstore;
			$_SESSION["data"] = $data;
			$_SESSION["prise"] = $prise;
			$erorn = "Вы ввели пустые значения";
			$_SESSION["erorn"] = $erorn;			
			header("Location: http://bd.lab/aviable/insert.php");

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["erorn"] = $erorn;
		header("Location: http://bd.lab/aviable/insert.php");
	}
?>