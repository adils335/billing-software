<?php

namespace app\models\Search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Agreement as AgreementModel;

/**
 * Agreement represents the model behind the search form of `app\models\Agreement`.
 */
class Agreement extends AgreementModel
{
    public $site_id;
    public $from_date;
    public $to_date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'file_no', 'state_id', 'schedule', 'contract_company_id', 'contract_company_state','status','district_id', 'created_at', 'created_by', 'updated_at', 'updated_by','contract_company_district'], 'integer'],
            [['session', 'agreement_no', 'date', 'expire_date', 'zone', 'gst_no', 'contract_company_gst','type','site_id','from_date','to_date'], 'safe'],
            [['cost', 'rate'], 'number'],
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
        $query = AgreementModel::find();
        $formatter = \Yii::$app->formatter;
        //echo "<pre>";print_r($this);die();
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $from_date = NULL;
        $to_date = NULL;

        if(!empty($this->from_date)){
		    $from_date = $formatter->asDate($this->from_date,'php:Y-m-d');
		}
		
		if(!empty($this->to_date)){
		    $to_date = $formatter->asDate($this->to_date,'php:Y-m-d');
		}

        // echo "<br>";print_r($this->session['all']);die();

        if($this->session != 'all' && empty( $this->from_date ) && empty( $this->to_date)){
            $dates = Yii::$app->helper->getDateBySession( $this->session );
            $from_date = $dates['from_date'];
            $to_date = $dates['to_date'];
            $this->session = empty( $this->session )?\app\models\Session::getCurrentSession():$this->session;
        }
        

        $query->andFilterWhere(['>=','date',$from_date]);
		$query->andFilterWhere(['<=','date',$to_date]);
        //echo "<pre>";print_r($this->site_id);die();
        if( $this->site_id ){
            $query->leftJoin('agreement_sites','agreement.id = agreement_sites.agreement_id');
            $query->andWhere(['site_id'=>$this->site_id]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if( empty( $this->status ) ) $this->status = 0;
        
        // grid filtering conditions
        $query->andFilterWhere([
            'agreement.id' => $this->id,
            'agreement.company_id' => $this->company_id,
            'agreement.district_id' => $this->district_id,
            'agreement.file_no' => $this->file_no,
            'agreement.cost' => $this->cost,
            'agreement.date' => $this->date,
            'agreement.status' => $this->status,
            'agreement.expire_date' => $this->expire_date,
            'agreement.state_id' => $this->state_id,
            'agreement.schedule' => $this->schedule,
            'agreement.rate' => $this->rate,
            'agreement.type' => $this->type,
            'agreement.contract_company_id' => $this->contract_company_id,
            'agreement.contract_company_state' => $this->contract_company_state,
            'agreement.contract_company_district' => $this->contract_company_district,
            'agreement.created_at' => $this->created_at,
            'agreement.created_by' => $this->created_by,
            'agreement.updated_at' => $this->updated_at,
            'agreement.updated_by' => $this->updated_by
        ]);
        if(!empty($this->from_date)){
		    $from_date = $formatter->asDate($this->from_date,'php:d-m-Y');
		}
		
		if(!empty($this->to_date)){
		    $to_date = $formatter->asDate($this->to_date,'php:d-m-Y');
		}
        $query->andFilterWhere(['like', 'agreement.agreement_no', $this->agreement_no])
            ->andFilterWhere(['like', 'agreement.zone', $this->zone])
            ->andFilterWhere(['like', 'agreement.gst_no', $this->gst_no])
            ->andFilterWhere(['like', 'agreement.contract_company_gst', $this->contract_company_gst]);
        
        //echo $query->createCommand()->getRawSql();die();
        return $dataProvider;
    }
}
