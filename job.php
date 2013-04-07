<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>送信テスト</title>
</head>
<?php
require_once 'mysql.php';
require_once 'util.php';

$dbuser = "localline";
$dbpass = "localline";
$dbserver = "localhost";
$dbname = "localline";
$mysql = new mysql($dbserver,$dbuser,$dbpass,$dbname);
$util = new util();


if($_SERVER['REQUEST_METHOD']=='GET'){

	date_default_timezone_set('Asia/Tokyo');
	//$now = new DateTime();
	$date = (new DateTime())->format('Y-m-d H:i:s');

	if(isset($_GET["job_send"])){//メッセージ送信
		//メッセージ追加
		$user_id = $_GET["from_id"];
		$msg = $_GET["msg"];
		$msg_id  = $util->CreatePrimaryId($mysql,'t_msg','msg_id');
		$sql = "insert into t_msg values($msg_id,$user_id,'$msg','$date','0','$date')";
		$mysql->query($sql);

		$str = $_GET["to_id"];
		$id_list = explode( ",", $str );
		foreach ($id_list as $user_id){
			$sql = "insert into t_to values($msg_id,$user_id,'0','0','$date')";
			$mysql->query($sql);
		}
	
	}else if(isset($_GET["job_del"])){//削除
		$user_id = $_GET["from_id"];
		$msg_id = $_GET["msg_id"];
		$sql = "update t_msg set del_time='$date' where msg_id=$msg_id";
		$mysql->query($sql);

	}else if(isset($_GET["job_undel"])){//削除取り消し
		$user_id = $_GET["from_id"];
		$msg_id = $_GET["msg_id"];
		$sql = "update t_msg set del_time='0' where msg_id=$msg_id";
		$mysql->query($sql);
	
	}else if(isset($_GET["job_read"])){//既読
		$user_id = $_GET["from_id"];
		$msg_id = $_GET["msg_id"];
		$sql = "update t_to set read_time='$date' where msg_id=$msg_id and user_id=$user_id";
		$mysql->query($sql);
		
	}else if(isset($_GET["job_ok"])){//了解
		$user_id = $_GET["from_id"];
		$msg_id = $_GET["msg_id"];
		$sql = "update t_to set ok_time='$date' where msg_id=$msg_id and user_id=$user_id";
		$mysql->query($sql);
	}
}
