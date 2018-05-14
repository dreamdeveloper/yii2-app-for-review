<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Offer;

/**
 * OfferSearch represents the model behind the search form about `app\models\Offer`.
 */
class OfferSearch extends Offer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offerId', 'featured'], 'integer'],
            [['image1', 'image2', 'image3'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Offer::find();

        if( !Yii::$app->user->identity->isAdmin() ){
            $query = $query->innerJoin('UserOffer', 'UserOffer.offerId = Offer.offerId')
                ->andWhere(['UserOffer.userId' => Yii::$app->user->identity->id])
                ->andWhere(['UserOffer.active' => 1]);
        }
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'offerId' => $this->offerId,
            'featured' => $this->featured,
        ]);

        $query->andFilterWhere(['like', 'image1', $this->image1])
            ->andFilterWhere(['like', 'image2', $this->image2])
            ->andFilterWhere(['like', 'image3', $this->image3]);

        return $dataProvider;
    }
}
