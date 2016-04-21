<?
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$id = $_SESSION["id"];
		$surname = clean ($_POST["newsurname"]);
		$years = clean ($_POST["newyears"]);
		$basic_diagnosis = clean ($_POST["newasic_diagnosis"]);
		$concomitant_diagnosis = clean ($_POST["newconcomitant_diagnosis"]);
		$privileges = clean ($_POST["newprivileges"]);
		$passwd = clean ($_POST["passwd"]);
		if(!empty($surname) && !empty($passwd))
		{
			if (check_length($surname, 1, 20) && check_length($years, 0, 3) && check_length($basic_diagnosis, 0, 50) && check_length($concomitant_diagnosis, 0, 50)  && check_length($passwd, 6, 100)) 
			{				
				require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
				if ($errorconect != 2) 
				{
					try{
						$STHC = $DBH->prepare("SELECT COUNT(id) FROM patient WHERE surname = ? AND id != ?");
						$STHC->bindParam(1, $surname);
						$STHC->bindParam(2, $id);
						$STHC->execute();
						$STHC->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHC->fetch();
						if ($row["count"] == 0)
						{
							$STHS = $DBH->prepare("UPDATE patient SET surname = ?, years = ?, basic_diagnosis = ?, concomitant_diagnosis = ?, privileges = ?, passwd = ? WHERE id = ?");
							$STHS->bindParam(1, $surname);
							$STHS->bindParam(2, $years); 
							$STHS->bindParam(3, $basic_diagnosis); 
							$STHS->bindParam(4, $concomitant_diagnosis);
							$STHS->bindParam(5, $privileges);
							$STHS->bindParam(6, $passwd);
							$STHS->bindParam(7, $id);
							$STHS->execute();
							$DBH = null;
							header("Location: http://bd.lab/user/userunlog.php?url=http://bd.lab");							
						}
						else
						{
							$erorn = "Такое имя уже есть";
							$_SESSION["newsurname"] = $surname;
							$_SESSION["years"] = $years;
							$_SESSION["basic_diagnosis"] = $basic_diagnosis;
							$_SESSION["concomitant_diagnosis"] = $concomitant_diagnosis;
							$_SESSION["privileges"] = $privileges;
							$_SESSION["erorn"] = $erorn;
							header("Location: http://bd.lab/user/upd.php");
						}
					}
					catch (PDOException $e) 
					{
						$_SESSION["newsurname"] = $surname;
						$_SESSION["years"] = $years;
						$_SESSION["basic_diagnosis"] = $basic_diagnosis;
						$_SESSION["concomitant_diagnosis"] = $concomitant_diagnosis;
						$_SESSION["privileges"] = $privileges;
						$erorn = "Опаньки, что-то пошло не так";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/user/upd.php");
					}
				}
				else
				{
					$_SESSION["newsurname"] = $surname;
					$_SESSION["years"] = $years;
					$_SESSION["basic_diagnosis"] = $basic_diagnosis;
					$_SESSION["concomitant_diagnosis"] = $concomitant_diagnosis;
					$_SESSION["privileges"] = $privileges;
					$erorn = "Опаньки, что-то пошло не так";
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/user/upd.php");
				}
				$DBH = null;
			}
			else 
			{
				$erorn = "Превышено кол-во символов";
				$_SESSION["erorn"] = $erorn;
				$_SESSION["newsurname"] = $surname;
				$_SESSION["years"] = $years;
				$_SESSION["basic_diagnosis"] = $basic_diagnosis;
				$_SESSION["concomitant_diagnosis"] = $concomitant_diagnosis;
				$_SESSION["privileges"] = $privileges;
				header("Location: http://bd.lab/user/upd.php");
				
			}	
		}
		else 
		{
			$erorn = "Вы ввели пустые значения";
			$_SESSION["erorn"] = $erorn;
			$_SESSION["newsurname"] = $surname;
			$_SESSION["years"] = $years;
			$_SESSION["basic_diagnosis"] = $basic_diagnosis;
			$_SESSION["concomitant_diagnosis"] = $concomitant_diagnosis;
			$_SESSION["privileges"] = $privileges;
			header("Location: http://bd.lab/user/upd.php");

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["newsurname"] = $_POST["newsurname"];
		$_SESSION["years"] = $_POST["newyears"];
		$_SESSION["basic_diagnosis"] = $_POST["newasic_diagnosis"];
		$_SESSION["concomitant_diagnosis"] = $_POST["newconcomitant_diagnosis"];
		$_SESSION["privileges"] = $_POST["newprivileges"];
		$_SESSION["erorn"] = $erorn;
		header("Location: http://bd.lab/user/upd.php");
	}
?>