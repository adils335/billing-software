<?php

namespace app\models\Search;


use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EmployeeLeave as EmployeeLeaveModel;

/**
 * EmployeeLeave represents the model behind the search form of `app\models\EmployeeLeave`.
 */
class EmployeeLeave extends EmployeeLeaveModel
{
    public $session;
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
            [['id', 'employee_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['month', 'leave', 'comments','session','from_month','to_month','from_date','to_date'], 'safe'],
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
        $query = EmployeeLeaveModel::find();
        

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
            'employee_id' => $this->employee_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);
        if($this->month){
            $query->andWhere(['month'=>$formatter->asDate("01-".$this->month,"php:Y-m-d")]);
        }

        $query->andFilterWhere(['like', 'leave', $this->leave])
            ->andFilterWhere(['like', 'comments', $this->comments]);

        //echo $query->createCommand()->getRawSql();die();

        return $dataProvider;
    }
}
