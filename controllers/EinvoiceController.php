<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Common;
use app\models\AgreementBill;

/**
 * EinvoiceController implements the CRUD actions for Documents model.
 */
class EinvoiceController extends Controller
{
     
    public function actionAuthenticateApi(){
        $api = Yii::$app->einvoice;
        $headers = $api->getAuthenticateHeader();
        echo "<pre>";print_r( $headers );die();
    }
    
    public function actionVerifyIrn( $id ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        if( empty( $id ) ){
            return ['status'=>false,'message'=>'unauthorized access'];
        }
        try{
            $formatter = \Yii::$app->formatter;
            $agreementBill = AgreementBill::findOne($id);
            $data = $agreementBill->getIrnData();
            $validate = \Yii::$app->einvoice_validation;
            $validateOutput = $validate->validateField( $data, false );
            //echo "<pre>";var_dump( $validateOutput );die();
            return $this->renderAjax('verify-irn',[
                'model'          => $agreementBill,
                'data'           => $data,
                'validate'       => $validateOutput['status']?[]:$validateOutput['data'],
                'validate_status'       => $validateOutput['status']
            ]);   
        }catch(\Exception $e){
            return ['status'=>false,'message'=>$e->getMessage()." File: ".$e->getFile()." Line no: ".$e->getLine()];
        }
    }

    public function actionCreateIrn( $id ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        $api = Yii::$app->einvoice;
        $output = $api->generateInvoice($id);
        return $output;;
    }
    
    public function actionSyncByDoc( $id ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        $api = Yii::$app->einvoice;
        $output = $api->syncByDoc($id);
        return $output;
    }
    
    public function actionIrnDetailsByDoc( $id ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        $api = Yii::$app->einvoice;
        $output = $api->getIrnDetailsByDoc( $id );
        return $output;
    }

    public function actionIrnDetailsByIrn( $irn ){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        $api = Yii::$app->einvoice;
        $output = $api->getIrnDetailsByIrn($irn);
        return $output;
    }
    
    public function actionCancelReasonIrn($id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        if( empty( $id ) ){
            return ['status'=>false,'message'=>'unauthorized access'];
        }
        try{
            $agreementBill = AgreementBill::findOne($id);
            return $this->renderAjax('cancel-reason-irn',[
                'model'          => $agreementBill
            ]);   
        }catch(\Exception $e){
            return ['status'=>false,'message'=>$e->getMessage()." File: ".$e->getFile()." Line no: ".$e->getLine()];
        }
    }
    
    public function actionCancelIrn(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
        $api = Yii::$app->einvoice;
        $post = \Yii::$app->request->post()['AgreementBill'];
        $output = $api->cancelIrn( $post['irn_no'], $post['cancel_reason'], $post['cancel_remarks'] );
        return $output;
    }

}
