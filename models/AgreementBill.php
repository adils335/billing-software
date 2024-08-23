<?php

namespace app\models;

use Yii;
use \app\models\base\AgreementBill as BaseAgreementBill;
use \app\models\AgreementBillStatus;
use \app\models\Einvoice;
use \app\models\Common;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

/**
 * This is the model class for table "agreement_bill".
 */
class AgreementBill extends BaseAgreementBill
{
    const STATUS_ACTIVE = 1;
    const STATUS_DELETE = 2;
    const STATUS_ARCHIVE = 3;
    const STATUS_PERMANENT_DELETE = 4;
    const STATUS_CREDIT_NOTE = 5;

    const CANCEL_DUPLICATE = 1;
    const CANCEL_ENTRY_MISTAKE = 2;
    const CANCEL_ORDER = 3;
    const CANCEL_OTHERS = 4;

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
    
	public function changeStatus( $id, $lastStatus, $status ){
		$agreementBillStatus = new AgreementBillStatus;
		$agreementBillStatus->bill_id = $id;
		$agreementBillStatus->last_status = $lastStatus;
		$agreementBillStatus->status = $status;
		if ( $agreementBillStatus->save() ) {
			return $agreementBillStatus->id;
		}else{
			return $agreementBillStatus->getErrors();
		}
	}

    public static function buildCancelReason(){
        return [
           Self::CANCEL_DUPLICATE => 'Duplicate',
           Self::CANCEL_ENTRY_MISTAKE => 'Data Entry Mistake',
           Self::CANCEL_ORDER => 'Order',
           Self::CANCEL_OTHERS => 'Others',    
        ];
    }

	public function getCancelLabel(){
	    if( !empty( Self::buildCancelReason()[$this->cancel_reason] ) ){
	        return Self::buildCancelReason()[$this->cancel_reason];
	    }
	}

    public static function buildStatus(){
        return [
           Self::STATUS_ACTIVE => 'Active',
           Self::STATUS_CREDIT_NOTE => 'Credit Note',
           Self::STATUS_DELETE => 'Cancel',
           //Self::STATUS_ARCHIVE => 'Archive',
           /*Self::STATUS_PERMANENT_DELETE => 'Delete', */   
        ];
    }
	
	public function getStatus(){
	    if( !empty( Self::buildStatus()[$this->status] ) ){
	        return Self::buildStatus()[$this->status];
	    }
	}
    
	public function getStatusLabel(){
	    if( !empty( Self::buildStatus()[$this->status] ) ){
	        return Self::buildStatus()[$this->status];
	    }
	}
	
	public function invoiceNo(){
		
		$invoice = Self::find()->select(['MAX(invoice_no) as invoice_no'])->where(['company_id'=>$this->company_id,'session'=>$this->session])->andWhere(['NOT IN', 'status', [Self::STATUS_ARCHIVE,Self::STATUS_PERMANENT_DELETE]])->one();
		if(empty($invoice)){
		    if($this->company_id == 3)
		      return 37;
		    else return 1 ;   
		}
		else return $invoice->invoice_no + 1;
		
	}
	
	public function getInvoiceNo(){
	    return $this->session."/".$this->invoice_no;
	}
	
    public function getBillingCompanyDistricts(){
        if(!$this->billing_company_state)
        {
         return [];
        }
        $district = \app\models\BillingCompanyGst::find()->where(['state_id'=>$this->billing_company_state])->one();
        $districts = json_decode($district->districts);
        $array = [];
        if($districts)
        foreach ($districts as $value) {
            $getDistrict = \app\models\District::find()->where(['id'=>$value])->one();
            $array[$value] = $getDistrict->district;
        }

        return $array;
    }

	public function createPdf(){
		
		$tmp_path = Yii::getAlias('@webroot/quotation-bill/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/agreement-bill/bill-pdf", [
            'model' => $this,
        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/agreement-bill/pdf-footer',[
            'model' => $this,
        ]);
		
