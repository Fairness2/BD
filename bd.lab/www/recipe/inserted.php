<?
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$date = clean ($_POST["date"]);
		if(!empty($date))
		{
			require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
			if ($errorconect != 2) 
			{
				try{
					$STHC = $DBH->prepare("SELECT COUNT(id) FROM recipe WHERE date = ? AND id_patient != ?");
					$STHC->bindParam(1, $date);
					$STHC->bindParam(2, $_SESSION["id"]);
					$STHC->execute();
					$STHC->setFetchMode(PDO::FETCH_ASSOC);
					$row = $STHC->fetch();
					if ($row["count"] == 0)
					{
						$STHS = $DBH->prepare("INSERT INTO recipe (date, id_patient) VALUES (?, ?)");
						$STHS->bindParam(1, $date);
						$STHS->bindParam(2, $_SESSION["id"]);
						$STHS->execute();
						$DBH = null;
						header("Location: http://bd.lab/recipe");							
					}
					else
					{
						$erorn = "Рецепт с этой датой уже есть";
						$_SESSION["date"] = $date;
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/recipe/insert.php");
					}
				}
				catch (PDOException $e) 
				{					
					$_SESSION["date"] = $date;
					$erorn = "Опаньки, что-то пошло не так";
					file_put_contents ("errorlist.txt", $e, FILE_APPEND);
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/recipe/insert.php");
				}
			}
			else
			{
				$_SESSION["date"] = $date;
				$erorn = "Опаньки, что-то пошло не так";
				$_SESSION["erorn"] = $erorn;
				header("Location: http://bd.lab/recipe/insert.php");
			}
			$DBH = null;			
		}
		else 
		{
			$erorn = "Вы ввели пустые значения";
			$_SESSION["erorn"] = $erorn;
			header("Location: http://bd.lab/recipe/insert.php");

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["erorn"] = $erorn;
		$_SESSION["date"] = $_POST["date"];
		header("Location: http://bd.lab/recipe/insert.php");
	}
?>