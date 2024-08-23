<?php

namespace app\controllers;

use yii;
use app\models\StoreIndents;
use app\models\Search\StoreIndents as StoreIndentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\StoreIndentsItems;
use app\models\Model;
use yii\data\ActiveDataProvider;
use kartik\mpdf\Pdf;
use yii\web\UploadedFile;
/**
 * StoreIndentsController implements the CRUD actions for StoreIndents model.
 */
class StoreIndentsController extends Controller
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

    /**
     * Lists all StoreIndents models.
     *
     * @return string
     */

    public function actionStoreIndentsFile($id){
		$model = $this->findModel($id);
        $tmp_path = Yii::getAlias('@webroot/store indents/'); 
        $content = Yii::$app->controller->renderPartial("@app/views/store-indents/bill-pdf", [
            'model' => $model,
        ]);							
        $footer = Yii::$app->controller->renderPartial('@app/views/store-indents/pdf-footer',[
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
        $searchModel = new StoreIndentsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    

    /**
     * Displays a single StoreIndents model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $query = StoreIndentsItems::find()->where(['store_indents_id' => $id]);

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
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new StoreIndents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new StoreIndents();
        $modelsStoreIndentsItems = [new StoreIndentsItems];
        $formatter = Yii::$app->formatter;
        
        if ($model->load(Yii::$app->request->post())) {
            $transaction = \Yii::$app->db->beginTransaction();
            $flag = false;
            $model->indent_date = $formatter->asDate($model->indent_date,"php:Y-m-d");
            $modelsStoreIndentsItems = Model::createMultiple(StoreIndentsItems::classname());
            Model::loadMultiple($modelsStoreIndentsItems, Yii::$app->request->post());
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsStoreIndentsItems) && $valid;
            $model->indentNo;
            $path = "/upload/store-indents/";  
		    $file = UploadedFile::getInstance($model, 'attachment_file');
            $filename = \app\models\Common::uploadFile($path,$file);
            if($filename){
                $model->attachment_file = $filename;
                $model->save();
                if ($valid) {  
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($modelsStoreIndentsItems as $modelStoreIndentsItems) {
                                $modelStoreIndentsItems->store_indents_id = $model->id;
                                if (! ($flag = $modelStoreIndentsItems->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            \Yii::$app->session->setFlash('success', 'Store Items has been submitted successfully');
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
            'modelsStoreIndentsItems' => (empty($modelsStoreIndentsItems)) ? [new StoreIndentsItems] : $modelsStoreIndentsItems
        ]);
    }

    /**
     * Updates an existing StoreIndents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsStoreIndentsItems = $model->storeIndentsItems;
        $formatter = Yii::$app->formatter;
        $oldFilename = $model->attachment_file; 

        if ($model->load(Yii::$app->request->post())) {
            // echo "<pre>";print_r(Yii::$app->request->post());die();
            $transaction = \Yii::$app->db->beginTransaction();
            $flag = false;
            $model->indent_date = $formatter->asDate($model->indent_date,"php:Y-m-d");

            $oldIDs = yii\helpers\ArrayHelper::map($modelsStoreIndentsItems, 'id', 'id');
            $modelsStoreIndentsItems = Model::createMultiple(StoreIndentsItems::classname(), $modelsStoreIndentsItems);
            Model::loadMultiple($modelsStoreIndentsItems, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(yii\helpers\ArrayHelper::map($modelsStoreIndentsItems, 'id', 'id')));
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsStoreIndentsItems) && $valid;

            $path = "/upload/store-indents/";  
            $file = UploadedFile::getInstance($model, 'attachment_file');
            $filename = \app\models\Common::uploadFile($path,$file,$oldFilename);
            if($filename){ 
            $model->attachment_file = $filename;
            $model->save();
                
                if ($valid) {
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                StoreIndentsItems::deleteAll(['id' => $deletedIDs]);
                            }

                            foreach ($modelsStoreIndentsItems as $modelStoreIndentsItems) {
                                $modelStoreIndentsItems->store_indents_id = $model->id;
                                if (! ($flag = $modelStoreIndentsItems->save(false))) {    
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
            'modelsStoreIndentsItems' => (empty($modelsStoreIndentsItems)) ? [new StoreIndentsItems] : $modelsStoreIndentsItems
        ]);
    }

    /**
     * Deletes an existing StoreIndents model.
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
     * Finds the StoreIndents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return StoreIndents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreIndents::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
