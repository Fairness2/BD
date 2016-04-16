<?
	session_start();
	$key = $_SESSION["key"];	
	if ((isset($_POST["del"])) && (isset($_POST["id"])) && (isset($_POST["lid"])) && ($_POST["key"] == $key)) 
	{
		$id = $_POST["id"];
		require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		try{
			$STH = $DBH->prepare("UPDATE list_of_drugs SET delete = true WHERE id = ?");
			$STH->bindParam(1, $_POST["lid"]);
			$STH->execute();
			$DBH = null;
			header("Location: http://bd.lab/recipe/list?id=$id");
			}
		catch (PDOException $e) {
			echo "Problems?";
			file_put_contents ("errorlist.txt", $e, FILE_APPEND);
		}
	}
?>