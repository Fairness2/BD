<?
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$name = clean ($_POST["newname"]);
		$number = clean ($_POST["newnumber"]);
		$address = clean ($_POST["newaddress"]);
		$type = clean ($_POST["type"]);
		if(!empty($name) && !empty($address) && !empty($type))
		{
			if (check_length($name, 1, 50) && check_length($number, 0, 15) && check_length($address, 1, 255)) 
			{				
				require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
				if ($errorconect != 2) 
				{
					try{
						$STHC = $DBH->prepare("SELECT COUNT(id) FROM drugstore WHERE name = ? AND address = ?");
						$STHC->bindParam(1, $name);
						$STHC->bindParam(2, $address);
						$STHC->execute();
						$STHC->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHC->fetch();
						$STHT = $DBH->prepare("SELECT id FROM type_dragstore WHERE name = ?");
						$STHT->bindParam(1, $type);
						$STHT->execute();
						$STHT->setFetchMode(PDO::FETCH_ASSOC);
						$rowt = $STHT->fetch();
						$type_id = $rowt["id"];
						if ($row["count"] == 0 && $type_id != 0)
						{
							$STHI = $DBH->prepare("INSERT INTO drugstore (name, number, address, type) VALUES (?, ?, ?, ?)");
							$STHI->bindParam(1, $name);
							$STHI->bindParam(2, $number); 
							$STHI->bindParam(3, $address); 
							$STHI->bindParam(4, $type_id);
							$STHI->execute();
							$DBH = null;
							header("Location: http://bd.lab/drugstore");							
						}
						else
						{
							$erorn = "Такое имя уже есть";
							$_SESSION["name"] = $name;
							$_SESSION["number"] = $alias_1;
							$_SESSION["type"] = $type;
							$_SESSION["address"] = $address;
							$_SESSION["erorn"] = $erorn;
							header("Location: http://bd.lab/drugstore/insert.php");
						}
					}
					catch (PDOException $e) 
					{
						$_SESSION["name"] = $name;
						$_SESSION["number"] = $alias_1;
						$_SESSION["type"] = $type;
						$_SESSION["address"] = $address;
						$erorn = "Опаньки, что-то пошло не так";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/drugstore/insert.php");
					}
				}
				else
				{
					$_SESSION["name"] = $name;
					$_SESSION["number"] = $alias_1;
					$_SESSION["type"] = $type;
					$_SESSION["address"] = $address;
					$erorn = "Опаньки, что-то пошло не так";
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/drugstore/insert.php");
				}
				$DBH = null;
			}
			else 
			{
				$erorn = "Превышено кол-во символов";
				$_SESSION["erorn"] = $erorn;
				$_SESSION["name"] = $name;
				$_SESSION["number"] = $alias_1;
				$_SESSION["type"] = $type;
				$_SESSION["address"] = $address;
				header("Location: http://bd.lab/drugstore/insert.php");
				
			}	
		}
		else 
		{
			$erorn = "Вы ввели пустые значения";
			$_SESSION["erorn"] = $erorn;
			$_SESSION["name"] = $name;
			$_SESSION["number"] = $alias_1;
			$_SESSION["type"] = $type;
			$_SESSION["address"] = $address;
			header("Location: http://bd.lab/drugstore/insert.php");

		}		
	}
	else
	{
		$erorn = "Опаньки, тот ли вы человечек";
		$_SESSION["erorn"] = $erorn;
		$_SESSION["name"] = $_POST["newname"];
		$_SESSION["number"] = $_POST["newnumber"];
		$_SESSION["type"] = $_POST["type"];
		$_SESSION["address"] = $_POST["newaddress"];
		header("Location: http://bd.lab/drugstore/insert.php");
	}
?>