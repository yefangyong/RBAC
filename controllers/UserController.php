<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/28
 * Time: 17:06
 */

namespace app\controllers;


use app\controllers\common\BaseController;
use app\models\Role;
use app\models\User;
use app\models\UserRole;
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

    /**
     * @return string
     * 用户和角色的添加和编辑
     */
    public function actionSet() {
        //如果是get请求只显示页面
        if(\Yii::$app->request->isGet) {
            $id = $this->get('id',0);
            $info = [];
            if($id) {
                $info = User::find()->where(array('id'=>$id,'status'=>1))->one();
            }
            //取出所有的角色
            $role_list = Role::find()->orderBy(array('id'=>SORT_DESC))->all();
            //取出所有用户的角色
            $user_role_list = UserRole::find()->where(array('uid'=>$id))->asArray()->all();
            $related_role_ids = array_column($user_role_list,'role_id');
            return $this->render('set',[
                'info'=>$info,
                'role_list'=>$role_list,
                'related_role_ids'=>$related_role_ids
            ]);
        }else {
            $id  = intval($this->post('id',0));
            $name = trim($this->post('name'));
            $email = trim($this->post('email'));
            $role_ids = $this->post('role_ids'); //选中的角色ID
            if(mb_strlen($name,'utf8') < 1 || mb_strlen($name,'utf8') > 20) {
                $this->show([],'请输入合法的用户名',-1);
            }
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)) {
                $this->show([],'请输入合法的邮箱',-2);
            }
            //查询该邮箱是否存在
            $has_in = User::find()->where(array('email'=>$email))->andWhere(['!=','id',$id])->one();
            if($has_in) {
                $this->show([],'该邮箱已经存在');
            }
            $user = User::find()->where(array('id'=>$id))->one();
            if($user) {
                $user_model = $user;
            }else {
                $user_model = new User();
                $user_model->status = 1;
                $user_model->create_time =time();
            }
            $user_model->name = $name;
            $user_model->update_time = time();
            $user_model->email = $email;
            if($user_model->save(0)) { //如果用户信息保存成功，就保存用户和角色的关系
                //找出删除的角色
                //A表示现在已有的角色集合，界面传递的角色是B，角色集合A不在角色集合B当中，就应该删除
                $user_role_list = UserRole::find()->where(array('uid'=>$user_model->id))->all();
                $related_role_ids = [];
                if($user_role_list) {
                    foreach ($user_role_list as $_item) {
                        $related_role_ids[] = $_item['role_id'];
                        if(!in_array($_item['role_id'],$role_ids)) {
                            $_item->delete();
                        }
                    }
                }
                //找出添加的角色
                //A集合表示现在已有的角色集合，界面传递的角色是B，角色集合B中的角色不在角色集合A中就应该添加
                if($role_ids) {
                    foreach ($role_ids as $role_id) {
                        if(!in_array($role_id,$related_role_ids)) {
                            $model_user_role = new UserRole();
                            $model_user_role->role_id = $role_id;
                            $model_user_role->uid = $user_model->id;
                            $model_user_role->create_time = time();
                            $model_user_role->save(0);
                        }
                    }
                }
            }
            $this->show([],'操作成功');
        }
    }

    public function actionIndex() {
        //查询所有的用户
        $user_list = User::find()->where(array('status'=>1))->all();
        return $this->render('index',[
           'user_list'=>$user_list
        ]);
    }




















}