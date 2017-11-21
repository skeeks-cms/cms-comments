<?php
use skeeks\cms\comments\CommentsModule;
use skeeks\cms\comments\widgets\CommentsFormWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\timeago\TimeAgo;
?>
<div id="sx-comment-<?= $model->id; ?>" class="comment" data-key="<?= $model->id; ?>">
    <?php if (CommentsModule::getInstance()->displayAvatar): ?>
        <div class="avatar">
            <? if ($model->user) : ?>
                <a class="author" href="<?= $model->user->profileUrl; ?>">
                    <img src="<?= CommentsModule::getInstance()->renderUserAvatar($model->user) ?>"/>
                </a>
            <? else : ?>
                <img src="<?= CommentsModule::getInstance()->renderUserAvatar($model->user) ?>"/>
            <? endif; ?>

        </div>
    <?php endif; ?>
    <div class="comment-content<?= (CommentsModule::getInstance()->displayAvatar) ? ' display-avatar' : '' ?>">
        <div class="comment-header">
            <? if ($model->user) : ?>
                <a class="author" href="<?= $model->user->profileUrl; ?>"><?= Html::encode($model->getAuthor()); ?></a>
            <? else : ?>
                <?= Html::encode($model->getAuthor()); ?> (<?php echo \Yii::t('skeeks/comments', 'Guest'); ?>)
            <? endif; ?>

            <span class="time dot-left dot-right"><?
                try{
                    echo TimeAgo::widget([
                        'timestamp' => $model->created_at,
                        'language'  => \Yii::$app->language
                    ]);
                } catch (\Exception $e)
                {
                    echo TimeAgo::widget([
                        'timestamp' => $model->created_at,
                        'language'  => 'en'
                    ]);
                }

                ?></span>
        </div>
        <div class="comment-text">
            <?= Html::encode($model->content); ?>
        </div>
        <?php if ($nested_level < CommentsModule::getInstance()->maxNestedLevel): ?>
            <div class="comment-footer">
                <?php if (!CommentsModule::getInstance()->onlyRegistered || !Yii::$app->user->isGuest): ?>
                    <a class="reply-button" data-reply-to="<?= $model->id; ?>"
                       href="#"><?= \Yii::t('skeeks/comments', 'Reply') ?></a>
                    <!--<span class="dot-left"></span>
                    <a class="glyphicon glyphicon-thumbs-up"></a> <span>0</span> &nbsp;
                    <a class="glyphicon glyphicon-thumbs-down"></a> <span>0</span><span class="dot-left"></span>
                    -->
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($nested_level < CommentsModule::getInstance()->maxNestedLevel): ?>
        <?php if (!CommentsModule::getInstance()->onlyRegistered || !Yii::$app->user->isGuest): ?>
            <div class="reply-form<?= (CommentsModule::getInstance()->displayAvatar) ? ' display-avatar' : '' ?>">
                <?php if ($model->id == ArrayHelper::getValue(Yii::$app->getRequest()->post(), 'Comment.parent_id')) : ?>
                    <?= CommentsFormWidget::widget(['reply_to' => $model->id]); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($model->comments)) : ?>
            <div class="sub-comments">
                <?php $nested_level++; ?>
                <?php foreach ($model->comments as $model) : ?>
                    <div class="comment">
                        <?= $this->render('comment', compact('model', 'widget', 'nested_level')) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
