<?php

namespace app\controllers;

use Yii;
use app\models\StoreIssue;
use app\models\Search\StoreIssue as StoreIssueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\StoreIssueItems;
use app\models\Model;
use yii\data\ActiveDataProvider;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
use app\models\StoreIndents;


/**
 * StoreIssueController implements the CRUD actions for StoreIssue model.
 */
class StoreIssueController extends Controller
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

    public function actionReport(){
        $formatter = Yii::$app->formatter;
        $searchModel = new StoreIssueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        

        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Lists all StoreIssue models.
     *
     * @return string
     */

    public function actionAjaxIndentsItems( $indent_no ){
        //$this->layout = false;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $indent = StoreIndents::find()->where(['indent_no'=>$indent_no])->one();
        $items = $indent->storeIndentsItems;
        $issueItems = [];
        foreach( $items as $item ){
             $issueItem = new StoreIssueItems;
             $issueItem->setAttributes( $item->attributes );
             $issueItems[] = $issueItem;
        }
        //echo "<pre>";print_r($issueItems);die();
        return $this->renderAjax('form\_item',['items'=>$issueItems,'form'=>\yii\widgets\ActiveForm::begin()]);
    }

    public function actionStoreIssueFile($id){
		$model = $this->findModel($id);
        $tmp_path = Yii::getAlias('@webroot/store issue/'); 
        $content = Yii::$app->controller->renderPartial("@app/views/store-issue/bill-pdf", [
            'model' => $model,
        ]);							
        $footer = Yii::$app->controller->renderPartial('@app/views/store-issue/pdf-footer',[
            'model' => $model,
        ]);
        
        $filename = str_replace( "/","-",$model->indent_no ).".pdf";
        $pdf = new \kartik\mpdf\Pdf([
            'orientation' => Pdf::ORIENT_LANDSCAPE, 
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetHeader(Yii::t('app', 'Indent No').':'.$model->indent_no); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
            
    }

    public function actionIndex()
    {
        $searchModel = new StoreIssueSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StoreIssue model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $query = StoreIssueItems::find()->where(['store_issue_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider
        ]);
    }

    /**
     * Creates a new StoreIssue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate( $indent_no = null)
    {
        $model = new StoreIssue();
        $items = [new StoreIssueItems];
        $formatter = Yii::$app->formatter;
        //if( $indent_no  ){
        //    $indent_no = urldecode($indent_no);
        //    $indent = StoreIndents::find()->where(['indent_no'=>$indent_no])->one();
        //    $indentItems = $indent->storeIndentsItems;
        //    $items = [];
        //    foreach( $indentItems as $indentItem ){
        //         $issueItem = new StoreIssueItems;
        //         $issueItem->setAttributes( $indentItem->attributes );
        //         $items[] = $issueItem;
        //    }
        //}
        //echo "<pre>";print_r( $items );die;
        if ($model->load(Yii::$app->request->post())) {

            $transaction = \Yii::$app->db->beginTransaction();
            $flag = false;
            $model->date = $formatter->asDate($model->date,"php:Y-m-d");
            $items = Model::createMultiple(StoreIssueItems::classname());
            Model::loadMultiple($items, Yii::$app->request->post());
            $valid = $model->validate();
            $valid = Model::validateMultiple($items) && $valid;

            $model->indentNo;

            $path = "/upload/store-issue/";  
		    $file = UploadedFile::getInstance($model, 'attachment_file');
            $filename = \app\models\Common::uploadFile($path,$file);
            if($filename){
                $model->attachment_file = $filename;
                $model->save();
                if ($valid) {  
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($items as $item) {
                                $item->store_issue_id = $model->id;
                                if (! ($flag = $item->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            // $model->createPdf();
                            $transaction->commit();
                            \Yii::$app->session->setFlash('success', 'Store Items has been submitted successfully');
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
            'items' => (empty($items)) ? [new StoreIssueItems] : $items
        ]);
    }

    /**
     * Updates an existing StoreIssue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $items = $model->storeIssueItems;
        $formatter = Yii::$app->formatter;
        $oldFilename = $model->attachment_file;

        if ($model->load(Yii::$app->request->post())) {
            
            $model->date = $formatter->asDate($model->date,"php:Y-m-d");
            $oldIDs = yii\helpers\ArrayHelper::map($items, 'id', 'id');
            $items = Model::createMultiple(StoreIssueItems::classname(), $items);
            Model::loadMultiple($items, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(yii\helpers\ArrayHelper::map($items, 'id', 'id')));
            $valid = $model->validate();
            $valid = Model::validateMultiple($items) && $valid; 
            
            $path = "/upload/store-issue/";  
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
                                StoreIssueItems::deleteAll(['id' => $deletedIDs]);
                            }

                            foreach ($items as $item) {
                                $item->store_issue_id = $model->id;
                                if (! ($flag = $item->save(false))) {    
                                    $transaction->rollBack(); 
                                    break;
                                }
                            }
                        }
                        if ($flag) {    
                            $transaction->commit();
                            \Yii::$app->session->setFlash('success', ' Items has been updated successfully');
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
            'items' => (empty($items)) ? [new StoreIssueItems] : $items
        ]);
    }

    /**
     * Deletes an existing StoreIssue model.
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
     * Finds the StoreIssue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return StoreIssue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreIssue::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
