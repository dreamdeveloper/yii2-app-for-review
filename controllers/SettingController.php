<?php

namespace app\controllers;

use app\models\User;
use app\models\Setting;
use yii\web\Controller;
use Yii;
use kartik\growl\Growl;
use yii\web\UploadedFile;

class SettingController extends Controller
{
    public function actionIndex()
    {
        $model = User::findOne(['id' => Yii::$app->user->identity->id]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $folder = User::getPictureFolder();

            if ($model->file3 = UploadedFile::getInstance($model, 'file3')) {
                $fileName = time() . '_' . uniqid() . '.' . $model->file3->extension;
                $fileLocation = $folder . $fileName;
                $model->file3->saveAs($fileLocation);
                $model->logo_image = $fileName;
            }
            
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save(false)) {
                    Yii::$app->Notification->addAlert('Changes successfully created', Growl::TYPE_SUCCESS);
                } else {
                    throw new \Exception('Not saved');
                }

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                Yii::$app->Notification->addAlert('Changes is not saved', Growl::TYPE_DANGER);
            }
        }
        
        return $this->render('index', [
            'model' => $model,
        ]);
    }
    
    public function actionChangeEmail()
    {
        $model = Setting::findOne(['settingId' => Setting::EMAIL_ID]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->Notification->addAlert('Email successfully saved', Growl::TYPE_SUCCESS);
            } else {
                Yii::$app->Notification->addAlert('Email not saved', Growl::TYPE_DANGER);
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}