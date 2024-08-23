<?php

namespace app\models;

use Yii;
use \app\models\base\StoreIssue as BaseStoreIssue;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * This is the model class for table "store_issue".
 */
class StoreIssue extends BaseStoreIssue
{
    const STATUS_ACTIVE = 1;
	const STATUS_DEACTIVE = 2;
	const STATUS_DELETE = 3;

    public function getIssueProductQuantity( $product_id ){
        $item = StoreIssueItems::find()->Where(['store_issue_id'=>$this->id,'store_products_id'=>$product_id])->one();
        if( !empty( $item ) ){
            return $item->quantity;
        }
        // echo"<pre>";print_r($query);
        return 0 ;
    }

    public function getIssueGatePassNo( $product_id ){
        $item = StoreIssueItems::find()->Where(['store_issue_id'=>$this->id,'store_products_id'=>$product_id])->one();
        if( !empty( $item ) ){
            return $item->gate_pass_no;
        }
        // echo"<pre>";print_r($query);
        return 0 ;
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
    public static function buildStatus(){
        return [
            self::STATUS_DEACTIVE =>'Deactive',
            self::STATUS_ACTIVE	=>'Active',
            self::STATUS_DELETE	=>'Delete',
        ];
    }

    public function getStatusLabel(){
        if(isset(self::buildStatus()[$this->status])){
            return self::buildStatus()[$this->status];
        }
    }

    public function getIndentNo(){
        $indent_no = '';

        $last_id = StoreIssue::find()->max('id');
        $id = $this->company_id;
        $session = $this->session;
        $last_id++;
        $this->indent_no = $id."/".$session."/".$last_id;
    }

    // public function createPdf(){
		
	// 	$tmp_path = Yii::getAlias('@webroot/store issue/'); 
	// 	$content = Yii::$app->controller->renderPartial("@app/views/store-issue/bill-pdf", [
    //                                         'model' => $this,
    //                                     ]);
	// 	//echo $content;die();								
	// 	$footer = Yii::$app->controller->renderPartial('@app/views/store-issue/pdf-footer',[
    //         'model' => $this,
    //     ]);
		
	// 	$filename = str_replace( "/","-",$this->indent_no ).".pdf";
    //     $pdf = new \kartik\mpdf\Pdf([
    //     'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
    //     ]); 
    //     $mpdf = $pdf->api; 
    //     $mpdf->allow_charset_conversion = true;
    //     $mpdf->SetHeader(Yii::t('app', 'Indent No').':'.$this->indent_no); 
    //     $mpdf->setAutoBottomMargin ='stretch';
    //     $mpdf->SetHTMLFooter($footer); 
    //     $mpdf->WriteHtml($content); 
    //     $mpdf->Output($tmp_path.$filename,'I'); 
		
	// }

    

}
