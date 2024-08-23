<?php

namespace app\models;

use Yii;
use \app\models\base\District as BaseDistrict;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "district".
 */
class District extends BaseDistrict
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
	public static function buildDistrict(){
		   $sitesDistricts = \app\models\Sites::find()->where(['status'=>\app\models\Sites::ACTIVE_STATUS])->all();
			
           foreach ($sitesDistricts as $sitesDistrict) {
			   $district = $sitesDistrict->district;
               $data[$district->id] = $district->district;
           }
		   return $data;
	}
}
