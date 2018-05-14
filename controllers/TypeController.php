<?php

namespace app\controllers;

use app\models\OfferType;
use Yii;
use app\models\Type;
use app\models\TypeSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use kartik\growl\Growl;

/**
 * TypeController implements the CRUD actions for Type model.
 */
class TypeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors('index', 'view', 'create', 'update', 'delete');
    }

    /**
     * Lists all Type models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Type model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Type model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Type();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->active = Type::STATUS_ACTIVE;
            if ($model->save()) {
                Yii::$app->Notification->addAlert('Type successfully created', Growl::TYPE_SUCCESS);
            } else {
                Yii::$app->Notification->addAlert('Type is not saved', Growl::TYPE_DANGER);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Type model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->Notification->addAlert('Type has been successfully updated', Growl::TYPE_SUCCESS);
            } else {
                Yii::$app->Notification->addAlert('Type is not updated', Growl::TYPE_DANGER);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Type model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Type::deleteAll(['typeId' => $id])) {
            OfferType::deleteAll(['typeId' => $id]);
            Yii::$app->Notification->addAlert('Type successfully removed', Growl::TYPE_SUCCESS);
        } else {
            Yii::$app->Notification->addAlert('Type is not removed', Growl::TYPE_DANGER);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Type model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Type the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Type::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
