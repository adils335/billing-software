<?php

namespace app\models;

use Yii;
use \app\models\base\VendorBillItems as BaseVendorBillItems;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vendor_bill_items".
 */
class VendorBillItems extends BaseVendorBillItems
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
	
	public function saveItem($bill,$data){
		
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
			 $model = new \app\models\VendorBillItems;
		 
		   if($data[$key]['quantity']){
			   $loadArray['VendorBillItems'] = array(
										'bill_id' => $bill->id,
			                            'district_id' => $value['district_id'],  
			                            'site_id' => $value['site_id'], 
			                            'particular' => $value['particular'], 
			                            'work_type' => $value['work_type'],  
			                            'work_name' => $value['work_name'],  
			                            'uom_id' => $value['uom_id'],  
			                            'hsn_no' => $value['hsn_no'], 
			                            'quantity' => $value['quantity'], 
			                            'rate' => $value['rate'], 
			                            'amount' => $value['amount'], 
										'session' => $bill->session,
										'company_id' => $bill->company_id
			                            );
			  		  
			  if($model->load($loadArray) && $model->validate()){
				  $model->save();
				  
			  }else{
			  	  print_r($model->errors());die();
				  return false;
			  }							
		   }
          		 
		}
		
		if(!empty($removeId))
		   Self::deleteAll(['id'=>$removeId]);
		
		return $flag;
		
	}
	
	public function itemDistrictSiteWise($bill_id,$district_id,$site_id){
		
		$model = Self::find()->where(['bill_id'=>$bill_id,'district_id'=>$district_id,'site_id'=>$site_id])->all();
		return $model;
		
	}
	
	public function getItemWorkType(){
		
		$model = \app\models\VendorWorkRate::find()->where(['vendor_id'=>$this->bill->vendor_id])->groupBy(['work_type'])->all();
		$response = [];
		foreach( $model as $work ){
		    $response[$work->work_type] = $work->workType->name;
		}
		return $response;
		
	}
	
	public function getItemWorks(){
		
		$model = \app\models\VendorWorkRate::find()->where(['vendor_id'=>$this->bill->vendor_id,'work_type'=>$this->work_type])->all();
		$response = [];
		foreach( $model as $work ){
		    $response[$work->work_name] = $work->workName->name;
		}
		return $response;
		
	}
	
}
