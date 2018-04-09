<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/29
 * Time: 11:55
 */

namespace app\controllers;


use app\controllers\common\BaseController;
use yii\web\Controller;

class TestController extends BaseController
{

    public function actionPage1() {
        return $this->render("page1");
    }

    public function actionPage2() {
        return $this->render('page2');
    }
    public function actionPage3() {
        return $this->render("page3");
    }

    public function actionPage4() {
        return $this->render('page4');
    }

    public function actionPage5() {
        return $this->render("page5");
    }


}