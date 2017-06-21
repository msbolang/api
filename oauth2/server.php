<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$dsn      = 'mysql:dbname=api_oauth2;host=127.0.0.1';
$username = 'root';
$password = '123';
 
// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);
 
// Autoloading (composer is preferred, but for this example let's just do this)
//require_once('oauth2-server-php/src/OAuth2/Autoloader.php');
require_once(__DIR__.'/vendor/autoload.php');

OAuth2\Autoloader::register();
 
// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));
 
// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server($storage);
 
// Add the "Client Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
 
// Add the "Authorization Code" grant type (this is where the oauth magic happens)
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));