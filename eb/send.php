<?php
//Sedns the messages to the user 
include_once('../helpers.php');
session_start();
$conn=connect();
if(!$_SESSION["eb"])
{
	header("Location: /chits/denied.php");
	exit();
}
$council=$_SESSION["council"];
//$sender=$_SESSION["user"];
$country=$_POST["dest"];
$message=$_POST["message"];
$eb=false;
if(isset($_POST["EB"]))
	$eb=true;
try{
	$stmt=$conn->prepare("select username from ".$council."_users where country=:country");
	$stmt->bindParam(':country',$country);
	$stmt->execute();
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	$to=$row["username"];
	$stmt=$conn->prepare("insert into ".$council."_msg(frm,recipient,message,eb) values(:sender,:recipient,:message,:eb)");
	$stmt->bindValue(':sender',"eb");
	
	$stmt->bindParam(':recipient',$to);
	$stmt->bindParam(':message',$message);
	$stmt->bindValue(':eb',0,PDO::PARAM_INT);
	$stmt->execute();
}
catch(PDOException $e)
{
	echo $e->getMessage();
}
?>