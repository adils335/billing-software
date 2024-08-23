<?php

namespace app\controllers;

use Yii;
use app\models\BillingCompany;
use app\models\CompanyAddresses;
use app\models\BillingCompanyGst;
use app\models\Search\BillingCompany as BillingCompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BillingCompanyController implements the CRUD actions for BillingCompany model.
 */
class BillingCompanyController extends Controller
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
     * Lists all BillingCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BillingCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BillingCompany model.
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
     * Creates a new BillingCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BillingCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\Yii::$app->session->setFlash('success', 'Billing Party Created successfully');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BillingCompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			\Yii::$app->session->setFlash('success', 'Billing Party Updated successfully');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BillingCompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

		\Yii::$app->session->setFlash('success', 'Billing Party Deleted successfully');
            
        return $this->redirect(['index']);
    }

	  public function actionCompanyGst($id=null,$gst_id=null)
    {
		$model = $this->findModel($id);
		
		if($gst_id)
			$companyGst = BillingCompanyGst::find()->where(['company_id'=>$id,'id'=>$gst_id])->one();
		else
            $companyGst = new  BillingCompanyGst();
        
        $modelsAddresses = CompanyAddresses::getAddresses( CompanyAddresses::TYPE_BILLING_COMPANY, $gst_id);
        if( empty( $modelsAddresses ) ){
            $modelAddress = new CompanyAddresses;
            $modelAddress->type_id = $gst_id;
            $modelAddress->type = CompanyAddresses::TYPE_BILLING_COMPANY;
            $modelsAddresses = [$modelAddress];
        }

        $post = Yii::$app->request->post();
        if(!empty($post['BillingCompanyGst']['districts'])){
          $post['BillingCompanyGst']['districts'] = json_encode($post['BillingCompanyGst']['districts']);
        }
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
        return $this->render('company-gst', [
            'model' => $model,
            'companyGst' => $companyGst,
            'modelsAddresses'=>$modelsAddresses
        ]);
    }
	
	
    public function actionDeleteCompanyGst($id=null,$company_id=null)
    {
        BillingCompanyGst::findOne($id)->delete();

		\Yii::$app->session->setFlash('success', 'Company GST has been Deleted Successfully');
        return $this->redirect(['view', 'id' => $company_id]);
    }

    /**
     * Finds the BillingCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BillingCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BillingCompany::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
