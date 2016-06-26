<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 31.05.2015
 */
namespace skeeks\cms\comments\controllers;

use skeeks\cms\comments\models\CmsComment;
use skeeks\cms\modules\admin\controllers\AdminModelEditorController;
use yii\helpers\ArrayHelper;

/**
 * Class AdminCommentController
 * @package skeeks\cms\comments\controllers
 */
class AdminCommentController extends AdminModelEditorController
{
    public function init()
    {
        $this->name                   = \Yii::t('skeeks/comments', 'Comments');
        $this->modelShowAttribute      = "id";
        $this->modelClassName          = CmsComment::className();

        parent::init();

    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [

            'create' =>
            [
                'visible'    => false
            ]
        ]);
    }

}
