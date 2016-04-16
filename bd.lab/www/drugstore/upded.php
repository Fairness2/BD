<?
	session_start();
	$key = $_SESSION["key"];
	if ((isset($_POST["done"])) && ($_POST["key"] == $key))
	{
		$_SESSION["erorn"] = "";
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$id = $_POST["id"];
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
						$STHC = $DBH->prepare("SELECT COUNT(id) FROM drugstore WHERE name = ? AND address = ? AND id != ?");
						$STHC->bindParam(1, $name);
						$STHC->bindParam(2, $address);
						$STHC->bindParam(3, $id);
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
							$STHS = $DBH->prepare("UPDATE drugstore SET name = ?, number = ?, address = ?, type = ? WHERE id = ?");
							$STHS->bindParam(1, $name);
							$STHS->bindParam(2, $number); 
							$STHS->bindParam(3, $address); 
							$STHS->bindParam(4, $type_id);
							$STHS->bindParam(5, $id);
							$STHS->execute();
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
							$_SESSION["dsid"] = $id;
							$_SESSION["erorn"] = $erorn;
							header("Location: http://bd.lab/drugstore/upd.php");
						}
					}
					catch (PDOException $e) 
					{
						$_SESSION["name"] = $name;
						$_SESSION["number"] = $alias_1;
						$_SESSION["type"] = $type;
						$_SESSION["address"] = $address;
						$_SESSION["dsid"] = $id;
						$erorn = "Опаньки, что-то пошло не так";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
						$_SESSION["erorn"] = $erorn;
						header("Location: http://bd.lab/drugstore/upd.php");
					}
				}
				else
				{
					$_SESSION["name"] = $name;
					$_SESSION["number"] = $alias_1;
					$_SESSION["type"] = $type;
					$_SESSION["address"] = $address;
					$_SESSION["dsid"] = $id;
					$erorn = "Опаньки, что-то пошло не так";
					$_SESSION["erorn"] = $erorn;
					header("Location: http://bd.lab/drugstore/upd.php");
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
				$_SESSION["dsid"] = $id;
				header("Location: http://bd.lab/drugstore/upd.php");
				
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
			$_SESSION["dsid"] = $id;
			header("Location: http://bd.lab/drugstore/upd.php");

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
		$_SESSION["dsid"] = $_POST["id"];
		header("Location: http://bd.lab/drugstore/upd.php");
	}
?>