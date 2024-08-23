<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "account".
 */
class Common extends \yii\db\ActiveRecord
{
    const FROM_HEAD_CONTRACT_COMPANY = 1;
    const FROM_HEAD_COMPANY = 2;
    const TO_HEAD_CONTRACT_COMPANY = 1;
    const TO_HEAD_COMPANY = 2;
    const TO_HEAD_EMPLOYEE = 3;
    const TO_HEAD_VENDOR = 4;
    const TO_HEAD_WORKER = 5;
    const TO_HEAD_WORKER_VENDOR = 6;


    public static function getSiteNameByContractCompany($contract_company_id){
        $agreements = \app\models\Agreement::find()->select(['id'])->where(['contract_company_id' => $contract_company_id]);
        $agreement_sites = \app\models\AgreementSites::find()->where(['agreement_id'=>$agreements])->orderBy('id')->all();
        $output = [];
        foreach( $agreement_sites as $sites ){
            $output[$sites->site_id] = $sites->site->name;
        }
        return $output;
    }

    //new start
    public function billingCompany($district_id){
        $billing_name = \app\models\ContractCompanyGst::find()->where(["LIKE",'districts','%"'.$district_id.'"%',false])->all();
        $output = [];
        foreach( $billing_name as $company ){
            $output[$company->company_id] = $company->company->name;
        }
        return $output; 
    }

    public function siteName($agreement_id){
        $name = \app\models\AgreementSites::find()->where(['agreement_id'=>$agreement_id])->all();
        $output = [];
        foreach( $name as $agreement ){
            $output[$agreement->site_id] = $agreement->site->name;
        }
        return $output; 
    }
    //new end

    public function getCompanyAddresses( $company_id, $state_id, $type, $district_id ){
        $response = [];
        $class = "";
        switch( $type ){
           case 1:$class="\app\models\CompanyGst";break;
           case 2:$class="\app\models\ContractCompanyGst";break;
           case 3:$class="\app\models\BillingCompanyGst";break;
        }
        if( empty( $class ) ){
           return $response;
        }
        $type_id = $class::find()->where(['company_id'=>$company_id,'state_id'=>$state_id])->one();
        if( empty( $type_id ) ){
           return $response;
        }
        $address =  \app\models\CompanyAddresses::find()->where(['type_id'=>$type_id,'district_id'=>$district_id])->one();
        if( empty( $address ) ){
           return $response;
        }
        $response['gst_no'] = $type_id->gst_no;
        $response['state_tin'] = $type_id->state->state_tin;
        $response['legal_name'] = $address->legal_name;
        $response['trade_name'] = $address->trade_name;
        $response['address_1'] = $address->address_1;
        $response['address_2'] = $address->address_2;
        $response['location'] = $address->location;
        $response['pincode'] = $address->pincode;
        $response['phone'] = $address->phone;
        $response['email'] = $address->email;
        return $response;
    }
    
    public function agreementNoByStatus($contract_company_id,$status){
        $contract_company_with_status = Agreement::find()
                                        ->where(['contract_company_id'=>$contract_company_id,'status'=>$status])
                                        ->asArray()
                                        ->all();
        $data = \yii\helpers\ArrayHelper::map($contract_company_with_status,'id','agreement_no');
        // echo "<pre>";print_r($data);die();
        return $data;
    }

    public static function getCompanies(){
        /*$companies = Yii::$app->user->identity->access_company;
        if( !empty($companies) ){
           $companies = json_decode($companies,true); 
           return \yii\helpers\ArrayHelper::map(\app\models\Company::find()->where(['id'=>$companies])->orderBy('id')->asArray()->all(), 'id', 'name');
        }*/
        return \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name');
    }
  public static function contractCompanyDistricts($company_id,$state_id){
		$district = \app\models\ContractCompanyGst::find()->where(['state_id'=>$state_id,'company_id'=>$company_id])->one();
        $districts = json_decode($district->districts);
        $array = [];
        if($districts)
        foreach ($districts as $value) {
        	$getDistrict = \app\models\District::find()->where(['id'=>$value])->one();
        	$array[$value] = $getDistrict->district;
        }

		return $array;
  } 
  public static function contractCompanyState($company_id){
        $states = \app\models\ContractCompanyGst::find()->where(['company_id' => $company_id])->all();
        $response = [];
        foreach($states as $state){
            $response[$state->state_id] = $state->state->state;
        }
        return $response;
  }    
  
