<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementRateSchedule as BaseAgreementRateSchedule;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agreement_rate_schedule".
 */
class AgreementRateSchedule extends BaseAgreementRateSchedule
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
    
    public function getRemainingQuantity(){
		  
		  $totalQuantity = $this->quantity;
		  $agreement = $this->agreement_id;
		  $item = $this->id;
		  $invoices = \app\models\AgreementBill::find()->select(['id'])->where(['agreement_id'=>$agreement,'related_invoice'=>Null])->andWhere(['!=','status',\app\models\AgreementBill::STATUS_DELETE])->all();
		  $invoice_ids  = \yii\helpers\ArrayHelper::getColumn($invoices,'id');
		  $billsQuantity = \app\models\BillItem::find()->where(['agreement_id'=>$agreement,'item'=>$item,'invoice_id'=>$invoice_ids])->sum("quantity");
		  
	      return $totalQuantity - $billsQuantity;
		  
	}
	
	public static function saveGeneralRate($agreement,$data){
	    $billItem = $data;
	    unset($billItem['item_text']);
	    $loop = $data['sno'];
	    foreach($loop as $key=>$item){
	        $rateSchedule = new \app\models\AgreementRateSchedule;
	        if(!empty($data['item'][$key])){
	            $rateSchedule = \app\models\AgreementRateSchedule::find()->where(['id'=>$data['item'][$key]])->one();
	        }
	        $rateSchedule->agreement_id = $agreement->id;
	        $rateSchedule->session = $agreement->session;
	        $rateSchedule->company_id = $agreement->company_id;
	        $rateSchedule->sno = $data['sno'][$key];
	        $rateSchedule->type = null;
	        $rateSchedule->item = $data['item_text'][$key];
	        $rateSchedule->hsn_no = $data['hsn_no'][$key];
	        $rateSchedule->unit = $data['unit'][$key];
	        $rateSchedule->quantity = $data['quantity'][$key];
	        $rateSchedule->rate = $data['rate'][$key];
	        if(!$rateSchedule->save()){
	            echo "<pre>";print_r($rateSchedule->getErrors());die();
	        }
	        $billItem['item'][$key] = $rateSchedule->id;
	    }
	    return $billItem;
	    
	}
	
}
