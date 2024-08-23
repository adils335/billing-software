<?php

namespace app\models;

use Yii;
use \app\models\base\CompanyAddresses as BaseCompanyAddresses;
use yii\helpers\ArrayHelper;
use app\models\Model;

/**
 * This is the model class for table "company_addresses".
 */
class CompanyAddresses extends BaseCompanyAddresses
{

    const TYPE_COMPANY = 1;
    const TYPE_CONTRACT_COMPANY = 2;
    const TYPE_BILLING_COMPANY = 3;


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

    public static function buildType(){
        return [
            self::TYPE_COMPANY =>'Company ',
            self::TYPE_CONTRACT_COMPANY	=>'Contract Company',
            self::TYPE_BILLING_COMPANY	=>'Biling Company',
        ];
    }

    public  function getTypeLabel(){
            if(isset(self::buildType()[$this->type_id])){
                return self::buildType()[$this->type_id];
            }
    }

    public static function getAddresses( $type, $type_id ){
        return self::find()->where(['type'=>$type,'type_id'=>$type_id])->all();
    }

    public static function saveAddress( $modelsAddress, $data, $model ){
        $oldIDs = ArrayHelper::map($modelsAddress, 'id', 'id');
        $modelsAddress = Model::createMultiple(self::classname(), $modelsAddress);
        Model::loadMultiple($modelsAddress, $data );
        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsAddress, 'id', 'id')));
        foreach ($modelsAddress as $modelAddress) {
            if (! empty($deletedIDs)) {
                self::deleteAll(['id' => $deletedIDs]);
            }
            $modelAddress-> type_id = $model->id;
            if (! ($flag = $modelAddress->save())) {
                return ['models'=>$modelsAddress,'model'=>$modelAddress];
            }
        }
        return true;
    }

}
