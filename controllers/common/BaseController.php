<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/28
 * Time: 16:50
 */

namespace app\controllers\common;


use app\models\User;
use app\services\UrlService;
use yii\web\Controller;
use yii\web\Cookie;

class BaseController extends Controller
{

    protected $key = 'rbac';

    public $current_user;

    protected $allowAllAction = [
        'user/login',
        'user/vlogin'
    ];

    public $ignore_url = [
        'error/forbidden' ,
        'user/vlogin',
        'user/login'
    ];

    protected $auto_cookies_name = 'rbac_user';
    /**
     * @param $key
     * @param string $default
     * @return array|mixed
     * 获取post方法参数的数据
     */
    public function post($key, $default = '') {
        return \Yii::$app->request->post($key,$default);
    }

    /**
     * @param \yii\base\Action $action
     * @return bool|void|\yii\web\Response
     * 拦截器，所有方法之前所使用的方法
     */
    public function beforeAction($action)
    {
        $login_status = $this->checkLoginStatus();
        if ( !$login_status && !in_array( $action->uniqueId,$this->allowAllAction )  ) {
            if(\Yii::$app->request->isAjax){
                $this->renderJSON([],"未登录,请返回用户中心",-302);
            }else{
                $this->redirect( UrlService::buildUrl("/user/login") );//返回到登录页面
            }
            return false;
        }
        return true;


    }

    /**
     * @return bool
     * 检查登录状态
     */
    public function checkLoginStatus() {
        $cookies = \Yii::$app->request->cookies;
        $auth_cookies = $cookies->get($this->auto_cookies_name);
        if(!$auth_cookies) {
            return false;
        }
        list($auth_token,$uid) = explode('#',$auth_cookies);
        if(!$auth_token || !$uid ) {
            return false;
        }
        if($uid && preg_match("/^\d+$/",$uid)) {
            $user = User::findOne(['id'=>$uid]);
            if(empty($user)) {
                return false;
            }
            if($auth_token != $this->createAuthToken($user['id'],$user['name'],$user['email'],$_SERVER['HTTP_USER_AGENT'])) {
                return false;
            }else {
                $this->current_user = $user;
                $view = \Yii::$app->view;
                $view->params['current_user'] = $user;
                return true;
            }
        }else {
            return false;
        }
    }

    /**
     * @param $userInfo
     * 设置登录的状态，写入cookies
     */
    public function createLoginStatus($userInfo) {
        $auth_token = $this->createAuthToken($userInfo['id'],$userInfo['name'],$userInfo['email'],$_SERVER['HTTP_USER_AGENT']);
        $cookies = \Yii::$app->response->cookies;
        $cookies->add(new Cookie([
            'name'=>$this->auto_cookies_name,
            'value'=>$auth_token."#".$userInfo['id']
        ]));
    }

    /**
     * @param $uid
     * @param $name
     * @param $email
     * @param $user_agent
     * @return string
     *用户相关信息的加密函数
     */
    private function createAuthToken($uid,$name,$email,$user_agent) {
        return md5($uid.$name.$email.$user_agent.$this->key);
    }

    /**
     * @param $key
     * @param string $default
     * @return array|mixed
     * 获取get参数的数据
     */
    public function get($key, $default = '') {
        return \Yii::$app->request->get($key,$default);
    }

    /**
     * @param array $data
     * @param string $msg
     * @param int $code
     * 封装返回的JSON数据，用于前端js ajax交互
     */
    public function renderJson($data = [],$msg = 'ok',$code = 200) {
        header("Content-type:application/json");
        echo json_encode([
            'data'=>$data,
            'msg'=>$msg,
            'code'=>$code,
            'req_id'=>uniqid()
        ]);
        return \Yii::$app->end();
    }
}