<?php
header('Content-Type:text/html;Charset=utf-8;');  
include __DIR__."/xcrypt.php";  

//密钥  
//$key = '12345678123456781234567812345678'; //256 bit  
//$key = '1234567812345678'; //128 bit  
$key = '12345678'; //64 bit  
  
//设置模式和IV  
//$m = new Xcrypt($key, 'cbc', 'auto');  
  $m = new Xcrypt($key, 'cbc','auto');  
//获取向量值  
//echo '向量：';  $m->getIV();

$code = $_GET['code'];

//解密  
$c = $m->decrypt($code, 'base64');  
  
echo '解密后：';  
var_dump($c);  

