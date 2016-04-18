<?	
	session_start();
	include_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/head.html";
	include_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/navigation.php";
	require  $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/conect.php"; 
  	if ($errorconect != 2) 
 	{
   		try
    	{
	      $STHuser = $DBH->prepare("SELECT surname FROM patient WHERE delete = false ORDER BY surname");
	      $STHuser->execute();
	      $STHuser->setFetchMode(PDO::FETCH_ASSOC); 
	    }
	    catch (PDOException $e) {
	      $errorconect = 1;
	      file_put_contents ("errorlist.txt", $e, FILE_APPEND);
	    }
	    if ($errorconect != 1) {
	    	$key = $_SESSION["key"];
			include "midl.html";
	    }
	}
	$DBH = null;
	include_once $_SERVER['DOCUMENT_ROOT']."/navigation_and_head/foot.html";

?>