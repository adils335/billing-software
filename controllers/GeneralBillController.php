<?php

namespace app\controllers;

use Yii;
use app\models\AgreementBill;
use app\models\Agreement;
use app\models\Search\AgreementBill as AgreementBillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * AgreementBillController implements the CRUD actions for AgreementBill model.
 */
class GeneralBillController extends Controller
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

    public function actionChangeStatus($id,$status,$refresh){
        if( Yii::$app->request->isAjax ){
           $flag = false;
           \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
           $connection = \Yii::$app->db;
           $transaction = $connection->beginTransaction(); 
           $agreementBill = AgreementBill::findOne($id);
           $lastStatus = $agreementBill->status;
           $agreementBill->status = $status;
           if( $agreementBill->save() ){
             $output = $agreementBill->changeStatus( $id, $lastStatus, $status );
             if( is_integer( $output ) ){
                   $flag = true;
             }else{
                   $flag = false;
                   $error = json_encode($output);
             }
           }else{
               $flag = false;
               $error = json_encode($agreementBill->getErrors()); 
           }  
           if( $flag ){
             $transaction->commit();
             return ['status'=>true,'refresh'=>$refresh];
           } else {
             $transaction->rollBack();
             return ['false'=>false,'error'=>$error];
           }
        }
   }
    
    public function actionShowGeneralBill( $id ){
        $model = AgreementBill::find()->where(['agreement_id'=>$id])->one();
        $tmp_path = Yii::getAlias('@webroot/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/general-bill/bill-pdf", [
                                            'model' => $model,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/general-bill/pdf-footer',[
            'model' => $model,
        ]);
		
		$filename = $model->company_id."-".$model->session."-".$model->invoice_no.".pdf";
        $pdf = new \kartik\mpdf\Pdf([
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetHeader(Yii::t('app', 'Invoice No').': '.$model->session."/".sprintf("%02d",$model->invoice_no)); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
		  
    }
    
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['agreement_type'=>Agreement::TYPE_GENERAL]);
        $dataProvider->query->orderBy(['session'=>SORT_DESC,'invoice_no'=>SORT_DESC]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AgreementBill model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AgreementBill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$agreement = new Agreement();
        $model = new AgreementBill();
		$billItem = [new \app\models\BillItem];
	    $billTax = [new \app\models\BillTax];
		$billDeduction = new \app\models\BillDeduction;

        if (Yii::$app->request->post()) {
			
			$postData = Yii::$app->request->post();
			//echo "<pre>";print_r($postData);die();
			$formatter = Yii::$app->formatter;
			$flag = true;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $shuffleBill = \app\models\Common::shuffleBill($postData);
		    //echo "<pre>";print_r($shuffleBill);die();
            $agreement = $shuffleBill['Agreement'];
            if($agreement->validate()){
                $agreement->save();
            }else{
                $flag = false;
                //echo "<pre>";print_r($agreement->getErrors());die();
            }
            if($flag){
              $shuffleBill['BillItem'] = \app\models\AgreementRateSchedule::saveGeneralRate($agreement,$shuffleBill['BillItem']);
            }
            $model = $shuffleBill['AgreementBill'];
            $model->agreement_id = $agreement->id;
			
			if($model->validate()){
				
				$model->invoice_no = $model->invoiceNo();
				
				$model->save();
				$billItem = new \app\models\BillItem;
				if($billItem->saveItem($model,$shuffleBill['BillItem'])){
					$billTax = new \app\models\BillTax;
				      if($billTax->saveTax($model,$shuffleBill['BillTax'])){
					        //$flag = true;
					       /* if(Yii::$app->request->post()["BillPenality"]["tax_id"][0]){
				              if(!$billPenality->savePenality($model,Yii::$app->request->post("BillPenality"))){
					              $transaction->rollBack();
					              $errors = $billPenality->errors;
					              $flag = false;
				              }
							} */
                            $billDeduction = new \app\models\BillDeduction;
					        if($shuffleBill['BillDeduction']["tax_id"][0]){
				              if(!$billDeduction->saveDeduction($model,$shuffleBill['BillDeduction'])){
					              $transaction->rollBack();
					              $errors = $billTax->errors;
					              $flag = false;
				              }
							}
							if($flag){
					          $transaction->commit();
					          $model->createGeneralPdf();
                              \Yii::$app->session->setFlash('success',  Yii::t('app', 'Bill Invoice No# <strong>{session}/{invoice_no}</strong>', ['session'=>$model->session,'invoice_no' => $model->invoice_no]));
                              return $this->redirect(['general-bill/index']);
							}
				       }else{
					        
					        $transaction->rollBack();
					        $errors = $billTax->errors;
					
				      }
				
				}else{
					
					$transaction->rollBack();
					$errors = $billItem->errors;
				}
				
			}else{
				
			print_r($model->getErrors());die();
				
			}
			
        }
        
        if(empty($agreement->session)){
         $agreement->session = \app\models\Session::getCurrentSession();
        }
        if(empty($agreement->id)){
            $agreement->id = \app\models\Common::tempId();
        }

        return $this->render('create', [
            'model' => $model,
			'agreement' => $agreement,
			'billItem' => $billItem,
			'billTax' => $billTax,
			'billDeduction' => $billDeduction,
        ]);
    }

    /**
     * Updates an existing AgreementBill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $agreement = Agreement::findOne($id);
        $model = AgreementBill::find()->where(['agreement_id'=>$id])->one();
		$billItem = \app\models\BillItem::find()->where(['agreement_id'=>$id])->orderBy(['id'=>SORT_DESC])->all();
	    $billTax = \app\models\BillTax::find()->where(['agreement_id'=>$id])->orderBy(['id'=>SORT_DESC])->all();;
		$billDeduction = \app\models\BillDeduction::find()->where(['agreement_id'=>$id])->orderBy(['id'=>SORT_DESC])->all();;
					  
        if(empty($billDeduction))
			$billDeduction = new \app\models\BillDeduction;
		
        if (Yii::$app->request->post()) {
			
			$postData = Yii::$app->request->post();
			//echo "<pre>";print_r($postData);die();
			$formatter = Yii::$app->formatter;
			$flag = true;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            $shuffleBill = \app\models\Common::shuffleBill($postData);
			//echo "<pre>";print_r($shuffleBill);die();
            $agreement = $shuffleBill['Agreement'];
            if($agreement->validate()){
                $agreement->save();
            }else{
                $flag = false;
                echo "<pre>";print_r($agreement->getErrors());die();
            }
            if($flag){
              $shuffleBill['BillItem'] = \app\models\AgreementRateSchedule::saveGeneralRate($agreement,$shuffleBill['BillItem']);
            }
            //echo "<pre>";print_r($shuffleBill);die();
            $model = $shuffleBill['AgreementBill'];
            $model->agreement_id = $agreement->id;
			
			if($model->validate()){
				
				$model->save();
				$billItem = new \app\models\BillItem;
				$deductionId = [];
                if(!empty($shuffleBill['BillDeduction']["tax_id"])){
		        	$deductionId = $shuffleBill['BillDeduction']["tax_id"];
                }
		
			    $deductionCondition = [
			                           'AND',
			                           ['invoice_id'=>$model->id,'agreement_id'=>$model->agreement_id],
			                           ['NOT IN','id',$deductionId]
			                          ];
			    \app\models\BillDeduction::deleteAll($deductionCondition);
			
				if($billItem->saveItem($model,$shuffleBill['BillItem'])){
					$billTax = new \app\models\BillTax;
				      if($billTax->saveTax($model,$shuffleBill['BillTax'])){
					        //$flag = true;
					       /* if(Yii::$app->request->post()["BillPenality"]["tax_id"][0]){
				              if(!$billPenality->savePenality($model,Yii::$app->request->post("BillPenality"))){
					              $transaction->rollBack();
					              $errors = $billPenality->errors;
					              $flag = false;
				              }
							} */
                            $billDeduction = new \app\models\BillDeduction;
					        if($shuffleBill['BillDeduction']["tax_id"][0]){
				              if(!$billDeduction->saveDeduction($model,$shuffleBill['BillDeduction'])){
					              $transaction->rollBack();
					              $errors = $billTax->errors;
					              $flag = false;
				              }
							}
							if($flag){
					          $transaction->commit();
					          $model->createGeneralPdf();
                              \Yii::$app->session->setFlash('success',  Yii::t('app', 'Bill Invoice No# <strong>{session}/{invoice_no}</strong>', ['session'=>$model->session,'invoice_no' => $model->invoice_no]));
                              return $this->redirect(['general-bill/index']);
							}
				       }else{
					        
					        $transaction->rollBack();
					        $errors = $billTax->errors;
					
				      }
				
				}else{
					
					$transaction->rollBack();
					$errors = $billItem->errors;
				}
				
			}else{
				
			print_r($model->getErrors());die();
				
			}
			
        }
        
        return $this->render('create', [
            'model' => $model,
			'agreement' => $agreement,
			'billItem' => $billItem,
			'billTax' => $billTax,
			'billDeduction' => $billDeduction,
        ]);
    }

    /**
     * Deletes an existing AgreementBill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
		$model = AgreementBill::find()->where(['id'=>$id])->one();
		$model->status = AgreementBill::STATUS_DELETE;
		
		$model->save();
		
        \Yii::$app->session->setFlash('success','General Bill deleted successfully');
        return $this->redirect(['index']);
    }

    /**
     * Finds the AgreementBill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgreementBill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgreementBill::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
	
	public function actionPdf(){
		$model = $this->findModel(30);
		$model->createGeneralPdf();
	}
}
