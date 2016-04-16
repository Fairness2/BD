<?
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$id = $_POST["id"];
		$date = clean ($_POST["date"]);
		if(!empty($date))
		{
			require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
			if ($errorconect != 2) 
			{
				try{
					$STHC = $DBH->prepare("SELECT COUNT(id) FROM recipe WHERE date = ? AND id != ?");
					$STHC->bindParam(1, $date);
					$STHC->bindParam(2, $id);
					$STHC->execute();
					$STHC->setFetchMode(PDO::FETCH_ASSOC);
					$row = $STHC->fetch();
					if ($row["count"] == 0)
					{
						$STHS = $DBH->prepare("UPDATE recipe SET date = ? WHERE id = ?");
						$STHS->bindParam(1, $date);
						$STHS->bindParam(2, $id);
						$STHS->execute();
						$DBH = null;
						header("Location: http://bd.lab/recipe");							
					}
					else
					{
						$erorn = "Рецепт с этой датой уже есть";
						$_SESSION["rid"] = $id;
						$_SESSION["date"] = $date;
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/recipe/upd.php");
					}
				}
				catch (PDOException $e) 
				{
					$_SESSION["rid"] = $id;
					$_SESSION["date"] = $date;
					$erorn = "Опаньки, что-то пошло не так";
					file_put_contents ("errorlist.txt", $e, FILE_APPEND);
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/recipe/upd.php");
				}
			}
			else
			{
				$_SESSION["rid"] = $id;
				$_SESSION["date"] = $date;
				$erorn = "Опаньки, что-то пошло не так";
				$_SESSION["erorn"] = $erorn;
				header("Location: http://bd.lab/recipe/upd.php");
			}
			$DBH = null;			
		}
		else 
		{
			$erorn = "Вы ввели пустые значения";
			$_SESSION["erorn"] = $erorn;
			$_SESSION["rid"] = $id;
			header("Location: http://bd.lab/recipe/upd.php");

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["erorn"] = $erorn;
		$_SESSION["rid"] = $_POST["id"];
		$_SESSION["date"] = $_POST["date"];
		header("Location: http://bd.lab/recipe/upd.php");
	}
?>