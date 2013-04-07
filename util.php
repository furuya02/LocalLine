<?php
class util{

	//コンストラクタ
	function util(){
	}


	function CreatePrimaryId($mysql,$table,$keyname){
		for($i=1;$i<9999999;$i++){
			$id = $i;
			$mysql->query("select * from $table where $keyname=$id");
			if($mysql->row_count()==0){
				return $i;
			}
		}
	}
}
?>