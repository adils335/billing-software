<?php

namespace app\controllers;

use Yii;
use app\models\BillBackMaster;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Model;

/**
 * BillBackMasterController implements the CRUD actions for BillBackMaster model.
 */
class BillBackMasterController extends Controller
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

    public function actionAjaxRecordBySrmid($srmid){
        
        $model = BillBackMaster::find()->where(['srmid'=>$srmid])->orderBy(['sno'=>SORT_ASC])->all();
        return $this->renderAjax('view', [
            'model' => $model,
        ]);
        
    }
    
    /**
     * Lists all BillBackMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BillBackMaster::find()->groupBy(['type']),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BillBackMaster model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($srmid)
    {
        $model = BillBackMaster::find()->where(['srmid'=>$srmid])->one();
        //$billBack = BillBackMaster::find()->where(['srmid'=>$srmid])->all();
        $billBack = new ActiveDataProvider([
            'query' => BillBackMaster::find()->where(['srmid'=>$srmid])->orderBy('id DESC'),
        ]);
        return $this->render('view', [
            'model' => $billBack,
        ]);
    }

    /**
     * Creates a new BillBackMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = [new BillBackMaster()];
        $flag = true;
        if (Yii::$app->request->post()) {
             $type = Yii::$app->request->post()['BillBackMaster'][0]['type'];
                $model = Model::createMultiple(BillBackMaster::classname(), []);
                             Model::loadMultiple($model, Yii::$app->request->post());
             $flag = BillBackMaster::find()->where(['type'=>$type])->exists();
             if($flag){
                \Yii::$app->session->setFlash('error', 'Type Already exist');
             }else{
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction(); 
                $srmid = time();
                foreach ($model as  $billBack) {
                   $billBack->srmid = $srmid;
                   $billBack->save();
                }     
                $transaction->commit();
                \Yii::$app->session->setFlash('success', 'Bill Back has been Created Successfully');
                return $this->redirect(['bill-back-master/index']);
             }
            
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BillBackMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($srmid)
    {
        $model = BillBackMaster::find()->where(['srmid'=>$srmid])->all();
        $flag = true;
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        if (Yii::$app->request->post()) {
            $billBackData = Yii::$app->request->post('BillBackMaster');
            $ids = array_column($billBackData,'id');
            $ids = array_filter($ids);
            if($ids){
               BillBackMaster::deleteAll(['AND',['srmid'=>$srmid],['NOT IN','id',$ids]]);
            }
            $srmid = $billBackData[0]['srmid'];
            foreach($billBackData as $billBack){
                if(!empty($billBack['id'])){
                    $BillBackMasterModel = BillBackMaster::findOne($billBack['id']);
                }else{
                    $BillBackMasterModel = new BillBackMaster;
                    $check = BillBackMaster::find()
                                                ->where(['type'=>$billBack['type']])
                                                ->andWhere(['!=','srmid',$srmid])->one();
                    if(!empty($check)){
                        \Yii::$app->session->setFlash('error', 'Type should be unique.');
                        $flag = false;
                        break;
                    }                            
                }
                $loadArray['BillBackMaster'] = $billBack;
                $BillBackMasterModel->load($loadArray);
                $BillBackMasterModel->srmid = $srmid;
                $BillBackMasterModel->type = trim($BillBackMasterModel->type);
                $BillBackMasterModel->save();
            }
            if($flag){
                $transaction->commit();
                \Yii::$app->session->setFlash('success', 'Bill Back has been Updated Successfully');
                return $this->redirect(['bill-back-master/index']);
            }else{
                \Yii::$app->session->setFlash('error', 'Type should be unique.');
                $transaction->rollback();
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BillBackMaster model.
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
     * Finds the BillBackMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BillBackMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BillBackMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
