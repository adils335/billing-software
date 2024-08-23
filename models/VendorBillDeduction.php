<?php

namespace app\models;

use Yii;
use \app\models\base\VendorBillDeduction as BaseVendorBillDeduction;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vendor_bill_deduction".
 */
class VendorBillDeduction extends BaseVendorBillDeduction
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
	
	public function saveDeduction($bill,$data){
		$flag = true;
		
		$removeId = [];
		$idArray = Self::find()->select(['id'])->where(['bill_id'=>$bill->id])->asArray()->all();
		foreach($idArray as $key=>$ids){
			$removeId[] = $ids['id'];
		}
		
		foreach($data as $key=>$value){
	     
         if(!empty($value['id'])){		 
		     $model = Self::findOne($value['id']);
			 $removeId = array_diff($removeId,[$value['id']]);
		 }
		 else
			 $model = new \app\models\VendorBillDeduction;
		       
			   $isRate = !empty($value['is_rate']) && !empty($value['is_rate'])?1:2;
			   $rate = $isRate==1?$value['rate']:0;
			   $loadArray['VendorBillDeduction'] = array(
										'bill_id' => $bill->id, 
			                            'tax_id' => $value['tax_id'], 
			                            'is_rate' => $isRate, 
			                            'rate' => $rate, 
			                            'amount' => $value['amount'], 
										'session' => $bill->session,
										'company_id' => $bill->company_id
			                            );
			  		  
			  if($model->load($loadArray) && $model->validate()){
				  $model->save();
				  
			  }else{
			  	  print_r($model->errors());
				  return false;
			  }							
		   		 
		}
		
		if(!empty($removeId))
		   Self::deleteAll(['id'=>$removeId]);
		
		return $flag;
		
	}
}
