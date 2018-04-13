<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/4/13
 * Time: 10:41
 */

namespace app\controllers;


use yii\web\Controller;

class ErrorController extends Controller
{
    public function actionForbidden() {
        return $this->render('forbidden');
    }
}