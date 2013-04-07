<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>t_msg操作</title>
<link rel="stylesheet" href="sample.css">
<script src="sample.js"></script>
</head>
<body>
<header></header>
<nav></nav>

<?php
require_once 'mysql.php';
require_once 'util.php';

$table = "t_msg";
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
		$msg_id = key($_POST["submit_del"]);
		$sql = "delete from $table where msg_id=$msg_id";
	}else if(isset($_POST["submit_edit"])){//変更
		$msg_id = key($_POST["submit_edit"]);
		$user_id = $_POST["user_id-".$msg_id];
		$msg = $_POST["msg-".$msg_id];
		$send_time = $_POST["send_time-".$msg_id];
		$del_time = $_POST["del_time-".$msg_id];
		$sql = "update $table set user_id='$user_id',msg='$msg',send_time='$send_time',del_time='$del_time',date='$date' where msg_id=$msg_id";
	}else if(isset($_POST["submit_add"])){ //追加
		$msg_id = $util->CreatePrimaryId($mysql,"$table","msg_id");
		$user_id = $_POST["user_id"];
		$msg = $_POST["msg"];
		$sql = "insert into $table values($msg_id,$user_id,'$msg','$date','0','$date')";
	}else if(isset($_POST["submit_flg_set"])){//削除フラグ有効
		$msg_id = $_POST["msg_id"];
		$sql = "update $table set del_time='$date' where msg_id=$msg_id";
	}else if(isset($_POST["submit_flg_unset"])){//削除フラグ無効
		$msg_id = $_POST["msg_id"];
		$sql = "update $table set del_time='0' where msg_id=$msg_id";
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
<table border=1><tr><td>msg_id<td>user_id<td>msg<td>send_date<td>del_date<td>date<td colspan=2>
EOT;

while($r = $mysql->fetch()){
	$id = $r["msg_id"];
	$user_id = $r["user_id"];
	$msg = $r["msg"];
	$send_time = $r["send_time"];
	$del_time = $r["del_time"];
	$date = $r["date"];
printf("<tr><td>%07d",$id);
	echo <<<EOT
<td><input type="text" name="user_id-$id" value="$user_id" size ="10">
<td><textarea name="msg-$id" rows="4" cols="40">$msg</textarea>
<td><input type="text" name="send_time-$id" value="$send_time" size ="18">
<td><input type="text" name="del_time-$id" value="$del_time" size ="18">
<td><input type="text" name="date-$id" value="$date" size ="18">
<td><input type="submit" name= "submit_edit[$id]" value="変更">
<td><input type="submit" name= "submit_del[$id]" value="削除">

EOT;

}

echo "</table>";

echo <<<EOT
<p>
user_id:<input type="text" name="user_id" size ="15">
msg:<textarea name="msg" rows="4" cols="40"></textarea>
<input type="submit" name= "submit_add" value="追加">

<p>

msg_id:<input type="text" name="msg_id" size ="15">
<input type="submit" name= "submit_flg_set" value="削除フラグ有効">
<input type="submit" name= "submit_flg_unset" value="削除フラグ無効ト">


</form>
EOT;


$mysql->free();


?>
<footer></footer>
</body>
</html>
