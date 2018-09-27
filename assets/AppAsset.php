<?php
/**
 * @see http://www.yiiframework.com/
 *
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 *
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
       // 'css/swiper.css',
       // 'PhotoSwipe/dist/photoswipe.css',
       // 'PhotoSwipe/dist/default-skin/default-skin.css',
    ];
    public $js = [
      //  'js/block.submit.js',
      //  'js/swiper.js',
      //  'PhotoSwipe/dist/photoswipe.js',
      //  'PhotoSwipe/dist/photoswipe-ui-default.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        //'macgyer\yii2materializecss\assets\MaterializeAsset',
    ];
}
