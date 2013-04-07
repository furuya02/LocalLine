<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>送信テスト</title>
</head>
<body>
<body>
<h1>送信テスト</h1>

<form action="job.php" method="GET">
送信者:<input type="text" name="from_id"  value="1"><br>
宛先:<input type="text" name="to_id"  value="2,3"><br>
<textarea rows="5" cols="40" name="msg">何かメッセージを入力してください</textarea><br>
<input type="submit"  name="job_send"  value="job_send">

</form>

</body>
</html>
