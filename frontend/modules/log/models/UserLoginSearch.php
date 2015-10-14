<?php

namespace frontend\modules\log\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\log\models\UserLogin;

/**
 * UserLoginSearch represents the model behind the search form about `frontend\models\UserLogin`.
 */
class UserLoginSearch extends UserLogin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'login_at'], 'integer'],
            [['data', 'user_id', 'action'], 'safe'],
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
        $query = UserLogin::find();

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
        
        $query->joinWith('user');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
//            'user_id' => $this->user_id,
            'login_at' => $this->login_at,
        ]);

        $query->andFilterWhere(['like', 'data', $this->data]);
        $query->andFilterWhere(['like', 'user.name', $this->user_id]);

        return $dataProvider;
    }
}
