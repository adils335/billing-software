<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AgreementBill as AgreementBillModel;

/**
 * AgreementBill represents the model behind the search form of `app\models\AgreementBill`.
 */
class AgreementBill extends AgreementBillModel
{
    public $from_date;
    public $to_date;
    public $from_month;
    public $to_month;
    public $state_id;
    public $district_id;
    public $contract_company_id;
    public $site_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'agreement_id', 'agreement_type', 'invoice_no', 'schedule', 'company_id','status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['invoice_date', 'order_no', 'work_name', 'estimate_no', 'section_name', 'start_date', 'complete_date', 'circle_name', 'session','from_date','to_date','from_month','to_month','billing_company_id','billing_company_state','billing_company_district','state_id','district_id','contract_company_id','site_id'], 'safe'],
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
        
        if( $this->state_id || $this->district_id || $this->contract_company_id){
            $query->leftJoin('agreement','agreement_bill.agreement_id = agreement.id');
        }
        if( $this->state_id ){
            $query->andWhere(['agreement.state_id'=>$this->state_id]);
        }
        if( $this->district_id ){
            $query->andWhere(['agreement.district_id'=>$this->district_id]);
        }
        if( $this->contract_company_id ){
            $query->andWhere(['agreement.contract_company_id'=>$this->contract_company_id]);
        }

        if( $this->site_id ){
            $query->leftJoin('agreement_sites','agreement_bill.agreement_id = agreement_sites.agreement_id');
            $query->andWhere(['agreement_sites.site_id'=>$this->site_id]);
        }

        // $query->andWhere(['billing_company_state'=>$this->billing_company_state]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if(!isset($this->status)){
          $query->andWhere(['agreement_bill.status'=>AgreementBill::STATUS_ACTIVE]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'agreement_bill.id' => $this->id,
            'agreement_bill.agreement_id' => $this->agreement_id,
            'agreement_bill.agreement_type' => $this->agreement_type,
            'agreement_bill.invoice_no' => $this->invoice_no,
            'agreement_bill.invoice_date' => $this->invoice_date,
            'DATE_FORMAT(invoice_date,"%m-%Y")' => $this->from_month,
            'agreement_bill.start_date' => $this->start_date,
            'agreement_bill.complete_date' => $this->complete_date,
            'agreement_bill.base_amount' => $this->base_amount,
            'agreement_bill.schedule' => $this->schedule,
            'agreement_bill.schedule_rate' => $this->schedule_rate,
            'agreement_bill.schedule_amount' => $this->schedule_amount,
            'agreement_bill.taxable_amount' => $this->taxable_amount,
            'agreement_bill.tax_amount' => $this->tax_amount,
            'agreement_bill.payable_amount' => $this->payable_amount,
            'agreement_bill.deduction_amount' => $this->deduction_amount,
            'agreement_bill.pay_amount' => $this->pay_amount,
            'agreement_bill.billing_company_id' => $this->billing_company_id,
            'agreement_bill.billing_company_state' => $this->billing_company_state,
            'agreement_bill.billing_company_district' => $this->billing_company_district,
            'agreement_bill.status' => $this->status,
            'agreement_bill.company_id' => $this->company_id,
            'agreement_bill.created_at' => $this->created_at,
            'agreement_bill.created_by' => $this->created_by,
            'agreement_bill.updated_at' => $this->updated_at,
            'agreement_bill.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'agreement_bill.order_no', $this->order_no])
            ->andFilterWhere(['like', 'agreement_bill.work_name', $this->work_name])
            ->andFilterWhere(['like', 'agreement_bill.estimate_no', $this->estimate_no])
            ->andFilterWhere(['like', 'agreement_bill.section_name', $this->section_name])
            ->andFilterWhere(['like', 'agreement_bill.circle_name', $this->circle_name]);
        
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
        }else{
            $query->andFilterWhere(['agreement_bill.session'=> $this->session]);
        }

        if(!empty($this->from_date)){
            $this->from_date = $formatter->asDate($this->from_date,'php:d-m-Y');
        }
        if(!empty($this->to_date)){
            $this->to_date = $formatter->asDate($this->to_date,'php:d-m-Y');
        }
        // echo $query->createCommand()->getRawSql(); die();
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
          $query->andWhere(['agreement_bill.status'=>AgreementBill::STATUS_ACTIVE]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'agreement_bill.agreement_id' => $this->agreement_id,
            'agreement_bill.agreement_type' => $this->agreement_type,
            'agreement_bill.invoice_no' => $this->invoice_no,
            'agreement_bill.invoice_date' => $this->invoice_date,
            'DATE_FORMAT(invoice_date,"%m-%Y")' => $this->from_month,
            'agreement_bill.start_date' => $this->start_date,
            'agreement_bill.complete_date' => $this->complete_date,
            'agreement_bill.base_amount' => $this->base_amount,
            'agreement_bill.schedule' => $this->schedule,
            'agreement_bill.schedule_rate' => $this->schedule_rate,
            'agreement_bill.schedule_amount' => $this->schedule_amount,
            'agreement_bill.taxable_amount' => $this->taxable_amount,
            'agreement_bill.tax_amount' => $this->tax_amount,
            'agreement_bill.payable_amount' => $this->payable_amount,
            'agreement_bill.deduction_amount' => $this->deduction_amount,
            'agreement_bill.pay_amount' => $this->pay_amount,
            'agreement_bill.status' => $this->status,
            'agreement_bill.company_id' => $this->company_id,
            'agreement_bill.created_at' => $this->created_at,
            'agreement_bill.created_by' => $this->created_by,
            'agreement_bill.updated_at' => $this->updated_at,
            'agreement_bill.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'agreement_bill.order_no', $this->order_no])
            ->andFilterWhere(['like', 'agreement_bill.work_name', $this->work_name])
            ->andFilterWhere(['like', 'agreement_bill.estimate_no', $this->estimate_no])
            ->andFilterWhere(['like', 'agreement_bill.section_name', $this->section_name])
            ->andFilterWhere(['like', 'agreement_bill.circle_name', $this->circle_name])
            ->andFilterWhere(['like', 'agreement_bill.session', $this->session]);
        
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
            $this->from_date = $formatter->asDate($this->from_date,'php:d-m-Y');
        }
        if(!empty($this->to_date)){
            $this->to_date = $formatter->asDate($this->to_date,'php:d-m-Y');
        }

        return $dataProvider;
    }
}
