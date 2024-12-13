<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Videos;

/**
 * VideosSearch represents the model behind the search form of `app\models\Videos`.
 */
class VideosSearch extends Videos
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['VideoID', 'UserID', 'ViewCount', 'LikeCount'], 'integer'],
            [['Title', 'URL', 'UploadedAt', 'UpdatedAt', 'PictureURL'], 'safe'],
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
        $query = Videos::find();

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
            'VideoID' => $this->VideoID,
            'UserID' => $this->UserID,
            'UploadedAt' => $this->UploadedAt,
            'UpdatedAt' => $this->UpdatedAt,
            'ViewCount' => $this->ViewCount,
            'LikeCount' => $this->LikeCount,
        ]);

        $query->andFilterWhere(['like', 'Title', $this->Title])
            ->andFilterWhere(['like', 'URL', $this->URL])
            ->andFilterWhere(['like', 'PictureURL', $this->PictureURL]);

        return $dataProvider;
    }
}
