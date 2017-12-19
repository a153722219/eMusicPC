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
	
	
	$sql = "select * from r_user where username='$username' and password=md5($password)";
	
	$rs = sql_exec($sql);
	
	if(mysqli_affected_rows($conn)==0){
		die('{"code":-1}');
	}else{
		$_SESSION['username']=$username;
		$_SESSION['avatar']=$rs[0]['avatar'];
		$_SESSION['uid']=$rs[0]['id'];
		die('{"code":1}');
	}
?>