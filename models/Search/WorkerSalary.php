<?php

namespace app\models\Search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkerSalary as WorkerSalaryModel;

/**
 * WorkerSalary represents the model behind the search form of `app\models\WorkerSalary`.
 */
class WorkerSalary extends WorkerSalaryModel
{
    public $from_date;
    public $to_date;
    public $from_month;
    public $to_month;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'worker_vendor_id', 'worker_id', 'working_days', 'leave', 'extra_work_days', 'company_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['month', 'session','from_month','to_month','from_date','to_date'], 'safe'],
            [['base_salary', 'per_day_salary', 'extra_salary', 'salary', 'allowance', 'salary_with_allowance', 'worker_deduction', 'payable_salary', 'employer_deduction', 'net_salary'], 'number'],
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
        $formatter = Yii::$app->formatter;
        $query = WorkerSalaryModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $from_month = "";$to_month="";
        if(empty( $this->from_month ) && empty( $this->to_month)){
            $dates = Yii::$app->helper->getDateBySession( $this->session );
            $from_month = $dates['from_date'];
            $to_month = $dates['to_date'];
            $this->session = empty( $this->session )?\app\models\Session::getCurrentSession():$this->session;
        }else{
            if( $this->from_month ){
                $from_month = $formatter->asDate("01-".$this->from_month,'php:Y-m-d') ;
            }
            if( $this->to_month ){
                $to_month = date("Y-m-t",strtotime( "01-".$this->to_month ));
            }
        }

        $query->andFilterWhere(['>=','month',$from_month]);
		$query->andFilterWhere(['<=','month',$to_month]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'worker_vendor_id' => $this->worker_vendor_id,
            'worker_id' => $this->worker_id,
            'month' => $this->month,
            'base_salary' => $this->base_salary,
            'per_day_salary' => $this->per_day_salary,
            'working_days' => $this->working_days,
            'leave' => $this->leave,
            'extra_work_days' => $this->extra_work_days,
            'extra_salary' => $this->extra_salary,
            'salary' => $this->salary,
            'allowance' => $this->allowance,
            'salary_with_allowance' => $this->salary_with_allowance,
            'worker_deduction' => $this->worker_deduction,
            'payable_salary' => $this->payable_salary,
            'employer_deduction' => $this->employer_deduction,
            'net_salary' => $this->net_salary,
            'company_id' => $this->company_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        //$query->andFilterWhere(['like', 'session', $this->session]);
        $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;
    }
}
