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
    public $occupationName;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id',  'cabinet_id', 'reserved', 'visited', 'sum'], 'integer'],
            [['name', 'phone', 'email', 'start_time', 'created_at', 'updated_at', 'specialist_id', 'occupationName'], 'safe'],
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
            'sort' => [
                'defaultOrder' => [
                    'specialist_id' => SORT_DESC,
//                    'occupationName' => SORT_DESC,
                    'start_time' => SORT_ASC, 
                ]
            ]
        ]);
        
//        $dataProvider->setSort([        
//            'attributes' => [
//                'start_time',
//                'occupationName',
//            ]
//        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
//             $query->where('0=1');
            return $dataProvider;
        }
        
        $query->joinWith('specialist.doctor');
        $query->joinWith('specialist.occupation');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cabinet_id' => $this->cabinet_id,
//            'start_time' => $this->start_time,
            'reserved' => $this->reserved,
            'visited' => $this->visited,
            'sum' => $this->sum,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'records.start_time', $this->start_time])            
            ->orFilterWhere(['like', 'doctors.name', $this->specialist_id])
            ->orFilterWhere(['like', 'occupations.name', $this->occupationName])
            ->orFilterWhere(['like', 'records.name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}