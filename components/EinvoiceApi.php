<?php

namespace app\components;

use yii;
use yii\helpers\Url;
use app\models\EinvoiceAuth;
use app\models\AgreementBill;
use app\models\Common;
use app\models\Einvoice;

class EinvoiceApi extends yii\base\BaseObject{

    private $settings;
    private $user_name;
    private $authToken;
    private $sek;
    private $isSandbox = true;

    public function __construct( $isSandbox = false ) {
        parent::__construct();
        $this->settings = Yii::$app->params['einvoice'];
        $this->isSandbox = $isSandbox;
    }
    
    private function createUrl( $baseUri, $hasVersion = true ){
        $base_url = $this->settings['live_url'] . $baseUri;
        if( $this->isSandbox ){
            $this->settings['sandbox_url'] . $baseUri;
        }
        $base_url = str_replace('{version​}',$this->settings['version'],$base_url);
        return $base_url;    
    }

    private function getBaseUrl( ){
        $base_url = $this->settings['live_url'];
        if( $this->isSandbox ){
            $this->settings['sandbox_url'];
        }
        return $base_url;
    }

    private function jsonHeader(){
        return [
            'Content-Type' => 'application/json',
            'accept' => '*/*'
        ];
    }

    private function getRequestHeader(){
        return [
            'client_id' => $this->settings['client_id'],
            'client_secret' => $this->settings['client_secret'],
            'gstin' =>  $this->settings['gstin']
        ];
    }

    public function getAuthenticateHeader(){
        $flag = false;
        do{
            $einvoiceAuth = EinvoiceAuth::find()->orderBy(['id'=>SORT_DESC])->one();
            if( !empty( $einvoiceAuth ) && strtotime( date( "Y-m-d H:i:s" ) ) <= strtotime( $einvoiceAuth->TokenExpiry ) ){
                $this->user_name = $einvoiceAuth->UserName;
                $this->authToken = $einvoiceAuth->AuthToken;
                $this->sek = $einvoiceAuth->Sek;
            }else{
                $this->apiAuthenticate();
                $flag = true;
            }
        }while( $flag );
        return array_merge( $this->getRequestHeader(),
            [
               'username' => $this->user_name,
               'auth-token' => $this->authToken,
               'ip_address' => Yii::$app->request->userIP
            ]);
    }

    private function encryptData( $source ){
        $filename = Yii::getAlias("@app/config/").$this->settings['public_key'];
        $fp=fopen($filename,"r");
        $pub_key_string=fread($fp,8192);
        fclose($fp);
        openssl_get_publickey($pub_key_string);
        // Encrypt using the public key
        openssl_public_encrypt($source, $encrypted, $pub_key_string);
        return (base64_encode($encrypted));
    }

    private function apiAuthenticate(){
        $appkey = base64_encode( random_bytes(32) );
        $data = [
            'UserName' => $this->settings['UserName'],
            'Password' => $this->settings['Password'],
            'client_id' => $this->settings['client_id'],
            'client_secret' => $this->settings['client_secret'],
            'gstin' => $this->settings['gstin'],
            'ip_address' => Yii::$app->request->userIP
        ];
        //$data = json_encode( $data );
        //$data = $this->encryptData($data);
        $url = $this->createUrl( $this->settings['resource']['auth'], false );
        $method = "GET";
        $headerParams = $data;//array_merge( $this->jsonHeader(),$this->getRequestHeader()  );
        $postData = [];//$data;//['data' => $data];
        $queryParams = ['email'=>$this->settings['email']];
        list($output, $responseCode, $responseHeader) = Yii::$app->curl->callApi( $url, $method, $headerParams, $queryParams, $postData );
        //echo "<pre>";var_dump( $output, $responseCode, $responseHeader );die();
        if( $output->status_cd == "Sucess" ){
            $einvoiceAuth = new EinvoiceAuth;
            $einvoiceAuth->ClientId = $output->data->ClientId;
            $einvoiceAuth->UserName = $output->data->UserName;
            $einvoiceAuth->AuthToken = $output->data->AuthToken;
            $einvoiceAuth->Sek = $output->data->Sek;
            $einvoiceAuth->TokenExpiry = $output->data->TokenExpiry;
            if( !$einvoiceAuth->save() ){
               echo "<pre>";print_r( $einvoiceAuth->getErrors() );die();
            }
            $einvoiceAuth->save();
            return true;
        }else{
            return $output;
        }
    }

