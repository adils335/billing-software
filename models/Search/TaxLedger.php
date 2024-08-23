<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TaxLedger as TaxLedgerModel;

/**
 * TaxLedger represents the model behind the search form of `app\models\TaxLedger`.
 */
class TaxLedger extends TaxLedgerModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'page_id', 'page', 'tax_id', 'tax_type', 'inout', 'status', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['date', 'session'], 'safe'],
            [['rate', 'amount'], 'number'],
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
        $query = TaxLedgerModel::find();

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
            'page_id' => $this->page_id,
            'page' => $this->page,
            'date' => $this->date,
            'tax_id' => $this->tax_id,
            'tax_type' => $this->tax_type,
            'rate' => $this->rate,
            'amount' => $this->amount,
            'inout' => $this->inout,
            'status' => $this->status,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'session', $this->session]);

        return $dataProvider;
    }
}
