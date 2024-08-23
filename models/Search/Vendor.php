<?php

namespace app\models\Search;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Vendor as VendorModel;

/**
 * Vendor represents the model behind the search form of `app\models\Vendor`.
 */
class Vendor extends VendorModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'code', 'state_id', 'district_id', 'balance_type', 'company_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'email', 'mobile', 'address', 'gst_no', 'pancard_no', 'session'], 'safe'],
            [['last_balance'], 'number'],
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
        $query = VendorModel::find();
        $query->where(['!=','status',Vendor::STATUS_DELETE]);
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
            'code' => $this->code,
            'state_id' => $this->state_id,
            'district_id' => $this->district_id,
            'last_balance' => $this->last_balance,
            'balance_type' => $this->balance_type,
            'company_id' => $this->company_id,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'gst_no', $this->gst_no])
            ->andFilterWhere(['like', 'pancard_no', $this->pancard_no])
            ->andFilterWhere(['like', 'session', $this->session]);

        return $dataProvider;
    }
}
