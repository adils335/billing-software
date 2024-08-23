<?php

namespace app\models;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

use dektrium\user\models\User as BaseUser;
use dektrium\user\helpers\Password;
use yii\base\Event;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $access_module
 */
class User extends BaseUser
{

    /**
     * {@inheritdoc}
    */
     
    public static function isSelf()
    {
        $role = Yii::$app->user->identity->role;
        $isSelf = false;
        if($role){
           $isSelf = \app\models\Roles::find()->where(['id'=>$role,'is_self'=>1])->exists();
        }
       
        return $isSelf;
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

    public function isSuperAdmin(){
        return Yii::$app->user->identity->role === 0?true:false;
    }
    
    public function getCompanies(){
        
        $companies = Yii::$app->user->identity->access_company;
        if( !empty($companies) ){
            $companies = json_decode($companies,true);
            return \app\models\Company::find()->where(['id'=>$companies])->all();
        }
        return [];
        
    }
    
    public function getEmployee(){
        
        $userId = Yii::$app->user->identity->id;
        return \app\models\Employee::find()->where(['user_id'=>$userId])->one();
        
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
        $identity = static::findOne(['id' => $id]);
        Yii::$app->user->login($identity);
        return $identity;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }

    public function setPassword($password){
       return $this->password_hash = Password::hash($password);
    }
}
