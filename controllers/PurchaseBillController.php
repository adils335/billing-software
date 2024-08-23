<?php

namespace app\controllers;

use Yii;
use app\models\PurchaseBill;
use app\models\Search\PurchaseBill as PurchaseBillSearch;
use app\models\PurchaseBillItems;
use app\models\PurchaseBillItemsTax;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;

/**
 * PurchaseBillController implements the CRUD actions for PurchaseBill model.
 */
class PurchaseBillController extends Controller
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
     * Lists all PurchaseBill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseBillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseBill model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $query = PurchaseBillItems::find()->where(['purchase_bill_id' => $id]);

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
     * Creates a new PurchaseBill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PurchaseBill();
        $items = [new PurchaseBillItems()];
        $itemsTax = [[new PurchaseBillItemsTax()]];
        //echo "<pre>";print_r(Yii::$app->request->post());die();
		$formatter = Yii::$app->formatter;
        if ($model->load(Yii::$app->request->post())) {
            $model->date = $formatter->asDate(Yii::$app->request->post()['PurchaseBill']['date'],'php:Y-m-d');
            $path = "/upload/purchase-bill/";  
		    $file = UploadedFile::getInstance($model, 'file');
            $filename = \app\models\Common::uploadFile($path,$file);
            if($filename) $model->file = $filename;
            $items = Model::createMultiple(PurchaseBillItems::classname());
            Model::loadMultiple($items, Yii::$app->request->post());
            
            $valid = $model->validate();
            
            if( !$valid ){
                 $message = implode(' ', array_map(function ($errors) {
                            return implode(' ', $errors);
                            }, $model->getErrors()));
                 \Yii::$app->session->setFlash('error',  $message );
            }
            
            if (isset($_POST['PurchaseBillItemsTax'][0][1])) {
                foreach ($_POST['PurchaseBillItemsTax'] as $indexItems => $taxes) {
                    foreach ($taxes as $indexTax => $tax) {
                        $data['PurchaseBillItemsTax'] = $tax;
                        $modelTax = new PurchaseBillItemsTax;
                        $modelTax->load($data);
                        $itemsTax[$indexItems][$indexTax] = $modelTax;
                        //$valid = $modelTax->validate();
                    }
                }
            }
            //echo "<pre>";print_r($valid);die();
            if( $valid ){
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        
                        foreach ($items as $indexItem => $modelItem) {
                            
                            if ($flag === false) {
                                break;
                            }

                            $modelItem->purchase_bill_id = $model->id;
                            if (! ($flag = $modelItem->save())) {
                                $message = implode(' ', array_map(function ($errors) {
                                              return implode(' ', $errors);
                                           }, $modelItem->getErrors()));
                                \Yii::$app->session->setFlash('error',  $message );
                                $transaction->rollBack();
                                break;
                            }
                            
                            if (isset($itemsTax[$indexItem]) && is_array($itemsTax[$indexItem])) {
                                foreach ($itemsTax[$indexItem] as $indexTax => $modelTax) {
                                    $modelTax->purchase_bill_items_id = $modelItem->id;
                                    if($modelTax->tax_id){
                                        if (!$modelTax->save(false)) {
                                            $flag = false;
                                            $message = implode(' ', array_map(function ($errors) {
                                                  return implode(' ', $errors);
                                               }, $modelTax->getErrors()));
                                            \Yii::$app->session->setFlash('error',  $message );
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                    if ($flag) {
                        \Yii::$app->session->setFlash('success',  'Purchase Bill Successfuly Added' );
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        //echo "<pre>";print_r($itemsTax);die();
        return $this->render('create', [
            'model' => $model,
            'items' => $items,
            'itemsTax' => empty( $itemsTax ) ? [[new PurchaseBillItemsTax]] : $itemsTax,
        ]);
    }

    /**
     * Updates an existing PurchaseBill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $items = $model->purchaseBillItems;
        $itemsTax = [];
        $oldItemsTax = [];
        
        if (!empty($items)) {
            foreach ($items as $indexItems => $modelItem) {
                $taxes = $modelItem->purchaseBillItemsTaxes;
                if( !empty($taxes) ){
                  $itemsTax[$indexItems] = $taxes;
                  $oldItemsTax = ArrayHelper::merge(ArrayHelper::index($taxes, 'id'), $oldItemsTax);   
                }else{
                  $itemsTax[$indexItems] = [new PurchaseBillItemsTax()];   
                }
            }
        }

		$formatter = Yii::$app->formatter;
        if ($model->load(Yii::$app->request->post())) { 
            $model->date = $formatter->asDate(Yii::$app->request->post()['PurchaseBill']['date'],'php:Y-m-d');
            $oldFilename = $model->file;
            $path = "/upload/purchase-bill/";  
		    $file = UploadedFile::getInstance($model, 'file');
		    if( !empty($oldFilename) ){
                $filename = \app\models\Common::uploadFile($path,$file,$oldFilename);
		    }else{
                $filename = \app\models\Common::uploadFile($path,$file);
		    }
            if($filename) $model->file = $filename;
            
            $oldIDs = ArrayHelper::map($items, 'id', 'id');
            $items = Model::createMultiple(PurchaseBillItems::classname(), $items);
            Model::loadMultiple($items, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($items, 'id', 'id')));
            
            
            $valid = $model->validate();
            
            $taxesIDs = [];
            if (isset($_POST['PurchaseBillItemsTax'][0][0])) {
                foreach ($_POST['PurchaseBillItemsTax'] as $indexItem => $taxes) {
                    $taxesIDs = ArrayHelper::merge($taxesIDs, array_filter(ArrayHelper::getColumn($taxes, 'id')));
                    foreach ($taxes as $indexTax => $tax) {
                        $data['PurchaseBillItemsTax'] = $tax;
                        $modelTax = (isset($tax['id']) && isset($oldItemsTax[$tax['id']])) ? $oldItemsTax[$tax['id']] : new PurchaseBillItemsTax;
                        $modelTax->load($data);
                        $itemsTax[$indexItem][$indexTax] = $modelTax;
                        //$valid = $modelTax->validate();
                    }
                }
            }

            $oldTaxesIDs = ArrayHelper::getColumn($oldItemsTax, 'id');
            $deletedTaxesIDs = array_diff($oldTaxesIDs, $taxesIDs);
            //var_dump($deletedTaxesIDs);die();
            if ( !$valid ){
                 $message = implode(' ', array_map(function ($errors) {
                            return implode(' ', $errors);
                            }, $model->getErrors()));
                 \Yii::$app->session->setFlash('error',  $message );
            }else{
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save()) {
                        
                        if (! empty($deletedTaxesIDs)) {
                            PurchaseBillItemsTax::deleteAll(['id' => $deletedTaxesIDs]);
                        }
                        if (! empty($deletedIDs)) {
                            PurchaseBillItems::deleteAll(['id' => $deletedIDs]);
                        }

                        foreach ($items as $indexItem=>$modelItem) {
                            if ($flag === false) {
                                break;
                            }
                            
                            $modelItem->purchase_bill_id = $model->id;
                            if (! ($flag = $modelItem->save())) {
                                $message = implode(' ', array_map(function ($errors) {
                                              return implode(' ', $errors);
                                           }, $modelItem->getErrors()));
                                \Yii::$app->session->setFlash('error',  $message );
                                $transaction->rollBack();
                                break;
                            }
                            
                            if (isset($itemsTax[$indexItem]) && is_array($itemsTax[$indexItem])) {
                                foreach ($itemsTax[$indexItem] as $indexTax => $modelTax) {
                                    $modelTax->purchase_bill_items_id = $modelItem->id;
                                    if($modelTax->tax_id){
                                        if (!($flag = $modelTax->save(false))) {
                                            $message = implode(' ', array_map(function ($errors) {
                                                  return implode(' ', $errors);
                                               }, $modelTax->getErrors()));
                                            \Yii::$app->session->setFlash('error',  $message );
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }        
                                }
                            }
                            
                        }
                    }
                    if ($flag) {
                        \Yii::$app->session->setFlash('success',  'Purchase Bill Successfuly Updated' );
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $items,
            'itemsTax' => (empty($itemsTax)) ? [[new PurchaseBillItemsTax]] : $itemsTax
        ]);
    }

    /**
     * Deletes an existing PurchaseBill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PurchaseBill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PurchaseBill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchaseBill::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
