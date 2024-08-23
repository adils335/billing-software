<?php

namespace app\models;

use Yii;
use \app\models\base\BillItem as BaseBillItem;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bill_item".
 */
class BillItem extends BaseBillItem
{
    public $remaining_quantity;
    public $item_text;

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
	
	public function saveItem($agreementBill,$data){
		
		$flag = true;
		
		$items = $data['item'];
		$addedItems = [];
		 //echo "<pre>";print_r($items);die();
		foreach($items as $key=>$value){
			
		 $model = Self::find()
		        ->where(['item'=>$value,'invoice_id'=>$agreementBill->id])->one();
		 if(empty($model))
			 $model = new \app\models\BillItem;
		 
		   if($data['quantity'][$key]){
		       $addedItems[] = $value;
			   $loadArray['BillItem'] = array(
			                            'sno' => $data['sno'][$key],  
			                            'item' => $data['item'][$key],  
			                            'hsn_no' => $data['hsn_no'][$key], 
			                            'unit' => $data['unit'][$key], 
			                            'quantity' => $data['quantity'][$key], 
			                            'rate' => $data['rate'][$key], 
			                            'amount' => $data['amount'][$key], 
										'invoice_id' => $agreementBill->id,
										'agreement_id' => $agreementBill->agreement_id,
										'session' => $agreementBill->session,
										'company_id' => $agreementBill->company_id
			                            );
			  		  
			  if($model->load($loadArray) && $model->validate()){
				  $model->save();
				  
			  }else{
				  return false;
			  }							
		   }
          		 
		}
		BillItem::deleteAll(['AND',['invoice_id'=>$agreementBill->id],['NOT IN','item',array_values($addedItems)]]);
		return $flag;
		
	}
	
	public function getItem($model,$item){
		  
		  $itemDetail = Self::find()->where(['session'=>$model->session,'invoice_id'=>$model->id,'agreement_id'=>$model->agreement_id,'item'=>$item])->one();
		  
		  if(!empty($itemDetail))
		       return $itemDetail;
	      else return null;
		  
	}

	public function getTaxAmountByHsnNo( $invoices, $hsn_no, $tax_id ){
		$total_tax = 0;
		$invoices = explode( ",", $invoices );
		foreach ($invoices as $invoice) {
			$invoiceModel = \app\models\AgreementBill::find()->where(['id'=>$invoice])->one();
			$amount = self::find()->where(['invoice_id'=>$invoice,'hsn_no'=>$hsn_no])->sum('amount');
			if ($invoiceModel->schedule == 1 && $invoiceModel->schedule_rate ) {
				$amount += $amount * $invoiceModel->schedule_rate / 100;
			}elseif( $invoiceModel->schedule == 2 && $invoiceModel->schedule_rate ){
				$amount -= $amount * $invoiceModel->schedule_rate / 100;
			}
			$rate = \app\models\BillTax::find()->where(['invoice_id'=>$invoice,'tax_id'=>$tax_id])->one();
			$total_tax += sprintf("%0.2f",$amount * $rate->rate/100);
		}
		return sprintf("%0.2f",$total_tax);
	}

	public function getAmountByHsnNo( $invoices, $hsn_no ){
		$total_amount = 0;
		$invoices = explode( ",", $invoices );
		foreach ($invoices as $invoice) {
			$invoiceModel = \app\models\AgreementBill::find()->where(['id'=>$invoice])->one();
			$amount = self::find()->where(['invoice_id'=>$invoice,'hsn_no'=>$hsn_no])->sum('amount');
			if ($invoiceModel->schedule == 1 && !empty( $invoiceModel->schedule_rate ) ) {
				$amount += $amount * $invoiceModel->schedule_rate / 100;
			}elseif( $invoiceModel->schedule == 2 && !empty( $invoiceModel->schedule_rate ) ){
				$amount -= $amount * $invoiceModel->schedule_rate / 100;
			}
			$total_amount += $amount;
		}
		return sprintf("%0.2f",$total_amount);
	}

	public function getInvoiceNoByInvoiceIds( $invoiceIds ){
		$invoiceIds = explode(",", $invoiceIds);
		return yii\helpers\ArrayHelper::getColumn(\app\models\AgreementBill::find()->select(['invoice_no'])
		              ->where(['id'=>$invoiceIds])
					  ->distinct()
					 ->asArray() 
					  ->all(),'invoice_no');
	}
	
}
