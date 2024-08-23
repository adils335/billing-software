<?php

namespace app\controllers;

use Yii;
use app\models\WorkerVendor;
use app\models\Document;
use app\models\Account;
use app\models\Search\WorkerVendor as WorkerVendorSearch;
use app\models\Search\Ledger as LedgerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WorkerVendorController implements the CRUD actions for WorkerVendor model.
 */
class WorkerVendorController extends Controller
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
     * Lists all WorkerVendor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkerVendorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkerVendor model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $document = new Document;
        $account = new Account;
        return $this->render('view', [
            'model' => $model,
            'document'=>$document,
            'account'=>$account,
        ]);
    }

    /**
     * Creates a new WorkerVendor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorkerVendor();
        $formatter = Yii::$app->formatter;

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			  
			  $model->code = $model->getCode();
			  
			  if($model->joining_date)
			       $model->joining_date = $formatter->asDate($model->joining_date,'php:Y-m-d');
			  
			  if($model->validate() && $model->save()){
			        
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_WORKER_VENDOR;
			    
			    $ledger_transaction = false;
			    
			    if($model->inout_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_VENDOR_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_VENDOR_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Worker Vendor has been Created Successfully');
                        return $this->redirect(['index']);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			  }else{
			      $model->validate(); 
			      print_r($model->getErrors());die();
			      
			  }
			  
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WorkerVendor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $formatter = Yii::$app->formatter;

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			  
			  if($model->joining_date)
			       $model->joining_date = $formatter->asDate($model->joining_date,'php:Y-m-d');
			  
			  if($model->save()){
			          
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_WORKER_VENDOR;
			    
			    $ledger_transaction = false;
			    
			    if($model->inout_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_VENDOR_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_VENDOR_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Worker Vendor has been Updated Successfully');
                        return $this->redirect(['index']);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			  }else{
			      $model->validate(); 
			      print_r($model->getErrors());die();
			      
			  }
			  
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WorkerVendor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        
        $model = $this->findModel($id);
        $model->status = WorkerVendor::STATUS_DELETE;
        $model->save();
        
        \Yii::$app->session->setFlash('success', 'Worker Vendor has been Deleted Successfully');
        return $this->redirect(['index']);
    }

    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $status = $model->status == $model::STATUS_ACTIVE?$model::STATUS_DEACTIVE:$model::STATUS_ACTIVE;
        $statusLabel = $model->status == $model::STATUS_ACTIVE?'Deactived':'Actived';
        $model->status = $status;
        $model->save();
        \Yii::$app->session->setFlash('success', $model->name.'-'.$model->code.' is '.$statusLabel);
        return $this->redirect(['view', 'id' => $model->id]);
                        
    }
    /**
     * Finds the WorkerVendor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorkerVendor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkerVendor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
