<?php
use skeeks\cms\comments\CommentsModule;
use skeeks\cms\comments\components\CommentsHelper;
use skeeks\cms\comments\models\CmsComment;
use skeeks\cms\comments\widgets\CommentsFormWidget;
use yii\timeago\TimeAgo;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model yeesoft\comments\models\Comment */
$commentsPage = Yii::$app->getRequest()->get("comment-page", 1);
$cacheKey = 'comment' . $model . $model_id . $commentsPage;
$cacheProperties = CommentsHelper::getCacheProperties($model, $model_id);
?>
<div class="comments">
    <?php if ($this->beginCache($cacheKey . '-count', $cacheProperties)) : ?>
        <h5><?= \Yii::t('skeeks/comments', 'All Comments') ?> (<?= CmsComment::activeCount($model, $model_id) ?>)</h5>
        <?php $this->endCache(); ?>
    <?php endif; ?>

    <?php if (!CommentsModule::getInstance()->onlyRegistered || !Yii::$app->user->isGuest): ?>
        <div class="comments-main-form">
            <?= CommentsFormWidget::widget(); ?>
        </div>
    <?php endif; ?>

    <?php if ($this->beginCache($cacheKey, $cacheProperties)) : ?>
        <?php
        Pjax::begin();
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'emptyText' => \Yii::t('skeeks/comments', 'No Comments'),
            'itemView' => function ($model, $key, $index, $widget) {
                $nested_level = 1;
                return $this->render('comment', compact('model', 'widget', 'nested_level'));
            },
            'options' => ['class' => 'comments'],
            'itemOptions' => ['class' => 'comment'],
            'layout' => '{items}<div class="text-center">{pager}</div>',
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'options' => ['class' => 'pagination pagination-sm'],
            ],
        ]);
        Pjax::end();
        $this->endCache();
        ?>
    <?php else: ?>
        <?php TimeAgo::widget(); ?>
    <?php endif; ?>
</div>