<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Banner;
use Yii;
use yii\web\UploadedFile;
use kartik\growl\Growl;

class BannerController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors('index');
    }

    public function actionIndex()
    {
        $model = Banner::find()->one();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }
        $oldName = $model->image;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $folder = Yii::getAlias('@app') . '/web/images/uploads/banner/';

            if ($model->file = UploadedFile::getInstance($model, 'file')) {
                if ($oldName && file_exists(Yii::getAlias('@app') .$oldName)) {
                    unlink(Yii::getAlias('@app') .$oldName);
                }
                $fileName = time() . '_' . uniqid() . '.' . $model->file->extension;
                $fileLocation = $folder . $fileName;
                $model->file->saveAs($fileLocation);
                @chmod($fileLocation, 0777);
                $model->image = Yii::getAlias('@web') . '/images/uploads/banner/' . $fileName;
            }
            if ($model->save(false)) {
                Yii::$app->Notification->addAlert('Banner successfully saved', Growl::TYPE_SUCCESS);
            } else {
                Yii::$app->Notification->addAlert('Banner is not saved', Growl::TYPE_DANGER);
            }

            return $this->redirect(['/banner']);
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

}
