<?
	session_start();
	$key = $_SESSION["key"];	
	if ((isset($_POST["buy"])) && (isset($_POST["id"])) && ($_POST["key"] == $key)) 
	{
		require_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php";
		try{
			$STH = $DBH->prepare("SELECT id FROM availability_of_drugs WHERE id_drugstore = ? AND id_drug = ? AND date_of_manufacture = ? AND price = ? AND status = false AND delete = false");
			$STH->bindParam(1, $_POST["did"]);
			$STH->bindParam(2, $_POST["id"]);
			$STH->bindParam(3, $_POST["date"]);
			$STH->bindParam(4, $_POST["bdprice"]);
			$STH->execute();
			$STH->setFetchMode(PDO::FETCH_ASSOC);
			$row_dr = $STH->fetch();
			$id_dr = $row_dr["id"];

			$today = date("Y-m-d H:i:s");

			$STHP = $DBH->prepare("INSERT INTO purchase (id_patient, price, data) VALUES (?, ?, ?)");
			$STHP->bindParam(1, $_SESSION["id"]);
			$STHP->bindParam(2, $_POST["price"]);
			$STHP->bindParam(3, $today);
			$STHP->execute();

			$STHav = $DBH->prepare("SELECT id FROM purchase WHERE id_patient = ? AND price = ? AND data = ?");
			$STHav->bindParam(1, $_SESSION["id"]);
			$STHav->bindParam(2, $_POST["price"]);
			$STHav->bindParam(3, $today);
			$STHav->execute();
			$STHav->setFetchMode(PDO::FETCH_ASSOC);
			$row_pur = $STHav->fetch();
			$id_pur = $row_pur["id"];

			$STHP = $DBH->prepare("UPDATE availability_of_drugs SET status = true, id_purchase = ? WHERE id = ?");
			$STHP->bindParam(1, $id_pur);
			$STHP->bindParam(2, $id_dr);
			$STHP->execute();


			$DBH = null;
			header("Location: http://bd.lab");
			}
		catch (PDOException $e) {
			echo "Problems?";
			file_put_contents ("errorlist.txt", $e, FILE_APPEND);
		}
		$DBH = null;
	}
?>