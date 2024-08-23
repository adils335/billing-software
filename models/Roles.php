<?php

namespace app\models;

use Yii;
use \app\models\base\Roles as BaseRoles;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "roles".
 */
class Roles extends BaseRoles
{
    
    const IS_SELF_NO = 0;
    const IS_SELF_YES = 1;
    
    
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
    
    
	public static function buildSelf(){
			return [
				self::IS_SELF_NO =>'No',
				self::IS_SELF_YES	=>'Yes',
		];
	}

	public  function getSelfLabel(){
		
			if(isset(self::buildSelf()[$this->is_self])){
				return self::buildSelf()[$this->is_self];
			}
		
	}
    
}
