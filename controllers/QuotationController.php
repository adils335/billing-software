<?php

namespace app\controllers;

use Yii;
use app\models\Agreement;
use app\models\Model;
use app\models\AgreementRateSchedule;
use app\models\AgreementGauranty;
use app\models\AgreementSites;
use app\models\AgreementTax;
use app\models\AgreementVendor;
use app\models\Search\Agreement as AgreementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter; 
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\components\Wizard;

/**
 * AssessmentController implements the CRUD actions for Assessment model.
 */
 
class QuotationController extends Controller
{
    const AGREEMENT_WIZARD_STEP_INFORMATION = 'information';
    const AGREEMENT_WIZARD_STEP_RATE_SCHEDULE = 'rate-schedule';
    // AGREEMENT_WIZARD_STEP_SITES = 'sites';
    //const AGREEMENT_WIZARD_STEP_VENDOR = 'vendor';
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

    /**
     * Lists all Assessment models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $searchModel = new AgreementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andwhere(['type'=>Agreement::TYPE_QUOTATION]);

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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
        
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCreateQuotation($id = null, $step = null) {  
        
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
                self::AGREEMENT_WIZARD_STEP_RATE_SCHEDULE => [
                    'title' => Yii::t('app', 'Work Description'),
                    'callback' => 'agreement_wizard_step_rate_schedule',
                    'view' => 'rate-schedule'
                ], 
                /*self::AGREEMENT_WIZARD_STEP_SITES => [
                    'title' => Yii::t('app', 'Sites'),
                    'callback' => 'agreement_wizard_step_sites',
                    'view' => 'sites'
                ], */ 
                self::AGREEMENT_WIZARD_STEP_TAX => [
                    'title' => Yii::t('app', 'Tax'),
                    'callback' => 'agreement_wizard_step_tax',
                    'view' => 'tax'
                ],
                /*self::AGREEMENT_WIZARD_STEP_VENDOR => [
                    'title' => Yii::t('app', 'Vendor'),
                    'callback' => 'agreement_wizard_step_vendor',
                    'view' => 'vendor'
                ],  */
            ],
            'onCompleteWizard' => 'agreement_wizard_completed',
            'data' => [
                'quotation' => $agreement,
            ]
        ]; 
         
        if(!empty($config)){
            $config['class'] = Wizard::className();
            $this->attachBehavior('agreementWizard', $config); 
        }  
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $stepData = $agreement_wizard->_getSession('stepData');
        //echo "<pre>";print_r($stepData);
		
		//die("1");
        if(!empty($agreement_wizard->get_manual_current_step())){
            if($agreement_wizard->get_manual_current_step()=='completed'){
                \Yii::$app->session->setFlash('success',  Yii::t('app', 'Quotation Completed for Ref No# <strong>{ref_no}</strong>', ['ref_no' => $agreement->agreement_no])); 
                $agreement_wizard->set_manual_current_step('');
                return $this->redirect(['/quotation/view','id'=>$id]); 
            }else{
                
                    $url=\yii\helpers\Url::to(['quotation/create-quotation','id'=>$id,'step'=>$agreement_wizard->get_manual_current_step()]);
                $agreement_wizard->set_manual_current_step('');
                
                return $this->redirect($url);
                
                
            }
        }
        
        return $this->step($step); 
    }

    public function agreement_wizard_step_information() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['quotation']; 
            $updateAgreement = Agreement::findOne($agreement->id);
			//echo "<pre>";print_r($agreement_wizard);die;
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
					$agreement_wizard->data['quotation'] = $updateAgreement; 
				
                    //$agreement_wizard->saveCurrentStep($stepData);
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Quotation is not completed yet but data have been saved for Quotation File No# <strong>{file_no}</strong>', ['file_no' => $agreement->file_no]));
            $this->redirect(['/quotation/view','id'=>$agreement->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['quotation'] = $updateAgreement;

        return false;
    }
	
    public function agreement_wizard_step_rate_schedule() { 
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['quotation']; 
        $formatter = Yii::$app->formatter;
        $updateAgreementRateSchedule = AgreementRateSchedule::find()->where(['agreement_id'=>$agreement->id])->all();
		//echo "<pre>";var_dump( empty($updateAgreementRateSchedule) );die;
        $flag = true;
        $scenario = 'update';
		if(empty($updateAgreementRateSchedule)){
		    $scenario = 'create';
			$updateAgreementRateSchedule = [new AgreementRateSchedule];
		}
        if (Yii::$app->request->post()) { 
            try {
                if($scenario == 'create'){
                    $updateAgreementRateSchedule = Model::createMultiple(AgreementRateSchedule::classname());
                    Model::loadMultiple($updateAgreementRateSchedule, Yii::$app->request->post());
                }else{
                    $oldIDs = ArrayHelper::map($updateAgreementRateSchedule, 'id', 'id');
                    $updateAgreementRateSchedule = Model::createMultiple(AgreementRateSchedule::classname(), $updateAgreementRateSchedule);
                    Model::loadMultiple($updateAgreementRateSchedule, Yii::$app->request->post());
                    $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($updateAgreementRateSchedule, 'id', 'id')));
                }
                $transaction = \Yii::$app->db->beginTransaction();
                $agreement->load(Yii::$app->request->post());
                //echo "<pre>";print_r($agreement);die;
                if( !($flag = $agreement->save()) ){
                    Yii::$app->session->setFlash('error',json_encode($agreement->errors));
                    $transaction->rollBack();
                }
                if($flag ){
                    if ($scenario == 'update' && ! empty($deletedIDs)) {
                        AgreementRateSchedule::deleteAll(['id' => $deletedIDs]);
                    }
                    foreach ($updateAgreementRateSchedule as $agreementRateSchedule) {
                        if (! ($flag = $agreementRateSchedule->save(false))) {
                            Yii::$app->session->setFlash('error',json_encode($rateScheduleModel->errors));
                            $transaction->rollBack();
                            break;
                        }
                    }
                }
                if( $flag ){
			        $transaction->commit();
			        $backStep = $agreement_wizard->getPrevStep();
                    $backStep = !empty($backStep)?$backStep:''; 
                    $getStep = empty($_POST['prev'])?(!empty($_POST['step'])?$_POST['step']:''):$backStep;
                    $agreement_wizard->set_manual_current_step($getStep); 
			        $agreement_wizard->data['quotation'] = $agreement; 
                    if(!empty(Yii::$app->request->post("save-step"))){
                        \Yii::$app->session->setFlash('success',  Yii::t('app', 'Quotation is not completed yet but data have been saved for Quotation File No# <strong>{file_no}</strong>', ['file_no' => $agreement->file_no]));
                        $this->redirect(['/quotation/view','id'=>$agreement->id]); 
                    }else{
                            return true;
                    }
			    }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error',$e->getMessage()." on line no ".$e->getLine()." in file ".$e->getFile());
                $transaction->rollBack();
            }
        }  
         
        $agreement_wizard->data['schedules'] = $updateAgreementRateSchedule;

        return false;
    }
	
    public function agreement_wizard_step_sites() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['quotation']; 
    
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
					$agreement_wizard->data['quotation'] = $agreement; 
                //$assessment_wizard->saveCurrentStep($stepData);
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Quotation is not completed yet but data have been saved for Quotation File No# <strong>{file_no}</strong>', ['file_no' => $updateAgreement->file_no]));
            $this->redirect(['/quotation/view','id'=>$agreement->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['sites'] = $updateAgreementSites;

        return false;
    }
      	 
    public function agreement_wizard_step_tax() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['quotation']; 
        $updateAgreementTax = AgreementTax::find()->where(['agreement_id'=>$agreement->id])->all();
        $flag = true;
        $scenario = 'update';
		if(empty($updateAgreementTax)){
		    $scenario = 'create';
			$updateAgreementTax = [new AgreementTax];
		}
        if (Yii::$app->request->post()) { 
            try {
                if($scenario == 'create'){
                    $updateAgreementTax = Model::createMultiple(AgreementTax::classname());
                    Model::loadMultiple($updateAgreementTax, Yii::$app->request->post());
                }else{
                    $oldIDs = ArrayHelper::map($updateAgreementTax, 'id', 'id');
                    $updateAgreementTax = Model::createMultiple(AgreementTax::classname(), $updateAgreementTax);
                    Model::loadMultiple($updateAgreementTax, Yii::$app->request->post());
                    $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($updateAgreementTax, 'id', 'id')));
                }
                $transaction = \Yii::$app->db->beginTransaction();
                $agreement->load(Yii::$app->request->post());
                if( !($flag = $agreement->save()) ){
                    Yii::$app->session->setFlash('error',json_encode($agreement->errors));
                    $transaction->rollBack();
                }
                if( $flag ){
                    if ($scenario == 'update' && ! empty($deletedIDs)) {
                        AgreementTax::deleteAll(['id' => $deletedIDs]);
                    }
                    foreach ($updateAgreementTax as $agreementTax) {
                        if (! ($flag = $agreementTax->save(false))) {
                            Yii::$app->session->setFlash('error',json_encode($agreementTax->errors));
                            $transaction->rollBack();
                            break;
                        }
                    }
                }
                if( $flag ){
			        $transaction->commit();
			        $backStep = $agreement_wizard->getPrevStep();
                    $backStep = !empty($backStep)?$backStep:''; 
                    $getStep = empty($_POST['prev'])?(!empty($_POST['step'])?$_POST['step']:''):$backStep;
                    $agreement_wizard->set_manual_current_step($getStep); 
			        $agreement_wizard->data['quotation'] = $agreement; 
                    if(!empty(Yii::$app->request->post("save-step"))){
                        \Yii::$app->session->setFlash('success',  Yii::t('app', 'Quotation is not completed yet but data have been saved for Quotation File No# <strong>{file_no}</strong>', ['file_no' => $agreement->file_no]));
                        $this->redirect(['/quotation/view','id'=>$agreement->id]); 
                    }else{
                            return true;
                    }
			    }
            } catch (Exception $e) {
                Yii::$app->session->setFlash('error',$e->getMessage()." on line no ".$e->getLine()." in file ".$e->getFile());
                $transaction->rollBack();
            }
        }  
        $agreement_wizard->data['taxes'] = $updateAgreementTax;
        return false;
    }
  
    public function agreement_wizard_step_vendor() { 
        
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['quotation']; 
    
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
					$agreement_wizard->data['quotation'] = $agreement; 
                //$assessment_wizard->saveCurrentStep($stepData);
                 
       if(!empty(Yii::$app->request->post("save-step"))){
            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Quotation is not completed yet but data have been saved for Quotation File No# <strong>{file_no}</strong>', ['file_no' => $updateAgreement->file_no]));
            $this->redirect(['/agreement/view','id'=>$lead->id]); 
        }else{
                return true;
        }

            }  
         
        $agreement_wizard->data['vendors'] = $updateAgreementVendor;

        return false;
    }
 

    public function agreement_wizard_completed(){ 
	
        $agreement_wizard = $this->getBehavior('agreementWizard');
        $agreement = $agreement_wizard->data['quotation'];  
        return $this->redirect(['/quotation/view','id'=>$agreement->id]);
          
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
