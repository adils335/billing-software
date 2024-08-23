<?php

namespace app\models;

use Yii;
use \app\models\base\BillDeduction as BaseBillDeduction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bill_deduction".
 */
class BillDeduction extends BaseBillDeduction
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
	
	public function saveDeduction($agreementBill,$data){
		//echo "<pre>";print_r($data);die();
		$flag = true;
		
		$taxes = $data['tax_id'];
		
		foreach($taxes as $key=>$value){
			
		 $model = Self::find()
		        ->where(['tax_id'=>$value,'invoice_id'=>$agreementBill->id,'session'=>$agreementBill->session,'company_id'=>$agreementBill->company_id])->one();
		 if(empty($model))
			 $model = new \app\models\BillDeduction;
		       $isRate = !empty($data['is_rate']) && !empty($data['is_rate'][$key])?1:2;
		       $rate = $isRate==1?$data['rate'][$key]:Null;
			   $loadArray['BillDeduction'] = array(
			                            'tax_id' => $data['tax_id'][$key],  
			                            'is_rate' => $isRate,  
			                            'rate' => $rate, 
			                            'amount' => $data['amount'][$key], 
										'invoice_id' => $agreementBill->id,
										'agreement_id' => $agreementBill->agreement_id,
										'session' => $agreementBill->session,
										'company_id' => $agreementBill->company_id
			                            );
			  if($model->load($loadArray) && $model->save()){
				  
			  }else{
			      echo "<pre>";print_r($model->getErrors());die();
				  return false;
			  }							
		   		 
		}
		
		return $flag;
		
	}
	
}
