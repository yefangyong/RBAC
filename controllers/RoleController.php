<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/29
 * Time: 10:32
 */

namespace app\controllers;


use app\controllers\common\BaseController;
use app\models\Role;
use app\models\User;

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
}