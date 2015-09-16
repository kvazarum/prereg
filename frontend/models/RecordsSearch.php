<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Records;

/**
 * RecordsSearch represents the model behind the search form about `frontend\models\Records`.
 */
class RecordsSearch extends Records
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'cabinet_id', 'reserved', 'visited', 'sum'], 'integer'],
            [['start_time', 'name', 'phone', 'created_at', 'updated_at', 'specialist_id'], 'safe'],
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
        $query = Records::find();

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
        
        $query->joinWith('specialist.doctor');
//        $query->joinWith('specialist.occupation');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'specialist_id' => $this->specialist_id,
            'cabinet_id' => $this->cabinet_id,
//            'start_time' => $this->start_time,
            'reserved' => $this->reserved,
            'visited' => $this->visited,
            'sum' => $this->sum,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'records.start_time', $this->start_time])
//            ->andFilterWhere(['like', 'occupations.name', $this->specialist_id])
            ->andFilterWhere(['like', 'doctors.name', $this->specialist_id]);

        return $dataProvider;
    }
}
