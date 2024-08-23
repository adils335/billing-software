<?php

namespace app\models\Search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Company as CompanyModel;

/**
 * Company represents the model behind the search form of `app\models\Company`.
 */
class Company extends CompanyModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'state', 'district', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'address', 'pincode', 'person', 'number', 'email', 'pancard_no', 'gst_no'], 'safe'],
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
        $query = CompanyModel::find();

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
            'type' => $this->type,
            'state' => $this->state,
            'district' => $this->district,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'pincode', $this->pincode])
            ->andFilterWhere(['like', 'person', $this->person])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'pancard_no', $this->pancard_no])
            ->andFilterWhere(['like', 'gst_no', $this->gst_no]);

        return $dataProvider;
    }
}
