<?php

namespace app\controllers;

use Yii;
use app\models\ContractCompany;
use app\models\ContractCompanyGst;
use app\models\CompanyAddresses;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContractCompanyController implements the CRUD actions for ContractCompany model.
 */
class ContractCompanyController extends Controller
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
     * Lists all ContractCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ContractCompany::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContractCompany model.
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
     * Creates a new ContractCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContractCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		\Yii::$app->session->setFlash('success', 'Contract Company has been Created Successfully');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContractCompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
		\Yii::$app->session->setFlash('success', 'Contract Company has been Updated Successfully');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ContractCompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

		\Yii::$app->session->setFlash('success', 'Contract Company has been Deleted Successfully');
        return $this->redirect(['index']);
    }

	public function actionCompanyGst($id=null,$gst_id=null)
    {
		$model = $this->findModel($id);
        $modelsAddresses = CompanyAddresses::getAddresses( CompanyAddresses::TYPE_CONTRACT_COMPANY, $gst_id);
        if( empty( $modelsAddresses ) ){
            $modelAddress = new CompanyAddresses;
            $modelAddress->type = CompanyAddresses::TYPE_CONTRACT_COMPANY;
            $modelAddress->type_id = $gst_id;
            $modelsAddresses = [$modelAddress];
        }

		if($gst_id)
			$companyGst = ContractCompanyGst::find()->where(['company_id'=>$id,'id'=>$gst_id])->one();
		else
            $companyGst = new  ContractCompanyGst();
        
        $post = Yii::$app->request->post();
        if(!empty($post['ContractCompanyGst']['districts'])){
          $post['ContractCompanyGst']['districts'] = json_encode($post['ContractCompanyGst']['districts']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        if ( Yii::$app->request->isPost && $companyGst->load($post) && $companyGst->save() ) {
            $flag = CompanyAddresses::saveAddress( $modelsAddresses, $post, $companyGst );		
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
            'modelsAddresses' => $modelsAddresses
        ]);
    }
	
	
    public function actionDeleteCompanyGst($id=null,$company_id=null)
    {
        ContractCompanyGst::findOne($id)->delete();

		\Yii::$app->session->setFlash('success', 'Company GST has been Deleted Successfully');
        return $this->redirect(['view', 'id' => $company_id]);
    }

    /**
     * Finds the ContractCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContractCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContractCompany::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
