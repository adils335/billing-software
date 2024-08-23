<?php 
namespace app\components;
 
use yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Event;
use yii\db\ActiveRecord;

class History extends Component
{   
	
	public function create( $object ){
	    if( empty( $object->id ) ) return; 
		try{
			$history = new \app\models\History;
			$history->model_name = get_class($object);
			$history->model_id = $object->id;
			if( get_class($object) == "app\\models\Payment" ){
               $history->other_id = $object->ref_no;
			}
			$history->action_status = $history::ACTION_STATUS_INSERT;
			$history->meta_label = "New";
			$history->meta = json_encode( $object->attributes );
			$history->seen_status = $history::STATUS_UNSEEN;
			$history->flush_status = $history::STATUS_UNFLUSH;
			$history->save();
		}catch( Exception $e ){
            \Yii::$app->session->setFlash('error',$e->getMessage());
		}
	}

	public function update( $object ){
		$updatedData = $this->updatedAttributes( $object );
		if( !empty( $updatedData ) ){
			try{
				$history = new \app\models\History;
				$history->model_name = get_class($object);
				$history->model_id = $object->id;
				if( get_class($object) == "app\\models\Payment" ){
					$history->other_id = $object->ref_no;
				 }
				$history->action_status = $history::ACTION_STATUS_UPDATE;
				$history->meta_label = property_exists($object,'statusLabel')?$object->statusLabel:NULL;
				$history->meta = $updatedData;
				$history->seen_status = $history::STATUS_UNSEEN;
				$history->flush_status = $history::STATUS_UNFLUSH;
				$history->save();
			}catch( Exception $e ){
				\Yii::$app->session->setFlash('error',$e->getMessage());
			}
		}
	}

	public function delete( $object ){
		try{
			$history = new \app\models\History;
			$history->model_name = get_class($object);
			$history->model_id = $object->id;
			if( get_class($object) == "app\\models\Payment" ){
				$history->other_id = $object->ref_no;
			 }
			$history->action_status = $history::ACTION_STATUS_DELETE;
			$history->meta_label = "Delete";
			$history->meta = json_encode( $object->attributes );
			$history->seen_status = $history::STATUS_UNSEEN;
			$history->flush_status = $history::STATUS_UNFLUSH;
			$history->save();
		}catch( Exception $e ){
            \Yii::$app->session->setFlash('error',$e->getMessage());
		}
	}

	protected function updatedAttributes( $object ){
		//echo "<pre>";print_r($object->_attributes);die();
		$changes = [];
        foreach( $object->attributes as $key => $value ){
			if( isset($object->oldAttributes[$key]) && $value != $object->oldAttributes[$key]){
                $changes[$key] = ['old'=>$object->oldAttributes[$key],'new'=>$value]; 
			}
		}
		$flagArray = $changes;
		unset($flagArray['updated_at']);
		unset($flagArray['updated_by']);
		if( !empty( $flagArray ) ){
            return json_encode( $changes );
		}
		return Null;
	}

	public function record( $model_name, $model_id, $ref_no ){
        if( empty( $model_name ) && (empty( $model_id ) && $ref_no) ){
           return [];
		}
		$model_name = "app\\models\\".$model_name;
		if( $model_id ){
			return \app\models\History::find()->where(['model_name'=>$model_name,'model_id'=>$model_id])->all();
		}elseif($ref_no){
			return \app\models\History::find()->where(['model_name'=>$model_name,'other_id'=>$ref_no])->groupBy(['other_id'])->all();
		}
	}

	public function totalNotification(){
		$notInModels = $this->excludeModels();
		return \app\models\History::find()
		            ->where(['seen_status'=>\app\models\History::STATUS_UNSEEN])
					->andWhere(['NOT',['model_name'=>$notInModels]])
					//->groupBy(['other_id'])
					->count();
	}

	public function getAllNotification(){
		$notInModels = $this->excludeModels();
		return \app\models\History::find()
		            ->where(['seen_status'=>\app\models\History::STATUS_UNSEEN])
					->andWhere(['NOT',['model_name'=>$notInModels]])
					//->groupBy(['other_id'])
					->all();
	}

	private function excludeModels(){
		return ['app\\models\\AgreementGauranty','app\\models\\BillDeduction','app\\models\\BillItem',
		              'app\\models\\BillTax','app\\models\\VendorBillItems','app\\models\\VendorBillTax'];
	}
}

Event::on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT, function ($event) {
	$notInClass = ["app\models\History","app\models\Ledger","app\models\ErpModules","app\models\ErpModulesItems","app\models\ErpModulesAction"];
	if( !in_array(get_class($event->sender), $notInClass) ){
		$history = new \app\components\History;
		$history->create( $event->sender );
	}
});

Event::on(ActiveRecord::class, ActiveRecord::EVENT_BEFORE_UPDATE, function ($event) {
    $notInClass = ["app\models\History","app\models\Ledger","app\models\ErpModules","app\models\ErpModulesItems","app\models\ErpModulesAction"];
	if( !in_array(get_class($event->sender), $notInClass) ){
		$history = new \app\components\History;
		$history->update( $event->sender );
	}
});

Event::on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_DELETE, function ($event) {
    $notInClass = ["app\models\History","app\models\Ledger","app\models\ErpModules","app\models\ErpModulesItems","app\models\ErpModulesAction"];
	if( !in_array(get_class($event->sender), $notInClass) ){
		$history = new \app\components\History;
		$history->delete( $event->sender );
	}
});