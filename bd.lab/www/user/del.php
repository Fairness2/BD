<?	
	session_start();
	if ((isset($_POST["del"])) && (isset($_SESSION["id"]))) 
	{
		require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		try{
			$STH = $DBH->prepare("UPDATE patient SET delete = true WHERE id = ?");
			$STH->bindParam(1, $_SESSION["id"]);
			$STH->execute();
			$DBH = null;
			header("Location: http://bd.lab/user/userunlog.php");
			}
		catch (PDOException $e) {
			echo "Problems?";
			file_put_contents ("errorlist.txt", $e, FILE_APPEND);
		}
		$DBH = null;
	}
?>