<?php

namespace app\controllers;

use Yii;
use app\models\AgreementBill;
use app\models\Search\AgreementBill as AgreementBillSearch;
use app\models\BillTax;
use app\models\Search\Reports;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * CommonController implements the CRUD actions for Common model.
 */
class ReportsController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new Reports();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$model = $dataProvider->query->all();
        //$invoiceIds = $dataProvider->query->select(['id'])->asArray()->all();
        //$invoiceId = array_column($invoiceIds,'id');
        //$taxes = BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        //$pdf_file = AgreementBill::generateGstPdf('paid',$searchModel,$model,$taxes);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);  
    }
    
    public function actionGstPaid(){
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
        $taxesModel = BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        $taxes = [];
        foreach( $taxesModel as $billTax ){
            $taxes[] = ['name'=>$billTax->tax->name,'tax_id'=>$billTax->tax_id];
        }
        //$model = $dataProvider->query->all();                   
        return $this->render('gst-paid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'taxes' => $taxes
        ]);
    }


    public function actionGstBill()
    {
        $queryParams =Yii::$app->request->queryParams;
        $searchModel = new AgreementBillSearch();
        
        if( empty( $queryParams ) || 
            ( isset( $queryParams['AgreementBill'] ) && !isset( $queryParams['AgreementBill']['from_month'] ) && !isset( $queryParams['AgreementBill']['to_month'] )
            )
        ){
            $queryParams['AgreementBill']['from_month'] = date("01-m-Y");
            $queryParams['AgreementBill']['to_month'] = date("t-m-Y");
            

        }else{

            if($queryParams['AgreementBill']['from_month']){
                $queryParams['AgreementBill']['from_month'] = "01-".$queryParams['AgreementBill']['from_month'];
            }
            if($queryParams['AgreementBill']['to_month']){
                $queryParams['AgreementBill']['to_month'] = "01-".$queryParams['AgreementBill']['to_month'];
            }
        }
        $dataProvider = $searchModel->search($queryParams);
        $model = $dataProvider->query->all();
        $invoiceIds = $dataProvider->query->select(['agreement_bill.id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $taxes = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        $deduction = \app\models\BillDeduction::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        
        // AgreementBill::generateGstPdf('bill',$searchModel->from_month,$model,$taxes,$deduction);
        // return $this->redirect([Yii::getAlias("@web/agreement bill/filter.pdf")]);

        return $this->render('gst-bill', [
            'searchModel' => $searchModel,
            'model'=>$model,
            'taxes'=>$taxes,
            'deduction'=>$deduction,
        ]);
    }

    public function actionGstPurchase()
    {
        $queryParams =Yii::$app->request->queryParams;
        //echo "<pre>";print_r($queryParams);die();
        $searchModel = new PurchaseBillSearch();
        //echo "<pre>";print_r($searchModel);die();

        $searchVendorBill = new VendorBillSearch();
        
        /*$params = [];
        if( isset( $queryParams['AgreementBill'] ) ){
            foreach( $queryParams as $key => $value ){
                if( $key == "AgreementBill" ){
                    $params['PurchaseBill'] = $queryParams['AgreementBill'];
                    $params['VendorBill'] = $queryParams['AgreementBill'];
                }else{ 
                    $params[$key] = $value;
                }
            }
        } */
        
        if( empty( $queryParams ) || 
            ( isset( $queryParams['AgreementBill'] ) && !isset( $queryParams['AgreementBill']['from_month'] ) && !isset( $queryParams['AgreementBill']['to_month'] )
            )
        ){
            // die("1");
            $queryParams['AgreementBill']['from_month'] = date("01-m-Y");
            $queryParams['AgreementBill']['to_month'] = date("t-m-Y");
            

        }else{
            if($queryParams['AgreementBill']['from_month']){
                $queryParams['AgreementBill']['from_month'] = "01-".$queryParams['AgreementBill']['from_month'];
            }
            if($queryParams['AgreementBill']['to_month']){
                $queryParams['AgreementBill']['to_month'] = "01-".$queryParams['AgreementBill']['to_month'];
            }
        }
        //echo "<pre>";print_r($queryParams);die();

        $dataProvider = $searchModel->search($queryParams);
        $dataProviderVendorBill = $searchVendorBill->search($queryParams);
        
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

        // $month = $searchModel->from_month;
        
        return $this->render('gst-purchase', [
            'model' => $model,
            'searchModel' => $searchModel,
            'taxes' => $taxes,
            'vendorBillModel' => $vendorBillmodel,
            'vendorBillTaxes' => $vendorBilltaxes,
        ]);
        
    }
    //End Section Gst Reports 


    //Start Section Invoice Reports


    public function actionInvoiceDetail()
    { 
        $queryParams = Yii::$app->request->queryParams;
        $searchModel = new AgreementBillSearch();

        $dataProvider = $searchModel->invoiceSearch($queryParams);
        $bills = $dataProvider->query->all();

        $invoiceIds = $dataProvider->query->select(['agreement_bill.id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');

        $taxes = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
  
        return $this->render('invoice_report/invoice_detail', [
            'searchModel'=>$searchModel,
            'bills'=>$bills,
            'taxes'=>$taxes,
        ]);
    }
    
    public function actionInvoiceCancelled()
    { 
        $queryParams = Yii::$app->request->queryParams;
        $searchModel = new AgreementBillSearch();

        $dataProvider = $searchModel->invoiceSearch($queryParams);
        $bills = $dataProvider->query->all();

        $invoiceIds = $dataProvider->query->select(['agreement_bill.id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');

        $taxes = \app\models\BillTax::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        
        return $this->render('invoice_report/invoice_cancelled', [
            'searchModel'=>$searchModel,
            'bills'=>$bills,
            'taxes'=>$taxes,
        ]);
    }

    public function actionInvoicePenality()
    { 
        $queryParams = Yii::$app->request->queryParams;
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->invoiceSearch($queryParams);
        $bills = $dataProvider->query->all();

        $invoiceIds = $dataProvider->query->select(['agreement_bill.id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');

        $penality = \app\models\BillPenality::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();

        return $this->render('invoice_report/invoice_penality', [
            'searchModel'=>$searchModel,
            'bills'=>$bills,
            'penality'=>$penality,
        ]);
    }

    

    public function actionInvoiceDeduction()
    {
        $queryParams = Yii::$app->request->queryParams;
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->invoiceSearch($queryParams);
        $bills = $dataProvider->query->all();
        $invoiceIds = $dataProvider->query->select(['agreement_bill.id'])->asArray()->all();
        $invoiceId = array_column($invoiceIds,'id');
        $deductions = \app\models\BillDeduction::find()->select(['tax_id'])->where(['invoice_id'=>$invoiceId])->groupBy(['tax_id'])->all();
        return $this->render('invoice_report/invoice_deduction', [
            'searchModel'=>$searchModel,
            'bills'=>$bills,
            'deductions'=>$deductions,
        ]);
        
    }

    public function actionInvoicePayAmount()
    { 
        $queryParams = Yii::$app->request->queryParams;
        $searchModel = new AgreementBillSearch();
        $dataProvider = $searchModel->invoiceSearch($queryParams);
        
        $bills = $dataProvider->query->all();
        return $this->render('invoice_report/pay_amount', [
            'searchModel'=>$searchModel,
            'bills'=>$bills,

        ]);
    }

    //End Section Reports


    //Start Section Site Payment
    public function actionSitePayment(){
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->paymentSearch(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        $payments = $dataProvider->query->all();
        $paymentIds = $dataProvider->query->select(['payment.id'])->asArray()->all();
        $paymentId = array_column($paymentIds,'id');
        $ledger_data = Ledger::find()->where(['transaction_id'=>$paymentId])->orderBy(['id'=>SORT_DESC])->all();
        
        return $this->render('site_payment/index', [
            'searchModel'=>$searchModel,
            'payments'=>$payments,
            'ledger_data'=>$ledger_data,
        ]);
    }
    //End Section Site Payment


    //Start Section Site Payment
    public function actionCompany(){
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->companySearch(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        $payments = $dataProvider->query->all();
        $paymentIds = $dataProvider->query->select(['payment.id'])->asArray()->all();
        $paymentId = array_column($paymentIds,'id');
        $ledger_data = Ledger::find()->where(['transaction_id'=>$paymentId,'type'=>Ledger::TYPE_ACCOUNT])->orderBy(['id'=>SORT_DESC])->all();
        //echo $ledger_data->createcommand()->getRawSql();die();
        return $this->render('company_debit_credit/index', [
            'searchModel'=>$searchModel,
            'payments'=>$payments,
            'ledger_data'=>$ledger_data,
            'dataProvider'=>$dataProvider
        ]);
    }
    //End Section Site Payment


    //Start Section Site Payment
    public function actionCompanyRecord(){
        
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->companySearch(Yii::$app->request->queryParams);
        $dataProvider->pagination = false;
        $payments = $dataProvider->query->all();
        $paymentIds = $dataProvider->query->select(['payment.id'])->asArray()->all();
        $paymentId = array_column($paymentIds,'id');
        $ledger_data = Ledger::find()->where(['transaction_id'=>$paymentId])->orderBy(['id'=>SORT_DESC])->all();

        return $this->render('company_record_payment/index', [
            'searchModel'=>$searchModel,
            'payments'=>$payments,
            'ledger_data'=>$ledger_data,
        ]);
    }
    //End Section Site Payment


    //Start Invoice Payment Section
    public function actionInvoicePaymentRecord(){

        return $this->render('invoice_payment_record/index', [
        ]);
    }
    //End Invoice Payment Section

    //Start Invoice Deduction Section
    public function actionInvoiceDeductionRecord(){

        return $this->render('invoice_deduction_record/index', [
        ]);
    }
    //End Invoice Deduction Section

}