		$filename = $this->company_id."-".$this->session."-".$this->invoice_no.".pdf";
        $pdf = new \kartik\mpdf\Pdf([
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetHeader(Yii::t('app', 'Invoice No').': '.$this->session."/".sprintf("%02d",$this->invoice_no)); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'F'); 
		
	}
	
	public function viewPdf(){
		
		$tmp_path = Yii::getAlias('@webroot/quotation-bill/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/agreement-bill/bill-pdf", [
            'model' => $this,
        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/agreement-bill/pdf-footer',[
            'model' => $this,
        ]);
		
		$filename = $this->company_id."-".$this->session."-".$this->invoice_no.".pdf";
        $pdf = new \kartik\mpdf\Pdf([
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetHeader(Yii::t('app', 'Invoice No').': '.$this->session."/".sprintf("%02d",$this->invoice_no)); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
		
	}
	
	public function createGeneralPdf(){
		
		$tmp_path = Yii::getAlias('@webroot/agreement bill/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/general-bill/bill-pdf", [
                                            'model' => $this,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/general-bill/pdf-footer',[
            'model' => $this,
        ]);
		
		$filename = $this->company_id."-".$this->session."-".$this->invoice_no.".pdf";
        $pdf = new \kartik\mpdf\Pdf([
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->allow_charset_conversion = true;
        $mpdf->SetHeader(Yii::t('app', 'Invoice No').': '.$this->session."/".sprintf("%02d",$this->invoice_no)); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'F'); 
		
	}
	
	public function lastBill($company = null){
	    $session = \app\models\Session::getCurrentSession();
	    $condition[] = 'AND';
	    $condition[] = ['session'=>$session];
	    if( $company ){
	         $condition[] = ['company_id'=>$company];
	    }
	    if($this->invoice_no)
	        $condition[] = ['<','invoice_no',$this->invoice_no];
	    $lastBill = Self::find()->select(['invoice_no','invoice_date','session'])->where($condition)->andWhere(['!=','status',Self::STATUS_DELETE])->orderBy(['invoice_no'=>SORT_DESC])->one();
	    //echo $lastBill->createCommand()->getRawSql();die();
	    return $lastBill;
	}
	
	public function getLastBillAmount(){
	    if( !$this->related_invoice ){
	        return 0;
	    }
	    return Self::find()->where(['id'=>json_decode($this->related_invoice,true)])->sum('taxable_amount');
	}
	
	public function getLastBillInvoiceNo(){
	    if( !$this->related_invoice ){
	        return 0;
	    }
	    $model = Self::find()->select(['GROUP_CONCAT(invoice_no) AS invoice_no'])->where(['id'=>json_decode($this->related_invoice,true)])->one();
	    return $model->invoice_no;
	}
	
	public static function generatePdf($model){
	    
	    $filenames = array();
	    foreach($model as $filename){
           //Multiple pdf store in Mfiles array with full path.               
           array_push($filenames,\Yii::getAlias("@webroot/agreement bill/").$filename->company_id."-".$filename->session."-".$filename->invoice_no.'.pdf');
	    }
                           
        $outFile = Yii::getAlias("@webroot/agreement bill/").'filter.pdf';

        $pdf = new \Jurosh\PDFMerge\PDFMerger;
        if ($filenames) {
            foreach( $filenames as $file ){
                $pdf->addPDF($file, 'all', 'vertical');
            }
            $pdf->merge('file', $outFile);
        }
	    
	}
	
	public function billTax($tax_id){
	    return \app\models\BillTax::find()->where(['tax_id'=>$tax_id,'invoice_id'=>$this->id])->one();
	}
	
	public static function generateGstReportPdf($model,$from_month,$to_month,$gsts){
		$formatter = Yii::$app->formatter;
		if($from_month == $to_month){
		    $month = $formatter->asDate("01-".$from_month,'php:M-Y');
		}else{
		    $month = $formatter->asDate("01-".$from_month,'php:M-Y')." to ".$formatter->asDate("01-".$to_month,'php:M-Y');
		}
		$tmp_path = Yii::getAlias('@webroot/agreement-bill/'); 
		$content = Yii::$app->controller->renderPartial("@app/views/agreement-bill/gst-report-pdf", [
                                            'model' => $model,
                                            'gsts' => $gsts,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/agreement-bill/pdf-footer',[
            'model' => $model,
        ]);
		
		$filename = "gst-report.pdf";
        $pdf = new \kartik\mpdf\Pdf([
        'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->SetHeader(Yii::t('app', 'Month').': '.$month); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
		
	}
	
	public static function generateGstPdf($type,$searchModel, $model, $taxes,$deduction = null){
		$month = $searchModel->from_month;
		$company = \app\models\Company::findOne($searchModel->company_id);
		$tmp_path = Yii::getAlias('@webroot/agreement bill/'); 
		$pdf_file = 'gst-paid';
		$report_title = Yii::t('app', 'Paid GST For Month');
		switch($type){
		    case 'paid':
		        $pdf_file = 'gst-paid';
		        $report_title =  'Paid GST of '.$company->name.' For Month: '.$month;
		        break;
		    case 'purchase':
		        $pdf_file = 'gst-purchase';
		        $report_title = 'Purchase GST of '. $company->name .' For Month: '.$month;
		        break;
		    case 'bill':
		        $pdf_file = 'gst-bill';
		        $report_title = 'Bill GST of '. $company->name .' For Month: '.$month;
		        break;
			case 'hsnno':
		        $pdf_file = 'gst-hsnno';
		        $report_title = 'HSN wise GST of '. $company->name .' For Month: '.$month;
		        break;
		}
		$content = Yii::$app->controller->renderPartial("@app/views/agreement-bill/gst-report-pdf/".$pdf_file, [
                                            'model' => $model,
                                            'taxes' => $taxes,
                                            'deduction' => $deduction,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/agreement-bill/pdf-footer',[
            'model' => $model,
        ]);
		
		$filename = Yii::$app->user->identity->id."-gst-paid.pdf";
        $pdf = new Pdf([
           'format' => Pdf::FORMAT_A4,
           'orientation' => Pdf::ORIENT_LANDSCAPE,
           'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->SetHeader($report_title); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content);
		$mpdf->Output($tmp_path . $filename, 'F');
		return Yii::getAlias('@web/agreement bill/').$filename;
	}
	
	public static function generateGstPayablePdf($month, $bills, $taxes, $purchaseBills, $purchase_taxes){
		$tmp_path = Yii::getAlias('@webroot/agreement-bill/'); 
		$pdf_file = 'gst-payable';
		$report_title = Yii::t('app', 'Payable GST For Month'). ": ".$month;
		$content = Yii::$app->controller->renderPartial("@app/views/agreement-bill/gst-report-pdf/".$pdf_file, [
                                            'bills' => $bills,
                                            'taxes' => $taxes,
                                            'purchaseBills' => $purchaseBills,
                                            'purchase_taxes' => $purchase_taxes,
                                        ]);
		//echo $content;die();								
		$footer = Yii::$app->controller->renderPartial('@app/views/agreement-bill/pdf-footer',[
            'model' => $model,
        ]);
		
		$filename = "gst-payable.pdf";
        $pdf = new Pdf([
           'format' => Pdf::FORMAT_A4,
           'orientation' => Pdf::ORIENT_LANDSCAPE,
           'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
        ]); 
        $mpdf = $pdf->api; 
        $mpdf->SetHeader($report_title); 
        $mpdf->setAutoBottomMargin ='stretch';
        $mpdf->SetHTMLFooter($footer); 
        $mpdf->WriteHtml($content); 
        $mpdf->Output($tmp_path.$filename,'I'); 
		
	}
	
	public function getTaxRate(){
	    return \app\models\BillTax::find()->where(['invoice_id'=>$this->id])->sum('rate');
	}
	
	public function getTaxAmountById($tax_id){
	    $taxAmount = \app\models\BillTax::find()->select(['amount'])->where(['invoice_id'=>$this->id,'tax_id'=>$tax_id])->one();
	    if( !empty( $taxAmount ) ){
	        return $taxAmount->amount;
	    }
	    return  0;
	}
	public function getDeductionAmountById($tax_id){
	    $taxAmount = \app\models\BillDeduction::find()->select(['amount'])->where(['invoice_id'=>$this->id,'tax_id'=>$tax_id])->one();
	    if( !empty( $taxAmount ) ){
	        return $taxAmount->amount;
	    }
	    return  0;
	}

	public function isGeneratedIrnNo(){
        return Einvoice::find()->where(['DocNo'=>$this->getInvoiceNo(),'DocDt'=>$this->invoice_date])->exists();
	}

	public function getIrnData(){
		$formatter = \Yii::$app->formatter;
        $common = new Common;
        $agreementBill = $this;
        $company = $agreementBill->company;
        $agreement = $agreementBill->agreement;
        $companyStdcode = $agreement->state->state_tin;
        $contractCompany = $agreementBill->agreement->contractCompany;
        $contractCompanyStdcode = $agreement->contractCompanyState->state_tin;
        $billingCompany = $agreementBill->billingCompany;
        $companyAddress = Common::getCompanyAddresses( $agreement->company_id, $agreement->state_id,1, $agreement->district_id );
        $contractCompanyAddress = Common::getCompanyAddresses($agreement->contract_company_id, $agreement->contract_company_state,2, $agreement->contract_company_district );
        $billingCompanyAddress = Common::getCompanyAddresses( $agreementBill->billing_company_id,$agreementBill->billing_company_state,3, $agreementBill->billing_company_district );
        $billingStdcode = $agreementBill->billingCompanyState->state_tin;
        $billItems = $agreementBill->billItems;
        $data['Version'] = '1.1';//$this->settings['version'];
        $data['TranDtls'] = [
            "TaxSch"=> "GST",
            "SupTyp"=> "B2B",
            "RegRev"=> "Y",
            "EcmGstin"=> null,
            "IgstOnIntra"=> "N"
        ];
        $data['DocDtls'] = [
            "Typ"=> "INV",
            "No"=> $agreementBill->session . "/" . $agreementBill->invoice_no,
            "Dt"=> $formatter->asDate($agreementBill->invoice_date,'php:d/m/Y')
        ];
        $data['SellerDtls'] = [
            "Gstin"=> $companyAddress['gst_no'],
            "LglNm"=> $companyAddress['legal_name'],
            "TrdNm"=> $companyAddress['trade_name'],
            "Addr1"=> $companyAddress['address_1'],
            "Addr2"=> $companyAddress['address_2'],
            "Loc"=>  $companyAddress['location'],
            "Pin"=> $companyAddress['pincode'],
            "Stcd"=> strval( $companyAddress['state_tin'] ),
            "Ph"=> strval( $companyAddress['phone'] ),
            "Em"=> strval( $companyAddress['email'] )
        ];
        $data['BuyerDtls'] = [
            "Gstin"=> $contractCompanyAddress['gst_no'],
            "LglNm"=> $contractCompanyAddress['legal_name'],
            "TrdNm"=> $contractCompanyAddress['trade_name'],
            "Pos"=> strval( $billingCompanyAddress['state_tin'] ),
            "Addr1"=> $contractCompanyAddress['address_1'],
            "Addr2"=> $contractCompanyAddress['address_2'],
            "Loc"=>  $contractCompanyAddress['location'],
            "Pin"=> $contractCompanyAddress['pincode'],
            "Stcd"=> strval( $contractCompanyAddress['state_tin'] ),
            "Ph"=> strval( $contractCompanyAddress['phone'] ),
            "Em"=> strval( $contractCompanyAddress['email'] )
        ];
        $data['ShipDtls'] = [
            "Gstin"=> $billingCompanyAddress['gst_no'],
            "LglNm"=> $billingCompanyAddress['legal_name'],
            "TrdNm"=> $billingCompanyAddress['trade_name'],
            "Addr1"=> $billingCompanyAddress['address_1'],
            "Addr2"=> $billingCompanyAddress['address_2'],
            "Loc"=>  $billingCompanyAddress['location'],
            "Pin"=> $billingCompanyAddress['pincode'],
            "Stcd"=> strval( $billingCompanyAddress['state_tin'] )
        ];
        $data['ItemList'] = [];
        $counter = 1;
        $totalAmount = 0;$total_gst = 0;$total_cgst = 0;$total_sgst = 0;$total_igst = 0;$gst_rate = 0;
        $taxes = [];
        $taxes = ['CGST' => ['rate'=>0,'amount'=>0],'SGST' => ['rate'=>0,'amount'=>0],'IGST' => ['rate'=>0,'amount'=>0]];
        foreach( $agreementBill->billTaxes as $tax ){
            $gst_rate += $tax->rate;
            $taxes[$tax->tax->name]['rate'] = $tax->rate;
            $taxes[$tax->tax->name]['amount'] += $tax->amount;
        }
        foreach( $billItems as $billItem ){
            $itemDetail = $billItem->itemName;
            $gst = 0;
            $igst = 0;
            $cgst = 0;
            $sgst = 0;
            if( $billingCompanyAddress['state_tin'] == $contractCompanyAddress['state_tin'] ){
                $cgst = $billItem->amount * $taxes['CGST']['rate']/100;
                $sgst = $billItem->amount * $taxes['SGST']['rate']/100;
                $gst = $cgst + $sgst;
            }else{
                $gst = $igst = $billItem->amount * $taxes['IGST']['rate']/100;
            }
            $total_gst += $gst;
            $total_cgst += $cgst;
            $total_sgst += $sgst;
            $total_igst += $igst;
            $totalAmount += $billItem->amount;
            $item = [
                "SlNo" => strval( $counter ),
                "PrdDesc"=> $itemDetail->item,
                "IsServc"=> "Y",
                "HsnCd"=> $billItem->hsn_no,
                //"Barcde"=> "$billItem->item",
                "Qty"=> $billItem->quantity,
                //"FreeQty"=> "0",
                "Unit"=> $billItem->unitName->name,
                "UnitPrice"=> $billItem->rate,
                "TotAmt"=> $billItem->amount,
                //"Discount"=> 0,
                //"PreTaxVal"=> $billItem->amount,
                "AssAmt"=> $billItem->amount,
                "GstRt"=> $gst_rate,
                "IgstAmt"=> round( $igst, 3),
                "CgstAmt"=> round( $cgst,3),
                "SgstAmt"=> round( $sgst,3),
                "CesRt"=> 0,
                "CesAmt"=> 0,
                "CesNonAdvlAmt"=> 0,
                "StateCesRt"=> 0,
                "StateCesAmt"=> 0,
                "StateCesNonAdvlAmt"=> 0,
                "OthChrg"=> 0,
                "TotItemVal"=> $billItem->amount + $gst,
                "OrdLineRef"=> "$counter",
                "OrgCntry"=> "IN",
            ];  
            $counter++;
            $data['ItemList'][] = $item;
        }
        $data['ValDtls']['AssVal'] = $totalAmount;
        $data['ValDtls']['TotInvVal'] = $totalAmount + $total_gst;
        if( !empty( $taxes['IGST']['rate'] ) ){
            $data['ValDtls']['IgstVal'] = round($taxes['IGST']['amount'],3);
        }else{
            $data['ValDtls']['CgstVal'] = round($taxes['CGST']['amount'],3);
            $data['ValDtls']['SgstVal'] = round($taxes['SGST']['amount'],3);
        }
		return $data;
	}
}
