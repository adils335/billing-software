<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementBillStatus as BaseAgreementBillStatus;
use \app\models\AgreementBill;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agreement_bill_status".
 */
class AgreementBillStatus extends BaseAgreementBillStatus
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

    public function getStatusLabel(){
        $statuses = AgreementBill::buildStatus();
        return $statuses[$this->status];
    }
    
    public function getLastStatus(){
        $statuses = AgreementBill::buildStatus();
        return $statuses[$this->last_status];
    }
}
