<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	
	if ((isset($_GET["name"])) && (isset($_GET["id"]))) 
	{
		require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
		$name = clean ($_GET["name"]);
		$id = clean ($_GET["id"]);

		if (!empty($name) && !empty($id) && check_length($name, 1, 50) && check_length($id, 1, 6)) 
		{
			require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
			if ($errorconect != 2) 
			{	
				try{
						$STHD = $DBH->prepare("SELECT COUNT(id) FROM drug WHERE name = ? AND id = ? AND delete = false");
						$STHD->bindParam(1, $name);
						$STHD->bindParam(2, $id);
						$STHD->execute();
						$STHD->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHD->fetch();
						if ($row["count"] == 1)
						{
							$STHDru = $DBH->prepare("SELECT * FROM drug WHERE name = ? AND id = ? AND delete = false");
							$STHDru->bindParam(1, $name);
							$STHDru->bindParam(2, $id);
							$STHDru->execute();
							$STHDru->setFetchMode(PDO::FETCH_ASSOC);
							if ($row = $STHDru->fetch()) 
							{
								$alias_1 = htmlspecialchars ($row["alias_1"]);
								$alias_2 = htmlspecialchars ($row["alias_2"]);
								$alias_3 = htmlspecialchars ($row["alias_3"]);
								$shelf_life = htmlspecialchars ($row["shelf_life"]);
								$composition = htmlspecialchars ($row["composition"]);
								$STHA = $DBH->prepare("SELECT DISTINCT date_of_manufacture, price, drugstore.id AS did, drugstore.name AS dname, discount FROM availability_of_drugs, drugstore, type_dragstore  WHERE id_drug = ? AND id_drugstore = drugstore.id AND status = false AND drugstore.delete = false AND availability_of_drugs.delete = false AND type = type_dragstore.id ORDER BY price");
								$STHA->bindParam(1, $id);
								$STHA->execute();
								$STHA->setFetchMode(PDO::FETCH_ASSOC);
								$key = $_SESSION["key"];
								require_once "aviable.html";
							}
							else
							{
								echo "6";
								require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
							}								
						}
						else
						{
							echo "1";
							require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
						}					
					}
					catch (PDOException $e) 
					{
						echo "2";
						require_once $_SERVER['DOCUMENT_ROOT']."/error.html";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
					}
			}	
			else
			{
				echo "3";
				require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
			}
			$DBH = null;
		}
		else
		{
			echo "4";
			require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
		}
	}
	else
	{
		echo "5";
		require_once $_SERVER['DOCUMENT_ROOT']."/error.html";	
	}
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
?>