<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EmployeeExtraSalary as EmployeeExtraSalaryModel;

/**
 * EmployeeExtraSalary represents the model behind the search form of `app\models\EmployeeExtraSalary`.
 */
class EmployeeExtraSalary extends EmployeeExtraSalaryModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'employee_id', 'days', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['month'], 'safe'],
            [['salary', 'allowance', 'salary_with_allowance'], 'number'],
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
        $query = EmployeeExtraSalaryModel::find();

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
            'employee_id' => $this->employee_id,
            'month' => $this->month,
            'days' => $this->days,
            'salary' => $this->salary,
            'allowance' => $this->allowance,
            'salary_with_allowance' => $this->salary_with_allowance,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        return $dataProvider;
    }
}
