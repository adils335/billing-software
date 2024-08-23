<?php

namespace app\models;

use Yii;
use \app\models\base\StoreConsumed as BaseStoreConsumed;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "store_consumed".
 */
class StoreConsumed extends BaseStoreConsumed
{
    public function getConsumedProductQuantity( $product_id ){
        $item = StoreConsumedItems::find()->Where(['store_consumed_id'=>$this->id,'store_products_id'=>$product_id])->one();
        if( !empty( $item ) ){
            return $item->quantity;
        }
        // echo"<pre>";print_r($query);
        return 0 ;
    }
    const STATUS_ACTIVE = 1;
	const STATUS_DEACTIVE = 2;
	const STATUS_DELETE = 3;

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
    public function getInvoiceNo(){
        $invoice_no = '';
        $last_id = StoreConsumed::find()->max('id');
        $id = $this->company_id;
        $session = $this->session;
        $last_id++;
        $this->invoice_no = $id."/".$session."/".$last_id;
    }
}
