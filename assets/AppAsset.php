<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use app\services\UrlService;
use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
//    public $css = [
//        '/bootstrap/css/bootstrap.min.css',
//    ];
//    public $js = [
//        "/jquery/jquery.min.js",
//        "/bootstrap/js/bootstrap.min.js"
//    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function registerAssetFiles($view)
    {
        $release = "20180328";
        $this->css = [
            UrlService::buildUrl("/bootstrap/css/bootstrap.min.css",['v'=>$release]),
            UrlService::buildUrl("/css/app.css",['v'=>$release])
        ];

        $this->js = [
            //UrlService::buildUrl("/jquery/jquery-3.0.0.min.js"),
            UrlService::buildUrl("/bootstrap/js/bootstrap.min.js")
        ];

        parent::registerAssetFiles($view);
    }
}
