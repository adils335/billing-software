<?php

namespace app\controllers;

use app\models\PurchaseProduct;
use app\models\Search\PurchaseProduct as PurchaseProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\PurchaseProductItems;
use app\models\Model;
use yii;

/**
 * PurchaseProductController implements the CRUD actions for PurchaseProduct model.
 */
class PurchaseProductController extends Controller
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
     * Lists all PurchaseProduct models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PurchaseProduct model.
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
     * Creates a new PurchaseProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PurchaseProduct();
        $items = [new PurchaseProductItems];
        $formatter = Yii::$app->formatter;
        if ($model->load(Yii::$app->request->post())) {
            $transaction = \Yii::$app->db->beginTransaction();
            $model->invoice_date = $formatter->asDate($model->invoice_date,"php:Y-m-d");
            $flag = false;
            $items = Model::createMultiple(PurchaseProductItems::classname());

            Model::loadMultiple($items, Yii::$app->request->post());
            $valid = $model->validate();

            $valid = Model::validateMultiple($items) && $valid;

            // echo"<pre>";
            // print_r($items[0]->getErrors());
            // die();

            if($model->save()){

                if ($valid) {  
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($items as $item) {
                                $item->purchase_product_id = $model->id;
                                if (! ($flag = $item->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            \Yii::$app->session->setFlash('success', 'Purchase product has been submitted successfully');
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
            'items' => (empty($items)) ? [new PurchaseProductItems] : $items
        ]);
    }

    /**
     * Updates an existing PurchaseProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $items = $model->purchaseProductItems;
        $formatter = Yii::$app->formatter;

        if ($model->load(Yii::$app->request->post())) {
            $model->invoice_date = $formatter->asDate($model->invoice_date,"php:Y-m-d");
            $oldIDs = yii\helpers\ArrayHelper::map($items, 'id', 'id');
            $items = Model::createMultiple(PurchaseProductItems::classname(), $items);
            Model::loadMultiple($items, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(yii\helpers\ArrayHelper::map($items, 'id', 'id')));
            $valid = $model->validate();
            $valid = Model::validateMultiple($items) && $valid;

            if($model->save()){
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            if (!empty($deletedIDs)) {
                                PurchaseProductItems::deleteAll(['id' => $deletedIDs]);
                            }

                            foreach ($items as $item) {
                                $item->purchase_product_id = $model->id;
                                if (! ($flag = $item->save(false))) {    
                                    $transaction->rollBack(); 
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            \Yii::$app->session->setFlash('success', ' Purchase  Products has been updated successfully');
                                
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
            'items' => (empty($items)) ? [new PurchaseProductItems] : $items
        ]);
    }

    /**
     * Deletes an existing PurchaseProduct model.
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
     * Finds the PurchaseProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PurchaseProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PurchaseProduct::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
