<?php

namespace app\controllers;

use app\models\OfferCity;
use app\models\Location;
use app\models\UserCity;

use Yii;
use app\models\City;
use app\models\CitySearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use kartik\growl\Growl;

/**
 * CityController implements the CRUD actions for City model.
 */
class CityController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors('index', 'view', 'create', 'update', 'delete');
    }

    /**
     * Lists all City models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single City model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new City model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new City();
        
        $locationModel = new Location();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->active = City::STATUS_ACTIVE;
            
            if ($model->save()) {
                
                $this->addRelationUserCity($model);

                if ($locationModel->load(Yii::$app->request->post()) && $locationModel->validate()) {
                    $locationModel->active = City::STATUS_ACTIVE;
                    $locationModel->cityId = $model->cityId;
                    
                    if ($locationModel->save()) {
                        Yii::$app->Notification->addAlert('Location successfully saved', Growl::TYPE_SUCCESS);
                    } else {
                        Yii::$app->Notification->addAlert('Location is not saved', Growl::TYPE_DANGER);
                    }
                }
                
                Yii::$app->Notification->addAlert('City successfully created', Growl::TYPE_SUCCESS);
            } else {
                Yii::$app->Notification->addAlert('City is not saved', Growl::TYPE_DANGER);
            }
            
            
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'locationModel' => $locationModel
            ]);
        }
    }

    /**
     * Updates an existing City model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $locationModel = Location::findOne(['cityId' => $model->cityId]);
  
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                
                $this->addRelationUserCity($model);
                
                if ($locationModel->load(Yii::$app->request->post()) && $locationModel->validate()) {

                    if ($locationModel->save()) {
                        Yii::$app->Notification->addAlert('Location successfully saved', Growl::TYPE_SUCCESS);
                    } else {
                        Yii::$app->Notification->addAlert('Location is not saved', Growl::TYPE_DANGER);
                    }
                }
                
                Yii::$app->Notification->addAlert('City has been successfully updated', Growl::TYPE_SUCCESS);
            } else {
                Yii::$app->Notification->addAlert('City is not updated', Growl::TYPE_DANGER);
            }
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'locationModel' => $locationModel
            ]);
        }
    }

    public function addRelationUserCity(&$model)
    {
        $offer_exist = UserCity::findOne(['cityId' => $model->cityId, 'userId' => Yii::$app->user->identity->id]);
                
        if(!$offer_exist){
            $userCityModel = new UserCity();
            $userCityModel->userId = Yii::$app->user->identity->id;
            $userCityModel->cityId = $model->cityId;
            if (!$userCityModel->save()) {
                // /**
                // * todo remove in release
                // */
                // var_dump($userOfferModel->getErrors());
                // die;
            }
        }
    }
    /**
     * Deletes an existing City model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (City::deleteAll(['cityId' => $id])) {
            OfferCity::deleteAll(['cityId' => $id]);
            UserCity::deleteAll(['cityId' => $id]);
            Yii::$app->Notification->addAlert('City successfully removed', Growl::TYPE_SUCCESS);
        } else {
            Yii::$app->Notification->addAlert('City is not removed', Growl::TYPE_DANGER);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the City model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return City the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = City::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
