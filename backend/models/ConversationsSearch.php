<?php

/**
 * Coding by JiangYu 2210705
 * 使用 Gii 生成了 Conversations 的 CRUD
 */

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Conversations;

/**
 * ConversationsSearch represents the model behind the search form of `app\models\Conversations`.
 */
class ConversationsSearch extends Conversations
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ConversationID', 'UserID'], 'integer'],
            [['StartedAt', 'EndedAt', 'Status'], 'safe'],
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
        $query = Conversations::find();

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
            'ConversationID' => $this->ConversationID,
            'UserID' => $this->UserID,
            'StartedAt' => $this->StartedAt,
            'EndedAt' => $this->EndedAt,
        ]);

        $query->andFilterWhere(['like', 'Status', $this->Status]);

        return $dataProvider;
    }
}
