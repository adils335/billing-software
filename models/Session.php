<?php

namespace app\models;

use Yii;
use \app\models\base\Session as BaseSession;
use yii\helpers\ArrayHelper;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "session".
 */
class Session extends BaseSession
{
    const STATUS_DEACTIVE = 0;
	const STATUS_ACTIVE = 1;
	
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
		];
	}
	public  function getStatusLabel(){
		
			if(isset(self::buildStatus()[$this->status])){
				return self::buildStatus()[$this->status];
			}
		
	}
	public static function getCurrentSession(){
	    $session = "";
	    $model = Self::find()->select(['session'])->where(['status'=>1])->orderBy(['session'=>SORT_DESC])->one();
	    if($model){
	        $session = $model->session;
	    }
		return $session;
	}
}

