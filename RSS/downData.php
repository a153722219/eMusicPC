<?php
require_once('./init.php');
@$uid = $_SESSION['uid'];
if($uid!=null){
	$sql = "select title audio_name,image img,hash,pnum,isSub from r_sub where uid=$uid";
	
	$rs = sql_exec($sql);
	
	if(mysqli_affected_rows($conn)!=0){
		echo json_encode($rs);
	}else{
		echo '[]';
	}
	
}else{
	echo '[]';
}