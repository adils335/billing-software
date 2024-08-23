<?php

namespace app\controllers;

use Yii;
use app\models\Company;
use app\models\CompanyGst;
use app\models\CompanyAddresses;
use app\models\Search\Company as CompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends Controller
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
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
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
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post())) {
		   $path = "/upload/logo/";  
		   $file = UploadedFile::getInstance($model, 'logo');
           $filename = \app\models\Common::uploadFile($path,$file);
           if($filename){
               $model->logo = $filename;
               $model->save();
		       \Yii::$app->session->setFlash('success', 'Company has been Created Successfully');
               return $this->redirect(['view', 'id' => $model->id]);
           }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
     
	  public function actionCompanyGst($id=null,$state_id=null)
    {
		$model = $this->findModel($id);
        $gst_id = null; 
		if($state_id){
            $companyGst = CompanyGst::find()->where(['company_id'=>$id,'state_id'=>$state_id])->one();
            if( $companyGst ){
                $gst_id = $companyGst->id;
            }else{
                $companyGst = new  CompanyGst();    
            }
        }
        else
            $companyGst = new  CompanyGst();
        $companyGst->state_id = $state_id;
    
        $modelsAddresses = CompanyAddresses::getAddresses( CompanyAddresses::TYPE_COMPANY, $gst_id);
        if( empty( $modelsAddresses ) ){
            $modelAddress = new CompanyAddresses;
            $modelAddress->type = CompanyAddresses::TYPE_COMPANY;
            $modelAddress->type_id = $gst_id;
            $modelsAddresses = [$modelAddress];
        }
        $post = Yii::$app->request->post();
        $transaction = Yii::$app->db->beginTransaction();
        if ( Yii::$app->request->isPost && $companyGst->load($post) && $companyGst->save() ) {
            $flag = CompanyAddresses::saveAddress( $modelsAddresses, $post,$companyGst );		
            if( $flag === true ){
                $transaction->commit();
                \Yii::$app->session->setFlash('success', 'Company Gst No has been Added Successfully');
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                $modelsAddresses = $flag['models'];
                \Yii::$app->session->setFlash('error', json_encode( $flag['model']->getErrors() ) );
                $transaction->rollBack();
            }
        }
        //echo "<pre>";print_r(  );die();
        return $this->render('company-gst', [
            'model' => $model,
            'companyGst' => $companyGst,
            'modelsAddresses' => $modelsAddresses
        ]);
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldFilename = $model->logo;
        if ($model->load(Yii::$app->request->post())) {
           $path = "/upload/logo/";  
		   $file = UploadedFile::getInstance($model,'logo');
           $filename = \app\models\Common::uploadFile($path,$file,$oldFilename);
           if($filename){
               $model->logo = $filename;
               $model->save();
		       \Yii::$app->session->setFlash('success', 'Company has been Updated Successfully');
               return $this->redirect(['view', 'id' => $model->id]);
           }
		}

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

		\Yii::$app->session->setFlash('success', 'Company has been Deleted Successfully');
        return $this->redirect(['index']);
    }
	
    public function actionDeleteCompanyGst($id=null,$company_id=null)
    {
        CompanyGst::findOne($id)->delete();

		\Yii::$app->session->setFlash('success', 'Company GST has been Deleted Successfully');
        return $this->redirect(['view', 'id' => $company_id]);
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
