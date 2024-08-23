<?php

namespace app\models;

use Yii;
use \app\models\base\BillPenality as BaseBillPenality;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bill_penality".
 */
class BillPenality extends BaseBillPenality
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
    

    public function savePenality($agreementBill,$data){
        
        $flag = true;
        
        $taxes = $data['tax_id'];
        
        foreach($taxes as $key=>$value){
            
         $model = Self::find()
                ->where(['tax_id'=>$value,'invoice_id'=>$agreementBill->id,'session'=>$agreementBill->session,'company_id'=>$agreementBill->company_id])->one();
         if(empty($model))
             $model = new \app\models\BillPenality;
         
               $loadArray['BillPenality'] = array(
                                        'tax_id' => $data['tax_id'][$key],  
                                        'amount' => $data['amount'][$key], 
                                        'invoice_id' => $agreementBill->id,
                                        'agreement_id' => $agreementBill->agreement_id,
                                        'session' => $agreementBill->session,
                                        'company_id' => $agreementBill->company_id
                                        );
              
              if($model->load($loadArray) && $model->save()){
                  
              }else{
                  return false;
              }                         
                 
        }
        
        return $flag;
        
    }
    
}
