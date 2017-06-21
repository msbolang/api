<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__ . '/ErrorCode.php';

class Article {
    /*
     * 数据库句柄
     *      
     */

    private $_db;

    public function __construct($_db) {
        $this->_db = $_db;
    }

//    创建文章
    public function create($title, $content, $userId) {
        if (empty($title)) {
            throw new Exception('文章标题不能为空！', ErrorCode::ARTICLE_TITLE_CONNOT_EMPTY);
        }
        $sql = "insert into `article`(`title`,`content`,`userId`,`createdAt`) values(:title,:content,:userId,:createdAt)";
        $createdAt = time();
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':createdAt', $createdAt);
        if (!$stmt->execute()) {
            throw new Exception("发表文章失败", ErrorCode::ARTICLE_CREATE_FAIL);
        }

        return [
            'articleId' => $this->_db->lastInsertId(),
            'title' => $title,
            'content' => $content,
            'userId' => $userId,
            'createdAt' => $createdAt
        ];
    }

    /**
     * 查看一篇文章
     * @param type $articleId 
     * @return type array
     * @throws Exception
     */
    public function view($articleId) {
       
        if (empty($articleId)) {
            throw new Exception('文章ID不能为空', ErrorCode::ARTICLE_ID_CANNOT_EMPTY);
        }
            $sql = "SELECT * FROM `article` WHERE `id`=:id";
             $stmt = $this->_db->prepare($sql);
             $stmt->bindParam(":id", $articleId);
            $stmt->execute();
            $article = $stmt->fetch(PDO::FETCH_ASSOC);
      
            if (empty($article)) {
                throw new Exception('文章不存在', ErrorCode::ARTICLE_NOT_FOUND);
            }
            return $article;
        
    }

  
    /**
     * 编辑文章
     * @param type $articleId
     * @param type $title
     * @param type $content
     * @param type $userId
     * @return type 
     * @throws Exception
     */
    public function edit($articleId, $title, $content, $userId) {
        $article = $this->view($articleId);

        if ($article['userId'] !== $userId) {
            throw new Exception('您无权编辑该文章', ErrorCode::PERMISSION_DENIED);
        }
        $title = empty($title) ? $article['title'] : $title;
        $content = empty($content) ? $article['content'] : $content;
        if ($title === $article['title'] && $content === $article['content']) {
            return $article;
        }
        $sql = 'UPDATE `article` SET `title`=:title, `content`=:content WHERE `id`=:id';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $articleId);
        if (!$stmt->execute()) {
            throw new Exception('文章编辑失败', ErrorCode::ARTICLE_EDIT_FAIL);
        }
        return [
            'articleId' => $articleId,
            'title' => $title,
            'content' => $content,
            'userId' => $userId
        ];
    }

    //删除
    public function delete($articleId, $userId) {
        $article = $this->view($articleId);
        if($article['userId']!==$userId){
                 throw new Exception('文章编辑失败',ErrorCode::PERMISSION_DENIED);
        }
        $sql = 'DELETE FROM `article` WHERE `id`=:id AND `userId`=:userId';
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':id',$articleId);
        $stmt->bindParam(':userId',$userId);
        if (FALSE===$stmt->execute()){
            throw new Exception('删除失败',  ErrorCode::ARTICLE_DELETE_FAIL);
        }
        return true;
    }

    /**
     * 
     * @param type $userId
     * @param type $page
     * @param type $size
     * @return type
     * @throws Exception
     */
    public function getList($userId, $page = 1, $size = 10) {
        if($size>100){
            throw new Exception("分页大小最大为100",  ErrorCode::PAGE_SIZE_TO_BIG);
        }
        $sql = 'SELECT * FROM `article` WHERE `userId` =:userId LIMIT :limit,:offset';
        $limit = ($page -1)*$size;
        $limit = $limit < 0 ? 0 : $limit;
        $stmt = $this->_db->prepare($sql);
        $stmt->bindParam(':userId',$userId);
        $stmt->bindParam(':limit',$limit);
        $stmt->bindParam(':offset',$size);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $data;
        
    }

}
