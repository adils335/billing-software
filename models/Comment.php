<?php

namespace app\models;

use Yii;
use \app\models\base\Comment as BaseComment;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "comment".
 */
class Comment extends BaseComment
{

    const TYPE_EMPLOYEE = 1;
	const TYPE_VENDOR = 2;
	const TYPE_WORKER = 3;
	const TYPE_WORKER_VENDOR = 4;
	const TYPE_SITE_DUES = 5;
	const TYPE_COMPANY_DUES = 6;
	
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
				self::TYPE_EMPLOYEE =>'Employee',
				self::TYPE_VENDOR	=>'Vendor',
				self::TYPE_WORKER	=>'Wokrer',
				self::TYPE_WORKER_VENDOR =>'Worker Vendor',
				self::TYPE_SITE_DUES	=>'Site Dues',
				self::TYPE_COMPANY_DUES	=>'Company Dues',
		];
	}

	public  function getTypeLabel(){
		
			if(isset(self::buildType()[$this->type])){
				return self::buildType()[$this->type];
			}
		
	}
    
}