    public function getGstDetails( $gstin ){
        $headerParams = $this->getAuthenticateHeader();
        $queryParams = [
            'param1' => $gstin,
            'email'  => $this->settings['email']
        ];
        $url = $this->createUrl( $this->settings['resource']['gst_details'] );
        $method = "GET";
        list($output, $responseCode, $responseHeader) = Yii::$app->curl->callApi( $url, $method, $headerParams, $queryParams );
        echo "<pre>";var_dump( $output );die();
    }

    public function generateInvoice($id){
        if( empty( $id ) ){
            return ['status'=>false,'message'=>'unauthorized access'];
        }
        try{
            $agreementBill = AgreementBill::findOne($id);
            $formatter = Yii::$app->formatter;
            $data = $agreementBill->getIrnData();
            $validate = Yii::$app->einvoice_validation;
            $validateOutput = $validate->validateField( $data, false );
            if( !$validateOutput['status'] ){
                return ['status'=>false,'message'=>json_encode($validateOutput['data']),'data'=>$data];
            }
            $url = $this->createUrl( $this->settings['resource']['generate_irn'] );
            $method = "POST";
            $headerParams = array_merge( $this->jsonHeader(), $this->getAuthenticateHeader() );
            //$headerParams =  $this->getAuthenticateHeader();
            $queryParams = [
                'email'  => $this->settings['email']
            ];
            //echo "<pre>";var_dump( $headerParams );die();
            //$yourData = json_encode( $data );
            //echo "<pre>";print_r( $yourData );die();
            list($response,$responseStatus,$responseHeader) = Yii::$app->curl->callApi( $url, $method, $headerParams, $queryParams, $data );
            //echo "<pre>";print_r( $response );die();
            if( isset( $response->status_cd ) && $response->status_cd == 0 ){
                $message = isset( $response->status_desc )?$response->status_desc:$response->error->message;
                return ['status'=>false,'message'=> $message,'data'=>$data];
            }else{
                $einvoice = new Einvoice;
                $irn = $response->data;
                $einvoice->DocNo = $agreementBill->session . "/" . $agreementBill->invoice_no;
                $einvoice->DocDt = $formatter->asDate($agreementBill->invoice_date,"php:Y-m-d");
                $einvoice->AckNo = strval( $irn->AckNo );
                $einvoice->AckDt = $irn->AckDt;
                $einvoice->Irn = $irn->Irn;
                $einvoice->SignedInvoice = $irn->SignedInvoice;
                $einvoice->SignedQRCode = $irn->SignedQRCode;
                $einvoice->Status = $irn->Status;
                $einvoice->EwbNo = $irn->EwbNo;
                $einvoice->EwbDt = $irn->EwbDt;
                $einvoice->EwbValidTill = $irn->EwbValidTill;
                $einvoice->Remarks = $irn->Remarks;
                if( $einvoice->save() ){
                    $agreementBill->has_sync = 2;
                    $agreementBill->irn_no = $irn->Irn;
                    if( $agreementBill->save() ){
                        return ['status'=>true,'message'=> "Irn generated successfully. IRN no is ".$irn->Irn];
                    }
                    else{
                        return ['status'=>true,'message'=> "Irn generated successfully please re-sync again. IRN no is ".$irn->Irn];
                    }
                }else{
                    return ['status'=>true,'message'=> "Irn generated successfully but not saved please sync again."];
                }
            }
        }catch(\Exception $e){
            return ['status'=>false,'message'=>$e->getMessage()." File: ".$e->getFile()." Line no: ".$e->getLine()];
        }
    }

