<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employee as EmployeeModel;

/**
 * Employee represents the model behind the search form of `app\models\Employee`.
 */
class Employee extends EmployeeModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'emp_company', 'designation', 'expense_type', 'personal_type', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['emp_code', 'emp_name', 'email', 'mobile', 'dob', 'joining_date', 'refference', 'aadhar', 'pancard', 'session'], 'safe'],
            [['expense_balance', 'personal_balance'], 'number'],
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
        $query = EmployeeModel::find();
        
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
            'emp_company' => $this->emp_company,
            'dob' => $this->dob,
            'joining_date' => $this->joining_date,
            'designation' => $this->designation,
            'expense_balance' => $this->expense_balance,
            'expense_type' => $this->expense_type,
            'personal_balance' => $this->personal_balance,
            'personal_type' => $this->personal_type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        
        if(!$this->status){
            $query->where(['status'=>Employee::STATUS_ACTIVE]);
        }
        
        $query->andFilterWhere(['like', 'emp_code', $this->emp_code])
            ->andFilterWhere(['like', 'emp_name', $this->emp_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'refference', $this->refference])
            ->andFilterWhere(['like', 'aadhar', $this->aadhar])
            ->andFilterWhere(['like', 'pancard', $this->pancard])
            ->andFilterWhere(['like', 'session', $this->session]);

        return $dataProvider;
    }
}
