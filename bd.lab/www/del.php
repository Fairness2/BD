<?
	session_start();
	$key = $_SESSION["key"];	
	if ((isset($_POST["del"])) && (isset($_POST["id"])) && ($_POST["key"] == $key)) 
	{
		require_once "/navigation_and_head/conect.php";
		try{
			$STH = $DBH->prepare("UPDATE drug SET delete = true WHERE id = ?");
			$STH->bindParam(1, $_POST["id"]);
			$STH->execute();
			$DBH = null;
			header("Location: http://bd.lab");
			}
		catch (PDOException $e) {
			echo "Problems?";
			file_put_contents ("errorlist.txt", $e, FILE_APPEND);
		}
	}
?>