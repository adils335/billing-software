<?php

namespace app\controllers;

use app\models\AgreementProduct;
use app\models\Search\AgreementProduct as AgreementProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\AgreementProductItems;
use app\models\Model;
use yii;

/**
 * AgreementProductController implements the CRUD actions for AgreementProduct model.
 */
class AgreementProductController extends Controller
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
     * Lists all AgreementProduct models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AgreementProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AgreementProduct model.
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
     * Creates a new AgreementProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AgreementProduct();
        $items = [new AgreementProductItems];
        if ($model->load(Yii::$app->request->post())) {
            $transaction = \Yii::$app->db->beginTransaction();
            $flag = false;
            $items = Model::createMultiple(AgreementProductItems::classname());

            Model::loadMultiple($items, Yii::$app->request->post());
            $valid = $model->validate();

            $valid = Model::validateMultiple($items) && $valid;

            if($model->save()){

                if ($valid) {  
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($items as $item) {
                                $item->	agreement_product_id = $model->id;
                                if (! ($flag = $item->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            \Yii::$app->session->setFlash('success', 'Agreement product has been submitted successfully');
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
            'items' => (empty($items)) ? [new AgreementProductItems] : $items
        ]);
    }

    /**
     * Updates an existing AgreementProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $items = $model->agreementProductItems;

        if ($model->load(Yii::$app->request->post())) {
            $oldIDs = yii\helpers\ArrayHelper::map($items, 'id', 'id');
            $items = Model::createMultiple(AgreementProductItems::classname(), $items);
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
                                AgreementProductItems::deleteAll(['id' => $deletedIDs]);
                            }

                            foreach ($items as $item) {
                                $item->agreement_product_id = $model->id;
                                if (! ($flag = $item->save(false))) {    
                                    $transaction->rollBack(); 
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            \Yii::$app->session->setFlash('success', ' Agreement  Products has been updated successfully');
                                
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
            'items' => (empty($items)) ? [new AgreementProductItems] : $items
        ]);
    }

    /**
     * Deletes an existing AgreementProduct model.
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
     * Finds the AgreementProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return AgreementProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AgreementProduct::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
