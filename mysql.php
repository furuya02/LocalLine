<?php
class mysql{

	//データベースハンドル
	var $dbh = null;
	//クエリ結果
	var $result = null;

	//コンストラクタ
	function mysql($dbserver,$dbuser,$dbpass,$dbname){
		//MySQLへの接続
		$this->dbh = mysql_connect($dbserver,$dbuser,$dbpass);
		if(!$this->dbh){
			die("faild mysql_connect()");
		}
		//DB接続
		if(!mysql_selectdb($dbname,$this->dbh)){
			die("faild mysql_selectdb()");
		}
	}

	//リクエスト発行
	function query($sql){
		if(!$this->result = mysql_query($sql,$this->dbh)){
			die("faild mysql_query()");
			return false;
		}
		return true;
	}

	//１行取得
	function fetch(){
		return mysql_fetch_array($this->result);
	}

	//クエリ結果の初期化
	function free(){
		mysql_free_result($this->result);
	}

	//行数取得
	function row_count(){
		return mysql_num_rows($this->result);
	}

	//カラム数取得
	function col_count(){
		return mysql_num_fields($this->result);
	}
}
?>