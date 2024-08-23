<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StoreConsumed as StoreConsumedModel;

/**
 * StoreConsumed represents the model behind the search form of `app\models\StoreConsumed`.
 */
class StoreConsumed extends StoreConsumedModel
{
    public $date_from;
	public $date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'state_id', 'district_id', 'billing_company_id', 'agreement_id', 'site_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['invoice_no', 'bill_no', 'comment', 'session','date_from','date_to'], 'safe'],
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
        $query = StoreConsumedModel::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        
        $this->load($params);
        

        if (!$this->validate() ){
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'company_id' => $this->company_id,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'billing_company_id' => $this->billing_company_id,
            'agreement_id' => $this->agreement_id,
            'site_id' => $this->site_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        
        $date_from = "";
        $date_to = "";

        // $date_from = Null;
        if(!empty($this->date_from)){
		    $date_from = $formatter->asDate($this->date_from,'php:Y-m-d');
		}
		// $date_to = Null;
		if(!empty($this->date_to)){
		    $date_to = $formatter->asDate($this->date_to,'php:Y-m-d');
		}
        $query->andFilterWhere(['>=','invoice_date',$date_from]);
		$query->andFilterWhere(['<=','invoice_date',$date_to]);
        $query->orderBy(['id'=>SORT_DESC]);

        $query->andFilterWhere(['like', 'invoice_no', $this->invoice_no])
            ->andFilterWhere(['like', 'bill_no', $this->bill_no])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'session', $this->session]);
            
        //echo $query->createCommand()->getRawSql();die();
            
        return $dataProvider;
    }
}
