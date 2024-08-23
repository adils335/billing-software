<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CompanyDues as CompanyDuesModel;

/**
 * CompanyDues represents the model behind the search form of `app\models\CompanyDues`.
 */
class CompanyDues extends CompanyDuesModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'code', 'state_id', 'district_id', 'status', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'father_name', 'mobile', 'pancard_no', 'aadhar_no', 'address', 'pincode', 'joining_date', 'session'], 'safe'],
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
        $query = CompanyDuesModel::find();

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
            'code' => $this->code,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'joining_date' => $this->joining_date,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'father_name', $this->father_name])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'pancard_no', $this->pancard_no])
            ->andFilterWhere(['like', 'aadhar_no', $this->aadhar_no])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'session', $this->session]);

        return $dataProvider;
    }
}
