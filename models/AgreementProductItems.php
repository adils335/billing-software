<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementProductItems as BaseAgreementProductItems;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agreement_product_items".
 */
class AgreementProductItems extends BaseAgreementProductItems
{
    const STATUS_ACTIVE = 1;
	const STATUS_DEACTIVE = 2;
	const STATUS_DELETE = 3;

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
            self::STATUS_DEACTIVE =>'Deactive',
            self::STATUS_ACTIVE	=>'Active',
            self::STATUS_DELETE	=>'Delete',
        ];
    }

    public function getStatusLabel(){
        if(isset(self::buildStatus()[$this->status])){
            return self::buildStatus()[$this->status];
        }
    }
}
