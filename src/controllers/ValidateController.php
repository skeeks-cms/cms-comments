<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 24.06.2016
 */
namespace skeeks\cms\comments\controllers;

use skeeks\cms\comments\models\CmsComment;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ValidateController extends Controller
{

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-form' => ['post'],
                ],
            ],
        ]);
    }

    /**
     * Action for AJAX form validation
     *
     * @return array
     */
    public function actionIndex()
    {
        $model = new CmsComment(['scenario' => (Yii::$app->user->isGuest) ? CmsComment::SCENARIO_GUEST : CmsComment::SCENARIO_USER]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }
}