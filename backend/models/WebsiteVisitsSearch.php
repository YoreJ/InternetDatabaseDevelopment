<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WebsiteVisits;

/**
 * WebsiteVisitsSearch represents the model behind the search form of `app\models\WebsiteVisits`.
 */
class WebsiteVisitsSearch extends WebsiteVisits
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'VisitCount'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = WebsiteVisits::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            'VisitCount' => $this->VisitCount,
        ]);

        return $dataProvider;
    }

    /**
     * Increments the visit count.
     *
     * @return boolean whether the increment was successful
     */
    public function incrementVisitCount()
    {
        $websiteVisit = WebsiteVisits::find()->one();
        if ($websiteVisit === null) {
            $websiteVisit = new WebsiteVisits();
            $websiteVisit->VisitCount = 1;
        } else {
            $websiteVisit->VisitCount += 1;
        }
        return $websiteVisit->save();
    }

    /**
     * Gets the current visit count.
     *
     * @return int the current visit count
     */
    public function getVisitCount()
    {
        $websiteVisit = WebsiteVisits::find()->one();
        return $websiteVisit ? $websiteVisit->VisitCount : 0;
    }
}
