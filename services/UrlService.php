<?php
/**
 * Created by PhpStorm.
 * User: yefy
 * Date: 2018/3/28
 * Time: 16:19
 */

namespace app\services;


use yii\helpers\Url;

class UrlService
{
    /**
     * @param $url
     * @param array $params
     * @return string
     * 返回一个内部链接
     */
    public static function buildUrl($url ,$params = []) {
        return Url::toRoute(array_merge([$url],$params));
    }

    /**
     * @return string
     * 返回一个空链接
     */
    public static function buildNullUrl() {
        return "javascript:void(0)";
    }
}