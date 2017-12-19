<?php
	require_once('./init.php');
	@$username=$_REQUEST['username'];
	@$password=$_REQUEST['password'];
	
	if(!$username){
		die('{"code":-1}');
	}
	if(!$password){
		die('{"code":-1}');
	}
	
	$sql = "insert into r_user values(null,'$username',md5($password),default)";
	
	$rs=sql_exec($sql);
	
	if(!$rs){
		die('{"code":-1}');
	}else{
		die('{"code":1}');
	}
?>