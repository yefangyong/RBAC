<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/4/11
 * Time: 15:22
 */

namespace app\controllers;


use app\controllers\common\BaseController;
use app\models\Access;

class AccessController extends BaseController
{
    /**
     * @return string
     * 权限列表
     */
    public function actionIndex() {
        $access_list = Access::find()->where(array('status'=>1))->orderBy(array('id'=>SORT_DESC))->all();
        return $this->render('index',['list'=>$access_list]);
    }

    /**
     * @return string|void
     * 编辑或者添加权限
     */
    public function actionSet() {
        if(\Yii::$app->request->isGet) {
            $id = $this->get('id',0);
            $info = [];
            if($id) {
                $info = Access::find()->where(array('status'=>1,'id'=>$id))->one();
            }
            return  $this->render('set',['info'=>$info]);
        }else {
            $id = $this->post('id',0);
            $title = trim($this->post('title'));
            $urls = trim($this->post('urls'));
            if(mb_strlen($title) < 1 && mb_strlen($title) > 20) {
                $this->show([],'请输入合法的标题',-1);
            }
            if(!$urls) {
                 $this->show([],'请输入Url',-1);
            }
            $urls = explode("\n",$urls);
            if(!$urls) {
                 $this->show([],'请输入url',-1);
            }
            //查询同一标题是否存在
            $has_in = Access::find()->where(array('title'=>$title))->andWhere(['!=','id',$id])->one();
            if($has_in) {
                 $this->show([],'标题已经存在',-1);
            }
            $access = Access::find()->where(array('id'=>$id))->one();
            if($access) {
                $access_model = $access;
            }else {
                $access_model = new Access();
                $access_model->create_time = time();
            }
            $access_model->urls = json_encode($urls);
            $access_model->title = $title;
            $access_model->status = 1;
            $access_model->save(0);
            $this->show([],'操作成功');
        }
    }
}