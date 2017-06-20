<?php
error_reporting(3);
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require __DIR__ . '/../lib/Article.php';
require __DIR__ . '/../lib/User.php';
require __DIR__ . '/../lib/db.php';

class Restful {

    private $_article;
    private $_user;

    /**
     * 请求类型
     * @var type 
     */
    private $_requestMethod;

    /**
     * 请求名称
     * @var type 
     */
    private $_requestName;

    /**
     * 请求的资源ID
     * @var type 
     */
    private $_id;

    /**
     * 允许请求的资源列表
     * @var type array
     */
    private $_allowResources = ['users', 'articles'];

    /**
     * 允许请求的方法
     * @var type ARRAY
     */
    private $_allRequestMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
    private $_statusCodes = [
        200 => 'Ok',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Server Internal Error'
    ];

    public function __construct(User $_user, Article $_article) {
        $this->_user = $_user;
        $this->_article = $_article;
    }

    public function run() {
        try {
            $this->_setupRequestMethod();
            $this->_setupResource();
            return $this->handle_();
        } catch (Exception $e) {
            $this->_json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    private function _json($array, $code = 0) {
        if ($code > 0 && $code != 200 && $code != 204) {
            header("HTTP/1.1 " . $code . " " . $this->_statusCodes[$code]);
        }
        header('Content-Type:application/json;charset=utf-8');
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
     * 初始化请求方法
     */
    private function _setupRequestMethod() {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        if (!in_array($this->_requestMethod, $this->_allRequestMethods)) {
            throw new Exception('请求方法不被允许', 405);
        }
    }

    /**
     * 初始化请求资源
     */
    private function _setupResource() {
        //   $this->err($_SERVER);
        //    $path = $_SERVER['PATH_INFO'];

        if (strstr($_SERVER['REQUEST_URI'], "/restful/")) {
            $this->_requestName = str_replace('/restful/', '', $_SERVER['REQUEST_URI']);
            if (strstr($this->_requestName, "/")) {
                $paths_ = explode('/', $this->_requestName);
                $this->_requestName = $paths_[0];
                $this->_id = $paths_[1];
            }
            if (!in_array($this->_requestName, $this->_allowResources)) {
                throw new Exception('请求资源不被允许', 403);
            }
        } else {

            echo '来调试 php7没有显示path_info';
            //     $path = $_SERVER['PATH_INFO'];
        }
    }

    private function err($variable) {
        echo '<pre>';
        if (is_array($variable)) {
            print_r($variable);
        } else if (is_object($variable)) {
            var_dump($variable);
        }
        exit;
    }

    private function handle_() {
        $str = 'handle' . $this->_requestName;
        return $this->_json($this->$str());
    }

    private function handleusers() {
        echo 2;exit;
        if ($this->_requestMethod != 'POST') {
            throw new Exception('请求方法不被允许', 405);
        }
        $body = $this->_getBodyParams();
        if (empty($body['username'])) {
            throw new Exception('用户名不能为空', 400);
        }
        if (empty($body['password'])) {
            throw new Exception('用户名密码不能为空', 400);
        }
        $data = $this->_user->register($body['username'], $body['password']);
        return $data;
    }

    private function handlearticles() {
        switch ($this->_requestMethod) {
            case 'POST':
                return $this->_handleArticleCreate();
            case 'PUT':
                return $this->_handleArticleEdit();
            case 'DELETE':
                return $this->_handleArticleDelete();
            case 'GET':
                if (empty($this->_id)) {
                    return $this->_handleArticleList();
                } else {
                    return $this->_handleArticleView();
                }
        }
    }

    private function _handleArticleCreate() {
        $body = $this->_getBodyParams();
        if(empty($body['title'])){
            throw new Exception('文章标题不能为空',400);
        }
        if(empty($body['content'])){
            throw new Exception('文章内容不能为空',400);
        }
        $user = $this->_userLogin($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
    }

    
    private function _userLogin($PHP_AUTH_USER,$PHP_AUTH_PW){
        try {
            return $this->_user->login($PHP_AUTH_USER,$PHP_AUTH_PW);
        } catch (Exception $ex) {
            if(in_array($ex->getCode())){

                }
        }
    }
    
    private function _handleArticleEdit() {
        
    }

    private function _handleArticleDelete() {
        
    }

    private function _handleArticleList() {
        
    }

    private function _handleArticleView() {
        
    }

    /**
     * 获取请求参数
     * @return type
     * @throws Exception
     */
    private function _getBodyParams() {
        $raw = file_get_contents('php://input');
        if (empty($raw)) {
            throw new Exception('请求参数错误', 400);
        }
        return json_decode($raw, true);
    }

}

$article = new Article($pdo);
$user = new User($pdo);
$restful = new Restful($user, $article);
$restful->run();
?>
