<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>t_user操作</title>
<link rel="stylesheet" href="sample.css">
<script src="sample.js"></script>
</head>
<body>
<header></header>
<nav></nav>

<?php
require_once 'mysql.php';
require_once 'util.php';

$table = "t_user";
echo "<h1>$table 操作</h1>";
//*************************************************
//mysqlクラスの生成
//*************************************************
$dbuser = "localline";
$dbpass = "localline";
$dbserver = "localhost";
$dbname = "localline";
$mysql = new mysql($dbserver,$dbuser,$dbpass,$dbname);
$util = new util();


if($_SERVER['REQUEST_METHOD']=='POST'){
	$sql = "";

	date_default_timezone_set('Asia/Tokyo');
	$now = new DateTime();
	$date = $now->format('Y-m-d H:i:s');

	if(isset($_POST["submit_del"])){//削除
		$user_id = key($_POST["submit_del"]);
		$sql = "delete from $table where user_id=$user_id";

	}else if(isset($_POST["submit_edit"])){//変更
		$user_id = key($_POST["submit_edit"]);
		$user_name = $_POST["user_name-".$user_id];
		$user_pass = $_POST["user_pass-".$user_id];
		$sql = "update $table set user_name='$user_name',user_pass='$user_pass',date='$date' where user_id=$user_id";
	}else if(isset($_POST["submit_add"])){ //追加
		$user_id = $util->CreatePrimaryId($mysql,"$table","user_id");
		$user_name = $_POST["user_name"];
		$user_pass = $_POST["user_pass"];
		$sql = "insert into $table values($user_id,'$user_name','$user_pass','$date')";
	}

	if($sql!=""){
		$mysql->query($sql);
	}
}


//*************************************************
//フォーム(一覧)
//*************************************************
$mysql->query("select * from $table order by user_id");

echo <<<EOT
<form action=$_SERVER[PHP_SELF] method="POST">
<table border=1><tr><td>user_id<td>user_name<td>user_pass<td>date<td colspan=2>

EOT;

while($r = $mysql->fetch()){
	$id = $r["user_id"];
	$user_name = $r["user_name"];
	$user_pass = $r["user_pass"];
	$date = $r["date"];
printf("<tr><td>%07d",$id);
	echo <<<EOT
<td><input type="text" name="user_name-$id" value="$user_name" size ="15">
<td><input type="text" name="user_pass-$id" value="$user_pass" size ="15">
<td><input type="text" name="date-$id" value="$date" size ="22">
<td><input type="submit" name= "submit_edit[$id]" value="変更">
<td><input type="submit" name= "submit_del[$id]" value="削除">

EOT;

}
echo "</table>";

echo <<<EOT

<p>
username:<input type="text" name="user_name" size ="15">
user_pass:<input type="text" name="user_pass" size ="15">
<input type="submit" name= "submit_add" value="追加">
</form>
EOT;

$mysql->free();


?>
<footer></footer>
</body>
</html>
