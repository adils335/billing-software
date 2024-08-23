<?php

namespace app\models;

use Yii;
use \app\models\base\ContractCompanyGst as BaseContractCompanyGst;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "contract_company_gst".
 */
class ContractCompanyGst extends BaseContractCompanyGst
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
    public function getDistrict(){
        $company = Self::findOne($this->id);
        $districtName = [];
        if($company->districts){
          $districts = json_decode($company->districts,true);
          foreach ($districts as $district) {
              $getDistrict = \app\models\District::findOne($district);
              $districtName[] = $getDistrict->district;
          }
        }
        return implode(",", $districtName);
    }
}
