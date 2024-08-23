<?php

namespace app\models;

use Yii;
use \app\models\base\Permission as BasePermission;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "permission".
 */
class Permission extends BasePermission
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
    
}
