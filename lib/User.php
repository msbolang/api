<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once  __DIR__.'/ErrorCode.php';

class User{
    private $_db;
    
    public function __construct($db) {
        $this->_db = $db;
    }

    public function login($username,$password){
        
          if(empty($password)) {
            throw new Exception('密码不能为空',  ErrorCode::PASSWORD_CANNOT_EMPTY);
        }
        
        $password = md5($password);
        
         if(empty($username)) {
            throw new Exception('用户名不能为空',  ErrorCode::USERNAME_CANNOT_EMPTY);
        }
        
        $sql = 'SELECT * FROM `user` WHERE `username`=:username AND `pwd`=:password';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':username',$username);
        $stmt->bindParam(':password',$password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(empty($user)){
            throw new Exception('用户名或密码错误',  ErrorCode::USERNAME_OR_PASSWORD_INVALID);
        }
        unset($user['pwd']);
        return $user;
    }

    public function register($username,$password){
         if(empty($password)) {
            throw new Exception('密码不能为空',  ErrorCode::PASSWORD_CANNOT_EMPTY);
        }
        
         if(empty($username)) {
            throw new Exception('用户名不能为空',  ErrorCode::USERNAME_CANNOT_EMPTY);
        }
        
        if($this->_isUsernameExiste($username)){
            throw new Exception('用户名已存在',ErrorCode::USERNAME_EXISTS);
        };
       
        $sql = 'INSERT INTO `user`(`username`,`pwd`,`createdAt`) VALUES(:username,:password,:createdAt)';
        $createdAt = time();
        $password = md5($password);
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':username',$username);
        $stmt->bindParam(':password',$password);
        $stmt->bindParam(':createdAt',$createdAt);
        if(!$stmt->execute()){
            throw new Exception('注册失败',  ErrorCode::REGISTER_ERR);
        }
        return [
            'userId' => $this->_db->lastInsertId(), //pdo保存了最后插入的自增ID 取出来即可
            'userName' => $username
        ];
    }

    private function _isUsernameExiste($username) {
        $sql = 'SELECT * FROM `user` WHERE `username`=:username';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':username',$username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return !empty($result);
    }

}
?>