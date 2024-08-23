<?php

namespace app\controllers;

use yii;
use app\models\StoreConsumed;
use app\models\StoreIssueItems;
use app\models\Search\StoreIssue as StoreIssueSearch;
use app\models\Search\StoreConsumed as StoreConsumedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\StoreConsumedItems;
use app\models\Model;
use yii\data\ActiveDataProvider;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;


/**
 * StoreConsumedController implements the CRUD actions for StoreConsumed model.
 */
class StoreConsumedController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionStatement(){
        $params = $this->request->queryParams;
        $searchModel = new StoreConsumedSearch();
        $consumedDataProvider = $searchModel->search( $params );
        $products = [];
        $consumedDataProvider->pagination = false;
        $consumedDataProvider->totalCount = false;
        $consumedQuery = clone $consumedDataProvider->query;
        $consumedData = $consumedQuery->all();
        $consumedId = \yii\helpers\ArrayHelper::getColumn($consumedData,'id');
        $items = StoreConsumedItems::find()->where(['store_consumed_id'=>$consumedId])->groupBy('store_products_id')->all();
        $consumedProducts  = [];
        foreach( $items as $item ){
            $products[$item->store_products_id] = $item->storeProducts->name;
        }
        $issueModel = new StoreIssueSearch();
        
        $issueParams['StoreIssue'] = $params['StoreConsumed'];
        $issueDataprovider = $issueModel->storeConsumedSearch($issueParams);
        $issueDataprovider->pagination = false;
        $issueQuery = clone $issueDataprovider->query;
        $issueData = $issueQuery->all();
        $issuesId = \yii\helpers\ArrayHelper::getColumn($issueData,'id');
        $items = StoreIssueItems::find()->where(['store_issue_id'=>$issuesId])->groupBy('store_products_id')->all();
        $issueProducts  = [];
        foreach( $items as $item ){
            $products[$item->store_products_id] = $item->storeProducts->name;
        }
        $data = array_merge($issueDataprovider->getModels(), $consumedDataProvider->getModels());
        
        $dataProvider = new \yii\data\ArrayDataProvider([
          'allModels' => $data
        ]);
        
        if( empty($searchModel->session) || empty($searchModel->date_from) || empty($searchModel->date_to) || empty($searchModel->company_id)){
            $products = [];
            $consumedData = [];
            $issueData = [];
            return $this->render('statement/statement', [
                'products' => $products,
                'issue' => $issueData,
                'consumed' => $consumedData,
                'searchModel' => $searchModel,
            ]);
        }
        
        return $this->render('statement/statement', [
            'searchModel' => $searchModel,
            'products' => $products,
            'issue' => $issueData,
            'consumed' => $consumedData,
            'dataProvider' => $dataProvider,
           
        ]);
    }

    public function actionStoreConsumedFile($id){
		$model = $this->findModel($id);
        $tmp_path = Yii::getAlias('@webroot/store consumed/'); 
        $content = Yii::$app->controller->renderPartial("@app/views/store-consumed/bill-pdf", [
            'model' => $model,
        ]);							
        $footer = Yii::$app->controller->renderPartial('@app/views/store-consumed/pdf-footer',[
            'model' => $model,
        ]);
        
        $filename = str_replace( "/","-",$model->invoice_no ).".pdf";
        $pdf = new \kartik\mpdf\Pdf([
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetHeader(Yii::t('app', 'Invoice No').':'.$model->invoice_no); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
            
    }

    /**
     * Lists all StoreConsumed models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StoreConsumedSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StoreConsumed model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StoreConsumed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new StoreConsumed();
        $items = [new StoreConsumedItems];
        $formatter = Yii::$app->formatter;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = \Yii::$app->db->beginTransaction();
            $flag = false;
            $model->invoice_date = $formatter->asDate($model->invoice_date,"php:Y-m-d");
            $items = Model::createMultiple(StoreConsumedItems::classname());
            Model::loadMultiple($items, Yii::$app->request->post());
            $valid = $model->validate();
            
            $valid = Model::validateMultiple($items) && $valid;
            $model->invoiceNo;
            $path = "/upload/store-consumed/";  
		    $file = UploadedFile::getInstance($model, 'attachment_file');
            $filename = \app\models\Common::uploadFile($path,$file);
            if($filename){
                $model->attachment_file = $filename;
                $model->save();
                if ($valid) {  
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($items as $item) {
                                $item->store_consumed_id = $model->id;
                                if (! ($flag = $item->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            \Yii::$app->session->setFlash('success', 'Store consumed has been submitted successfully');
                            $transaction->commit();
                            return $this->redirect(['index']);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'items' => (empty($items)) ? [new StoreConsumedItems] : $items
        ]);
    }

    /**
     * Updates an existing StoreConsumed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $items = $model->storeConsumedItems;
        $formatter = Yii::$app->formatter;
        $oldFilename = $model->attachment_file;

        if ($model->load(Yii::$app->request->post())) {
            $model->invoice_date = $formatter->asDate($model->invoice_date,"php:Y-m-d");
            $oldIDs = yii\helpers\ArrayHelper::map($items, 'id', 'id');
            $items = Model::createMultiple(StoreConsumedItems::classname(), $items);
            Model::loadMultiple($items, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(yii\helpers\ArrayHelper::map($items, 'id', 'id')));
            $valid = $model->validate();
            $valid = Model::validateMultiple($items) && $valid;

            $path = "/upload/store-consumed/";  
            $file = UploadedFile::getInstance($model, 'attachment_file');
            $filename = \app\models\Common::uploadFile($path,$file,$oldFilename);
            if($filename){ 
            $model->attachment_file = $filename;
            $model->save();
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                StoreConsumedItems::deleteAll(['id' => $deletedIDs]);
                            }

                            foreach ($items as $item) {
                                $item->store_consumed_id = $model->id;
                                if (! ($flag = $item->save(false))) {    
                                    $transaction->rollBack(); 
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            \Yii::$app->session->setFlash('success', ' Items has been updated successfully');
                                
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } 
                    catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
        }
        return $this->render('update', [
            'model' => $model,
            'items' => (empty($items)) ? [new StoreConsumedItems] : $items
        ]);
    }

    /**
     * Deletes an existing StoreConsumed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StoreConsumed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return StoreConsumed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreConsumed::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
