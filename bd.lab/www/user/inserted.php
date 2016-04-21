<?	
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$surname = clean ($_POST["newsurname"]);
		$years = clean ($_POST["newyears"]);
		$basic_diagnosis = clean ($_POST["newasic_diagnosis"]);
		$concomitant_diagnosis = clean ($_POST["newconcomitant_diagnosis"]);
		$privileges = clean ($_POST["newprivileges"]);
		$passwd = clean ($_POST["passwd"]);
		if(!empty($surname) && !empty($passwd))
		{
			if (check_length($surname, 1, 20) && check_length($years, 0, 3) && check_length($basic_diagnosis, 0, 50) && check_length($concomitant_diagnosis, 0, 50) && check_length($passwd, 6, 100)) 
			{				
				require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
				if ($errorconect != 2) 
				{
					try{
						$STHI = $DBH->prepare("SELECT COUNT(id) FROM patient WHERE surname = ? AND delete = false");
						$STHI->bindParam(1, $surname);
						$STHI->execute();
						$STHI->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHI->fetch();
						if ($row["count"] == 0)
						{
							$STHIN = $DBH->prepare("INSERT INTO patient (surname, years, basic_diagnosis, concomitant_diagnosis, privileges, passwd) VALUES (?, ?, ?, ?, ?, ?)");
							$STHIN->bindParam(1, $surname);
							$STHIN->bindParam(2, $years); 
							$STHIN->bindParam(3, $basic_diagnosis); 
							$STHIN->bindParam(4, $concomitant_diagnosis);
							$STHIN->bindParam(5, $privileges);
							$STHIN->bindParam(6, $passwd);
							$STHIN->execute();
							$DBH = null;
							header("Location: http://bd.lab");							
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
							header("Location: http://bd.lab/user/insert.php");
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
						header("Location: http://bd.lab/user/insert.php");
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
					header("Location: http://bd.lab/user/insert.php");
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
				header("Location: http://bd.lab/user/insert.php");
				
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
			header("Location: http://bd.lab/user/insert.php");

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["erorn"] = $erorn;
		$_SESSION["newsurname"] = $_POST["newsurname"];
		$_SESSION["years"] = $_POST["newyears"];
		$_SESSION["basic_diagnosis"] = $_POST["newasic_diagnosis"];
		$_SESSION["concomitant_diagnosis"] = $_POST["newconcomitant_diagnosis"];
		$_SESSION["privileges"] = $_POST["newprivileges"];
		header("Location: http://bd.lab/user/insert.php");
	}
?>