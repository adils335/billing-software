<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BillItem as BillItemModel;

/**
 * BillItem represents the model behind the search form of `app\models\BillItem`.
 */
class BillItem extends BillItemModel
{
    public $from_month;
    public $to_month;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'invoice_id', 'agreement_id', 'sno', 'item', 'unit', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['hsn_no', 'session','from_month','to_month'], 'safe'],
            [['gst', 'quantity', 'percentage', 'rate', 'amount'], 'number'],
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
        $query = BillItemModel::find();

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
            'invoice_id' => $this->invoice_id,
            'agreement_id' => $this->agreement_id,
            'sno' => $this->sno,
            'item' => $this->item,
            'gst' => $this->gst,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'percentage' => $this->percentage,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'hsn_no', $this->hsn_no])
            ->andFilterWhere(['like', 'session', $this->session]);

        return $dataProvider;
    }
}
