<?php

namespace app\models;

use Yii;
use \app\models\base\BillTax as BaseBillTax;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bill_tax".
 */
class BillTax extends BaseBillTax
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
	
	public function saveTax($agreementBill,$data){
		
		$flag = true;
		
		$taxes = $data['tax_id'];
		
		foreach($taxes as $key=>$value){
			
		 $model = Self::find()
		        ->where(['tax_id'=>$value,'invoice_id'=>$agreementBill->id,'session'=>$agreementBill->session,'company_id'=>$agreementBill->company_id])->one();
		 if(empty($model))
			 $model = new \app\models\BillTax;
		 
			   $loadArray['BillTax'] = array(
			                            'tax_id' => $data['tax_id'][$key],  
			                            'rate' => $data['rate'][$key], 
			                            'amount' => $data['amount'][$key], 
										'invoice_id' => $agreementBill->id,
										'agreement_id' => $agreementBill->agreement_id,
										'session' => $agreementBill->session,
										'company_id' => $agreementBill->company_id
			                            );
			  
			  if($model->load($loadArray) && $model->save()){
				 // echo "<pre>";print_r($model);die();
			  }else{
				  return false;
			  }							
		   		 
		}
		
		return $flag;
		
	}
	
	public static function getTaxValue($invoice_id,$gst){
	    $tax = Self::find()->where(['invoice_id'=>$invoice_id,'tax_id'=>$gst])->one();
	    if(!empty($tax)){
	        return $tax->amount;
	    }else{
	        return "0.00";
	    }
	}
	
}
