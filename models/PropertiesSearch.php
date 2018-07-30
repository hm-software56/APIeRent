<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PropertiesSearch represents the model behind the search form of `app\models\Properties`.
 */
class PropertiesSearch extends Properties
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'longtitude', 'lattitude', 'properties_type_id', 'packages_id'], 'integer'],
            [['date_start', 'date_end', 'date_update', 'date_create'], 'safe'],
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
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Properties::find();

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
            'status' => $this->status,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'longtitude' => $this->longtitude,
            'lattitude' => $this->lattitude,
            'date_update' => $this->date_update,
            'date_create' => $this->date_create,
            'properties_type_id' => $this->properties_type_id,
            'packages_id' => $this->packages_id,
        ]);
        $query->andFilterWhere(['user_id' => \Yii::$app->user->id]);
        $query->orderBy('id DESC');

        return $dataProvider;
    }
}
