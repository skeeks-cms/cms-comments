<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 24.06.2016
 */
namespace skeeks\cms\comments\widgets;
use skeeks\cms\comments\models\CmsComment;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

class CommentsFormWidget extends \yii\base\Widget
{
    public static $autoIdPrefix = 'CommentsFormWidget';

    public $reply_to;
    private $_comment;
    public function init()
    {
        parent::init();
        if (!$this->_comment) {
            $this->_comment = new CmsComment(['scenario' => (Yii::$app->user->isGuest) ? CmsComment::SCENARIO_GUEST : CmsComment::SCENARIO_USER]);
            /*$post = Yii::$app->getRequest()->post();
            if ($this->_comment->load($post) && ($this->reply_to == ArrayHelper::getValue($post, 'Comment.parent_id'))) {
                $this->_comment->validate();
            }*/
        }
        if ($this->reply_to) {
            $this->_comment->parent_id = $this->reply_to;
        }
    }
    public function run()
    {
        if (Yii::$app->user->isGuest && empty($this->_comment->username)) {
            $this->_comment->username = HtmlPurifier::process(Yii::$app->getRequest()->getCookies()->getValue('username'));
        }
        if (Yii::$app->user->isGuest && empty($this->_comment->email)) {
            $this->_comment->email = HtmlPurifier::process(Yii::$app->getRequest()->getCookies()->getValue('email'));
        }
        return $this->render('form', ['comment' => $this->_comment]);
    }
}