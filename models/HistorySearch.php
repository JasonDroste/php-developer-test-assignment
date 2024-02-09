<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\History;

/**
 * HistorySearch represents the model behind the search form of `app\models\History`.
 */
class HistorySearch extends History
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'user_id', 'object_id'], 'integer'],
            [['ins_ts', 'event', 'object', 'message', 'detail'], 'safe'],
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
        $query = History::find();

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
            'id' => $this->id,
            'ins_ts' => $this->ins_ts,
            'customer_id' => $this->customer_id,
            'user_id' => $this->user_id,
            'object_id' => $this->object_id,
        ]);

        $query->andFilterWhere(['like', 'event', $this->event])
            ->andFilterWhere(['like', 'object', $this->object])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'detail', $this->detail]);

        return $dataProvider;
    }
}
