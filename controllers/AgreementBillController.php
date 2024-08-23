<?php

namespace app\controllers;

use Yii;
use app\models\AgreementBill;
use app\models\BillTax;
use app\models\Model;
use app\models\Agreement;
use app\models\AgreementBillStatus;
use app\models\Search\AgreementBill as AgreementBillSearch;
use app\models\Search\BillItem as BillItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use app\models\BillItem;

/**
 * AgreementBillController implements the CRUD actions for AgreementBill model.
 */
class AgreementBillController extends Controller
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
    
    public function actionGstPayable()
    {
        
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if( empty( Yii::$app->request->queryParams ) || 
            ( isset( Yii::$app->request->queryParams['AgreementBill'] ) 
              && !isset( Yii::$app->request->queryParams['AgreementBill']['from_month'] )
            )
        ){
            $dataProvider->query->where(['DATE_FORMAT(invoice_date,"%m-%Y")'=>date('m-Y')]);
            $searchModel->from_month = date("m-Y");
        }
        $bills = $dataProvider->query->all();
        $invoiceIds = $dataProvider->query->select(['id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $taxes = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        
        $searchModel = new \app\models\Search\PurchaseBill();
        $params = [];
        if( isset( Yii::$app->request->queryParams['AgreementBill'] ) ){
            foreach( Yii::$app->request->queryParams as $key => $value ){
                if( $key == "AgreementBill" ){
                    $params['PurchaseBill'] = Yii::$app->request->queryParams['AgreementBill'];
                }else{ 
                    $params[$key] = $value;
                }
            }
        }
        $dataProvider = $searchModel->search($params);
        
        if( empty( Yii::$app->request->queryParams ) || 
            ( isset( Yii::$app->request->queryParams['AgreementBill'] ) 
              && !isset( Yii::$app->request->queryParams['AgreementBill']['from_month'] )
            )
        ){
            $dataProvider->query->where(['DATE_FORMAT(date,"%m-%Y")'=>date('m-Y')]);
            $searchModel->from_month = date("m-Y");
        }
        $purchaseBills = $dataProvider->query->all();
        $invoiceIds = $dataProvider->query->select(['id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $subquery = \app\models\PurchaseBillItems::find()->select('id')->where(['purchase_bill_id'=>$invoiceId]);
        $purchase_taxes = \app\models\PurchaseBillItemsTax::find()->select(['tax_id'])->where(['IN','purchase_bill_items_id',$subquery])->groupBy(['tax_id'])->all();
        
        AgreementBill::generateGstPayablePdf($searchModel->from_month,$bills,$taxes,$purchaseBills,$purchase_taxes);
        return $this->redirect([Yii::getAlias("@web/agreement bill/filter.pdf")]);
        
    }
    
    public function actionGstBill()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if( empty( Yii::$app->request->queryParams ) || 
            ( isset( Yii::$app->request->queryParams['AgreementBill'] ) 
              && !isset( Yii::$app->request->queryParams['AgreementBill']['from_month'] )
            )
        ){
            $dataProvider->query->where(['DATE_FORMAT(invoice_date,"%m-%Y")'=>date('m-Y')]);
            $searchModel->from_month = date("m-Y");
        }
        $model = $dataProvider->query->all();
        $invoiceIds = $dataProvider->query->select(['id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $taxes = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        $deduction = \app\models\BillDeduction::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        
        AgreementBill::generateGstPdf('bill',$searchModel->from_month,$model,$taxes,$deduction);
        return $this->redirect([Yii::getAlias("@web/agreement bill/filter.pdf")]);
      
    }
    
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionGstPurchase()
    {
        $searchModel = new \app\models\Search\PurchaseBill();
        $searchVendorBill = new \app\models\Search\VendorBill();
        $params = [];
        if( isset( Yii::$app->request->queryParams['AgreementBill'] ) ){
            foreach( Yii::$app->request->queryParams as $key => $value ){
                if( $key == "AgreementBill" ){
                    $params['PurchaseBill'] = Yii::$app->request->queryParams['AgreementBill'];
                    $params['VendorBill'] = Yii::$app->request->queryParams['AgreementBill'];
                }else{ 
                    $params[$key] = $value;
                }
            }
        }
        $dataProvider = $searchModel->search($params);
        $dataProviderVendorBill = $searchVendorBill->search($params);
        
        if( empty( Yii::$app->request->queryParams ) || 
            ( isset( Yii::$app->request->queryParams['AgreementBill'] ) 
              && !isset( Yii::$app->request->queryParams['AgreementBill']['from_month'] )
            )
        ){
            $dataProvider->query->where(['DATE_FORMAT(date,"%m-%Y")'=>date('m-Y')]);
            $dataProviderVendorBill->query->where(['DATE_FORMAT(date,"%m-%Y")'=>date('m-Y')]);
            $searchModel->from_month = date("m-Y");
        }
        $model = $dataProvider->query->all();
        $invoiceIds = $dataProvider->query->select(['id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $subquery = \app\models\PurchaseBillItems::find()->select('id')->where(['purchase_bill_id'=>$invoiceId]);
        $taxes = \app\models\PurchaseBillItemsTax::find()
                        ->select(['tax_id'])
                        ->where(['IN','purchase_bill_items_id',$subquery])
                        ->groupBy(['tax_id'])
                        ->all();
        $vendorBillmodel = $dataProviderVendorBill->query->where(['>','tax_amount',0])->all();
        $invoiceIds = $dataProviderVendorBill->query->select(['id'])->where(['>','tax_amount',0])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $vendorBilltaxes = \app\models\VendorBillTax::find()
                        ->select(['tax_id'])
                        ->where(['IN','bill_id',$invoiceId])
                        ->groupBy(['tax_id'])
                        ->all();
        $month = $searchModel->from_month;
        $company = \app\models\Company::findOne($searchModel->company_id);
        $tmp_path = Yii::getAlias('@webroot/agreement-bill/'); 
        $pdf_file = 'gst-purchase';
        $report_title = 'Purchase GST of '. $company->name .' For Month: '.$month;
        $content = Yii::$app->controller->renderPartial("@app/views/agreement-bill/gst-report-pdf/".$pdf_file, [
                                            'model' => $model,
                                            'taxes' => $taxes,
                                            'vendorBillModel' => $vendorBillmodel,
                                            'vendorBillTaxes' => $vendorBilltaxes,
                                        ]);							
        $footer = Yii::$app->controller->renderPartial('@app/views/agreement-bill/pdf-footer',[
            'model' => $model,
        ]);
        $filename = "gst-paid.pdf";
        $pdf = new Pdf([
           'format' => Pdf::FORMAT_A4,
           'orientation' => Pdf::ORIENT_LANDSCAPE,
           'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->SetHeader($report_title); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
                                        
        return $this->redirect([Yii::getAlias("@web/agreement bill/filter.pdf")]);
        
    }
    
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionGstHsnno()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->searchHsnWise( Yii::$app->request->queryParams );
        if( empty( Yii::$app->request->queryParams ) || 
            ( isset( Yii::$app->request->queryParams['AgreementBill'] ) 
              && !isset( Yii::$app->request->queryParams['AgreementBill']['from_month'] )
            )
        ){
            $dataProvider->query->where(['DATE_FORMAT(invoice_date,"%m-%Y")'=>date('m-Y')]);
            $searchModel->from_month = date("m-Y");
        }
        $invoiceIds = $dataProvider->query->select(['id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $model = \app\models\BillItem::find()
            ->select(['*', 'remaining_quantity' => 'GROUP_CONCAT(DISTINCT invoice_id)', 'quantity' => 'SUM(quantity)', 'amount' => 'SUM(amount)'])
            ->where(['invoice_id' => $invoiceId])
            ->groupBy(['hsn_no'])
        ->all();
        $taxes = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        AgreementBill::generateGstPdf('hsnno',$searchModel,$model,$taxes);
        return $this->redirect([Yii::getAlias("@web/agreement bill/filter.pdf")]);
    }
    
    public function actionLastBillAmount(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $invoice_no = Yii::$app->request->post()['invoice_no'];
        if( empty($invoice_no) ){
            return 0;
        }
        return AgreementBill::find()->where(['id'=>$invoice_no])->sum('taxable_amount');
        
    }

    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionGenerateGstReport()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->query->all();
        $gsts = \app\models\Tax::find()->where(['tax_type'=>1])->all();
        $gstArray = [];
        foreach($gsts as $gst){
            $gstArray[$gst->name] = $gst->id;
        }
        if(!empty($model)){
            AgreementBill::generateGstReportPdf($model,$params['AgreementBill']['from_month'],$params['AgreementBill']['from_month'],$gstArray);
        }
        \Yii::$app->session->setFlash('error',  Yii::t('app', 'Result not found'));
                              
        return $this->render('gst-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionGstReport()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if( empty( Yii::$app->request->queryParams ) || 
            ( isset( Yii::$app->request->queryParams['AgreementBill'] ) 
              && !isset( Yii::$app->request->queryParams['AgreementBill']['from_month'] )
              && !isset( Yii::$app->request->queryParams['AgreementBill']['to_month'] )
            )
        ){
            $dataProvider->query->where(['DATE_FORMAT(invoice_date,"%m-%Y")'=>date('m-Y')]);
            $searchModel->from_month = date("m-Y");
            $searchModel->to_month = date("m-Y");
        }
        $newData = clone $dataProvider;
        $invoiceIds = $newData->query->select(['id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $taxesModel = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        $taxes = [];
        foreach( $taxesModel as $billTax ){
            $taxes[] = ['name'=>$billTax->tax->name,'tax_id'=>$billTax->tax_id];
        }
        //$model = $dataProvider->query->all();                   
        return $this->render('gst-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taxes' => $taxes
        ]);
    }
    
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionGstPaid()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if( empty( Yii::$app->request->queryParams ) || 
            ( isset( Yii::$app->request->queryParams['AgreementBill'] ) 
              && !isset( Yii::$app->request->queryParams['AgreementBill']['from_month'] )
            )
        ){
            $dataProvider->query->where(['DATE_FORMAT(invoice_date,"%m-%Y")'=>date('m-Y')]);
            $searchModel->from_month = date("m-Y");
        }
        $model = $dataProvider->query->all();
        $invoiceIds = $dataProvider->query->select(['id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $taxes = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        $pdf_file = AgreementBill::generateGstPdf('paid',$searchModel,$model,$taxes);
        return $this->render('gst-report', [
            'searchModel' => $searchModel,
            'pdf_file' => $pdf_file
        ]);  
    }
    
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionGeneratePdf()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = $dataProvider->query->all();
        if(!empty($model)){
            AgreementBill::generatePdf($model);
            $this->redirect([Yii::getAlias("@web/agreement bill/filter.pdf")]);
        }
        \Yii::$app->session->setFlash('error',  Yii::t('app', 'Result not found'));
                              
        return $this->render('bill-summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionCancelBill()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['session'=>SORT_DESC,'invoice_no'=>SORT_DESC]);
        return $this->render('bill-summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionBillSummary()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['session'=>SORT_DESC,'invoice_no'=>SORT_DESC]);
        return $this->render('bill-summary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all AgreementBill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
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
    public function actionCreate($agreement_id = null)
    {
		$agreement = Agreement::findOne($agreement_id);
		$itemDetails = $agreement->agreementRateSchedule;
		$billItems = [];
		if( !empty($itemDetails) ){
		    foreach( $itemDetails as $itemDetail ){
		        $billItem = new BillItem;
		        $billItem->setAttributes($itemDetail->attributes);
		        $billItems[] = $billItem;
		    }
		}
		if( empty($billItems) ){
		    $billItems = [new BillItem];
		}
        $model = new AgreementBill();
        $model->agreement_id = $agreement_id;
	    $billTaxes = [new BillTax];
       
	    $billItemModel = new BillItem;
	    $billTaxModel = new BillTax;
        if (Yii::$app->request->post()) {
			$formatter = Yii::$app->formatter;
            $transaction = \Yii::$app->db->beginTransaction();
			if($model->load(Yii::$app->request->post()) && $model->validate()){
				$model->invoice_no = $model->invoiceNo();
				$model->invoice_date = $formatter->asDate($model->invoice_date,'php:Y-m-d');
				if($model->start_date){
				    $model->start_date = $formatter->asDate($model->start_date,'php:Y-m-d');
				}
				if($model->complete_date){
				    $model->complete_date = $formatter->asDate($model->complete_date,'php:Y-m-d');
				}
				$billItems = Model::createMultiple(BillItem::classname());
                Model::loadMultiple($billItems, Yii::$app->request->post());
                $valid = Model::validateMultiple($billItems);
                if ($valid) {
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($billItems as $billItem) {
                                $billItem->invoice_id = $model->id;
                                if (! ($flag = $billItem->save(false))) {
                                    $errors = json_encode($billItem);
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if( $flag ){
                            $postBillTax = Yii::$app->request->post()['BillTax']??[];
                            if( $model->tax_on_items ){
                                foreach( $billItems as $billItem ){
                                    $billTax['invoice_id'] = $billItem->invoice_id;
                                    $billTax['agreement_id'] = $billItem->agreement_id;
                                    $billTax['item_id'] = $billItem->id;
                                    $billTax['tax_id'] = $billItem->tax_id;
                                    $billTax['rate'] = $billItem->tax_rate;
                                    $billTax['amount'] = $billItem->tax_amount;
                                    $billTax['company_id'] = $billItem->company_id;
                                    $billTax['session'] = $billItem->session;
                                    $postBillTax[] = $billTax;
                                }
                            }
                            $billTaxes = Model::createMultiple(BillTax::classname());
                            Model::loadMultiple($billTaxes, $postBillTax);
                            $flag = Model::validateMultiple($billTaxes);
                            if($flag){
                                if ($flag = $model->save(false)) {
                                    foreach ($billTaxes as $billTax) {
                                        if (! ($flag = $billTax->save(false))) {
                                            $errors = json_encode($billTax);
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }
                                }
                            }else{
                                \Yii::$app->session->setFlash('error', json_encode($billTaxes[0]->errors));
                                $transaction->rollBack();
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            $model->createPdf();
                            \Yii::$app->session->setFlash('success',  Yii::t('app', 'Bill Invoice No# <strong>{session}/{invoice_no}</strong>', ['session'=>$model->session,'invoice_no' => $model->invoice_no]));
                            return $this->redirect(['quotation/view', 'id' => $agreement->id]);
                        }else{
                            \Yii::$app->session->setFlash('error', $errors);
                            $transaction->rollBack();
                        }
                    } catch (Exception $e) {
                        \Yii::$app->session->setFlash('error', $e->getMessage()." on line no ".$e->getLine()." in file ".$e->getFile());
                        $transaction->rollBack();
                    }
                }else{
                    \Yii::$app->session->setFlash('error', json_encode($billItems[0]->errors));
                    $transaction->rollBack();
                }
			}else{
			    \Yii::$app->session->setFlash('error', json_encode($model->errors));
			    $transaction->rollBack();
			}
        }

        return $this->render('create', [
            'model' => $model,
			'agreement' => $agreement,
			'billItems' => $billItems,
			'billTaxes' => $billTaxes,
        ]);
    }

    /**
     * Updates an existing AgreementBill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreateBillPdf($id){
        $model = $this->findModel($id);
        $model->viewPdf();
        
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $agreement = Agreement::findOne($model->agreement_id);
		$billItem = new \app\models\BillItem;
	    $billTax = new \app\models\BillTax;
		$billDeduction = \app\models\BillDeduction::find()
		              ->where(['invoice_id'=>$model->id,'agreement_id'=>$model->agreement_id,'session'=>$model->session,'company_id'=>$model->company_id])->all();
		$billPenality = \app\models\BillPenality::find()
		              ->where(['invoice_id'=>$model->id,'agreement_id'=>$model->agreement_id,'session'=>$model->session,'company_id'=>$model->company_id])->all();
		$sdm =  new \app\models\BillDeduction;             
		$spm =  new \app\models\BillPenality;             
        			  
        if(empty($billDeduction))
			$billDeduction = new \app\models\BillDeduction;
        if(empty($billPenality))
			$billPenality = new \app\models\BillPenality;
		
        if (Yii::$app->request->post()) {
            $deductionId = [];
            if(!empty(Yii::$app->request->post()['BillDeduction']['tax_id'])){
		    	$deductionId = Yii::$app->request->post()['BillDeduction']['tax_id'];
            }
            $penaltyId = [];
            if(!empty(Yii::$app->request->post()['BillPenality']['tax_id'])){
			   $penaltyId = Yii::$app->request->post()['BillPenality']['tax_id'];
            }
		
			    $deductionCondition = [
			                           'AND',
			                           ['invoice_id'=>$model->id,'agreement_id'=>$model->agreement_id,'session'=>$model->session,'company_id'=>$model->company_id],
			                           ['NOT IN','id',$deductionId]
			                          ];
			    \app\models\BillDeduction::deleteAll($deductionCondition);
			
			    $penaltyCondition = [
			                           'AND',
			                           ['invoice_id'=>$model->id,'agreement_id'=>$model->agreement_id,'session'=>$model->session,'company_id'=>$model->company_id],
			                           ['NOT IN','id',$penaltyId]
			                          ];
			    \app\models\BillPenality::deleteAll($penaltyCondition);
			
			$formatter = Yii::$app->formatter;
			
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
			
			if($model->load(Yii::$app->request->post()) && $model->validate()){
				
				$model->invoice_date = $formatter->asDate($model->invoice_date,'php:Y-m-d');
				
			    $related_invoice = Yii::$app->request->post()['AgreementBill']['related_invoice'];
			    $has_percentage = Yii::$app->request->post()['AgreementBill']['has_percentage'];
				$model->related_invoice = !empty($related_invoice)?json_encode($related_invoice):Null;
				$model->has_percentage = !empty($has_percentage)?1:0;
				
				if($model->start_date)
				    $model->start_date = $formatter->asDate($model->start_date,'php:Y-m-d');
				
				if($model->complete_date)
				    $model->complete_date = $formatter->asDate($model->complete_date,'php:Y-m-d');
				
				$model->save();
				
				if($billItem->saveItem($model,Yii::$app->request->post("BillItem"))){
					
				      if($billTax->saveTax($model,Yii::$app->request->post("BillTax"))){
					        $flag = true;
					        if(Yii::$app->request->post()["BillPenality"]["tax_id"][0]){
				              if(!$spm->savePenality($model,Yii::$app->request->post("BillPenality"))){
					              $transaction->rollBack();
					              $errors = $spm->errors;
					              $flag = false;
				              }
							}

					        if(Yii::$app->request->post()["BillDeduction"]["tax_id"][0]){
				              if(!$sdm->saveDeduction($model,Yii::$app->request->post("BillDeduction"))){
					              $transaction->rollBack();
					              $errors = $sdm->errors;
					              $flag = false;
				              }
							}
							if($flag){
					           $transaction->commit();
					           $model->createPdf();
                               \Yii::$app->session->setFlash('success',  Yii::t('app', 'Bill Invoice No# <strong>{session}/{invoice_no}</strong>', ['session'=>$model->session,'invoice_no' => $model->invoice_no]));
                               return $this->redirect(['agreement/view', 'id' => $agreement->id]);
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
				
				$errors = $model->errors;
				
			}
			
        }

        return $this->render('create', [
            'model' => $model,
			'agreement' => $agreement,
			'billItem' => $billItem,
			'billTax' => $billTax,
			'billDeduction' => $billDeduction,
			'billPenality' => $billPenality,
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
		$model = $this->findModel($id);
        $model->status = AgreementBill::STATUS_PERMANENT_DELETE;
		
		$model->save();

        return $this->redirect(['agreement/view','id'=>$model->agreement_id]);
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
		$model->createPdf();
	}
}
