<?php

namespace app\controllers;

use app\models\Type;
use app\models\OfferType;
use app\models\Location;
use app\models\OfferLocation;
use app\models\UserOffer;
use app\models\User;
use kartik\growl\Growl;
use Yii;
use app\models\Offer;
use app\models\OfferSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use app\models\City;
use yii\web\UploadedFile;
use app\models\OfferCity;

/**
 * OfferController implements the CRUD actions for Offer model.
 */
class OfferController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors('index', 'view', 'create', 'update', 'delete', 'delete-city-from-offer', 'delete-type-from-offer', 'delete-location-from-offer');
    }

    /**
     * Lists all Offer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OfferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Offer model.
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
     * Creates a new Offer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return array|string|\yii\web\Response
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        /**
         * todo add transaction
         */
        $model = new Offer(['scenario' => 'create']);
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->active = Offer::STATUS_ACTIVE;
            $model->created = time();
            $model->days = implode(',', $model->days);
            
            //image1
            $folder = Offer::getPictureFolder();
            if ($model->file1 = UploadedFile::getInstance($model, 'file1')) {
                $fileName = time() . '_' . uniqid() . '.' . $model->file1->extension;
                $fileLocation = $folder . $fileName;
                $model->file1->saveAs($fileLocation);
                $model->image1 = $fileName;
            }

            //image2
            if ($model->file2 = UploadedFile::getInstance($model, 'file2')) {
                $fileName = time() . '_' . uniqid() . '.' . $model->file2->extension;
                $fileLocation = $folder . $fileName;
                $model->file2->saveAs($fileLocation);
                $model->image2 = $fileName;
            }

            //image3
            if ($model->file3 = UploadedFile::getInstance($model, 'file3')) {
                $fileName = time() . '_' . uniqid() . '.' . $model->file3->extension;
                $fileLocation = $folder . $fileName;
                $model->file3->saveAs($fileLocation);
                $model->image3 = $fileName;
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save(false)) {
                    Yii::$app->Notification->addAlert('Offer successfully created', Growl::TYPE_SUCCESS);
                } else {
                    throw new \Exception('Not saved');
                }
                $this->parseOffer($model);

                $this->parseCities($model);

                $this->parseTypes($model);

                $this->parseAddresses($model);
                
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                Yii::$app->Notification->addAlert('Offer is not saved', Growl::TYPE_DANGER);
            }

            return $this->redirect(['index']);
        } else {
            $locationModel = [new Location];
            $cities = City::getAllCities();
            $types = Type::getAllTypes();
            
            if(!Yii::$app->user->identity->isAdmin()){
                $locations = Location::find()->innerJoin('UserCity', 'UserCity.cityId = Location.cityId')
                    ->andWhere(['UserCity.userId' => Yii::$app->user->identity->id])
                    ->andWhere(['UserCity.active' => 1])->asArray()->all();
            } else {
                $locations = Location::getAllLocations();
            }
                
            return $this->render('create', [
                'model' => $model,
                'cities' => $cities,
                'types' => $types,
                'locations' => $locations,
                'locationModel' => (empty($locationModel)) ? [new Location()] : $locationModel,
            ]);
        }
    }

    public function parseCities(&$model)
    {
        if ($model->cities) {
            foreach ($model->cities as $cityId) {
                $offerCityModel = new OfferCity();
                $offerCityModel->cityId = $cityId;
                $offerCityModel->offerId = $model->offerId;
                if (!$offerCityModel->save()) {
//                    /**
//                     * todo remove in release
//                     */
//                    var_dump($offerCityModel->getErrors());
//                    die;
                }
            }
        }
    }

    public function parseTypes(&$model)
    {
        if ($model->types) {
            // foreach ($model->types as $typeId) {
                $type_exist = OfferType::findOne(['offerId' => $model->offerId]);
                
                if(!$type_exist){
                    $offerTypeModel = new OfferType();
                    $offerTypeModel->typeId = $model->types;
                    $offerTypeModel->offerId = $model->offerId;
                    if (!$offerTypeModel->save()) {
    //                    /**
    //                     * todo remove in release
    //                     */
    //                    var_dump($offerTypeModel->getErrors());
    //                    die;
                    }
                } else {
                    $type_exist->typeId = $model->types;
                    $type_exist->active = Type::STATUS_ACTIVE;
                    if(!$type_exist->save()){
                        /**
    //                   * todo remove in release
    //                   */
    //                  var_dump($offerTypeModel->getErrors());
    //                  die;
                    }
                }
            // }
        }
    }
    
    public function parseOffer(&$model)
    {
        $offer_exist = UserOffer::findOne(['offerId' => $model->offerId, 'userId' => Yii::$app->user->identity->id]);
                
        if(!$offer_exist){
            $userOfferModel = new UserOffer();
            $userOfferModel->userId = Yii::$app->user->identity->id;
            $userOfferModel->offerId = $model->offerId;
            if (!$userOfferModel->save()) {
                // /**
                // * todo remove in release
                // */
                // var_dump($userOfferModel->getErrors());
                // die;
            }
        }
    }
    
    public function parseAddresses(&$model)
    {
        if ($model->locations) {
            foreach ($model->locations as $locationId) {
                $location_exist = OfferLocation::findOne(['offerId' => $model->offerId, 'locationId' => $locationId]);
                
                if(!$location_exist){
                    $offerLocationModel = new OfferLocation();
                    $offerLocationModel->locationId = $locationId;
                    $offerLocationModel->offerId = $model->offerId;
                    if (!$offerLocationModel->save()) {
    //                    /**
    //                     * todo remove in release
    //                     */
    //                    var_dump($offerLocationModel->getErrors());
    //                    die;
                    }
                }
            }
        }
    }
    
