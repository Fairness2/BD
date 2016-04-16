<?
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/forms_and_control/control_form.php";
	if ((isset($_GET["surname"])) && (isset($_GET["id"]))) 
	{
		$surname = clean ($_GET["surname"]);
		$id = clean ($_GET["id"]);

		if (!empty($surname) && !empty($id) && check_length($surname, 1, 20) && check_length($id, 1, 6)) 
		{
			require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
			if ($errorconect != 2) 
			{	
				try{
						$STHUs = $DBH->prepare("SELECT COUNT(id) FROM patient WHERE surname = ? AND id = ? AND delete = false");
						$STHUs->bindParam(1, $surname);
						$STHUs->bindParam(2, $id);
						$STHUs->execute();
						$STHUs->setFetchMode(PDO::FETCH_ASSOC);
						$row = $STHUs->fetch();
						if ($row["count"] == 1)
						{
							$_SESSION["surname"] = $_GET["surname"];
							$_SESSION["id"] = $_GET["id"];
							$_SESSION["key"] = md5($_GET["surname"].$_GET["id"].$_SERVER["REMOTE_ADDR"]);
							$DBH = null;
							header("Location: http://bd.lab");							
						}
						else
						{
							//echo "1";
							require_once "error.html";	
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