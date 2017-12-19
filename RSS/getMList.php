<?php
header('content-type:application:json;charset=utf8'); 
header('Access-Control-Allow-Origin:http://127.0.0.1:8080'); 
header('Access-Control-Allow-Credentials:true'); 
@$key=$_REQUEST['key'];
@$page = $_REQUEST['page'];

if(!$page){
	$page=1;
}
$url="http://songsearch.kugou.com/song_search_v2?keyword=$key&page=$page&pagesize=8&userid=-1&clientver=&platform=WebFilter&tag=em&filter=2&iscorrection=1&privilege_filter=0";
@$cookie_file =  tempnam('./temp','cookie');
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

//$contents = mb_convert_encoding($contents, "gb2312", "utf-8");

$rs = json_decode($contents,true);
//http://so.service.kugou.com/get/complex?word=关键字
$url = "http://so.service.kugou.com/get/complex?word=$key";
curl_setopt ($ch, CURLOPT_URL,$url);

$contents = curl_exec($ch);

$rs2 = json_decode($contents,true);

$sData=[];

if($rs2['data']!=""){
	$sData=$rs2['data'][1];
	$sId=$sData['singerid'];
	//http://www.kugou.com/singer/6333.html
	$url = "http://www.kugou.com/singer/$sId.html";
	curl_setopt ($ch, CURLOPT_URL,$url);
	$contents = curl_exec($ch);
	$contents=preg_replace("/[<\/>\t\n\r]+/","",$contents);
	//$contents = mb_convert_encoding($contents, "gb2312", "utf-8");
	/*
	strongdivp歌手简介pem title="更多介绍" class="more_detail"更多介绍
	*/
	//$regx = '/strongdivp[.*]pem title="更多介绍" class="more_detail"更多介绍em/';
	$regx = '/strongdivp.*pem title/';
	$a=[];
	preg_match($regx,$contents,$a);
	$str = str_replace("strongdivp","",$a[0]);
	$str = str_replace("pem title","",$str);
	
	$sData['sinfo']=$str;
	//echo $str;
}

$rs['sData']=$sData;

curl_close($ch);

echo json_encode($rs);