//     public function parseAddresses(&$model)
//     {
//         if ($locations = Yii::$app->request->post('Location')) {
//             foreach ($locations as $location) {
//                 if ($location['address'] && $location['long'] && $location['latitude']) {
//                     $locationModel = new Location();
//                     $locationModel->address = $location['address'];
//                     $locationModel->long = $location['long'];
//                     $locationModel->latitude = $location['latitude'];
//                     $locationModel->offerId = $model->offerId;
//                     if (!$locationModel->save()) {
// //                        /**
// //                         * todo remove in release
// //                         */
// //                        var_dump($locationModel->getErrors());
// //                        die;
//                     }
//                 }
//             }
//         }
//     }

    /**
     * Updates an existing Offer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        
        $userModel = User::findOne(['id' => Yii::$app->user->identity->id]);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $post = Yii::$app->request->post();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //image1
                $folder = Offer::getPictureFolder();
                if ($model->file1 = UploadedFile::getInstance($model, 'file1')) {
                    $fileName = time() . '_' . uniqid() . '.' . $model->file1->extension;
                    $fileLocation = $folder . $fileName;
                    $model->file1->saveAs($fileLocation);
                    $model->image1 = $fileName;
                }

                //image2
                if ($model->file2 = UploadedFile::getInstance($model, 'file2')) {
                    $fileName = time() . '_' . uniqid() . '.' . $model->file2->extension;
                    $fileLocation = $folder . $fileName;
                    $model->file2->saveAs($fileLocation);
                    $model->image2 = $fileName;
                }

                //image3
                // if ($model->file3 = UploadedFile::getInstance($model, 'file3')) {
                //     $fileName = time() . '_' . uniqid() . '.' . $model->file3->extension;
                //     $fileLocation = $folder . $fileName;
                //     $model->file3->saveAs($fileLocation);
                //     $model->image3 = $fileName;
                // }
                
                // Logo Image For Users
                $folder = User::getPictureFolder();

                if ($userModel->file3 = UploadedFile::getInstance($model, 'file3')) {
                    $fileName = time() . '_' . uniqid() . '.' . $userModel->file3->extension;
                    $fileLocation = $folder . $fileName;
                    $userModel->file3->saveAs($fileLocation);
                    $userModel->logo_image = $fileName;
                }
  
                $model->days = implode(',', $model->days);
                
                $this->parseTypes($model);
                $this->parseCities($model);
                $this->parseAddresses($model);
                $this->parseOffer($model);

                if (!$model->save(false)) {
                    throw new \Exception('Not updated');
                } else {
                    if ($userModel->save(false)) {
                        Yii::$app->Notification->addAlert('Changes successfully created', Growl::TYPE_SUCCESS);
                    } else {
                        throw new \Exception('Not saved');
                    }
                    Yii::$app->Notification->addAlert('Offer successfully updated', Growl::TYPE_SUCCESS);
                }
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                Yii::$app->Notification->addAlert('Offer is not updated', Growl::TYPE_DANGER);
            }

            return $this->redirect(['index']);
        } else {
            // $locationDataProvider = new ActiveDataProvider([
            //     'query' => OfferLocation::getLocationByOfferId($id)
            // ]);
    
            $locationDataProvider = new SqlDataProvider([
                'sql' => Location::setQuery(),
                'params' => [
                    ':offerId' => $id,
                    ':active' => OfferLocation::STATUS_ACTIVE,
                    ':activeLocation' => Location::STATUS_ACTIVE,
                ],
                'totalCount' => OfferLocation::getCount($id),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            $cityDataProvider = new SqlDataProvider([
                'sql' => City::setQuery(),
                'params' => [
                    ':offerId' => $id,
                    ':active' => OfferCity::STATUS_ACTIVE,
                    ':activeCity' => City::STATUS_ACTIVE,
                ],
                'totalCount' => OfferCity::getCount($id),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            $typeDataProvider = new SqlDataProvider([
                'sql' => Type::setQuery(),
                'params' => [
                    ':offerId' => $id,
                    ':active' => OfferType::STATUS_ACTIVE,
                    ':activeType' => Type::STATUS_ACTIVE,
                ],
                'totalCount' => OfferType::getCount($id),
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);

            $locationModel = [new Location];
            $cities = City::getAllCities();
            $types = Type::getAllTypes();
            
            if(!Yii::$app->user->identity->isAdmin()){
                $locations = Location::find()->innerJoin('UserCity', 'UserCity.cityId = Location.cityId')
                    ->andWhere(['UserCity.userId' => Yii::$app->user->identity->id])
                    ->andWhere(['UserCity.active' => 1])->asArray()->all();
            } else {
                $locations = Location::getAllLocations();
            }
                
            $data = array('offerId' => $id, 'active' => 1, 'activeType' => 1);
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand(Type::setQuery(), $data);
            $selected_types = $command->queryAll();

            $types_result = [];
            foreach($selected_types as $key => $selected_type){
                $types_result[$key] = $selected_type['typeId'];
            }
            
            $data = array('offerId' => $id, 'active' => 1, 'activeLocation' => 1);
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand(Location::setQuery(), $data);
            $selected_locations = $command->queryAll();

            $locations_result = [];
            foreach($selected_locations as $key => $selected_location){
                $locations_result[$key] = $selected_location['locationId'];
            }

            return $this->render('update', [
                'model' => $model,
                'cities' => $cities,
                'types' => $types,
                'types_result' => $types_result,
                'locations' => $locations,
                'locations_result' => $locations_result,
                'locationDataProvider' => $locationDataProvider,
                'cityDataProvider' => $cityDataProvider,
                'typeDataProvider' => $typeDataProvider,
                'locationModel' => (empty($locationModel)) ? [new Location()] : $locationModel,
            ]);
        }
    }

    public function actionDeleteCityFromOffer($id)
    {
        if (OfferCity::deleteAll(['offerCityId' => $id])) {
            Yii::$app->Notification->addAlert('City from offer successfully removed', Growl::TYPE_SUCCESS);
        } else {
            Yii::$app->Notification->addAlert('City from offer is not removed', Growl::TYPE_DANGER);
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionDeleteTypeFromOffer($id, $offer)
    {
        if (OfferType::deleteAll(['typeId' => $id, 'offerId' => $offer])) {
            Yii::$app->Notification->addAlert('Type from offer successfully removed', Growl::TYPE_SUCCESS);
        } else {
            Yii::$app->Notification->addAlert('Type from offer is not removed', Growl::TYPE_DANGER);
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function actionDeleteLocationFromOffer($id, $offer)
    {
        if (OfferLocation::deleteAll(['locationId' => $id, 'offerId' => $offer])) {
            Yii::$app->Notification->addAlert('Location from offer successfully removed', Growl::TYPE_SUCCESS);
        } else {
            Yii::$app->Notification->addAlert('Location from offer is not removed', Growl::TYPE_DANGER);
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * Deletes an existing Offer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Offer::deleteAll(['offerId' => $id]);
            OfferCity::deleteAll(['offerId' => $id]);
            OfferType::deleteAll(['offerId' => $id]);
            OfferLocation::deleteAll(['offerId' => $id]);
            UserOffer::deleteAll(['offerId' => $id]);

            $transaction->commit();
            Yii::$app->Notification->addAlert('Offer successfully removed', Growl::TYPE_SUCCESS);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->Notification->addAlert('Offer is not removed', Growl::TYPE_DANGER);

        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Offer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Offer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Offer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
