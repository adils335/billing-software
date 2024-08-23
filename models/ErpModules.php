<?php

namespace app\models;

use Yii;
use \app\models\base\ErpModules as BaseErpModules;
use yii\helpers\ArrayHelper;
use app\models\Permission;

/**
 * This is the model class for table "erp_modules".
 */
class ErpModules extends BaseErpModules
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
    

    /**
     * {@inheritdoc}
     */
    public static function isEmployee()
    {
        $userId = Yii::$app->user->identity->id;
        $employee = \app\models\Employee::find()->where(['user_id'=>$userId])->one();

        if($employee){
            return 'Ledger[type]=2&Ledger[account]='.$employee->id.'&Ledger[account_type]=1&Ledger[ledger]=1';
        }else{
            return false;
        }
    }

    public function mainModulePermission($module = null){
        
     if(empty($module) || empty(Yii::$app->user->identity->role)){
       return true;
     }
     
     $flag = false;
     $modules['Account'] = ['Employee','Account','Vendor','Worker','WorkerVendor','SiteDues','CompanyDues'];
     $modules['Agreement'] = ['Agreement','Quotation','GeneralBill','ScheduleRate','ScheduleRateMaster'];
     $modules['Setting'] = ['Roles','Session','State','District','Company Type','Designation','Tax','Sites',
                            'Company','ContractCompany','BillingParty','GaurantyType','Uom','Actions'];
     $modules['Verify'] = ['Payment'];                       
     
     $user = Yii::$app->user->identity->id;
     $permissions = Permission::find()->where(['user_id'=>$user,'controller'=>$modules[$module]])->andWhere(['IS NOT','action',Null])->all();
     
     foreach ($permissions as $permission) {
         if($permission->action)
            $flag = true;
     }
       
     return $flag;  

    }

    public function permission($controller = [],$action = null){
       
       if(empty($controller) || empty(Yii::$app->user->identity->role)){
         return true;
       }
       
       $user = Yii::$app->user->identity->id;
       if($action){
          $permission = Permission::find()->where(['user_id'=>$user,'controller'=>$controller[0]])->andWhere(['LIKE','action','"'.$action.'"'])->one();
       }else{
          $permission = Permission::find()->where(['user_id'=>$user,'controller'=>$controller[0]])->andWhere(['IS NOT','action',NULL])->one();
       }
       
       if(!empty($permission)){
         return true;
       }else{
         return false;
       }

    }

}
