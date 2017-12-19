<?php
require_once('./init.php');
@$json=json_decode($_REQUEST['jsonStr'],true);
@$uid = $_SESSION['uid'];
$result = 1;
if($json!=null && count($json)!=0){
	$sql = "delete from r_sub where uid = $uid";
	sql_exec($sql);
	
	
	foreach($json as $k=>$v){
	$title=$v['audio_name'];
	$image=$v['img'];
	$hash=$v['hash'];
	$pnum=$v['bitrate'];
	$isSub=true;
	$sql="insert into r_sub values(null,$uid,'$title','$image','$hash',$pnum,$isSub)";
	$rs = sql_exec($sql);
	
	if(!$rs){
		$result=0;
		break;
	}else{
		$result=1;
	}
}

}



echo json_encode(["code"=>$result]);


//var_dump($json);
?>