  public static function shuffleBill($data){
      $formatter = Yii::$app->formatter;
      $agreementData = $data['Agreement'];
      $agreement = new \app\models\Agreement;
      if(!empty($data['AgreementBill']['invoice_no'])){
          $agreement = \app\models\Agreement::find()->where(['id'=>$data['Agreement']['agreement_id']])->one();
      }
      unset($agreementData['agreement_id']);
      $agreement->load(['Agreement'=>$agreementData]);
      $agreement->agreement_name = $agreement->agreement_no;
      $agreement->cost = 0;
      $agreementBill = new \app\models\AgreementBill;
      if(!empty($data['AgreementBill']['invoice_no'])){
          $agreementBill = \app\models\AgreementBill::find()->where(['agreement_id'=>$agreement->id,'invoice_no'=>$data['AgreementBill']['invoice_no']])->one();
      }
      $agreementBill->load(['AgreementBill'=>$data['AgreementBill']]);
      $agreementBill->invoice_date = $formatter->asDate($agreementBill->invoice_date,'php:Y-m-d');
      $agreement->date = $agreementBill->invoice_date;
      $agreementBill->company_id = $agreement->company_id;
      $agreementBill->session = $agreement->session;
      $agreementBill->agreement_type = $agreement->type;
      $billItem = [];
      foreach($data['BillItem'] as $item){
          $billItem['sno'][] =  $item['sno'];
          $billItem['item'][] =  $item['item'];
          $billItem['item_text'][] =  $item['item_text'];
          $billItem['hsn_no'][] =  $item['hsn_no'];
          $billItem['unit'][] =  $item['unit'];
          $billItem['quantity'][] =  $item['quantity'];
          $billItem['rate'][] =  $item['rate'];
          $billItem['amount'][] =  $item['amount'];
      }
      $billTax = [];
      foreach($data['BillTax'] as $tax){
          $billTax['tax_id'][] =  $tax['tax_id'];
          $billTax['rate'][] =  $tax['rate'];
          $billTax['amount'][] =  $tax['amount'];
      }
      
      return ['Agreement'=>$agreement,'AgreementBill'=>$agreementBill,'BillItem'=>$billItem,'BillTax'=>$billTax,'BillDeduction'=>$data['BillDeduction']];
      
  }
        
  public static function tempId(){
     return time();  
  }
  
  public static function billToCompanyState(){
      
      $billToCompanyState = \app\models\ContractCompanyGst::find()->select(['state_id'])->distinct()->asArray()->all();
      $stateId = array_column($billToCompanyState,'state_id');
      $state = [];
      $allState = \app\models\State::find()->where(['id'=>$stateId])->asArray()->all();
      return $allState;
      
  }
  
  public static function shipToCompanyState(){
      
      $shipToCompanyState = \app\models\BillingCompanyGst::find()->select(['state_id'])->distinct()->asArray()->all();
      $stateId = array_column($shipToCompanyState,'state_id');
      $state = [];
      $allState = \app\models\State::find()->where(['id'=>$stateId])->asArray()->all();
      return $allState;
      
  }
  
  public static function agreementState(){
      
      $states = \app\models\Agreement::find()->select(['contract_company_state'])->distinct()->asArray()->all();
      $statesId = array_column($states,'contract_company_state');
      return \yii\helpers\ArrayHelper::map(\app\models\State::find()->where(['id'=>$statesId])->orderBy('id')->asArray()->all(), 'id', 'state');
      
  }
  
