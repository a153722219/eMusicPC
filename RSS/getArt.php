<?php
header('content-type:application:json;charset=utf8'); 
header('Access-Control-Allow-Origin:http://127.0.0.1:8080'); 
header('Access-Control-Allow-Credentials:true'); 
@$url=$_REQUEST['url'];
$cookie_file =  tempnam('./temp','cookie');
$ch = curl_init();  
$timeout = 10; // set to zero for no timeout  
curl_setopt ($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER, 0);  
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Maxthon/4.0 Chrome/30.0.1599.101 Safari/537.36');  
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);  
$contents = curl_exec($ch);  
curl_close($ch);
$contents = mb_convert_encoding($contents, "gb2312", "utf-8");

//$contents = file_get_contents($url); 
//如果出现中文乱码使用下面代码 
//$getcontent = iconv("gb2312", "utf-8",$contents); 
$regx = "/var msgList = ({.*?]});/";
$a=[];
preg_match($regx,$contents,$a);

echo $a[1];
?>