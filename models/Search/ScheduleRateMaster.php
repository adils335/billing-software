<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ScheduleRateMaster as ScheduleRateMasterModel;

/**
 * ScheduleRateMaster represents the model behind the search form of `app\models\ScheduleRateMaster`.
 */
class ScheduleRateMaster extends ScheduleRateMasterModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'srmid', 'unit', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['type', 'item', 'hsn_no'], 'safe'],
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
        $query = ScheduleRateMasterModel::find();

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
            'srmid' => $this->srmid,
            'unit' => $this->unit,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'item', $this->item])
            ->andFilterWhere(['like', 'hsn_no', $this->hsn_no]);
        $query->groupBy(['type']);
        return $dataProvider;
    }
}
