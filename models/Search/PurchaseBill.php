<?php

namespace app\models\Search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseBill as PurchaseBillModel;

/**
 * PurchaseBill represents the model behind the search form of `app\models\PurchaseBill`.
 */
class PurchaseBill extends PurchaseBillModel
{
    
    public $from_month;
    public $to_month;
    public $from_date;
    public $to_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'gstin', 'invoice_no','session', 'date','from_month','to_month','company_id','from_date','to_date'], 'safe'],
            [['amount', 'tax', 'total'], 'number'],
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
        $query = PurchaseBillModel::find();
        $formatter = \Yii::$app->formatter;
        // $query->andWhere(['!=','status',PurchaseBillModel::STATUS_DELETE]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        //echo "<pre>";print_r($params);die();
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
            //'DATE_FORMAT(date,"%m-%Y")' => $this->from_month,
            'amount' => $this->amount,
            'tax' => $this->tax,
            'total' => $this->total,
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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'gstin', $this->gstin])
            ->andFilterWhere(['like', 'invoice_no', $this->invoice_no]);
        /*if(!empty($this->from_month) && !empty($this->to_month)){
            $query->andWhere(['BETWEEN','DATE_FORMAT(date,"%m-%Y")',$this->from_month,$this->to_month]);
        }elseif(!empty($this->from_month)){
            $query->andWhere(['>=','DATE_FORMAT(date,"%m-%Y")',$this->from_month]);
        }elseif(!empty($this->to_month)){
            $query->andWhere(['<=','DATE_FORMAT(date,"%m-%Y")',$this->to_month]);
        }*/

        // echo $query->createCommand()->getRawsql();die();
        
        return $dataProvider;
    }
}
