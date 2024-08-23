<?php 
namespace app\components;
 
use yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;

class Wizard extends Behavior
{   
	public $id;
    public $steps = [];
   
    public $data = [];
    private $_current_step;    
    private $_steps_order = [];
    public $onCompleteWizard = '';
	
	public function getController(){
       
       return Yii::$app->controller->id;	   
	   	
	}
	
	public function getView(){
       
       $currentController = Yii::$app->controller->id;	   
	   
	   $view  = array(
	                      'agreement'=>'agreement/agreement-steps',
						  'quotation'=>'quotation/quotation-steps'
					) ;
								
       return $view[$currentController];
	
	}
	
	public function getCreate(){
       
       $currentController = Yii::$app->controller->id;	   
	   
	   
	   $create  = array(
	                        'agreement'=>'agreement/create-agreement',
						    'quotation'=>'quotation/create-quotation'
					  ) ;
								
       return $create[$currentController];
	
	}
	
	public function set_manual_current_step($manual_current_step){
		
		 Yii::$app->session->set('manual_current_step',$manual_current_step);
		 
	}
	public function get_manual_current_step(){
		 return Yii::$app->session->get('manual_current_step');
	}
    public function init(){
        $this->_steps_order = array_keys($this->steps);
        parent::init();
    }

    public function getCurrentFormId(){
        return $this->currentStep.'_form';
    }
    
    private function _getSessionKey(){
        return 'wizard.'.$this->id;
    }

    public function getCurrentStep(){
        return $this->_current_step;
    }
    
    public function getNextStep(){
        $step_number = array_search($this->_current_step, $this->_steps_order);
        if($step_number === FALSE){
            $this->step($this->_steps_order[0]);
            return $this->currentStep;
        }
        elseif($step_number == (count($this->steps)-1)){ //If last step, return false
            return false;
        }
        else{   
            $nextStep = $step_number + 1; 
            return $this->_steps_order[$nextStep];
        }
    }

    public function getPrevStep(){
        $step_number = array_search($this->_current_step, $this->_steps_order);
        if($step_number === FALSE){
            $this->step($this->_steps_order[0]);
            return $this->currentStep;
        }
        elseif($step_number == 0){ //If first step, return false
            return false;
        }
        else{   
            $prevStep = $step_number - 1; 
            return $this->_steps_order[$prevStep];
        }
    }

    public function _setSession($key, $data, $context = null){
        $session = \Yii::$app->session;
        $session_key = $this->_getSessionKey();
        $session_data = $session[$session_key];
        if(!empty($context)){
            $context_session_data = !empty($session_data[$context])?$session_data[$context]:[];
            $context_session_data[$key] = $data;
            $session_data[$context] = $context_session_data;
        }
        else{
            $session_data[$key] = $data;
        }
        $session[$session_key] = $session_data;
    }
    
    public function resetWizard(){
        $session = \Yii::$app->session;
        $session_key = $this->_getSessionKey();
        $session->remove($session_key);
    }

    public function _getSession($key, $context = null){
        $session = \Yii::$app->session;
        $session_key = $this->_getSessionKey();
        $session_data = $session[$session_key];
        if(!empty($context)){
            return !empty($session_data[$context][$key])?$session_data[$context][$key]:false;
        }
        else{
            return !empty($session_data[$key])?$session_data[$key]:false;
        }
    }

    public function step($step = null){
        //Check if step is set.
        if(empty($step)){
            //If yes step, go to first step
            $this->_current_step = $this->_steps_order[0];
            $step = $this->_current_step;
        }        
        elseif(empty($this->steps[$step])){
            $this->_current_step = null;
            //If invalid step, redirect to 404
            throw new \yii\web\HttpException(404, 'Invalid Step');
        }
        $this->_current_step = $step;
        $next_step = $this->getNextStep();
        
        if(true === call_user_func_array([$this->owner, $this->steps[$step]['callback']], [])){
            if(!$next_step){ //End of wizard
                if(empty($this->onCompleteWizard)){
                    return false;
                }                
                return call_user_func_array([$this->owner, $this->onCompleteWizard], []);
            }
            else{
                return Yii::$app->response->redirect([$this->getCreate(), 'step'=>$this->nextStep, 'id'=>$this->data[$this->getController()]->id]);
            }
        }
        else{
            //Render current step
            return Yii::$app->controller->render('/'.$this->getView().'\\container', ['wizard'=>$this]);
        }
    }

    public function saveCurrentStep($data){
        if(!$this->currentStep){
            return false;
        }
        $this->_setSession($this->currentStep, $data, 'stepData');
    }


    public function getStepAlreadySaved(){
        if(!$this->currentStep){
            return false;
        }
        $stepData = $this->_getSession($this->currentStep, 'stepData');
        return !empty($stepData);
    }

    public function getCurrentStepData(){
        if(!$this->currentStep){
            return false;
        }
        return $this->_getSession($this->currentStep, 'stepData');
    }
}