<?php
use skeeks\cms\comments\assets\CommentsAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $modelyeesoft\comments\models\Comment */
?>

<?php
$col12 = \skeeks\cms\comments\CommentsModule::getInstance()->gridColumns;
$col6 = (int) ($col12 / 2);
$formID = 'comment-form' . (($comment->parent_id) ? '-' . $comment->parent_id : '');
$replyClass = ($comment->parent_id) ? 'comment-form-reply' : '';
?>

<div class="comment-form <?= $replyClass ?> clearfix">

    <?php
    $form = ActiveForm::begin([
        'action' => NULL,
        'options' =>
        [
            'data-pjax' => '1',
        ],
        //'enableAjaxValidation' => true,
        //'enableClientValidation' => false,
        'validateOnBlur' => false,
        'validationUrl' => Url::to(['/' . \skeeks\cms\comments\CommentsModule::getInstance()->commentsModuleID . '/validate/index']),
        'id' => $formID,
        'class' => 'com-form'
    ]);
    if ($comment->parent_id) {
        echo $form->field($comment, 'parent_id')->hiddenInput()->label(false);
    }
    ?>
    <?php if (\skeeks\cms\comments\CommentsModule::getInstance()->displayAvatar): ?>
        <div class="avatar">
            <img src="<?= \skeeks\cms\comments\CommentsModule::getInstance()->renderUserAvatar(Yii::$app->user->identity) ?>"/>
        </div>
    <?php endif; ?>
    <div class="comment-fields<?= (\skeeks\cms\comments\CommentsModule::getInstance()->displayAvatar) ? ' display-avatar' : '' ?>">

        <div class="row">
            <div class="col-lg-<?= $col12 ?>">
                <?= $form->field($comment, 'content')->textarea([
                    'class' => 'form-control input-sm',
                    'placeholder' => \Yii::t('skeeks/comments', 'Share your thoughts...')
                ])->label(false) ?>
            </div>
        </div>

        <div class="comment-fields-more">
            <div class="buttons text-right">
                <?= Html::button(\Yii::t('skeeks/comments', 'Cancel'), ['class' => 'btn btn-default btn-sm reply-cancel']) ?>
                <?= Html::submitButton(($comment->parent_id) ? \Yii::t('skeeks/comments', 'Reply') : \Yii::t('skeeks/comments', 'Post'), ['class' => 'btn btn-primary btn-sm']) ?>
            </div>
            <div class="fields">
                <div class="row">
                    <?php if (Yii::$app->user->isGuest): ?>
                        <div class="col-lg-<?= $col6 ?>">
                            <?= $form->field($comment, 'username', ['enableClientValidation' => false, 'enableAjaxValidation' => true])->textInput([
                                'maxlength' => true,
                                'class' => 'form-control input-sm',
                                'placeholder' => \Yii::t('skeeks/comments', 'Your name')
                            ])->label(false) ?>
                        </div>
                        <div class="col-lg-<?= $col6 ?>">
                            <?= $form->field($comment, 'email')->textInput([
                                'maxlength' => true,
                                'email' => true,
                                'class' => 'form-control input-sm',
                                'placeholder' => \Yii::t('skeeks/comments', 'Your email')
                            ])->label(false) ?>
                        </div>
                    <?php else: ?>
                        <div class="col-lg-<?= $col6 ?>">
                            <?= (($comment->parent_id) ? \Yii::t('skeeks/comments', 'Reply as') : \Yii::t('skeeks/comments', 'Post as')) . ' <b>' . Yii::$app->user->identity->displayName . '</b>'; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
//if (Yii::$app->getRequest()->post()) {
//$options    = Json::htmlEncode($form->getClientOptions());
//$attributes = Json::htmlEncode($form->attributes);
//\yii\widgets\ActiveFormAsset::register($this);
//$this->registerJs("jQuery('#$formID').yiiActiveForm($attributes, $options);");
//}
?>