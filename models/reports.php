<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementBill as BaseAgreementBill;
use \app\models\AgreementBillStatus;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * This is the model class for table "agreement_bill".
 */
class Reports extends BaseAgreementBill
{
    const REPORT_PAID_GST = 1;

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

    public function buildReport()
    {
        return [
            self::REPORT_PAID_GST => 'Paid Gst'
        ];
    }

    public function reportsAction( $action ){
        $reports = $this->buildReport();
        if( isset( $reports[$action] ) ){
            return $reports[$action];
        }
    }
    
}
