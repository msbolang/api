<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$pdo = new PDO('mysql:host=127.0.0.1;dbname=api','root','123');
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,FALSE);//不检查数据类型
return $pdo;
