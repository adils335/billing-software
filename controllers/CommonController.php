<?php

namespace app\controllers;

use Yii;
use app\models\Common;
use app\models\Agreement;
use app\models\AgreementSites;
use app\models\AgreementBill;
use app\models\Erpmeta;
use app\models\Employee;
use app\models\Worker;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use app\models\StoreIndents;
use app\models\District;
use app\models\ContractCompanyGst;

/**
 * CommonController implements the CRUD actions for Common model.
 */
class CommonController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionAjaxFromHead($from_head){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if($from_head == Common::FROM_HEAD_CONTRACT_COMPANY){
            $contract_company = \app\models\ContractCompany::find()->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name');    

        }else if($from_head == Common::FROM_HEAD_COMPANY){
            $employees = \app\models\Company::find()->orderBy('id')->all();

            $data = \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name');
        }
        return $data;

    }

    public function actionAjaxToHead($to_head){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

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

    public function actionAjaxAccountByVendor($to_head){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $worker_vendors = \app\models\Worker::find()->where(['worker_vendor_id'=>$vendor])->andWhere(['status'=>\app\models\Worker::STATUS_ACTIVE])->orderBy('id')->all();
        $data = \yii\helpers\ArrayHelper::map(\app\models\WorkerVendor::find()->orderBy('id')->asArray()->all(), 'id', 'name');       
        return $data;
    }
    
    public function actionAjaxBillCompany($billing_company_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $billing_compnay = Agreement::find()->where(['contract_company_id'=>$billing_company_id])->asArray()->all();
        return ArrayHelper::map($billing_compnay,'id','agreement_no');
    }

    public function actionAjaxVendorToAccount($worker_vendor){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\ArrayHelper::map(\app\models\Worker::find()->where(['worker_vendor_id'=>$worker_vendor])->andWhere(['status'=>\app\models\Worker::STATUS_ACTIVE])->orderBy('id')->all(), 'id', 'name');       
        return $data;
    }
    //ajax new search condtition for active bill//
    /*public function actionAjaxState($state_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $districts = District::find()->where(['state_id'=>$state_id])->asArray()->all();
        return ArrayHelper::map($districts,'id','district');
    }*/
    /*public function actionAjaxDistrict($district_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $contract_companies = ContractCompanyGst::find()->where(["LIKE",'districts','%"'.$district_id.'"%',false])->all();
        $output = [];
        foreach( $contract_companies as $company ){
            $output[$company->company_id] = $company->company->name;
        }
        return $output;
    }
    public function actionAjaxContractCompany($contract_company_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $contract_company = Agreement::find()->where(['contract_company_id'=>$contract_company_id])->asArray()->all();
        return ArrayHelper::map($contract_company,'id','agreement_no');
    }*/

    public function actionAjaxAgreement($agreement_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $agreements = AgreementSites::find()->where(['agreement_id'=>$agreement_id])->all();
        $output = [];
        foreach( $agreements as $agreement ){
            $output[$agreement->site_id] = $agreement->site->name;
        }
        return $output;
    }

    public function actionAjaxSite($site_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $sites = StoreIndents::find()->where(['site_id'=>$site_id])->all();
        return ArrayHelper::map($sites,'indent_no','indent_no');
    }

    //end ajax

    //ajax new search condtition for site of payment reports//
    public function actionAjaxState($state_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $districts = District::find()->where(['state_id'=>$state_id])->asArray()->all();
        return ArrayHelper::map($districts,'id','district');
    }
    public function actionAjaxDistrict($district_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $contract_companies = ContractCompanyGst::find()->where(["LIKE",'districts','%"'.$district_id.'"%',false])->all();
        $output = [];
        foreach( $contract_companies as $company ){
            $output[$company->company_id] = $company->company->name;
        }
        return $output;
    }
   public function actionAjaxContractCompany($contract_company_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $contract_company = Agreement::find()->where(['contract_company_id'=>$contract_company_id])->asArray()->all();
        $contract_company = ArrayHelper::map($contract_company,'id','id');
        $agreements = AgreementSites::find()->where(['agreement_id'=>$contract_company])->all();
        $output = [];
        foreach( $agreements as $agreement ){
            $output[$agreement->site_id] = $agreement->site->name;
        }
        return $output;
    }
    
    public function actionAjaxAgreementNo($contract_company_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $contract_company = Agreement::find()->where(['contract_company_id'=>$contract_company_id])->asArray()->all();
        return ArrayHelper::map($contract_company,'id','agreement_no');
    }
    
    public function actionAjaxSites($agreement_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $agreements = AgreementSites::find()->where(['agreement_id'=>$agreement_id])->all();
        $output = [];
        foreach( $agreements as $agreement ){
            $output[$agreement->site_id] = $agreement->site->name;
        }
        return $output;
    }


    //end ajax
    
    public function actionErpmeta(){
        $model = new Erpmeta;
        if( !empty( Yii::$app->request->post() ) ){
            $transaction = Yii::$app->db->beginTransaction();
            $model->load(Yii::$app->request->post());
            if( $model->save() ){
                if( $model::TYPE_EMPLOYEE == $model->type ){
                    $typeModel = Employee::findOne($model->type_id);
                }elseif( $model::TYPE_WORKER == $model->type ){
                    $typeModel = Worker::findOne($model->type_id);
                }
                $typeModel->{$model->meta_key} = Yii::$app->formatter->asDate($model->meta_value,'php:Y-m-d');
                if( $typeModel->save() ){
                    Yii::$app->session->setFlash('success','Successfully Added');
                    $transaction->commit();
                }else{
                    Yii::$app->session->setFlash('error', json_encode($typeModel->getErrors()));
                    $transaction->rollBack();
                } 
            }else{
                Yii::$app->session->setFlash('error', json_encode($model->getErrors()));
                $transaction->rollBack();
            }  
        }
        $this->redirect( Yii::$app->request->referrer );
    }
    public function actionContractCompany($company_id){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->where(['company_id'=>$company_id])->asArray()->all(),'id','name');;
        
    }
    
    public function actionSite($company_id, $state, $district, $model ){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $sites = \app\models\Sites::find()->where(['company_id' => $company_id,'state_id'=>$state, 'district_id'=>$district,'status'=>\app\models\Sites::ACTIVE_STATUS])->all();
        $response = [];
        if( !empty($sites) ){
            foreach($sites as $site){
                $response[$site->id] = $site->name;
            }
        }
        
        return $response;
        
    }

    
    public function actionDistrict($company_id, $state, $model ){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($model == "BillingCompany"){
           $districts = \app\models\BillingCompanyGst::find()->select('districts')->where(['company_id' => $company_id,'state_id'=>$state])->one();
        }elseif($model == "ContractCompany"){
           $districts = \app\models\Sites::find()->where(['company_id' => $company_id,'state_id'=>$state])->all();
            foreach($districts as $district){
                $response[$district->district_id] = $district->district->district;
            }
            return $response;
        }else{
           $districts = \yii\helpers\ArrayHelper::map(\app\models\District::find()->select(['id','district'])->where(['state_id'=>$state])->all(),'id','district');  
           return $districts;
        }
        $response = [];
        if( !empty($districts) ){
            $districts = json_decode($districts->districts,true);
            $districts = \app\models\District::find()->select(['id','district'])->where(['id'=>$districts])->orderBy('id')->all();
            foreach($districts as $district){
                $response[$district->id] = $district->district;
            }
        }
        
        
        return $response;
        
    }
    
    public function actionState($company_id,$model="BillingCompany"){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if($model == "BillingCompany"){
           $states = \app\models\BillingCompanyGst::find()->where(['company_id' => $company_id])->all();
        }elseif($model == "ContractCompany"){
           $states = \app\models\Sites::find()->where(['company_id' => $company_id])->all();
           
           
        }
        $response = [];
        foreach($states as $state){
            $response[$state->state_id] = $state->state->state;
        }
        
        return $response;
        
    }
    
    public function actionAjaxWorkRate($vendor_id,$work_type,$work_name){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $workNames = \app\models\VendorWorkRate::find()->where(['vendor_id'=>$vendor_id,'work_type'=>$work_type,'work_name'=>$work_name])->one();
      $output = ['rate'=>''];
      if( !empty($workNames) ){
          $output['rate'] = $workNames->rate;
      }
      return $output;
    }
    
    public function actionAjaxWorkName($vendor_id,$work_type){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $workNames = \app\models\VendorWorkRate::find()->where(['vendor_id'=>$vendor_id,'work_type'=>$work_type])->all();
      $output[] = ['id'=>'','text'=>'select'];
      foreach($workNames as $workName){
          $output[] = ['id'=>$workName->work_name,'text'=>$workName->workName->name];
      }
      return ['data'=>$output];
    }
    
    public function actionAjaxWorkType($vendor_id){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $workTypes = \app\models\VendorWorkRate::find()->where(['vendor_id'=>$vendor_id])->groupBy(['work_type'])->all();
      $output[] = ['id'=>'','text'=>'select'];
      foreach($workTypes as $workType){
          $output[] = ['id'=>$workType->work_type,'text'=>$workType->workType->name];
      }
      return ['data'=>$output];
    }
    
    public function actionAjaxStamp($company){
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
      $signatureMaster = \app\models\SignatureMaster::find()->where(['company_id'=>$company])->all();
      $signature = [];
      foreach($signatureMaster as $item){
          $signature[$item->id] = $item->type->type;
      }
      $data['signature'] = $signature;
      $billModel = new AgreementBill();
      $lastBill = $billModel->lastBill($company);
      $lastBillDetail = $this->renderAjax('../general-bill/_last_bill_detail',['lastBill'=>$lastBill]);
      $data['lastBillDetail'] = $lastBillDetail;
      return $data;
      
    }

    public function actionShipToDistrict($state){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $districts = \app\models\BillingCompanyGst::find()->where(['state_id'=>$state])->all();
        $billingDistrict = [];
        foreach($districts as $district){
            $json = json_decode($district->districts,true);
            foreach($json as $dist){
                $billingDistrict[] = $dist;
            }
        }
        $billingDistrict = array_unique($billingDistrict);
        $allDistrict = \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['id'=>$billingDistrict])->asArray()->all(),'id','district');
        return $allDistrict;
        
    }
    
    public function actionShipToCompany($district,$model="BillingCompany"){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if($model == "BillingCompany"){
           $districts = \app\models\BillingCompanyGst::find()->where(['like','districts','"'.$district.'"'])->all();
        }elseif($model == "ContractCompany"){
           $districts = \app\models\ContractCompanyGst::find()->where(['like','districts','"'.$district.'"'])->all();
        }
        $companyId = [];
        foreach($districts as $district){
            $companyId[] = $district->company_id;
        }
        if($model == "BillingCompany"){
           $allDistrict = \yii\helpers\ArrayHelper::map(\app\models\BillingCompany::find()->where(['id'=>$companyId])->asArray()->all(),'id','name');
        }elseif($model == "ContractCompany"){
           $allDistrict = \yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->where(['id'=>$companyId])->asArray()->all(),'id','name');
        }
        
        return $allDistrict;
        
    }
    
    public function actionShipToGst($company,$state){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $gst = \app\models\BillingCompanyGst::find()->where(['company_id'=>$company,'state_id'=>$state])->one();
        return $gst->gst_no;
        
    }
    
    public function actionContractCompanyDistrict($state){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $districts = \app\models\Agreement::find()->select(['contract_company_district'])->where(['contract_company_state'=>$state])->distinct()->asArray()->all();
        $districtId = array_column($districts,'contract_company_district');
        $districts = \app\models\District::find()->select(['id','district'])->where(['id'=>$districtId])->orderBy('id')->all();
        $data = [['id' => '', 'text' => '']];
        foreach ($districts as $district) {
            $data[] = ['id' => $district->id, 'text' => $district->district];
        }
        return ['data' => $data];
        
    }

    public function actionContractCompanyName($district){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $contract_companies = \app\models\ContractCompanyGst::find()->where(["LIKE",'districts','%"'.$district.'"%',false])->all();
        // echo "<pre>";print_r($contract_companies);die();
        $output = [];
        foreach( $contract_companies as $company ){
            $output[$company->company_id] = $company->company->name;
        }
        return $output;
        
    }

    public function actionAjaxByStatus($contract_company_id,$status){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $contract_company_with_status = Agreement::find()->where(['contract_company_id'=>$contract_company_id,'status'=>$status])->asArray()->all();
        $data = \yii\helpers\ArrayHelper::map($contract_company_with_status,'id','agreement_no');
        // echo "<pre>";print_r($data);die();
        return $data;


    }

    // public function actionAjaxAgreementNo($contract_company_id){
    //     \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    //     $contract_company_name = Agreement::find()->where(['contract_company_id'=>$contract_company_id])->asArray()->all();
    //     $data = \yii\helpers\ArrayHelper::map($contract_company_name,'id','agreement_no');
    //     return $data;
    // }


    public function actionAjaxSiteName($agreement_no){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $agreement_sites = AgreementSites::find()->where(['agreement_id'=>$agreement_no])->all();
        // echo "<pre>";print_r($agreement_sites);die();
        $data = [];
        foreach( $agreement_sites as $site ){
            // echo "<pre>" ; print_r($site);die();
            $data[$site->site_id] = $site->site->name;
        }
        return $data;
    }
    public function actionAjaxDistrictByState($state_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return \yii\helpers\ArrayHelper::map(\app\models\District::find()->where(['state_id'=>$state_id])->all(),'id','district');
    }

    

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Common::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    
    
}
