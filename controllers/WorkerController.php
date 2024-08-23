<?php

namespace app\controllers;

use Yii;
use app\models\Worker;
use app\models\Document;
use app\models\Account;
use app\models\Search\Worker as WorkerSearch;
use app\models\Search\Ledger as LedgerSearch;
use app\models\WorkerSalary;
use app\models\WorkerSalaryAllowance;
use app\models\WorkerSalaryDeduction;
use app\models\WorkerLeave;
use app\models\History;
use app\models\Ledger;
use app\models\Holidays;
use app\models\WorkerAllowance;
use app\models\DeductionMaster;
use app\models\Erpmeta;
use app\models\Search\WorkerSalary as WorkerSalarySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WorkerController implements the CRUD actions for Worker model.
 */
class WorkerController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'delete-salary' => ['POST'],
                ],
            ],
        ];
    }
    
    
    public function actionGenerateSalary($month=Null){
        $searchModel = new WorkerSalarySearch();
        $searchModel->search(Yii::$app->request->queryParams);
        if(empty($searchModel->month)){
          return $this->render('generate-salary',[
             'model'=>$searchModel,
          ]);
        }

        $formatter = Yii::$app->formatter;
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $month = date("Y-m-d",strtotime("01-".$searchModel->month));
        $endmonth = date("Y-m-t",strtotime($month));
        if(strtotime($endmonth)>=strtotime(date("Y-m-t"))){
          \Yii::$app->session->setFlash('error',"You can not generate current month or future salary.");
          return $this->render('generate-salary',[
             'model'=>$searchModel,
          ]);
        }
        if(strtotime($month)<=strtotime( date("Y-m-d",strtotime( date("Y-m-01")." -2 Month" )) )){
          \Yii::$app->session->setFlash('error',"You can not generate previous month salary.");
          return $this->render('generate-salary',[
             'model'=>$searchModel,
          ]);
        }
        
        $workers = Worker::find()->where(['status'=>Worker::STATUS_ACTIVE])->all();
        foreach($workers as $worker){
            $workerDates = $worker->getLatestDate();
            if( !empty( $workerDates['joining_date'] ) ){
                $joining_date = $workerDates['joining_date'];
            }
            $end_date = null;
            if( !empty( $workerDates['end_date'] ) && strtotime($workerDates['joining_date']) < strtotime($workerDates['end_date']) ){
                $end_date = $workerDates['end_date'];
            }
            if( 
                strtotime( date("Y-m-01",strtotime( $joining_date )) ) > strtotime( $month )
                || ( !empty($end_date) && strtotime( date("Y-m-t",strtotime( $end_date )) ) < strtotime( $endmonth ) )
            ){
                continue;
            }
           $daysInMonth = date("t",strtotime($month));
           $model = WorkerSalary::find()->where(['month'=>$endmonth,'worker_id'=>$worker->id])->orderBy(['id'=>SORT_ASC])->one();
           if(empty($model)){
             $model = new WorkerSalary;
           }
           //$getAllotedDays = $worker->leaves?json_decode($worker->leaves,true):[];
           //$allotedLeave = $this->getAllotedLeave($month,$getAllotedDays);
           $allotedLeave = $worker->fixed_leave;
           $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->count('*');
           $model->per_day_salary = round($model->base_salary/$daysInMonth,2);
           $working_days = $daysInMonth;
           if(date("m-Y",strtotime($worker->joining_date)) == date("m-Y",strtotime($month))){
               $working_days = $daysInMonth - date("d",strtotime($worker->joining_date)) + 1;
               //$allotedLeave = $this->getAllotedLeave($month,$getAllotedDays,$worker->joining_date);
               $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->andWhere(['>','date',$worker->joining_date])->count('*');
           }
           $model->month = date("Y-m-t",strtotime($month));
           $model->worker_id = $worker->id;
           $model->company_id = $worker->company_id;
           $model->worker_vendor_id = $worker->worker_vendor_id;
           $model->session = \app\models\Session::getCurrentSession();
           $model->base_salary = $worker->salary;
           $model->per_day_salary = $worker->salary/$daysInMonth;
           $totalAllotedLeave = $allotedLeave+$holidays;
           $leaves = WorkerLeave::find()->where(['worker_id'=>$worker->id,'month'=>date("Y-m-d",strtotime($month))])->one();
           $model->leave = 0;
           $myleave = 0;
           if(!empty($leaves)){
              $leave = json_decode($leaves->leave,true);
              $myleave = count($leave);
           }
           $model->leave = $myleave>$totalAllotedLeave?$myleave-$totalAllotedLeave:0;
           $model->working_days = $working_days-$model->leave;
           $model->extra_work_days = $myleave>$totalAllotedLeave?0:$totalAllotedLeave-$myleave;;
           $model->salary = $model->leave?$worker->salary-$model->per_day_salary*$model->leave:($working_days+$model->extra_work_days)*$model->per_day_salary;
           $model->allowance = WorkerAllowance::find()->where(['worker_id'=>$worker->id])->sum('value*'.$working_days/$daysInMonth);
           $model->salary_with_allowance = $model->allowance + $model->salary;
           $model->worker_deduction = 0;
           if($worker->is_deduction == $worker::DEDUCTION_YES){
              $model->worker_deduction = DeductionMaster::find()->where(['type'=>1])->sum($model->base_salary.'*`rate`/100');
           }
           $model->payable_salary = $model->salary_with_allowance - $model->worker_deduction;
           $model->employer_deduction = 0;
           if($worker->is_deduction){
              $model->employer_deduction = DeductionMaster::find()->where(['type'=>2])->sum($model->base_salary.'*`rate`/100');
           }
           $model->net_salary = $model->salary_with_allowance + $model->employer_deduction;
           if($model->save()){
 
              $allowances = WorkerAllowance::find()->where(['worker_id'=>$worker->id])->andWhere(['!=','value',0])->all();
              foreach($allowances as $allowance){
                 $allowanceModel = WorkerSalaryAllowance::find()->where(['salary_id'=>$model->id,'allowance_id'=>$allowance->id])->one();
                 if(empty($allowanceModel)){
                    $allowanceModel = new WorkerSalaryAllowance;
                 }
                 $allowanceModel->worker_id = $model->worker_id;
                 $allowanceModel->salary_id = $model->id;
                 $allowanceModel->allowance_id = $allowance->id;
                 $allowanceModel->actual_amount = $allowance->value;
                 $allowanceModel->per_day = $allowance->value/$daysInMonth;
                 $allowanceModel->amount = ($allowanceModel->per_day)*$working_days;
                 if(!$allowanceModel->save()){
                    $transaction->rollback();
                    foreach($$allowanceModel->getErrors() as $error){
                         \Yii::$app->session->setFlash('error', $error[0]);
                    }
                    return $this->render('generate-salary',[
                       'model'=>$searchModel,
                    ]);
                 }
              }
              
              if($worker->is_deduction == $worker::DEDUCTION_YES){
                 $deductions = DeductionMaster::find()->all();
                 foreach($deductions as $deduction){
                    $deductionModel = WorkerSalaryDeduction::find()->where(['salary_id'=>$model->id,'deduction_id'=>$deduction->id])->one();
                    if(empty($deductionModel)){
                       $deductionModel = new WorkerSalaryDeduction;
                    }
                    $deductionModel->worker_id = $model->worker_id;
                    $deductionModel->salary_id = $model->id;
                    $deductionModel->deduction_id = $deduction->id;
                    $deductionModel->rate = $deduction->rate;
                    $deductionModel->type = $deduction->type;
                    $deductionModel->per_day = ($model->base_salary*$deduction->rate/100)/$daysInMonth;
                    $deductionModel->amount = ($deductionModel->per_day)*$working_days;
                    if(!$deductionModel->save()){
                       $transaction->rollback();
                       foreach($$deductionModel->getErrors() as $error){
                            \Yii::$app->session->setFlash('error', $error[0]);
                       }
                       return $this->render('salary-record',[
                          'model'=>$searchModel,
                       ]);
                    }
                 }
              }
              $ledger = new Ledger;
              if(!$ledger->saveLedger($model->month,$model->worker_id,$model->id,"Salary",0,$model->payable_salary,$ledger::INOUT_DEBIT,$ledger::TYPE_WORKER,$model->company_id,$model->session,$ledger::FROM_WORKER_SALARY_PAGE)){
                  $transaction->rollback();
                  return $this->render('salary-record',[
                     'model'=>$searchModel,
                  ]);
              }
           }else{
                $transaction->rollback();
                foreach($model->getErrors() as $error){
                     \Yii::$app->session->setFlash('error', $error[0]);
                }
                return $this->render('salary-record',[
                   'model'=>$searchModel,
                ]);
           }
        }
        $transaction->commit();
        \Yii::$app->session->setFlash('success', "Salary Successfully Generated for ".$formatter->asDate($month,'php:M-Y')." Month.");
        return $this->redirect("salary-record");
    }
    
    public function actionGetBaseSalary($worker_id,$month){
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $month = "01-".$month;
        $model = $this->findModel($worker_id);
        //$getAllotedDays = $model->leaves?json_decode($model->leaves,true):[];
        //$allotedLeave = $this->getAllotedLeave($month,$getAllotedDays);
        $allotedLeave = $model->fixed_leave;
        $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->count('*');
        $actualMonthDays = date("t",strtotime($month)); 
        $monthDays = date("t",strtotime($month)); 
        if( strtotime($model->joining_date) < strtotime($model->end_date) && strtotime($month) > strtotime($model->end_date) ){
            return [];
        }
        $data['per_day_salary'] = round($model->salary/$monthDays,2);
        if( date("m-Y",strtotime($model->end_date)) == date("m-Y",strtotime($month)) && strtotime($model->joining_date) < strtotime($model->end_date) ){
            $monthDays = date("d",strtotime($model->end_date)); 
        }
        if(date("m-Y",strtotime($model->joining_date)) == date("m-Y",strtotime($month))){
            $monthDays = $monthDays - date("d",strtotime($model->joining_date)) + 1;
            //$allotedLeave = $this->getAllotedLeave($month,$getAllotedDays,$model->joining_date);
            $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->andWhere(['>','date',$model->joining_date])->count('*');
        }
        $totalAllotedLeave = $allotedLeave+$holidays;
        $data['base_salary'] = $model->salary;
        $leave = WorkerLeave::find()->where(['worker_id'=>$worker_id,'month'=>date("Y-m-d",strtotime($month))])->one();
        //echo $leave->createCommand()->getRawSql();die();
        $data['leave'] = 0;$myleave= 0;
        if(!empty($leave)){
           $leaves = json_decode($leave->leave,true);
           $myleave = count($leaves);
        }
        $data['leave'] = $myleave>$totalAllotedLeave?$myleave-$totalAllotedLeave:0;
        $data['working_days'] = $monthDays-$data['leave']; 
        $data['extra_work_days'] = $myleave>$totalAllotedLeave?0:$totalAllotedLeave-$myleave;
        $data['extra_salary'] = $data['leave']?0:$data['extra_work_days']*$data['per_day_salary'];
        $data['extra_salary'] = round($data['extra_salary']);
        //$data['salary'] = $data['leave']?$model->salary-$data['per_day_salary']*$data['leave']:$data['per_day_salary']*$data['working_days']+$data['extra_work_days']*$data['per_day_salary'];
        $data['salary'] = $actualMonthDays == $data['working_days']?$model->salary:$data['per_day_salary']*$data['working_days']+$data['extra_work_days']*$data['per_day_salary'];
        $data['salary'] = round($data['salary']);
        //echo "<pre>";print_r($data);die();
        return $data;
    }

    public function actionAdditionalSalary($worker_id){
        $model = $this->findModel($worker_id);
        $workerAllowance = WorkerSalaryAllowance::loadData(Null,$worker_id);
        $workerDeduction = [];
        $employerDeduction = [];
        if($model->is_deduction == $model::DEDUCTION_YES){
           $workerDeduction = WorkerSalaryDeduction::loadData(1,Null,$worker_id);
           $employerDeduction = WorkerSalaryDeduction::loadData(2,Null,$worker_id);
        }

        return $this->renderAjax('form/_additional_salary', [
            'model' => $model,
            'workerAllowance' => $workerAllowance,
            'workerDeduction' => $workerDeduction,
            'employerDeduction' => $employerDeduction,
        ]);
    }

    public function actionSalaryForm($id = Null){
        $model = new WorkerSalary();
        $workerAllowance = new WorkerSalaryAllowance;
        $workerDeduction = new WorkerSalaryDeduction;
        $employerDeduction = new WorkerSalaryDeduction;
        if($id){
           $model = WorkerSalary::find()->where(['id'=>$id])->one();
            $workerAllowance = WorkerSalaryAllowance::find()->where(['worker_id'=>$model->worker_id,'salary_id'=>$model->id])->all();
            $workerDeduction = WorkerSalaryDeduction::find()->where(['worker_id'=>$model->worker_id,'salary_id'=>$model->id,'type'=>1])->all();
            $employerDeduction = WorkerSalaryDeduction::find()->where(['worker_id'=>$model->worker_id,'salary_id'=>$model->id,'type'=>2])->all();
        }
        if(Yii::$app->request->isPost){

            $allowances = Yii::$app->request->post()['allowance'];
            $workerAllowance = WorkerSalaryAllowance::loadData($allowances);
            $deductions = Yii::$app->request->post()['deduction'];
            $workerDeduction = WorkerSalaryDeduction::loadData(1,$deductions);
            $employerDeduction = WorkerSalaryDeduction::loadData(2,$deductions);
            $formatter = Yii::$app->formatter;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();   
            $month = "01-".Yii::$app->request->post()['WorkerSalary']['month'];
            $month = $formatter->asDate($month,'php:Y-m-d');
            $model->load(Yii::$app->request->post()); 
            $model->month = date("Y-m-t",strtotime($month));
            if($model->save()){
                $flag = true;
                if(!empty($allowances))
                foreach($allowances as $allowance){
                   $dataModel = new WorkerSalaryAllowance;
                   if(!empty($allowance['WorkerSalaryAllowance']['id'])){
                     $dataModel = WorkerSalaryAllowance::find()->where(['id'=>$allowance['WorkerSalaryAllowance']['id']])->one();
                   } 
                   $dataModel->load($allowance);
                   $dataModel->salary_id = $model->id;
                   $dataModel->worker_id = $model->worker_id;
                   if(!$dataModel->save()){
                      $flag = false;
                   }
                }
                if(!empty($deductions))
                  foreach($deductions as $deduction){
                     $dataModel = new WorkerSalaryDeduction;
                     if(!empty($deduction['WorkerSalaryDeduction']['id'])){
                       $dataModel = WorkerSalaryDeduction::find()->where(['id'=>$deduction['WorkerSalaryDeduction']['id']])->one();
                     } 
                     $dataModel->load($deduction);
                     $dataModel->salary_id = $model->id;
                     $dataModel->worker_id = $model->worker_id;
                     if(!$dataModel->save()){
                        $flag = false;
                     }
                  }
                $ledger = new Ledger;
                if(!$ledger->saveLedger($model->month,$model->worker_id,$model->id,"Salary",0,$model->payable_salary,$ledger::INOUT_DEBIT,$ledger::TYPE_WORKER,$model->company_id,$model->session,$ledger::FROM_WORKER_SALARY_PAGE)){
                     $flag = false;
                }
                if($flag){
                   \Yii::$app->session->setFlash('success', 'Salary has been Created Successfully.');
                   $transaction->commit(); 
                   return $this->redirect('salary-record');
                }else{
                   $transaction->rollback(); 
                }
            }else{
                foreach($model->getErrors() as $error){
                     \Yii::$app->session->setFlash('error', $error[0]);
                }
            }
        }
        
        if($model->month){
           $model->month = date("m-Y",strtotime($model->month));
        }
        return $this->render('form/salary-form', [
            'model' => $model,
            'workerAllowance' => $workerAllowance,
            'workerDeduction' => $workerDeduction,
            'employerDeduction' => $employerDeduction,
            'id'=>$id,
        ]);
    }

    public function actionSalaryRecord(){
        $searchModel = new WorkerSalarySearch();
        $params = Yii::$app->request->queryParams;
        $worker_vendor_id = Null;
        if( !empty( $params['WorkerSalary']['worker_id'] )  ){
            $worker_vendor_id = $params['WorkerSalary']['worker_vendor_id'];
            unset($params['WorkerSalary']['worker_vendor_id']);
        }
        //echo "<pre>";print_r( $params );die();
        $dataProvider = $searchModel->search( $params );
        $searchModel->worker_vendor_id = $worker_vendor_id;
        return $this->render('salary-record', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getAllotedLeave($month,$days = [],$jd = null){
        $totalDays = 0;
        $weekdays = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
        foreach($days as $day){
           $weekday = $weekdays[$day];
           $start = date("d",strtotime("first $weekday of ".date("M",strtotime($month))." ".date("Y",strtotime($month))));
           if($jd){
               $start = $day != date("w",strtotime($jd))?date("d",strtotime($jd." next ".$weekday)):date("d",strtotime($jd));
           }
           $end = date("t",strtotime($month));
           for($i = $start;$i <= $end;$i+=7){
               $totalDays++;
           }
        }
        return $totalDays;
    }

    public function actionAddAllowance($worker_id){
     
        if(Yii::$app->request->isPost){
            
            $postData = Yii::$app->request->post()['WorkerAllowance'];
            $flag = true;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
            foreach($postData as $data){
                $allowance = new \app\models\WorkerAllowance;
                if($data['WorkerAllowance']['id']){
                    $allowance = \app\models\WorkerAllowance::find()->where(['id'=>$data['WorkerAllowance']['id']])->one();
                }
                $allowance->load($data);
                if(!$allowance->save()) $flag = false;
            }
            if($flag){
                $transaction->commit();
            	\Yii::$app->session->setFlash('success', 'Allowances added successfully.');
                return $this->redirect(['view', 'id' => $worker_id]);
            }else{
                $transaction->rollback();
            	\Yii::$app->session->setFlash('error', 'Errors');
                return $this->redirect(['view', 'id' => $worker_id]);
            }
            
        }
        
    }
    
	public function actionLedger()
    {
        $searchModel = new LedgerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$data = $searchModel->dataSearch(Yii::$app->request->queryParams);
		
		if($searchModel->fromDate){
		    $searchModel->fromDate = $formatter->asDate($searchModel->fromDate,'php:d-m-Y');
		}
		
		if($searchModel->toDate){
		    $searchModel->toDate = $formatter->asDate($searchModel->toDate,'php:d-m-Y');
		}
		
        return $this->render('ledger', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'data' => $data,
        ]);
    }

    /**
     * Lists all Worker models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorkerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Worker model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        
        $model = $this->findModel($id);
        $document = new Document;
        $account = new Account;
        $allowances = $model->workerAllowances;
        $erpmeta = new Erpmeta;
        return $this->render('view', [
            'model' => $model,
            'document'=>$document,
            'account'=>$account,
            'allowances' => $allowances,
            'erpmeta' => $erpmeta,
        ]);
    }

    /**
     * Creates a new Worker model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Worker();
        $formatter = Yii::$app->formatter;

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			  
			  $model->code = $model->getCode();
			  
			  if($model->joining_date)
			       $model->joining_date = $formatter->asDate($model->joining_date,'php:Y-m-d');
			  
			  if($model->end_date)
			       $model->end_date = $formatter->asDate($model->end_date,'php:Y-m-d');
			  
			  if($model->save()){
			          
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_WORKER;
			    
			    $ledger_transaction = false;
			    
			    if($model->inout_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Worker has been Created Successfully');
                        return $this->redirect(['index']);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			  }else{
			      $model->validate(); 
			      print_r($model->getErrors());die();
			      
			  }
			  
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Worker model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $formatter = Yii::$app->formatter;
        if($model->joining_date)
            $model->joining_date = $formatter->asDate($model->joining_date,'php:d-m-Y');
        if($model->end_date)
            $model->end_date = $formatter->asDate($model->end_date,'php:d-m-Y');

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        
        if ($model->load(Yii::$app->request->post())) {
			    if($model->joining_date)
			        $model->joining_date = $formatter->asDate($model->joining_date,'php:Y-m-d');
			    if($model->end_date)
			        $model->end_date = $formatter->asDate($model->end_date,'php:Y-m-d');

			  if($model->save()){
			          
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_WORKER;
			    
			    $ledger_transaction = false;
			    
			    if($model->inout_type == $model::TYPE_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", $model->last_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Opening Balance", 0,$model->last_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->company_id, $model->session,$ledger::FROM_WORKER_PAGE);
				   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Worker has been Created Successfully');
                        return $this->redirect(['index']);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			  }else{
			      $model->validate(); 
			      print_r($model->getErrors());die();
			      
			  }
			  
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Worker model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model = Worker::STATUS_DELETE;
        $model->save();
        
        \Yii::$app->session->setFlash('success', 'Worker has been Deleted Successfully');
        return $this->redirect(['index']);
    }

    public function actionStatus($id)
    {
        $model = $this->findModel($id);
        $status = $model->status == $model::STATUS_ACTIVE?$model::STATUS_DEACTIVE:$model::STATUS_ACTIVE;
        $statusLabel = $model->status == $model::STATUS_ACTIVE?'Deactived':'Actived';
        $model->status = $status;
        $model->save();
        \Yii::$app->session->setFlash('success', $model->name.'-'.$model->code.' is '.$statusLabel);
        return $this->redirect(['view', 'id' => $model->id]);
                        
    }
    public function actionDeleteSalary($id)
    {

        $model = WorkerSalary::findOne($id);

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        $model->delete();
        $ledger = Ledger::find()->where(['transaction_id'=>$model->id,'entry_from'=>Ledger::FROM_WORKER_SALARY_PAGE])->one();
        if(!$ledger->delete()){
          foreach($ledger->getErrors() as $error){
             \Yii::$app->session->setFlash('error', $error[0]);
          }
        }else{
            \Yii::$app->session->setFlash('success', "Salary Successfully Deleted");
            $transaction->commit();
        }
        return $this->redirect(['salary-record']);
    }

    /**
     * Finds the Worker model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Worker the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Worker::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
