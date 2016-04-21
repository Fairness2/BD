<?
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
	if ((isset($_POST["surname"])) && (isset($_POST["passwd"])) && (isset($_POST["done"]))) 
	{
		$surname = clean ($_POST["surname"]);
		$passwd = clean ($_POST["passwd"]);

		if (!empty($surname) && !empty($passwd) && check_length($surname, 1, 20) && check_length($passwd, 1, 100)) 
		{
			require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
			if ($errorconect != 2) 
			{	
				try{
						$STHUs = $DBH->prepare("SELECT COUNT(id) FROM patient WHERE surname = ? AND passwd = ? AND delete = false");
						$STHUs->bindParam(1, $surname);
						$STHUs->bindParam(2, $passwd);
						$STHUs->execute();
						$STHUs->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHUs->fetch();
						
						if ($row["count"] == 1)
						{
							$STHp = $DBH->prepare("SELECT id, privileges, role FROM patient WHERE surname = ?");
							$STHp->bindParam(1, $surname);
							$STHp->execute();
							$STHp->setFetchMode(PDO::FETCH_ASSOC);
							$rowp = $STHp->fetch();
							$privileges = $rowp["privileges"];
							$id = $rowp["id"];
							$role = $rowp["role"];

							$_SESSION["surname"] = $surname;
							$_SESSION["id"] = $id;
							$_SESSION["privileges"] = $privileges;
							$_SESSION["role"] = $role;
							$_SESSION["key"] = md5($surname.$passwd.$_SERVER["REMOTE_ADDR"]);
							$DBH = null;
							$url = clean ($_POST["url"]);
							header("Location: $url");							
						}
						else
						{
							//echo "1";
							$_SESSION["erorr_log"] = "Неправильный логин или пароль";
							$_SESSION["erorr_name"] = $surname;
							$url = clean ($_POST["url"]);
							header("Location: $url");
						}					
					}
					catch (PDOException $e) 
					{
						//echo "2";
						require_once "error.html";
						file_put_contents ("errorlist.txt", $e, FILE_APPEND);
					}
			}	
			else
			{
				//echo "3";
				require_once "error.html";	
			}
			$DBH = null;
		}
		else
		{
			//echo "4";
			require_once "error.html";	
		}
	}
	else
	{
		//echo "5";
		require_once "error.html";	
	}
?>