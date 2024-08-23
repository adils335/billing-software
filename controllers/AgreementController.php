<?php

namespace app\controllers;

use Yii;
use app\models\Agreement;
use app\models\AgreementBill;
use app\models\AgreementGauranty;
use app\models\AgreementSites;
use app\models\AgreementTax;
use app\models\AgreementVendor;
use app\models\AgreementBillBack;
use app\models\Search\Agreement as AgreementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter; 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\Wizard;
use app\models\Documents;

/**
 * AssessmentController implements the CRUD actions for Assessment model.
 */
 
class AgreementController extends Controller
{
    const AGREEMENT_WIZARD_STEP_INFORMATION = 'information';
    const AGREEMENT_WIZARD_STEP_GAURANTY = 'gauranty';
    const AGREEMENT_WIZARD_STEP_SITES = 'sites';
    const AGREEMENT_WIZARD_STEP_VENDOR = 'vendor';
    const AGREEMENT_WIZARD_STEP_TAX = 'tax';
    
    /**
     * @inheritdoc
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
    
    public function actionChangeStatus($id,$status,$refresh){
         
         if( Yii::$app->request->isAjax ){
              \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
              $agreement = Agreement::findOne($id);
              $agreement->status = $status;
              $agreement->save();
              return ['status'=>true,'refresh'=>$refresh];
         }
         
    }
    
    public function actionIndexByAjax($id){
        $this->layout = 'history-main';
        $searchModelBill = new \app\models\Search\AgreementBill();
        $dataProviderBill = $searchModelBill->search(Yii::$app->request->queryParams);
        $dataProviderBill->query->where(['agreement_id'=>$id,'status'=>AgreementBill::STATUS_ACTIVE])->orderBy(['id'=>SORT_DESC]);
        $dataProviderBill->pagination = false;
        $document = new Documents;
        return $this->render('view', [
            'model' => $this->findModel($id),
			'searchModelBill' => $searchModelBill,
			'dataProviderBill' => $dataProviderBill,
			'document' => $document
        ]);
        
    }

    /**
     * Lists all Assessment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->query->andWhere(['type'=>Agreement::TYPE_AGREEMENT])->orderBy(['session'=>SORT_DESC,'file_no'=>SORT_DESC]);
        // echo "<pre>";print_r($dataProvider);die();
        //echo "<pre>";print_r($dataProvider->query);die();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Assessment model.
     * @param integer $id
     * @return mixed
     */

    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $searchModelBill = new \app\models\Search\AgreementBill();
        $dataProviderBill = $searchModelBill->search(Yii::$app->request->queryParams);
        $dataProviderBill->query->where(['agreement_id'=>$id,'status'=>AgreementBill::STATUS_ACTIVE])->orderBy(['id'=>SORT_DESC]);
        $document = new Documents;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'document'=>$document,
			'searchModelBill' => $searchModelBill,
			'dataProviderBill' => $dataProviderBill
        ]);
    }

    /**
     * Deletes an existing Assessment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = $this->findModel($id);
        $model->status = $model::STATUS_DELETE;
        $model->save();

        return $this->redirect(['index']);
    }

    public function actionCreateAgreement($id = null, $step = null) {  
        
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        } 
		
		$agreement = new Agreement;
		
		if($id)
           $agreement = $this->findModel($id); 
        
        $config = [
            'id'=> 'agreement_'.$id,
            'steps' => [
                self::AGREEMENT_WIZARD_STEP_INFORMATION => [
                    'title' => Yii::t('app', 'Information'),
                    'callback' => 'agreement_wizard_step_information',
                    'view' => 'information'
                ], 
                self::AGREEMENT_WIZARD_STEP_GAURANTY => [
                    'title' => Yii::t('app', 'Gauranty'),
                    'callback' => 'agreement_wizard_step_gauranty',
                    'view' => 'gauranty'
                ], 
                self::AGREEMENT_WIZARD_STEP_SITES => [
                    'title' => Yii::t('app', 'Sites'),
                    'callback' => 'agreement_wizard_step_sites',
                    'view' => 'sites'
                ],  
                self::AGREEMENT_WIZARD_STEP_TAX => [
                    'title' => Yii::t('app', 'Tax'),
                    'callback' => 'agreement_wizard_step_tax',
                    'view' => 'tax'
                ],
                self::AGREEMENT_WIZARD_STEP_VENDOR => [
                    'title' => Yii::t('app', 'Vendor'),
                    'callback' => 'agreement_wizard_step_vendor',
                    'view' => 'vendor'
                ],  
            ],
            'onCompleteWizard' => 'agreement_wizard_completed',
            'data' => [
                'agreement' => $agreement,
            ]
        ]; 
         
        if(!empty($config)){
            $config['class'] = Wizard::className();
            $this->attachBehavior('agreementWizard', $config); 
        }  
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $stepData = $agreement_wizard->_getSession('stepData');
        
        if(!empty($agreement_wizard->get_manual_current_step())){
            if($agreement_wizard->get_manual_current_step()=='completed'){
                \Yii::$app->session->setFlash('success',  Yii::t('app', 'Agreement Completed for Ref No# <strong>{ref_no}</strong>', ['ref_no' => $agreement->agreement_no])); 
                $agreement_wizard->set_manual_current_step('');
                return $this->redirect(['/agreement/view','id'=>$id]); 
            }else{
                
                    $url=\yii\helpers\Url::to(['agreement/create-agreement','id'=>$id,'step'=>$agreement_wizard->get_manual_current_step()]);
                $agreement_wizard->set_manual_current_step('');
                
                return $this->redirect($url);
                
                
            }
        }
        
        return $this->step($step); 
    }

    public function agreement_wizard_step_information() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['agreement']; 
    
            $updateAgreement = Agreement::findOne($agreement->id);
			
			if(empty($updateAgreement))
				$updateAgreement = new Agreement;
           
            if (Yii::$app->request->post()) { 
			
			    $formatter = Yii::$app->formatter;
                $updateAgreement->load(Yii::$app->request->post());
			    
				if(empty($updateAgreement->id))
				$updateAgreement->file_no = $updateAgreement->fileNo();
			
				$updateAgreement->date = $formatter->asDate($updateAgreement->date,"php:Y-m-d");
				if($updateAgreement->expire_date)
					$updateAgreement->expire_date = $formatter->asDate($updateAgreement->expire_date,"php:Y-m-d");
				else $updateAgreement->expire_date = Null;
				
				$updateAgreement->save();
				
                    $backStep = $agreement_wizard->getPrevStep();
                    $backStep = !empty($backStep)?$backStep:''; 
                    $getStep = empty($_POST['prev'])?(!empty($_POST['step'])?$_POST['step']:''):$backStep;
                    $agreement_wizard->set_manual_current_step($getStep); 
					$agreement_wizard->data['agreement'] = $updateAgreement; 

                    //$agreement_wizard->saveCurrentStep($stepData);
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Agreement is not completed yet but data have been saved for Agreement File No# <strong>{file_no}</strong>', ['file_no' => $updateAgreement->file_no]));
            $this->redirect(['/agreement/view','id'=>$updateAgreement->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['agreement'] = $updateAgreement;

        return false;
    }
    public function agreement_wizard_step_gauranty() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['agreement']; 
        $formatter = Yii::$app->formatter;

            $updateAgreementGauranty = AgreementGauranty::find()->where(['agreement_id'=>$agreement->id])->all();

			if(empty($updateAgreementGauranty))
				$updateAgreementGauranty = [new AgreementGauranty];
            
            if (Yii::$app->request->post()) { 
			    
				$agreementGaurantyData = Yii::$app->request->post("AgreementGauranty");
				
				foreach($agreementGaurantyData as $agreementGauranty){
					
					if(!empty($agreementGauranty['id']))
					     $gaurantyModel = AgreementGauranty::findOne($agreementGauranty['id']);
                    else $gaurantyModel = new AgreementGauranty;
                    
                    $loadArray['AgreementGauranty'] = $agreementGauranty;					
					
					$gaurantyModel->load($loadArray);
					
					$gaurantyModel->date = $formatter->asDate($gaurantyModel->date,"php:Y-m-d");
				
				    if($gaurantyModel->expire_date)
					    $gaurantyModel->expire_date = $formatter->asDate($gaurantyModel->expire_date,"php:Y-m-d");
				    else $gaurantyModel->expire_date = Null;
				
				    if($gaurantyModel->refund_date)
					    $gaurantyModel->refund_date = $formatter->asDate($gaurantyModel->refund_date,"php:Y-m-d");
				    else $gaurantyModel->refund_date = Null;
				
				    $gaurantyModel->save();
					
				}
				    
                    $backStep = $agreement_wizard->getPrevStep();
                    $backStep = !empty($backStep)?$backStep:''; 
                $getStep = empty($_POST['prev'])?(!empty($_POST['step'])?$_POST['step']:''):$backStep;
                    $agreement_wizard->set_manual_current_step($getStep); 
					$agreement_wizard->data['agreement'] = $agreement; 
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Agreement is not completed yet but data have been saved for Agreement File No# <strong>{file_no}</strong>', ['file_no' => $agreement->file_no]));
            $this->redirect(['/agreement/view','id'=>$agreement->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['gauranties'] = $updateAgreementGauranty;

        return false;
    }
	
    public function agreement_wizard_step_sites() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['agreement']; 
    
            $updateAgreementSites = AgreementSites::find()->where(['agreement_id'=>$agreement->id])->all();

			if(empty($updateAgreementSites))
				$updateAgreementSites = [new AgreementSites];
            
            if (Yii::$app->request->post()) { 
               
                
				$agreementSitesData = Yii::$app->request->post("AgreementSites");
				
				foreach($agreementSitesData as $agreementSites){
					
					if(!empty($agreementSites['id']))
					     $sitesModel = AgreementSites::findOne($agreementSites['id']);
                    else $sitesModel = new AgreementSites;
                    
                    $loadArray['AgreementSites'] = $agreementSites;					
					
					$sitesModel->load($loadArray);
					
				    $sitesModel->save();
					
				}
				
				
                    $backStep = $agreement_wizard->getPrevStep();
                    $backStep = !empty($backStep)?$backStep:''; 
                $getStep = empty($_POST['prev'])?(!empty($_POST['step'])?$_POST['step']:''):$backStep;
                    $agreement_wizard->set_manual_current_step($getStep); 
					$agreement_wizard->data['agreement'] = $agreement; 
                //$assessment_wizard->saveCurrentStep($stepData);
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Agreement is not completed yet but data have been saved for Agreement File No# <strong>{file_no}</strong>', ['file_no' => $agreement->file_no]));
            $this->redirect(['/agreement/view','id'=>$agreement->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['sites'] = $updateAgreementSites;

        return false;
    }
      	 
    public function agreement_wizard_step_tax() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['agreement']; 
    
            $updateAgreementTax = AgreementTax::find()->where(['agreement_id'=>$agreement->id])->all();

			if(empty($updateAgreementTax))
				$updateAgreementTax = [new AgreementTax];
            
            if (Yii::$app->request->post()) { 
               
				$agreementTaxData = Yii::$app->request->post("AgreementTax");
				
				foreach($agreementTaxData as $agreementTax){
					
					if(!empty($agreementTax['id']))
					     $taxModel = AgreementTax::findOne($agreementTax['id']);
                    else $taxModel = new AgreementTax;
                    
                    $loadArray['AgreementTax'] = $agreementTax;					
					
					$taxModel->load($loadArray);
					
				    $taxModel->save();
					
				}
				
				
                    $backStep = $agreement_wizard->getPrevStep();
                    $backStep = !empty($backStep)?$backStep:''; 
                $getStep = empty($_POST['prev'])?(!empty($_POST['step'])?$_POST['step']:''):$backStep;
                    $agreement_wizard->set_manual_current_step($getStep); 
					$agreement_wizard->data['agreement'] = $agreement; 
                //$assessment_wizard->saveCurrentStep($stepData);
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Agreement is not completed yet but data have been saved for Agreement File No# <strong>{file_no}</strong>', ['file_no' => $agreement->file_no]));
            $this->redirect(['/agreement/view','id'=>$agreement->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['taxes'] = $updateAgreementTax;

        return false;
    }
  
    public function agreement_wizard_step_vendor() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['agreement']; 
    
            $updateAgreementVendor = AgreementVendor::find()->where(['agreement_id'=>$agreement->id])->all();
            
			if(empty($updateAgreementVendor))
				$updateAgreementVendor = [new AgreementVendor];
            
            if (Yii::$app->request->post()) { 
               
				$agreementVendorData = Yii::$app->request->post("AgreementVendor");
				
				foreach($agreementVendorData as $agreementVendor){
					
					if(!empty($agreementVendor['id']))
					     $vendorModel = AgreementVendor::findOne($agreementVendor['id']);
                    else $vendorModel = new AgreementVendor;
                    
                    $loadArray['AgreementVendor'] = $agreementVendor;					
					
					$vendorModel->load($loadArray);
					
				    $vendorModel->save();
					
				}
                    $backStep = $agreement_wizard->getPrevStep();
                    $backStep = !empty($backStep)?$backStep:''; 
                $getStep = empty($_POST['prev'])?(!empty($_POST['step'])?$_POST['step']:''):$backStep;
                    $agreement_wizard->set_manual_current_step($getStep); 
					$agreement_wizard->data['agreement'] = $agreement; 
                //$assessment_wizard->saveCurrentStep($stepData);
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Agreement is not completed yet but data have been saved for Agreement File No# <strong>{file_no}</strong>', ['file_no' => $updateAgreement->file_no]));
            $this->redirect(['/agreement/view','id'=>$updateAgreement->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['vendors'] = $updateAgreementVendor;

        return false;
    }
 

    public function agreement_wizard_completed(){ 
	
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['agreement'];  
        return $this->redirect(['/agreement/view','id'=>$agreement->id]);
          
    } 
   
   public function actionBillBack($agreement_id,$type=null){
       
       $billBack = AgreementBillBack::find()->where(['agreement_id'=>$agreement_id])->all();
       $agreement = $this->findModel($agreement_id);
       if(empty($billBack)){
           $billBack = [new AgreementBillBack];
       }
       
       if($type){
           $billBack = [];
           $type = (urldecode($type));    
           $masterModel = \app\models\BillBackMaster::find()->select(['srmid','sno','type','description'])->where(['srmid'=>$type])->orderBy(['sno'=>SORT_ASC])->asArray()->all();
           $model = [];
           foreach ($masterModel as $master) {
               $array['AgreementBillBack'] = $master;
               $billBackArray = new AgreementBillBack;
               $billBackArray->load($array);
               $billBackArray->type = $master['srmid'];
               $billBack[] =  $billBackArray;
           }
        }
       
        if (Yii::$app->request->isPost) {
			
			$billBackData = Yii::$app->request->post('AgreementBillBack');
			$ids = array_column($billBackData,'id');
			$ids = array_filter($ids);
			$deleteCondition = ['AND',['agreement_id'=>$agreement_id],['NOT IN','id',$ids]];
			AgreementBillBack::deleteAll($deleteCondition);
			foreach($billBackData as $billBack){
				
				if(!empty($billBack['id']))
					$billBackModel = AgreementBillBack::findOne($billBack['id']);
				else $billBackModel = new AgreementBillBack;
				
				$loadArray['AgreementBillBack'] = $billBack;
				$billBackModel->load($loadArray);
				$billBackModel->save();
				
			}
			
			\Yii::$app->session->setFlash('success', 'Bill Back has been Updated Successfully');
            return $this->redirect(['agreement/bill-back', 'agreement_id' => $agreement->id]);
        }
        
       return $this->render('bill-back',[
           'model'=>$billBack,
           'agreement'=>$agreement,
       ]);
       
   }
     
    
    /**
     * Finds the Assessment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Assessment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    { 
        if (($model = Agreement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
