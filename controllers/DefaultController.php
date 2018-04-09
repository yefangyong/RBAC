<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/29
 * Time: 12:23
 */

namespace app\controllers;


use app\controllers\common\BaseController;
use yii\web\Controller;

class DefaultController extends BaseController
{
    //我才是默认首页
    public function actionIndex(){
        return $this->render("index");
    }
}