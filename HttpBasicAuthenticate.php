<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
error_log('1',3,$_SERVER['DOCUMENT_ROOT'].'/log/log.txt');
if(isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])){
    $username = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
error_log('$username='.$username."\r\t",3,$_SERVER[DOCUMENT_ROOT].'/log.txt');
    if ($username == 'wang' && $password == '123456'){
        return true;
    }
}

header('WWW-Authenticate: Basic realm="Git Server"');
header('HTTP/1.0 401 Unauthorized');