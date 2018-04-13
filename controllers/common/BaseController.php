<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/28
 * Time: 16:50
 */

namespace app\controllers\common;


use app\models\Access;
use app\models\AppAccessLog;
use app\models\Role;
use app\models\RoleAccess;
use app\models\User;
use app\models\UserRole;
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

    public $privilege_urls = [];

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
     * @return bool
     * 拦截器，进行权限验证
     */
    public function beforeAction($action)
    {
        $login_status = $this->checkLoginStatus();
        if ( !$login_status && !in_array( $action->uniqueId,$this->allowAllAction )  ) {
            if(\Yii::$app->request->isAjax){
                $this->show([],"未登录,请返回用户中心",-302);
            }else{
                $this->redirect( UrlService::buildUrl("/user/login") );//返回到登录页面
            }
            return false;
        }
        /**
         * 操作日志记录到数据库
         */
        $get = $this->get(null);
        $post = $this->post(null);
        $app_access_log_model = new AppAccessLog();
        $app_access_log_model->uid = $this->current_user['id'] ? $this->current_user['id'] : 0;
        $app_access_log_model->target_url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : '';
        $app_access_log_model->create_time  = time();
        $app_access_log_model->ip = $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : '';
        $app_access_log_model->ua = $_SERVER['HTTP_USER_AGENT'] ? $_SERVER['HTTP_USER_AGENT'] : '';
        $app_access_log_model->query_params = json_encode(array_merge($get,$post));
        $app_access_log_model->save(0);

        /**
         * 判断权限的逻辑
         * 获取用户所选择的角色
         * 获取角色所对应的权限
         * 获取权限列表中的URL
         * 判断当前的URL是否在其中
         */
        if(!$this->checkPrivilege($action->uniqueId)) {
            $this->redirect(UrlService::buildUrl("/error/forbidden"));
            return false;
        }
        return true;

    }

    /**
     * @param $url
     * @return bool
     * 检查权限
     */
    public function checkPrivilege($url) {
        if($this->current_user && $this->current_user['is_admin']) {
            return true;
        }
        if(in_array($url,$this->ignore_url)) {
            return true;
        }
        return in_array($url,$this->getRolePrivilege());
    }

    /**
     * @param int $uid
     * @return array
     * 获取角色的权限链接
     */
    public function getRolePrivilege($uid = 0) {
        if(!$uid && $this->current_user) {
            $uid = $this->current_user->id;
        }
        if(!$this->privilege_urls) {
            $role_ids = UserRole::find()->where(array('uid'=>$uid))->select('role_id')->asArray()->column();
            if($role_ids) {
                //通过角色取出权限关系
                $access_ids = RoleAccess::find()->where(array('role_id'=>$role_ids))->select('access_id')->asArray()->column();
                //在权限表中取出所有的权限关系
                $list = Access::find()->where(array('id'=>$access_ids))->all();
                if($list) {
                    foreach ($list as $item) {
                        $tmp_urls = json_decode($item['urls']);
                        $this->privilege_urls = array_merge($this->privilege_urls,$tmp_urls);
                    }
                }
            }
        }
        return $this->privilege_urls;
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
    public function show($data = [],$msg = 'ok',$code = 200) {
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