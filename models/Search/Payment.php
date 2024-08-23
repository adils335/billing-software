<?php

namespace app\models\Search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Payment as PaymentModel;
use yii\components\Helper;

/**
 * Payment represents the model behind the search form of `app\models\Payment`.
 */
class Payment extends PaymentModel
{
    public $from_date;
    public $to_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ref_no', 'district_id', 'site_id', 'from_head', 'to_head', 'status', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['from_account', 'particular', 'to_account', 'session','date','from_date','to_date'], 'safe'],
            [['amount', 'tds_rate', 'tds_amount', 'net_amount'], 'number'],
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
        $query = PaymentModel::find();
        
        $formatter = \Yii::$app->formatter;
         
		$query->where(['!=','status',PaymentModel::STATUS_DELETE ]);

        
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
        $query->andFilterWhere(['>=','date',$from_date]);
		$query->andFilterWhere(['<=','date',$to_date]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ref_no' => $this->ref_no,
            'district_id' => $this->district_id,
            'site_id' => $this->site_id,
            'from_head' => $this->from_head,
            'from_account' => $this->from_account,
            'to_head' => $this->to_head,
            'amount' => $this->amount,
            'tds_rate' => $this->tds_rate,
            'tds_amount' => $this->tds_amount,
            'net_amount' => $this->net_amount,
            'status' => $this->status,
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
			

		$query->groupBy(['ref_no'])->orderBy(['id'=>SORT_ASC]);
		
        //echo $query->createCommand()->getRawSql();die();
        return $dataProvider;
    }
    
    
    public function companySearch($params)
    {
        $query = PaymentModel::find();
        $formatter = \Yii::$app->formatter;
         
		$query->where(['!=','status',PaymentModel::STATUS_DELETE])->orderBy(['date'=>SORT_DESC])
		      ->andwhere(['OR',['from_head'=>PaymentModel::FROM_ACCOUNT],['to_head'=>PaymentModel::HEAD_ACCOUNT]]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);
        // echo "<pre>";print_r($this);die();
        
        $fromDate = "";
        $toDate = "";
        if(!empty($this->fromDate)){
		    $fromDate= $formatter->asDate($this->fromDate,'php:Y-m-d');
		}
		if(!empty($this->toDate)){
		    $toDate = $formatter->asDate($this->toDate,'php:Y-m-d');
		}
        if( $this->session != 'all' && empty( $this->fromDate ) && empty( $this->toDate ) ){
            $dates = Yii::$app->helper->getDateBySession( $this->session );
            $fromDate = $dates['from_date'];
            $toDate = $dates['to_date'];
            $this->session = empty( $this->session )?\app\models\Session::getCurrentSession():$this->session;
        }
        
        /*echo "<pre>";print_r($fromDate);
        echo "<pre>";print_r($toDate);die();*/
        
        $query->andFilterWhere(['>=','date',$fromDate]);
		$query->andFilterWhere(['<=','date',$toDate]);
        //echo $query->createCommand()->getRawSql();die();

        if (!$this->validate() || empty($params) || empty($this->contract_company_id) ) {
            //uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ref_no' => $this->ref_no,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'site_id' => $this->site_id,
            'contract_company_id' => $this->contract_company_id,
            'from_head' => $this->from_head,
            'to_head' => $this->to_head,
            'amount' => $this->amount,
            'tds_rate' => $this->tds_rate,
            'tds_amount' => $this->tds_amount,
            'net_amount' => $this->net_amount,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
		
		$this->fromDate = $fromDate;
		$this->toDate = $toDate;
		
		if(!empty($this->fromDate)){
		    $this->fromDate = $formatter->asDate($this->fromDate,'php:d-m-Y');
		}
		if(!empty($this->toDate)){
		    $this->toDate = $formatter->asDate($this->toDate,'php:d-m-Y');
		}
		
		//echo "<pre>";print_r($this);die();
		
        $query->andFilterWhere(['like', 'from_account', $this->from_account])
            ->andFilterWhere(['like', 'particular', $this->particular])
            ->andFilterWhere(['like', 'to_account', $this->to_account])
            ->andFilterWhere(['like', 'session', $this->session]);
			
		$query->groupBy(['ref_no'])->orderBy(['id'=>SORT_ASC]);
        //echo $query->createCommand()->getRawSql();die();

        return $dataProvider;
    }
}
