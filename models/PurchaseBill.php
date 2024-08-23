<?php

namespace app\models;

use Yii;
use \app\models\base\PurchaseBill as BasePurchaseBill;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "purchase_bill".
 */
class PurchaseBill extends BasePurchaseBill
{
    

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
    
	public function getTaxAmountById($tax_id){
	    $itemsId = \app\models\PurchaseBillItems::find()->select(['id'])->where(['purchase_bill_id'=>$this->id]);
	    $taxAmount = \app\models\PurchaseBillItemsTax::find()->select(['tax_amount'])->where(['purchase_bill_items_id'=>$itemsId,'tax_id'=>$tax_id])->one();
	    if( !empty( $taxAmount ) ){
	        return $taxAmount->tax_amount;
	    }
	    return  0;
	}
}