  public static function agreementDistrict($state){
      
      $districts = \app\models\Agreement::find()->select(['contract_company_district'])->where(['contract_company_state'=>$state])->distinct()->asArray()->all();
      $districtId = array_column($districts,'contract_company_district');
      return \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['id'=>$districtId])->orderBy('id')->asArray()->all(), 'id', 'district');
      
  }
    
  public static function signature($company){
      
      $signatureMaster = \app\models\SignatureMaster::find()->where(['company_id'=>$company])->all();
      $signature = [];
      foreach($signatureMaster as $item){
          $signature[$item->id] = $item->type->type;
      }
      return $signature;
      
  }
  
    public static function uploadFile($path,$file,$oldFile = null){
      
        $root = Yii::getAlias("@webroot");
        if(empty($file)){
            return $oldFile;
        }
        
        $filename = time(). "-" .uniqid().".".$file->extension;
        if($file->saveAs($root.$path.$filename)){
            if(!empty($oldFile))
                unlink($root.$path.$oldFile);
        }else{
            return false;
        } 
        return $filename;
    }

    public static function buildFromHead(){
        return[

            self::FROM_HEAD_CONTRACT_COMPANY =>'Contract Company',
            self::FROM_HEAD_COMPANY=>'Company',
        ];
			
	}
	public  function getFromHeadLabel(){
		
        if(isset(self::buildFromHead()[$this->from_head])){
            return self::buildFromHead()[$this->from_head];
        }
		
	}

    public static function buildToHead(){
        return[
            self::TO_HEAD_CONTRACT_COMPANY =>'Contract Company',
            self::TO_HEAD_COMPANY =>'Company',
            self::TO_HEAD_EMPLOYEE =>'Employee',
            self::TO_HEAD_VENDOR =>'Vendor',
            self::TO_HEAD_WORKER =>'Worker',
            self::TO_HEAD_WORKER_VENDOR=>'Worker Vendor',
        ];
			
	}
	public  function getToHeadLabel(){
		if(isset(self::buildToHead()[$this->to_head])){
            return self::buildToHead()[$this->to_head];
        }	
	}

    public function fromAccount($from_head){
		
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if($from_head == Common::FROM_HEAD_CONTRACT_COMPANY){
            $contract_company = \app\models\ContractCompany::find()->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name');    
            
        }else if($from_head == Common::FROM_HEAD_COMPANY){
            $employees = \app\models\Company::find()->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name');
        }
        return $data;
	}

    public function toAccount($to_head){
        //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if($to_head == Common::TO_HEAD_CONTRACT_COMPANY){
            $contract_company = \app\models\ContractCompany::find()->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name');    

        }else if($to_head == Common::TO_HEAD_COMPANY){
            $employees = \app\models\Company::find()->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name');

        }else if($to_head == Common::TO_HEAD_EMPLOYEE){
            $employees = \app\models\Employee::find()->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\Employee::find()->orderBy('id')->asArray()->all(), 'id', 'emp_name');

        }
        else if($to_head == Common::TO_HEAD_VENDOR){
            $vendor = \app\models\Vendor::find()->andWhere(['status'=>\app\models\Vendor::STATUS_ACTIVE])->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\Vendor::find()->orderBy('id')->asArray()->all(), 'id', 'name');
            
        }else if($to_head == Common::TO_HEAD_WORKER){
            $worker = \app\models\Worker::find()->andWhere(['status'=>\app\models\Worker::STATUS_ACTIVE])->orderBy('id')->all();  

            $data = \yii\helpers\ArrayHelper::map(\app\models\Worker::find()->orderBy('id')->asArray()->all(), 'id', 'name');

        }else if($to_head == Common::TO_HEAD_WORKER_VENDOR){
            $worker_vendor = \app\models\WorkerVendor::find()->andWhere(['status'=>\app\models\WorkerVendor::STATUS_ACTIVE])->orderBy('id')->all();
            
            $data = \yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->orderBy('id')->asArray()->all(), 'id', 'name');
        }
        return $data;
    }

}
