<?php

namespace app\controllers;

use Yii;
use app\models\SiteDues;
use app\models\Ledger;
use app\models\Document;
use app\models\Account;
use app\models\Search\SiteDues as SiteDuesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
 
/**
 * SiteDuesController implements the CRUD actions for SiteDues model.
 */
class SiteDuesController extends Controller
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
     * Lists all SiteDues models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SiteDuesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SiteDues model.
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
     * Creates a new SiteDues model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SiteDues();
        $formatter = Yii::$app->formatter;

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			  
			  $model->code = $model->getCode();
			  
			  if($model->joining_date)
			       $model->joining_date = $formatter->asDate($model->joining_date,'php:Y-m-d');
			  
			  if($model->save()){
			          
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_SITE_DUES;
			    
			    $ledger_transaction = false;
			    
			    if($model->inout_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_SITE_DUES_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_SITE_DUES_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Site Dues Member has been Created Successfully');
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
     * Updates an existing SiteDues model.
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
			    $type = $ledger::TYPE_SITE_DUES;
			    
			    $ledger_transaction = false;
			    
			    if($model->inout_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_SITE_DUES_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_SITE_DUES_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Site Dues Member has been Updated Successfully');
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
     * Deletes an existing SiteDues model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = $model::STATUS_DELETE;
        $model->save();
        \Yii::$app->session->setFlash('success', 'Site Dues Member has been Deleted Successfully');
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
     * Finds the SiteDues model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SiteDues the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SiteDues::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
