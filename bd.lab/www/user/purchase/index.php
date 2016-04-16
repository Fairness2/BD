<?	
	session_start();
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	if (isset($_SESSION["id"])) 
	{
		require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		if ($errorconect != 2) 
		{			
			try
			{
				$STHCou = $DBH->prepare("SELECT price, data FROM purchase  WHERE delete = false AND id = ?");
				$STHCou->bindParam(1, $_SESSION["id"]);  
				$STHCou->execute();
				$STHCou->setFetchMode(PDO::FETCH_ASSOC);					
			}
			catch (PDOException $e) {
				$errorconect = 1;
				file_put_contents ("errorlist.txt", $e, FILE_APPEND);
			}
			if ($errorconect != 1) 
			{
				$error = 1;
				while($row = $STHCou->fetch())
				{
					$id = $_SESSION["id"];
					$surname = $_SESSION["surname"];
					$years = htmlspecialchars ($row["years"]);
					$basic_diagnosis = htmlspecialchars ($row["basic_diagnosis"]);
					$concomitant_diagnosis = htmlspecialchars ($row["concomitant_diagnosis"]);
					$privileges = htmlspecialchars ($row["privileges"]);
					require "midl.html";
					$error = 2;
				}

				if ($error == 1)
					require "error.html";
				require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";
			}
			else
				require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errprconect.html";
		}
		else
			require $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/errorconect.html";
	}
	$DBH = null;	
?>