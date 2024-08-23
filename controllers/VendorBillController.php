<?php

namespace app\controllers;

use Yii;
use app\models\VendorBill;
use app\models\VendorBillItems;
use app\models\VendorBillTax;
use app\models\VendorBillDeduction;
use app\models\Vendor;
use app\models\Search\VendorBill as VendorBillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VendorBillController implements the CRUD actions for VendorBill model.
 */
class VendorBillController extends Controller
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

    /**
     * Lists all VendorBill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new VendorBill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VendorBill();
		$billItem = [new VendorBillItems];
	    $billTax = [new VendorBillTax];
		$billDeduction = [new VendorBillDeduction];

        if (Yii::$app->request->post()) {
			
			$formatter = Yii::$app->formatter;
			
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
			$flag = true;
			if($model->load(Yii::$app->request->post()) && $model->validate()){
				
				$model->bill_no = $model->billNo();
				$model->invoice_date = !empty($model->invoice_date)?$formatter->asDate($model->invoice_date,'php:Y-m-d'):"";
				$model->bill_date = $formatter->asDate($model->bill_date,'php:Y-m-d');
				
				$model->save(); 
				
				if( !(new VendorBillItems)->saveItem($model,Yii::$app->request->post("VendorBillItems"))){
					$flag = false;
					$transaction->rollBack();
				}
				if(Yii::$app->request->post()["VendorBillTax"][0]["tax_id"]){
                    if( !(new VendorBillTax)->saveTax($model,Yii::$app->request->post("VendorBillTax"))){
                        $flag = false;           
                        $transaction->rollBack();
                    }
                }
                if(Yii::$app->request->post()["VendorBillDeduction"][0]["tax_id"]){
                    if( !(new VendorBillDeduction)->saveDeduction($model,Yii::$app->request->post("VendorBillDeduction"))){
                        $flag = false;
                        $transaction->rollBack();
                    }
                }
				
			}else{
				
				$errors = $model->errors;
				
			}
            
            if( $flag ){
                $ledger = new \app\models\Ledger;
                $type = $ledger::TYPE_VENDOR;
                $ledger_transaction = $ledger->saveLedger($model->bill_date,$model->vendor_id, $model->id , "Vendor Document No",0, $model->pay_amount,
                                        $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_VENDOR_BILL_PAGE);
                if($ledger_transaction){
                    $model->createPdf();
                    $transaction->commit();     
                    \Yii::$app->session->setFlash('success',  Yii::t('app', 'Bill No# <strong>{session}/{bill_no}</strong>', ['session'=>$model->session,'bill_no' => $model->bill_no]));
                    return $this->redirect(['index']);
                }
            }
			
        }

        return $this->render('create', [
            'model' => $model,
            'billItem' => $billItem,
            'billTax' => $billTax,
            'billDeduction' => $billDeduction,
        ]);
    }

    /**
     * Updates an existing VendorBill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $billItem = VendorBillItems::find()->where(['bill_id'=>$model->id])->orderBy(['id'=>SORT_ASC])->all();
	    $billTax =  VendorBillTax::find()->where(['bill_id'=>$model->id])->orderBy(['id'=>SORT_ASC])->all();
		if(empty($billTax))
			$billTax = [new VendorBillTax];
		
		$billDeduction = VendorBillDeduction::find()->where(['bill_id'=>$model->id])->orderBy(['id'=>SORT_ASC])->all();
		if(empty($billDeduction))
			$billDeduction = [new VendorBillDeduction];

        if (Yii::$app->request->post()) {
			
			$formatter = Yii::$app->formatter;
			
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
			$idArray = VendorBillDeduction::find()->select(['id'])->where(['bill_id'=>$model->id])->asArray()->all();
		    foreach($idArray as $key=>$ids){
		    	$removeId[] = $ids['id'];
		    }
            if( !empty( Yii::$app->request->post()["VendorBillDeduction"][0]['tax_id'] ) ){
               $ids =  array_column(Yii::$app->request->post()["VendorBillDeduction"],'id');
               if( !empty($ids) ){
                   $removeId = array_diff( $removeId, $ids );
               }
            }
            if(!empty($removeId))
                VendorBillDeduction::deleteAll(['id'=>$removeId]);

            $flag = true;
			if($model->load(Yii::$app->request->post()) && $model->validate()){
				
				$model->bill_no = $model->billNo();
				$model->invoice_date = !empty($model->invoice_date)?$formatter->asDate($model->invoice_date,'php:Y-m-d'):"";
				$model->bill_date = $formatter->asDate($model->bill_date,'php:Y-m-d');
				
				$model->save();
				
				if( !(new VendorBillItems)->saveItem($model,Yii::$app->request->post("VendorBillItems"))){
					$flag = false;
					$transaction->rollBack();
				}
				if(Yii::$app->request->post()["VendorBillTax"][0]["tax_id"]){
                    if( !(new VendorBillTax)->saveTax($model,Yii::$app->request->post("VendorBillTax"))){
                        $flag = false;           
                        $transaction->rollBack();
                    }
                }
                if(Yii::$app->request->post()["VendorBillDeduction"][0]["tax_id"]){
                    if( !(new VendorBillDeduction)->saveDeduction($model,Yii::$app->request->post("VendorBillDeduction"))){
                        $flag = false;
                        $transaction->rollBack();
                    }
                }

			}else{
				$flag = false;
				$errors = $model->errors;
				
			}

            if( $flag ){
                $ledger = new \app\models\Ledger;
                $type = $ledger::TYPE_VENDOR;
                $ledger_transaction = $ledger->saveLedger($model->bill_date,$model->vendor_id, $model->id , "Vendor Document No",0, $model->pay_amount,
                                        $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_VENDOR_BILL_PAGE);
                if($ledger_transaction){
                    $model->createPdf();
                    $transaction->commit();     
                    \Yii::$app->session->setFlash('success',  Yii::t('app', 'Bill No# <strong>{session}/{bill_no}</strong>', ['session'=>$model->session,'bill_no' => $model->bill_no]));
                    return $this->redirect(['index']);
                }
            }
			
        }

        return $this->render('update', [
            'model' => $model,
            'billItem' => $billItem,
            'billTax' => $billTax,
            'billDeduction' => $billDeduction,
        ]);
    }


    /**
     * Deletes an existing VendorBill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $vendorBill = $this->findModel($id);
        $vendorBill->status = $vendorBill::STATUS_DELETE;
        $vendorBill->save();
        $ledgerModel = new \app\models\Ledger;
        $ledger = \app\models\Ledger::find()->where(['transaction_id'=>$id, 'entry_from'=>$ledgerModel::FROM_VENDOR_BILL_PAGE])->one();
        $ledger->status = $ledgerModel::STATUS_DELETE;
        $ledger->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the VendorBill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VendorBill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VendorBill::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
