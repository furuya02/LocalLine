<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>t_to操作</title>
<link rel="stylesheet" href="sample.css">
<script src="sample.js"></script>
</head>
<body>
<header></header>
<nav></nav>

<?php
require_once 'mysql.php';
require_once 'util.php';

$table = "t_to";
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
		$no = key($_POST["submit_del"]);
		$msg_id = $_POST["msg_id-$no"];
		$user_id = $_POST["user_id-$no"];
		$sql = "delete from $table where user_id=$user_id and msg_id=$msg_id";
	}else if(isset($_POST["submit_edit"])){//変更
		$no = key($_POST["submit_edit"]);
		$msg_id = $_POST["msg_id-$no"];
		$user_id = $_POST["user_id-$no"];
		$read_time = $_POST["read_time-$no"];
		$ok_time = $_POST["ok_time-$no"];
		$sql = "update $table set read_time='$read_time',ok_time='$ok_time',date='$date' where user_id=$user_id and msg_id=$msg_id";
	}else if(isset($_POST["submit_add"])){ //追加
		$msg_id = $_POST["msg_id"];
		$user_id = $_POST["user_id"];
		$sql = "insert into $table values($msg_id,$user_id,'0','0','$date')";
	}else if(isset($_POST["submit_read"])){ //既読
		$msg_id = $_POST["msg_id"];
		$user_id = $_POST["user_id"];
		$sql = "update $table set read_time='$date' where msg_id=$msg_id and user_id=$user_id";
	}else if(isset($_POST["submit_ok"])){ //了解
		$msg_id = $_POST["msg_id"];
		$user_id = $_POST["user_id"];
		$sql = "update $table set ok_time='$date' where msg_id=$msg_id and user_id=$user_id";
	}

	if($sql!=""){
		$mysql->query($sql);
	}
}


//*************************************************
//フォーム(一覧)
//*************************************************
$mysql->query("select * from $table order by date");

echo <<<EOT
<form action=$_SERVER[PHP_SELF] method="POST">
<table border=1><tr><td>msg_id<td>user_id<td>read_time<td>ok_time<td>date<td colspan=2>

EOT;
$no=1;
while($r = $mysql->fetch()){
	$msg_id = $r["msg_id"];
	$user_id = $r["user_id"];
	$read_time = $r["read_time"];
	$ok_time = $r["ok_time"];
	$date = $r["date"];
	echo <<<EOT
<tr><td><input type="hidden" name="msg_id-$no" value="$msg_id" size ="15" >$msg_id
<td><input type="hidden" name="user_id-$no" value="$user_id" size ="15" >$user_id
<td><input type="text" name="read_time-$no" value="$read_time" size ="22">
<td><input type="text" name="ok_time-$no" value="$ok_time" size ="22">
<td><input type="text" name="date-$no" value="$date" size ="22">
<td><input type="submit" name= "submit_edit[$no]" value="変更">
<td><input type="submit" name= "submit_del[$no]" value="削除">

EOT;
$no++;

}
echo "</table>";

echo <<<EOT

</form>

<p>
<form action=$_SERVER[PHP_SELF] method="POST">
msg_id:<input type="text" name="msg_id" size ="15">
user_id:<input type="text" name="user_id" size ="15">
<input type="submit" name= "submit_add" value="追加">
</form>


<p>
<form action=$_SERVER[PHP_SELF] method="POST">
msg_id:<input type="text" name="msg_id" size ="15">
user_id:<input type="text" name="user_id" size ="15">
<input type="submit" name= "submit_read" value="既読">
</form>

<p>
<form action=$_SERVER[PHP_SELF] method="POST">
msg_id:<input type="text" name="msg_id" size ="15">
user_id:<input type="text" name="user_id" size ="15">
<input type="submit" name= "submit_ok" value="了解">
</form>
EOT;

$mysql->free();


?>
<footer></footer>
</body>
</html>
