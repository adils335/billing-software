<?php

namespace app\controllers;

use Yii;
use app\models\BankAccount;
use app\models\AccountLedger;
use app\models\Ledger;
use app\models\Search\Ledger as LedgerSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BankAccountController implements the CRUD actions for BankAccount model.
 */
class BankAccountController extends Controller
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
        
        $formatter = \Yii::$app->formatter;
        
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
     * Lists all BankAccount models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BankAccount::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BankAccount model.
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
     * Creates a new BankAccount model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BankAccount();
        
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			  
			if($model->validate() && $model->save()){
			    
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_ACCOUNT;
			    $date = Yii::$app->formatter->asDate($model->created_at,'php:Y-m-d');
			    
			    $ledger_transaction = false;
			    
			    if($model->balance_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", $model->openning_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_ACCOUNT_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", 0,$model->openning_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_ACCOUNT_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Account has been Created Successfully');
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
     * Updates an existing BankAccount model.
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
			    $type = $ledger::TYPE_ACCOUNT;
			    $date = Yii::$app->formatter->asDate($model->created_at,'php:Y-m-d');
			    
			    $ledger_transaction = false;
			    
			    if($model->balance_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", $model->openning_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_ACCOUNT_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($date, $model->id, $model->id, "Opening Balance", 0,$model->openning_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_ACCOUNT_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Account has been Updated Successfully');
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
     * Deletes an existing BankAccount model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
			\Yii::$app->session->setFlash('success', 'Account has been Deleted Successfully');
            
        return $this->redirect(['index']);
    }

    /**
     * Finds the BankAccount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankAccount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BankAccount::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
