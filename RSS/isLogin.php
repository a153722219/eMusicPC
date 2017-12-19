<?php
	require_once('./init.php');
	@$un=$_SESSION['username'];
	@$ar=$_SESSION['avatar'];
	@$uid=$_SESSION['uid'];
	if(!$un){
		die('{"code":-1}');
	}else{
		die('{"code":1,"username":"'.$un.'","avatar":"'.$ar.'","uid":'.$uid.'}');
	}
	