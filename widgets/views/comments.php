<?php
use skeeks\cms\comments\CommentsModule;
use skeeks\cms\comments\components\CommentsHelper;
use skeeks\cms\comments\models\CmsComment;
use skeeks\cms\comments\widgets\CommentsFormWidget;
use yii\timeago\TimeAgo;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $widgetComments \skeeks\cms\comments\widgets\CommentsWidget */
$widgetComments = $this->context;

$commentsPage = Yii::$app->getRequest()->get("comment-page", 1);
$cacheKey = 'comment' . $model . $model_id . $commentsPage . \Yii::$app->language;
$cacheProperties = CommentsHelper::getCacheProperties($model, $model_id);
?>
<div class="comments">
    <?php Pjax::begin(); ?>
    <?php /*if ($this->beginCache($cacheKey . '-count', $cacheProperties)) : */?>
        <h5><?= \Yii::t('skeeks/comments', 'All Comments') ?> (<?= CmsComment::activeCount($model, $model_id) ?>)</h5>
        <?php /*$this->endCache(); */?><!--
    --><?php /*endif; */?>

    <?php if (!CommentsModule::getInstance()->onlyRegistered || !Yii::$app->user->isGuest): ?>
        <div class="comments-main-form">
            <?= CommentsFormWidget::widget(); ?>
        </div>
    <?php endif; ?>

    <?php /*if ($this->beginCache($cacheKey, $cacheProperties)) : */?><!--
        --><?php

        echo ListView::widget(\yii\helpers\ArrayHelper::merge([
            'dataProvider' => $dataProvider,
            'emptyText' => \Yii::t('skeeks/comments', 'No Comments'),
            'itemView' => function ($model, $key, $index, $widget) use ($widgetComments) {
                $nested_level = 1;
                return $this->render($widgetComments->itemViewFile, compact('model', 'widget', 'nested_level'));
            },
            'options'       => ['class' => 'comments'],
            'itemOptions'   => [
                'tag' => false
            ],
            'layout' => '{items}<div class="text-center">{pager}</div>',
            'pager' => [
                'class' => yii\widgets\LinkPager::className(),
                'options' => ['class' => 'pagination pagination-sm'],
            ],
        ], $widgetComments->listViewOptions));

        /*$this->endCache();
        */?>
    <?php /*else: */?><!--
        <?php /*TimeAgo::widget(); */?>
    --><?php /*endif; */?>
    <?php Pjax::end(); ?>
</div>