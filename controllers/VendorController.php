<?php

namespace app\controllers;

use Yii;
use app\models\Vendor;
use app\models\Search\Vendor as VendorSearch;
use app\models\Ledger;
use app\models\Document;
use app\models\Account;
use app\models\Search\Ledger as LedgerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VendorController implements the CRUD actions for Vendor model.
 */
class VendorController extends Controller
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

	public function actionLedger()
    {
        $searchModel = new LedgerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$data = $searchModel->dataSearch(Yii::$app->request->queryParams);
		
		if($searchModel->fromDate){
		    $searchModel->fromDate = $formatter->asDate($searchModel->fromDate,'php:d-m-Y');
		}
		
		if($searchModel->toDate){
		    $searchModel->toDate = $formatter->asDate($searchModel->toDate,'php:d-m-Y');
		}
		
        return $this->render('ledger', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'data' => $data,
        ]);
    }

    /**
     * Lists all Vendor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VendorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Vendor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        
        $document = new Document;
        $account = new Account;
        return $this->render('view', [
            'model' => $this->findModel($id),
            'document'=>$document,
            'account'=>$account,
        ]);
    }

    /**
     * Creates a new Vendor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Vendor();

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			  
			  $model->code = $model->getVendorCode();
			  
			if($model->validate() && $model->save()){
			    
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_VENDOR;
			    $date = Yii::$app->formatter->asDate($model->created_at,'php:Y-m-d');
			    
			    $ledger_transaction = false;
			    
			    if($model->balance_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_VENDOR_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_VENDOR_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Vendor has been Created Successfully');
                        return $this->redirect(['index']);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			}else{
				$errores = $model->getErrors();
                print_r($errores);die();
			}
			
			  
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Vendor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			  
			if($model->validate() && $model->save()){
			    
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_VENDOR;
			    $date = Yii::$app->formatter->asDate($model->created_at,'php:Y-m-d');
			    
			    $ledger_transaction = false;
			    
			    if($model->balance_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_VENDOR_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_VENDOR_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Vendor has been Updated Successfully');
                        return $this->redirect(['index']);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			}else{
				$errores = $model->getErrors();
                print_r($errores);die();
			}
			
			  
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Vendor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $vendor = $this->findModel($id);
        $vendor->status = Vendor::STATUS_DELETE;
        $vendor->save(); 

        return $this->redirect(['index']);
    }

    public function actionStatus($id)
    {
        $vendor = $this->findModel($id);
        $status = $vendor->status == $vendor::STATUS_ACTIVE?$vendor::STATUS_DEACTIVE:$vendor::STATUS_ACTIVE;
        $statusLabel = $vendor->status == $vendor::STATUS_ACTIVE?'Deactived':'Actived';
        $vendor->status = $status;
        $vendor->save();
        \Yii::$app->session->setFlash('success', $vendor->name.'-'.$vendor->code.' is '.$statusLabel);
        return $this->redirect(['view', 'id' => $vendor->id]);
                        
    }
    /**
     * Finds the Vendor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Vendor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Vendor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
