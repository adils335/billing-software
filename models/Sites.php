<?php

namespace app\models;

use Yii;
use \app\models\base\Sites as BaseSites;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sites".
 */
class Sites extends BaseSites
{
    const ACTIVE_STATUS = 1;
    const ARCHIVE_STATUS = 2;
    
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
    
    public static function buildStatus(){
        return [
                self::ACTIVE_STATUS => "Active",
                self::ARCHIVE_STATUS => "Archive"
               ];
    }
    
    public  function getStatusLabel(){
		
			if(isset(self::buildStatus()[$this->status])){
				return self::buildStatus()[$this->status];
			}
		
	}
    
    
	public function getContractCompanyDistricts(){
		$district = \app\models\ContractCompanyGst::find()->where(['state_id'=>$this->state_id])->one();
        $districts = json_decode($district->districts);
        $array = [];
        if($districts)
        foreach ($districts as $value) {
        	$getDistrict = \app\models\District::find()->where(['id'=>$value])->one();
        	$array[$value] = $getDistrict->district;
        }

		return $array;
	}
    
}
