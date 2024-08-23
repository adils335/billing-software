<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AgreementBill as AgreementBillModel;

/**
 * AgreementBill represents the model behind the search form of `app\models\AgreementBill`.
 */
class Reports extends AgreementBillModel
{
    public $from_date;
    public $to_date;
    public $from_month;
    public $to_month;
    public $report_type;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'agreement_id', 'agreement_type', 'invoice_no', 'schedule', 'company_id','status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['invoice_date', 'order_no', 'work_name', 'estimate_no', 'section_name', 'start_date', 'complete_date', 'circle_name', 'session','from_date','to_date','from_month','to_month','billing_company_id','billing_company_state','billing_company_district','report_type'], 'safe'],
            [['base_amount', 'schedule_rate', 'schedule_amount', 'taxable_amount', 'tax_amount', 'payable_amount', 'deduction_amount', 'pay_amount'], 'number'],
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
        $query = AgreementBillModel::find();
        $formatter = \Yii::$app->formatter;
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
        if(!isset($this->status)){
          $query->andWhere(['status'=>AgreementBill::STATUS_ACTIVE]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'agreement_id' => $this->agreement_id,
            'agreement_type' => $this->agreement_type,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'DATE_FORMAT(invoice_date,"%m-%Y")' => $this->from_month,
            'start_date' => $this->start_date,
            'complete_date' => $this->complete_date,
            'base_amount' => $this->base_amount,
            'schedule' => $this->schedule,
            'schedule_rate' => $this->schedule_rate,
            'schedule_amount' => $this->schedule_amount,
            'taxable_amount' => $this->taxable_amount,
            'tax_amount' => $this->tax_amount,
            'payable_amount' => $this->payable_amount,
            'deduction_amount' => $this->deduction_amount,
            'pay_amount' => $this->pay_amount,
            'billing_company_id' => $this->billing_company_id,
            'billing_company_state' => $this->billing_company_state,
            'billing_company_district' => $this->billing_company_district,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'work_name', $this->work_name])
            ->andFilterWhere(['like', 'estimate_no', $this->estimate_no])
            ->andFilterWhere(['like', 'section_name', $this->section_name])
            ->andFilterWhere(['like', 'circle_name', $this->circle_name])
            ->andFilterWhere(['like', 'session', $this->session]);
        if( empty( $this->from_date ) ){
            $this->from_date = date("Y-m-d");
        }
        if(!empty($this->from_date)){
            $this->from_date = $formatter->asDate($this->from_date,'php:Y-m-d');
        }
        if(!empty($this->to_date)){
            $this->to_date = $formatter->asDate($this->to_date,'php:Y-m-d');
        }
        
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->from_date = $formatter->asDate($this->from_date,'php:Y-m-d');
            $this->to_date = $formatter->asDate($this->to_date,'php:Y-m-d');
            $query->andWhere(['BETWEEN','invoice_date',$this->from_date,$this->to_date]);
        }elseif(!empty($this->from_date)){
            $query->andWhere(['>=','invoice_date',$this->from_date]);
        }elseif(!empty($this->to_date)){
            $query->andWhere(['<=','invoice_date',$this->to_date]);
        }

        if(!empty($this->from_date)){
            $this->from_date = $formatter->asDate($this->from_date,'php:m-Y');
        }
        if(!empty($this->to_date)){
            $this->to_date = $formatter->asDate($this->to_date,'php:m-Y');
        }

        return $dataProvider;
    }
    
    public function searchHsnWise($params)
    {
        $query = AgreementBillModel::find();
        $formatter = \Yii::$app->formatter;
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
        if(!isset($this->status)){
          $query->andWhere(['status'=>AgreementBill::STATUS_ACTIVE]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'agreement_id' => $this->agreement_id,
            'agreement_type' => $this->agreement_type,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'DATE_FORMAT(invoice_date,"%m-%Y")' => $this->from_month,
            'start_date' => $this->start_date,
            'complete_date' => $this->complete_date,
            'base_amount' => $this->base_amount,
            'schedule' => $this->schedule,
            'schedule_rate' => $this->schedule_rate,
            'schedule_amount' => $this->schedule_amount,
            'taxable_amount' => $this->taxable_amount,
            'tax_amount' => $this->tax_amount,
            'payable_amount' => $this->payable_amount,
            'deduction_amount' => $this->deduction_amount,
            'pay_amount' => $this->pay_amount,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'work_name', $this->work_name])
            ->andFilterWhere(['like', 'estimate_no', $this->estimate_no])
            ->andFilterWhere(['like', 'section_name', $this->section_name])
            ->andFilterWhere(['like', 'circle_name', $this->circle_name])
            ->andFilterWhere(['like', 'session', $this->session]);
        
        if(!empty($this->from_date)){
            $this->from_date = $formatter->asDate($this->from_date,'php:Y-m-d');
        }
        if(!empty($this->to_date)){
            $this->to_date = $formatter->asDate($this->to_date,'php:Y-m-d');
        }
        
        if(!empty($this->from_date) && !empty($this->to_date)){
            $this->from_date = $formatter->asDate($this->from_date,'php:Y-m-d');
            $this->to_date = $formatter->asDate($this->to_date,'php:Y-m-d');
            $query->andWhere(['BETWEEN','invoice_date',$this->from_date,$this->to_date]);
        }elseif(!empty($this->from_date)){
            $query->andWhere(['>=','invoice_date',$this->from_date]);
        }elseif(!empty($this->to_date)){
            $query->andWhere(['<=','invoice_date',$this->to_date]);
        }
        
        /*if(!empty($this->from_month) && !empty($this->to_month)){
            $query->andWhere(['BETWEEN','DATE_FORMAT(invoice_date,"%m-%Y")',$this->from_month,$this->to_month]);
        }elseif(!empty($this->from_month)){
            $query->andWhere(['>=','DATE_FORMAT(invoice_date,"%m-%Y")',$this->from_month]);
        }elseif(!empty($this->to_month)){
            $query->andWhere(['<=','DATE_FORMAT(invoice_date,"%m-%Y")',$this->to_month]);
        }*/
        
        if(!empty($this->from_date)){
            $this->from_date = $formatter->asDate($this->from_date,'php:d-m-Y');
        }
        if(!empty($this->to_date)){
            $this->to_date = $formatter->asDate($this->to_date,'php:d-m-Y');
        }

        return $dataProvider;
    }
}
