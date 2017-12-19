<?php
require_once('./init.php');
@$uid = $_SESSION['uid'];
if($uid!=null){
	$sql = "select count(*) count from r_sub where uid=$uid";
	
	$rs = sql_exec($sql);
	
	if(mysqli_affected_rows($conn)!=0){
		$c = $rs[0]['count'];
		echo '{"count":'.$c.'}';
	}else{
		echo '{"count":0}';
	}
	
}else{
	echo '{"count":0}';
}