    public function syncByDoc( $id ){
        if( empty( $id ) ){
            return ['status'=>false,'message'=>'unauthorized access'];
        }
        try{
            $formatter = \Yii::$app->formatter;
            $agreementBill = AgreementBill::findOne( $id );
            $url = $this->createUrl( $this->settings['resource']['irn_details_by_doc'] );
            $method = "GET";
            $headerParams = array_merge( [
                                        'docnum'  => $agreementBill->invoiceNo,
                                        'docdate' => $formatter->asDate($agreementBill->invoice_date,'php:d/m/Y')
                                    ],
                                    $this->getAuthenticateHeader()
                                );
            $queryParams = [
                'email'   => $this->settings['email'],
                'param1'  => 'INV'
            ];
            list($response,$responseStatus,$responseHeader) = Yii::$app->curl->callApi( $url, $method, $headerParams, $queryParams );
            if( isset( $response->status_cd ) && $response->status_cd == 0 ){
                $message = isset( $response->status_desc )?$response->status_desc:$response->error->message;
                return ['status'=>false,'message'=> $message,'data'=>array_merge( $queryParams, $headerParams) ];
            }else{
                $einvoice = Einvoice::find()->where(['DocNo'=>$agreementBill->invoiceNo,'DocDt'=>$agreementBill->invoice_date])->one();
                if( empty($einvoice) ){
                    $einvoice = new Einvoice;
                }
                $irn = $response->data;
                $einvoice->DocNo = $agreementBill->session . "/" . $agreementBill->invoice_no;
                $einvoice->DocDt = $formatter->asDate($agreementBill->invoice_date,"php:Y-m-d");
                $einvoice->AckNo = strval( $irn->AckNo );
                $einvoice->AckDt = $irn->AckDt;
                $einvoice->Irn = $irn->Irn;
                $einvoice->SignedInvoice = $irn->SignedInvoice;
                $einvoice->SignedQRCode = $irn->SignedQRCode;
                $einvoice->Status = $irn->Status;
                $einvoice->EwbNo = $irn->EwbNo;
                $einvoice->EwbDt = $irn->EwbDt;
                $einvoice->EwbValidTill = $irn->EwbValidTill;
                $einvoice->Remarks = $irn->Remarks;
                if( $einvoice->save() ){
                    $agreementBill->has_sync = 2;
                    $agreementBill->irn_no = $irn->Irn;
                    if( $agreementBill->save() ){
                        return ['status'=>true,'message'=> "Irn generated successfully. IRN no is ".$irn->Irn];
                    }
                    else{
                        return ['status'=>true,'message'=> "Irn generated successfully please re-sync again. IRN no is ".$irn->Irn];
                    }
                    return ['status'=>true,'message'=> "Irn generated successfully but not saved please sync again."];
                }else{
                }
            } 
        }catch(\Exception $e){
           return ['status'=>false,'message'=>$e->getMessage." File: ".$e->getFile()." Line no: ".$e->getLine()];
        }
    }

    public function getIrnDetailsByDoc( $id ){
        if( empty( $id ) ){
            return ['status'=>false,'message'=>'unauthorized access'];
        }
        try{
            $formatter = \Yii::$app->formatter;
            $agreementBill = AgreementBill::findOne($id);
            $url = $this->createUrl( $this->settings['resource']['irn_details_by_doc'] );
            $method = "GET";
            $headerParams = array_merge( [
                                            'docnum'  => $agreementBill->invoiceNo,
                                            'docdate' => $formatter->asDate($agreementBill->invoice_date,'php:d/m/Y')
                                        ],
                                        $this->getAuthenticateHeader()
                                    );
            $queryParams = [
                'email'   => $this->settings['email'],
                'param1'  => 'INV'
            ];
            list($response,$responseStatus,$responseHeader) = Yii::$app->curl->callApi( $url, $method, $headerParams, $queryParams );
            if( isset( $response->status_cd ) && $response->status_cd == 0 ){
                $message = isset( $response->status_desc )?$response->status_desc:$response->error->message;
                return ['status'=>false,'message'=> $message,'data'=>array_merge( $queryParams, $headerParams) ];
            }else{
                return ['status'=>true,'data'=>$response];
            }
        }catch(\Exception $e){
           return ['status'=>false,'message'=>$e->getMessage." File: ".$e->getFile()." Line no: ".$e->getLine()];
        }
    }
    
