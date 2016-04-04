<?
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once "/forms_and_control/control_form.php";
		$id = $_POST["id"];
		$name = clean ($_POST["newname"]);
		$alias_1 = clean ($_POST["newalias_1"]);
		$alias_2 = clean ($_POST["newalias_2"]);
		$alias_3 = clean ($_POST["newalias_3"]);
		$shelf_life = clean ($_POST["newshelf"]);
		$composition = clean ($_POST["newcomposition"]);
		if(!empty($name) && !empty($shelf_life) && !empty($composition))
		{
			if (check_length($name, 1, 50) && check_length($alias_1, 0, 50) && check_length($alias_2, 0, 50) && check_length($alias_3, 0, 50) && check_length($shelf_life, 1, 3) && check_length($composition, 1, 255)) 
			{				
				require_once "/navigation_and_head/conect.php";
				if ($errorconect != 2) 
				{
					try{
						$STHC = $DBH->prepare("SELECT COUNT(id) FROM drug WHERE name = ? AND id != ?");
						$STHC->bindParam(1, $name);
						$STHC->bindParam(2, $id);
						$STHC->execute();
						$STHC->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHC->fetch();
						if ($row["count"] == 0)
						{
							$STHS = $DBH->prepare("UPDATE drug SET name = ?, alias_1 = ?, alias_2 = ?, alias_3 = ?, shelf_life = ?, composition = ? WHERE id = ?");
							$STHS->bindParam(1, $name);
							$STHS->bindParam(2, $alias_1); 
							$STHS->bindParam(3, $alias_2); 
							$STHS->bindParam(4, $alias_3);
							$STHS->bindParam(5, $shelf_life);
							$STHS->bindParam(6, $composition);
							$STHS->bindParam(7, $id);
							$STHS->execute();
							$DBH = null;
							header("Location: http://bd.lab");							
						}
						else
						{
							$erorn = "Такое имя уже есть";
							$_SESSION["dname"] = $name;
							$_SESSION["dalias_1"] = $alias_1;
							$_SESSION["dalias_2"] = $alias_2;
							$_SESSION["dalias_3"] = $alias_3;
							$_SESSION["dshelf_life"] = $shelf_life;
							$_SESSION["dcomposition"] = $composition;
							$_SESSION["did"] = $id;
							$_SESSION["erorn"] = $erorn;
							header("Location: http://bd.lab/upd.php");
						}
					}
					catch (PDOException $e) 
					{
						$_SESSION["dname"] = $name;
						$_SESSION["dalias_1"] = $alias_1;
						$_SESSION["dalias_2"] = $alias_2;
						$_SESSION["dalias_3"] = $alias_3;
						$_SESSION["dshelf_life"] = $shelf_life;
						$_SESSION["dcomposition"] = $composition;
						$_SESSION["did"] = $id;
						$erorn = "Опаньки, что-то пошло не так";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/upd.php");
					}
				}
				else
				{
					$_SESSION["dname"] = $name;
					$_SESSION["dalias_1"] = $alias_1;
					$_SESSION["dalias_2"] = $alias_2;
					$_SESSION["dalias_3"] = $alias_3;
					$_SESSION["dshelf_life"] = $shelf_life;
					$_SESSION["dcomposition"] = $composition;
					$_SESSION["did"] = $id;
					$erorn = "Опаньки, что-то пошло не так";
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/upd.php");
				}
				$DBH = null;
			}
			else 
			{
				$erorn = "Превышено кол-во символов";
				$_SESSION["erorn"] = $erorn;
				$_SESSION["dname"] = $name;
				$_SESSION["dalias_1"] = $alias_1;
				$_SESSION["dalias_2"] = $alias_2;
				$_SESSION["dalias_3"] = $alias_3;
				$_SESSION["dshelf_life"] = $shelf_life;
				$_SESSION["dcomposition"] = $composition;
				$_SESSION["did"] = $id;
				header("Location: http://bd.lab/upd.php");
				
			}	
		}
		else 
		{
			$erorn = "Вы ввели пустые значения";
			$_SESSION["erorn"] = $erorn;
			$_SESSION["dname"] = $name;
			$_SESSION["dalias_1"] = $alias_1;
			$_SESSION["dalias_2"] = $alias_2;
			$_SESSION["dalias_3"] = $alias_3;
			$_SESSION["dshelf_life"] = $shelf_life;
			$_SESSION["dcomposition"] = $composition;
			$_SESSION["did"] = $id;
			header("Location: http://bd.lab/upd.php");

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["erorn"] = $erorn;
		$_SESSION["dname"] = $_POST["newname"];
		$_SESSION["dalias_1"] = $_POST["newalias_1"];
		$_SESSION["dalias_2"] = $_POST["newalias_2"];
		$_SESSION["dalias_3"] = $_POST["newalias_3"];
		$_SESSION["dshelf_life"] = $_POST["newshelf_life"];
		$_SESSION["dcomposition"] = $_POST["newcomposition"];
		$_SESSION["did"] = $_POST["id"];
		header("Location: http://bd.lab/upd.php");
	}
?>