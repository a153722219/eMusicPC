<?php
	require_once('./init.php');
	@$username=$_REQUEST['username'];
	
	$sql = "select * from r_user where username='$username'";
	
	$rs=sql_exec($sql);
	
	if(mysqli_affected_rows($conn)==1){
		die('{"code":-1}');
	}else{
		die('{"code":1}');
	}