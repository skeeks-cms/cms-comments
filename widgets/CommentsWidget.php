<?php
namespace skeeks\cms\comments\widgets;
use skeeks\cms\comments\assets\CommentsAsset;
use skeeks\cms\comments\CommentsModule;
use skeeks\cms\comments\components\CommentsHelper;
use skeeks\cms\comments\models\CmsComment;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class CommentsWidget extends \yii\base\Widget
{
    public $model;
    public $model_id = 0;
    public function init()
    {
        parent::init();
        if ($this->model instanceof Model) {
            $this->model_id = $this->model->id;
            $this->model = $this->model->tableName();
        }
    }
    public function run()
    {
        $commentsAsset = CommentsAsset::register($this->getView());
        CommentModule::getInstance()->commentsAssetUrl = $commentsAsset->baseUrl;
        $model = $this->model;
        $model_id = $this->model_id;
        $comment = new CmsComment(compact('model', 'model_id'));
        $comment->scenario = (Yii::$app->user->isGuest) ? CmsComment::SCENARIO_GUEST : CmsComment::SCENARIO_USER;
        if ((!CommentModule::getInstance()->onlyRegistered || !Yii::$app->user->isGuest) && $comment->load(Yii::$app->getRequest()->post())) {
            if ($comment->validate() && Yii::$app->getRequest()->validateCsrfToken()
                && Yii::$app->getRequest()->getCsrfToken(true) && $comment->save()
            ) {
                if (Yii::$app->user->isGuest) {
                    CommentsHelper::setCookies([
                        'username' => $comment->username,
                        'email' => $comment->email,
                    ]);
                }
                Yii::$app->getResponse()->redirect(Yii::$app->request->referrer);
                return;
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CmsComment::find(true)->where([
                'model' => $model,
                'model_id' => $model_id,
                'parent_id' => NULL,
                'status' => CmsComment::STATUS_PUBLISHED,
            ]),
            'pagination' => [
                'pageSize' => CommentsModule::getInstance()->commentsPerPage,
                'pageParam' => 'comment-page',
                'pageSizeParam' => 'comments-per-page',
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => CommentsModule::getInstance()->orderDirection,
                ]
            ],
        ]);
        return $this->render('comments', compact('model', 'model_id', 'comment', 'dataProvider'));
    }
}