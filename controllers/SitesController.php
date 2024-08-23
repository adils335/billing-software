<?php

namespace app\controllers;

use Yii;
use app\models\Sites;
use app\models\Search\SitesSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\models\Model;
use yii\helpers\ArrayHelper;

/**
 * SitesController implements the CRUD actions for Sites model.
 */
class SitesController extends Controller
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
                    'archive' => ['POST'],
                    'un-archive' => ['POST'],
                ],
            ],
        ];
    }
    
	public function actionAjaxSites($id){
		
		Yii::$app->response->format = Response::FORMAT_JSON;
		
		   $sites = \app\models\Sites::find()->where(['district_id'=>$id,'status'=>Sites::ACTIVE_STATUS])->orderBy('id')->all();
		   
		   $data = [['id' => '', 'text' => '']];
           foreach ($sites as $site) {
               $data[] = ['id' => $site->id, 'text' => $site->name];
           }
	    
        return ['data' => $data];
	}
	
    /**
     * Lists all Sites models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SitesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sites model.
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
     * Creates a new Sites model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($company_id)
    {
       $model = Sites::find()->where(['company_id'=>$company_id,'status'=>1])->orderBy(['id'=>SORT_ASC])->all();    
       if( empty($model) ){
         $model = [new Sites()];
       }
       $flag = true;
       if( Yii::$app->request->isPost ){
           $oldIDs = ArrayHelper::map($model, 'id', 'id');
           $model = Model::createMultiple(Sites::classname(),$model);
           Model::loadMultiple($model, Yii::$app->request->post());
           $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($model, 'id', 'id')));
           $valid = Model::validateMultiple($model);
           if( $valid ){
              try { 
                $transaction = \Yii::$app->db->beginTransaction();  
                if (! empty($deletedIDs)) {
                    Sites::UpdateAll(['status'=>2],['id' => $deletedIDs]);
                }
                foreach ($model as $modelItem) {
                    if (! ($flag = $modelItem->save())) {
                        $message = implode(' ', array_map(function ($errors) {
                                      return implode(' ', $errors);
                                   }, $modelItem->getErrors()));
                        \Yii::$app->session->setFlash('error',  $message );
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    \Yii::$app->session->setFlash('success',  'Sites Successfuly Added' );
                    $transaction->commit();
                    return $this->redirect(['./contract-company/view', 'id' => $company_id]);
                }
              } catch (Exception $e) {
                    $transaction->rollBack();
              }    
           }
           
       }
       
       return $this->render('create', [
           'model' => $model,
           'company_id' => $company_id
       ]);
    }

    /**
     * Updates an existing Sites model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', 'Site has been Updated Successfully');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Sites model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

            \Yii::$app->session->setFlash('success', 'Site has been Deleted Successfully');
        return $this->redirect(['index']);
    }
    
       public function actionArchive($company_id,$id)
    {
        $model = $this->findModel($id);
        $model->status = $model::ARCHIVE_STATUS;
        $model->save();
        
        \Yii::$app->session->setFlash('success', 'Site has been archived Successfully');
        return $this->redirect(['../contract-company/view','id'=>$company_id]);
    }
    
    
       public function actionUnArchive($company_id,$id)
    {
        $model = $this->findModel($id);
        $model->status = $model::ACTIVE_STATUS;
        $model->save();
        
        \Yii::$app->session->setFlash('success', 'Site has been un-archived Successfully');
        return $this->redirect(['../contract-company/view','id'=>$company_id]);
    }
    
    /**
     * Finds the Sites model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sites the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sites::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
