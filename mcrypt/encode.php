<?php
header('Content-Type:text/html;Charset=utf-8;');
include __DIR__ . "/mcrypt.php";
$a = 'userNane';
//密钥  
//$key = '12345678123456781234567812345678'; //256 bit  
//$key = '1234567812345678'; //128 bit  
$key = '12345678'; //64 bit  
//设置模式和IV  
//$m = new Xcrypt($key, 'cbc', 'auto');  
$m = new Mcrypten($key, 'cbc', 'auto');
//获取向量值  //echo '向量：';  //var_dump($m->getIV());  
//加密 
$b = $m->encrypt($a, 'base64');
//传送加密后的$b
?>

<!doctype html>
<html>
<!-- Copyright 2015 The Chromium Authors. All rights reserved.
     Use of this source code is governed by a BSD-style license that can be
     found in the LICENSE file. -->
<head>
  <meta charset="utf-8">
  <meta name="google" value="notranslate">
</head>
<body>
    <a href="http://api.com/mcrypt/decode.php?user=<?=$a?>&code=<?=$b?>" target="_blank">去解密</a>
</body>
</html>
