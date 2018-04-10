<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/4/9
 * Time: 15:33
 */

namespace app\services;


class StaticService
{
    /**
     * @param $type
     * @param $path
     * @param $depend
     * 引入静态文件
     */
    public static function includeStaticFile($type,$path,$depend) {
        $release_version = defined("RELEASE_VERSION") ? RELEASE_VERSION : "20180409";

        if(stripos($path,"?") !== false) {
            $path = $path . "&version=".$release_version;
        }else {
            $path = $path . "?version=".$release_version;
        }
        if($type == 'css') {
            return \Yii::$app->getView()->registerCssFile($path,['depend'=>$depend]);
        }else {
            return \Yii::$app->getView()->registerJsFile($path,['depend'=>$depend]);
        }
    }

    /**
     * @param $path
     * @param $depend
     * 引入Css静态文件
     */
    public static function includeCssFile($path,$depend) {
        self::includeStaticFile('css',$path,$depend);
    }

    /**
     * @param $path
     * @param $depend
     * 引入JS静态文件
     */
    public static function includeJsFile($path,$depend) {
        self::includeStaticFile('js',$path,$depend);
    }
}