<?php

namespace app\models;

use Yii;
use \app\models\base\VendorBill as BaseVendorBill;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "vendor_bill".
 */
class VendorBill extends BaseVendorBill
{

	const STATUS_ACTIVE = 1;
	const STATUS_DELETE = 2;
	
	const SCHEDULE_ABOVE = 1;
	const SCHEDULE_BELOW = 2;
	
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
	
	public static function buildSchedule(){
			return [
				self::SCHEDULE_ABOVE	=> 'Penalty',
				self::SCHEDULE_BELOW	=> 'Discount',
		];
	}
	public  function getScheduleLabel(){
		
			if(isset(self::buildSchedule()[$this->schedule])){
				return self::buildSchedule()[$this->schedule];
			}
		
	}
	
	public function billById($id){
		
		return Self::findOne($id);
		
	}
	
	public function BillNo(){
		
		if($this->bill_no){
		    return $this->bill_no;
		}
		
		$invoice = Self::find()->select(['MAX(bill_no) as bill_no'])->where(['company_id'=>$this->company_id,'session'=>$this->session])->one();
		if(empty($invoice))
			return 1;
		else return $invoice->bill_no + 1;
		
	}
	
	public function createPdf(){
		
		$tmp_path = Yii::getAlias('@webroot/vendor bill/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/vendor-bill/bill-pdf", [
                                            'model' => $this,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/vendor-bill/pdf-footer');
		
		$filename = $this->company_id."-".$this->session."-".$this->bill_no.".pdf";
        $pdf = new \kartik\mpdf\Pdf; 
        $mpdf = $pdf->api; 
        $mpdf->SetHeader(Yii::t('app', 'Document No').': '.$this->session."/".sprintf("%02d",$this->bill_no)); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'F'); 
		
	}

	public function getLastBills(){
		$output = [];
		$companies = Self::find()->select(['company_id'])->groupBy(['company_id'])->all() ;
		foreach( $companies as $company ){
			$session = !empty( $this->session ) ? $this->session : (new \app\models\Session)->currentSession;
			$model = Self::find()->select(['DATE_FORMAT(bill_date,"%d-%m-%Y") as bill_date','bill_no'])
			                    ->where(['company_id'=>$company->company_id,'session'=>$session])
								->andFilterWhere(['!=','id',$this->id])
								->andFilterWhere(['company_id'=>$this->company_id])
			                    ->orderBy(['id'=>SORT_DESC])->one();
			if( !empty( $model ) )					
            $output[$company->company_id] = ['bill_no'=>$model->bill_no,'bill_date'=>$model->bill_date];
		}
		return json_encode($output);
	}
	public function getTaxAmountById($tax_id){
	    $taxAmount = \app\models\VendorBillTax::find()->select(['amount'])->where(['bill_id'=>$this->id,'tax_id'=>$tax_id])->one();
	    if( !empty( $taxAmount ) ){
	        return $taxAmount->amount;
	    }
	    return  0;
	}
	
}
