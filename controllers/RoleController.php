<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/29
 * Time: 10:32
 */

namespace app\controllers;


use app\controllers\common\BaseController;
use app\models\Access;
use app\models\Role;
use app\models\RoleAccess;
use app\models\User;
use app\services\UrlService;

class RoleController extends BaseController
{
    //列表页面
    public function actionIndex() {
        $list = Role::find()->orderBy(array('id'=>SORT_DESC))->all();
        return $this->render("index",['list'=>$list]);
    }

    /**
     *添加角色页面
     * get 请求
     */
     public function actionSet() {
        if(\Yii::$app->request->isGet) {
            $id = $this->get("id",0);
            $info = [];
            if( $id ){
                $info = Role::find()->where([ 'id' => $id ])->one();
            }
            return $this->render("set",[
                "info" => $info
            ]);
        }
        $id = $this->post('id',0);
        $name = $this->post('name');
        if(!$name) {
            $this->show('','请填写角色名',-1);
        }
        //查询是否存在角色名相等的记录
        $role_info = Role::find()
            ->where([ 'name' => $name ])->andWhere([ '!=','id',$id ])
            ->one();
        if($role_info) {
            $this->show('','该角色名已经存在',-1);
        }
        $info = Role::find()->where([ 'id' => $id ])->one();

        if( $info ){//编辑动作
            $role_model = $info;
        }else{//添加动作
            $role_model = new Role();
            $role_model->create_time = time();
        }
        $role_model->name = $name;
        $role_model->update_time = time();
        $role_model->save(0);
        return $this->show([],'操作成功',200);
     }

     public function actionAccess() {
        if(\Yii::$app->request->isGet) {
            $id = $this->get('id',0);
            $rebackUrl = UrlService::buildUrl('/role/index');
            if(!$id) {
                $this->redirect($rebackUrl);
            }
            $info = Role::find()->where(array('id'=>$id))->one();
            if(!$info) {
                $this->redirect($rebackUrl);
            }
            //取出所有的权限
            $access_list = Access::find()->where(array('status'=>1))->orderBy(array('id'=>SORT_DESC))->all();
            //取出已经分配的权限
            $role_access_list = RoleAccess::find()->where(array('role_id'=>$id))->asArray()->all();
            $access_ids = array_column($role_access_list,'access_id');
            return $this->render('access',[
                'info'=>$info,
                'access_ids'=>$access_ids,
                'access_list'=>$access_list
            ]);
          }
            $id = $this->post('id',0);
            $access_ids = $this->post('access_ids',[]);
            if(!$id) {
                $this->show([],'角色不存在',-1);
            }
            $info = Role::find()->where(array('status'=>1))->one();
            if(!$info) {
                $this->show([],'角色不存在',-1);
            }
            //取出已经分配的权限
            $role_access_list = RoleAccess::find()->where(array('role_id'=>$id))->asArray()->all();
            $assign_access_ids = array_column($role_access_list,'access_id');
            //删除
            $delete_access_ids = array_diff($assign_access_ids,$access_ids);
            if($delete_access_ids) {
                RoleAccess::deleteAll(array('role_id'=>$id,$delete_access_ids));
            }
            //添加
            $add_access_ids = array_diff($access_ids,$assign_access_ids);
            if($add_access_ids) {
                foreach ($add_access_ids as $item) {
                    $tmp_access_role = new RoleAccess();
                    $tmp_access_role->role_id = $id;
                    $tmp_access_role->create_time = time();
                    $tmp_access_role->access_id = $item;
                    $tmp_access_role->save(0);
                }
            }
            $this->show([],'操作成功');
     }
}