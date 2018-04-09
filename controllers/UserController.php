<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/28
 * Time: 17:06
 */

namespace app\controllers;


use app\controllers\common\BaseController;
use app\models\User;
use app\services\UrlService;
use yii\web\Controller;

class UserController extends BaseController
{


    //用户登录页面
    public function actionLogin(){
        return $this->render("login",[
            'host' => $_SERVER['HTTP_HOST']
        ]);
    }


    /**
     * @return \yii\web\Response
     * 伪登录方法
     */
    public function actionVlogin() {
        $uid = $this->get('uid',0);
        $redirect_url = UrlService::buildUrl('/');
        if(!$uid) {
            return $this->redirect($redirect_url);
        }
        $user = User::find()->where(['id'=>$uid])->one();
        if(empty($user)) {
            return $this->redirect($redirect_url);
        }
        $this->createLoginStatus($user);
        return $this->redirect($redirect_url);
    }
}