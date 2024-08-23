<?php

namespace app\models;

use Yii;
use \app\models\base\VendorWorkRate as BaseVendorWorkRate;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vendor_work_rate".
 */
class VendorWorkRate extends BaseVendorWorkRate
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

    public function workDescription(){
        $work_type = VendorWorkRate::find()->where(['vendor_id'=>$this->vendor_id,'work_type'=>$this->work_type])->orderBy(['id'=>SORT_ASC])->all();

        return $work_type;
    }
}
