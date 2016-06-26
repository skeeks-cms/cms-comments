<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 23.06.2016
 */

/* @var $this yii\web\View */
/* @var $searchModel common\models\searchs\Game */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<? $pjax = \skeeks\cms\modules\admin\widgets\Pjax::begin(); ?>

    <?php /*echo $this->render('_search', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider
    ]); */?>

    <?= \skeeks\cms\modules\admin\widgets\GridViewStandart::widget([
        'dataProvider'  => $dataProvider,
        'filterModel'   => $searchModel,
        'adminController'   => $controller,
        'pjax'              => $pjax,
        'columns' =>
            [
                [
                    'class'         => \yii\grid\DataColumn::className(),
                    'attribute'     => "content",
                    'format'     => "raw",
                    'value' => function(\skeeks\cms\comments\models\CmsComment $cmsComment)
                    {
                        return $cmsComment->content;
                    }
                ],

                [
                    'class'         => \skeeks\cms\grid\UserColumnData::className(),
                    'attribute'     => "user_id"
                ],

                [
                    'class'         => \skeeks\cms\grid\DateTimeColumnData::className(),
                    'attribute'     => "created_at"
                ],

                [
                    'class'         => \yii\grid\DataColumn::className(),
                    'attribute'     => "status",
                    'format'     => "raw",
                    'filter'     => \skeeks\cms\comments\models\CmsComment::getStatusList(),
                    'value' => function(\skeeks\cms\comments\models\CmsComment $cmsComment)
                    {
                        return $cmsComment->getStatusList()[$cmsComment->status];
                    }
                ],

                [
                    'class'         => \yii\grid\DataColumn::className(),
                    'attribute'     => "url",
                    'format'        => "raw",
                    'value'         => function(\skeeks\cms\comments\models\CmsComment $cmsComment)
                    {
                        return \yii\bootstrap\Html::a(\skeeks\cms\helpers\StringHelper::substr($cmsComment->url, 0, 60), $cmsComment->commentUrl, [
                            'target' => '_blank',
                            'data-pjax' => '0',
                            'title' => $cmsComment->url
                        ]);
                    }
                ],
            ]
    ]); ?>

<? $pjax::end(); ?>
