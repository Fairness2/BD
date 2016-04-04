<?	
	session_start();
	require_once "/navigation_and_head/head.html";
	require_once "/navigation_and_head/navigation.php";
	require "/navigation_and_head/conect.php";
	if ($errorconect != 2) 
	{			
		try{
			$STHCou = $DBH->prepare("SELECT COUNT(id) FROM drug WHERE delete = false");
			$STHCou->execute();
			$STHCou->setFetchMode(PDO::FETCH_ASSOC);
			$arrcount = $STHCou->fetch();
			$page = $arrcount["count"] / 5;
			$STHSel = $DBH->prepare("SELECT * FROM drug WHERE delete = false ORDER BY name LIMIT 5 OFFSET ?");
			//$arr = array();
			//array_push($arr, "galaxy");
			//$STHSel->bindParam(1, $name);
			if (isset($_GET["page"]))
				$moment = ($_GET["page"] - 1) * 5;
			else
				$moment = 0;
			$STHSel->bindParam(1, $moment);  
			//$name = "galaxy";
			//var_dump($STH);
			//echo '<p style="color: red">';
			//var_dump($STH->execute($arr));
			//echo '</p>';
			//echo "<br />";
			//var_dump($STH);
			$STHSel->execute();
			$STHSel->setFetchMode(PDO::FETCH_ASSOC);		
			}
		catch (PDOException $e) {
			$errorconect = 1;
			file_put_contents ("errorlist.txt", $e, FILE_APPEND);
		}
		if ($errorconect != 1) 
		{
			$error = 1;
			//var_dump($STH);
			//print_r(expression);
			while($row = $STHSel->fetch())
			{
				$id = htmlspecialchars ($row["id"]);
				$name = htmlspecialchars ($row["name"]);
				$alias_1 = htmlspecialchars ($row["alias_1"]);
				$alias_2 = htmlspecialchars ($row["alias_2"]);
				$alias_3 = htmlspecialchars ($row["alias_3"]);
				$shelf_life = htmlspecialchars ($row["shelf_life"]);
				$composition = htmlspecialchars ($row["composition"]);
				require "midl.html";
				$error = 2;
			}

			if ($error == 1)
				require "error.html";
			require_once "footer.html";
			require_once "/navigation_and_head/foot.html";
		}
		else
			require "/navigation_and_head/errprconect.html";
	}
	else
		require "/navigation_and_head/errorconect.html";
	$DBH = null;	
?>