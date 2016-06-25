<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 24.06.2016
 */
namespace skeeks\cms\comments\assets;
use skeeks\cms\comments\CommentsModule;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\AssetBundle;
use yii\web\View;

class CommentsAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/comments/assets/source';
    public $css = [
        'css/comments.css',
    ];
    public $js = [
        'js/comments.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
        'skeeks\sx\assets\Custom',
    ];
    /**
     * Registers this asset bundle with a view.
     * @param \yii\web\View $view the view to be registered with
     * @return static the registered asset bundle instance
     */
    public static function register($view)
    {
        $commentsModuleID = CommentsModule::getInstance()->commentsModuleID;
        $getFormLink = Url::to(["/$commentsModuleID/default/get-form"]);

        $jsData = Json::encode([
            'commentsFormLink' => $getFormLink,
            'commentsModuleID' => $commentsModuleID,
        ]);
        $view->registerJs(<<<JS
new sx.classes.SkeekSComments($jsData);
JS
);
        return parent::register($view);
    }
}