<?php

namespace app\models\Search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VendorBill as VendorBillModel;

/**
 * VendorBill represents the model behind the search form of `app\models\VendorBill`.
 */
class VendorBill extends VendorBillModel
{
    public $from_date;
    public $to_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'vendor_id', 'bill_no', 'invoice_no', 'schedule', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['invoice_date', 'session','from_date','to_date'], 'safe'],
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
        $formatter = \Yii::$app->formatter;
        $query = VendorBillModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $from_date = "";
        $to_date = "";

        if(!empty($this->from_date)){
		    $from_date = $formatter->asDate($this->from_date,'php:Y-m-d');
		}
		
		if(!empty($this->to_date)){
		    $to_date = $formatter->asDate($this->to_date,'php:Y-m-d');
		}

        if(empty( $this->from_date ) && empty( $this->to_date)){
            $dates = Yii::$app->helper->getDateBySession( $this->session );
            $from_date = $dates['from_date'];
            $to_date = $dates['to_date'];
            $this->session = empty( $this->session )?\app\models\Session::getCurrentSession():$this->session;
        }
        $query->andFilterWhere(['>=','bill_date',$from_date]);
		$query->andFilterWhere(['<=','bill_date',$to_date]);
        

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'bill_no' => $this->bill_no,
            'invoice_no' => $this->invoice_no,
            'invoice_date' => $this->invoice_date,
            'base_amount' => $this->base_amount,
            'schedule' => $this->schedule,
            'schedule_rate' => $this->schedule_rate,
            'schedule_amount' => $this->schedule_amount,
            'taxable_amount' => $this->taxable_amount,
            'tax_amount' => $this->tax_amount,
            'payable_amount' => $this->payable_amount,
            'deduction_amount' => $this->deduction_amount,
            'pay_amount' => $this->pay_amount,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        if(!empty($this->from_date)){
            $from_date = $formatter->asDate($this->from_date,'php:d-m-Y');
        }
        
        if(!empty($this->to_date)){
            $to_date = $formatter->asDate($this->to_date,'php:d-m-Y');
        }
        
        if( empty( $this->status ) ){
             $query->andWhere(['status'=>1]);
        }

        // $query->andFilterWhere(['like', 'session', $this->session]);
        $query->orderBy(['bill_no'=>SORT_DESC]);

        //echo $query->createCommand()->getRawsql();die();

        return $dataProvider;
    }
}
