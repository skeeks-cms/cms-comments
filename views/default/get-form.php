<?php
use skeeks\cms\comments\Comments;
use skeeks\cms\comments\widgets\CommentsFormWidget;
?>

<?php if (!\skeeks\cms\comments\CommentsModule::getInstance()->onlyRegistered || !Yii::$app->user->isGuest): ?>
    <?= CommentsFormWidget::widget(compact('reply_to')) ?>
<?php endif; ?>