    public function getIrnDetailsByIrn( $irn ){
        if( empty( $irn ) ){
            return ['status'=>false,'message'=>'unauthorized access'];
        }
        try{
            $formatter = \Yii::$app->formatter;
            $agreementBill = AgreementBill::find()->where(['irn_no'=>$irn])->one();
            $url = $this->createUrl( $this->settings['resource']['irn_details_by_irn'] );
            $method = "GET";
            //$headerParams = array_merge( $this->jsonHeader(), $this->getAuthenticateHeader() );
            $headerParams = $this->getAuthenticateHeader();
            $queryParams = [
                'email'   => $this->settings['email'],
                'param1'  => $irn
            ];
            list($response,$responseStatus,$responseHeader) = Yii::$app->curl->callApi( $url, $method, $headerParams, $queryParams );
            if( isset( $response->status_cd ) && $response->status_cd == 0 ){
                $message = isset( $response->status_desc )?$response->status_desc:$response->error->message;
                return ['status'=>false,'message'=> $message,'data'=>array_merge( $queryParams, $headerParams) ];
            }else{
                return ['status'=>true,'data'=>$response];
            }
        }catch(\Exception $e){
           return ['status'=>false,'message'=>$e->getMessage." File: ".$e->getFile()." Line no: ".$e->getLine()];
        }
    }
    public function cancelIrn( $irn, $cancel_reason, $cancel_remarks ){
        if( empty( $irn ) ){
            return ['status'=>false,'message'=>'unauthorized access'];
        }
        try{
            $formatter = \Yii::$app->formatter;
            $agreementBill = AgreementBill::find()->where(['irn_no'=>$irn])->one();
            $url = $this->createUrl( $this->settings['resource']['irn_cancel'] );
            $method = "POST";
            $headerParams = array_merge( $this->jsonHeader(), $this->getAuthenticateHeader() );
            //$headerParams = $this->getAuthenticateHeader();
            $queryParams = [
                'email'   => $this->settings['email']
            ];
            //IRN can be cancelled within 24 hours of IRN generation.
            $data = [
                'Irn'    => $irn,
                'CnlRsn' => strval($cancel_reason),//"Cancel Reason 1- Duplicate, 2 - Data entry mistake, 3- Order Cancelled, 4 - Others"
                'CnlRem' => strval( $cancel_remarks)//"Cancel Remarks" maxlength 100
            ];
            list($response,$responseStatus,$responseHeader) = Yii::$app->curl->callApi( $url, $method, $headerParams, $queryParams,$data );
            //var_dump( $response, $responseStatus, $responseHeader );die();
            if( isset( $response->status_cd ) && $response->status_cd == 0 ){
                $message = isset( $response->status_desc )?$response->status_desc:$response->error->message;
                return ['status'=>false,'message'=> $message,'data'=>array_merge( $queryParams, $headerParams) ];
            }else{
                $agreementBill->cancel_date = $response->data->CancelDate;
                $agreementBill->cancel_reason = $cancel_reason;
                $agreementBill->cancel_remarks = $cancel_remarks;
                if( $agreementBill->save() ){
                    return ['status'=>true,'message'=>'Irn has been cancel successfully.'];
                }else{
                    return ['status'=>true,'message'=>'Irn has been cancel successfully but data not saved.','data'=>$response];
                }
            }
        }catch(\Exception $e){
           return ['status'=>false,'message'=>$e->getMessage." File: ".$e->getFile()." Line no: ".$e->getLine()];
        }
    }
}