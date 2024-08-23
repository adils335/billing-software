<?php

namespace app\models;

use Yii;
use \app\models\base\Agreement as BaseAgreement;
use yii\helpers\ArrayHelper;
use app\models\Documents;

/**
 * This is the model class for table "agreement".
 */
class Agreement extends BaseAgreement
{
	const TYPE_AGREEMENT = 1;
	const TYPE_QUOTATION = 2;
	const TYPE_GENERAL = 3;
	
	const SCHEDULE_ATPAR = 0;
	const SCHEDULE_ABOVE = 1;
	const SCHEDULE_BELOW = 2;
	
	const STATUS_RUNNING = 0;
	const STATUS_COMPLETED = 1;
	const STATUS_DELETE = 2;

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
	
	public static function buildSchedule(){
			return [
				self::SCHEDULE_ATPAR    => 'Atpar',
				self::SCHEDULE_ABOVE	=> 'Above',
				self::SCHEDULE_BELOW	=> 'Below',
		];
	}
	public  function getScheduleLabel(){
		
			if(isset(self::buildSchedule()[$this->schedule])){
				return self::buildSchedule()[$this->schedule];
			}
		
	}
	
	public static function buildType(){
			return [
				self::TYPE_AGREEMENT    => 'Agreement',
				self::TYPE_QUOTATION	=> 'Quotation',
				self::TYPE_GENERAL	=> 'General',
		];
	}
	public  function getTypeLabel(){
		
			if(isset(self::buildType()[$this->type])){
				return self::buildType()[$this->type];
			}
		
	}
	
	public static function buildStatus(){
			return [
				self::STATUS_RUNNING    => 'Running',
				self::STATUS_COMPLETED	=> 'Completed',
				self::STATUS_DELETE	=> 'Deleted',
		];
	}
	public  function getStatusLabel(){
		
			if(isset(self::buildStatus()[$this->status])){
				return self::buildStatus()[$this->status];
			}
		
	}
	
	public function getContractCompanyDistricts(){
		$district = \app\models\ContractCompanyGst::find()->where(['state_id'=>$this->contract_company_state])->one();
        $districts = json_decode($district->districts);
        $array = [];
        if($districts)
        foreach ($districts as $value) {
        	$getDistrict = \app\models\District::find()->where(['id'=>$value])->one();
        	$array[$value] = $getDistrict->district;
        }

		return $array;
	}

	public function fileNo(){
		
		$maxFileNo = Self::find()->select(['MAX(file_no) as file_no'])->where(['session'=>$this->session,'company_id'=>$this->company_id])->one();
		if($maxFileNo)
		     return $maxFileNo->file_no + 1;
		else return 1; 
		
	}
	
	public function totalBill(){
	    
	    $bills = \app\models\AgreementBill::find()->where(['agreement_id'=>$this->id,'status'=>AgreementBill::STATUS_ACTIVE])->count();
	    return $bills!=null?$bills:0;
	    
	}
	
	public function billing(){
	    
	    $bills = \app\models\AgreementBill::find()->where(['agreement_id'=>$this->id,'status'=>AgreementBill::STATUS_ACTIVE])->sum('taxable_amount');
	    return $bills!=null?$bills:0;
	    
	}
	
	public function balance(){
	    
	    $bills = \app\models\AgreementBill::find()->where(['agreement_id'=>$this->id,'status'=>AgreementBill::STATUS_ACTIVE])->sum('taxable_amount');
	    return $this->cost - $bills;
	    
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgreementRateSchedule()
    {
        return \app\models\AgreementRateSchedule::find()->where(['agreement_id' => $this->id,'is_active'=>1])->orderBy(['sno'=>SORT_ASC])->all();
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return Documents::find()->where(['source_id' => $this->id,'status'=>Documents::STATUS_ENABLED,'type'=>Documents::TYPE_AGREEMENT])->orderBy(['id'=>SORT_DESC])->all();
    }
}
