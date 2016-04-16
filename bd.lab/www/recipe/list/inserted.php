<?
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["upd"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$drug = clean ($_POST["drug"]);
		$id = $_POST["id"];
		if(!empty($drug))
		{
			if (check_length($drug, 1, 50)) 
			{				
				require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
				if ($errorconect != 2) 
				{
					try{
						$STHD = $DBH->prepare("SELECT id FROM drug WHERE name = ?");
						$STHD->bindParam(1, $drug);
						$STHD->execute();
						$STHD->setFetchMode(PDO::FETCH_ASSOC);
						$rowd = $STHD->fetch();

						$STHC = $DBH->prepare("SELECT COUNT(id) FROM list_of_drugs, drug WHERE id_recipe = ? AND id_drug = ?");
						$STHC->bindParam(1, $id);
						$STHC->bindParam(2, $rowd["id"]);
						$STHC->execute();
						$STHC->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHC->fetch();
						
						if ($row["count"] == 0)
						{
							$STHI = $DBH->prepare("INSERT INTO list_of_drugs (id_recipe, id_drug) VALUES (?, ?)");
							$STHI->bindParam(1, $id);
							$STHI->bindParam(2, $rowd["id"]); 
							$STHI->execute();
							$DBH = null;
							header("Location: http://bd.lab/recipe/list?id=$id");							
						}
						else
						{
							$erorn = "Такое имя уже есть";
							$_SESSION["erorn"] = $erorn;
							header("Location: http://bd.lab/recipe/list?id=$id");	
						}
					}
					catch (PDOException $e) 
					{
						$erorn = "Опаньки, что-то пошло не так";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/recipe/list?id=$id");	
					}
				}
				else
				{
					$erorn = "Опаньки, что-то пошло не так";
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/recipe/list?id=$id");	
				}
				$DBH = null;
			}
			else 
			{
				$erorn = "Превышено кол-во символов";
				$_SESSION["erorn"] = $erorn;
				header("Location: http://bd.lab/recipe/list?id=$id");	
				
			}	
		}
		else 
		{
			$erorn = "Вы ввели пустые значения";
			$_SESSION["erorn"] = $erorn;
			header("Location: http://bd.lab/recipe/list?id=$id");	

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["erorn"] = $erorn;
		header("Location: http://bd.lab/recipe/list?id=$id");	
	}
?>