<?php

namespace app\controllers;

use Yii;
use app\models\Employee;
use app\models\Ledger;
use app\models\Permission;
use app\models\Document;
use app\models\Account;
use app\models\EmployeeSalaryAllowance;
use app\models\EmployeeExtraSalaryAllowance;
use app\models\EmployeeSalaryDeduction;
use app\models\EmployeeLeave;
use app\models\History;
use app\models\Holidays;
use app\models\EmployeeAllowance;
use app\models\DeductionMaster;
use app\models\User;
use app\models\Erpmeta;
use app\models\Search\Employee as EmployeeSearch;
use app\models\Search\EmployeeSalarySearch;
use app\models\Search\EmployeeExtraSalary as EmployeeExtraSalarySearch;
use app\models\EmployeeSalary;
use app\models\EmployeeExtraSalary;
use app\models\Search\Ledger as LedgerSearch;
use yii\web\Controller; 
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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
                    //'delete-salary' => ['POST'],
                    'status' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionDeleteExtraSalary($id)
    {

        $model = EmployeeExtraSalary::findOne($id);

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        $model->delete();
        $ledger = Ledger::find()->where(['transaction_id'=>$model->id,'entry_from'=>Ledger::FROM_EMPLOYEE_EXTRA_SALARY_PAGE])->one();
        if(!$ledger->delete()){
          $transaction->rollback();    
          foreach($ledger->getErrors() as $error){
             \Yii::$app->session->setFlash('error', $error[0]);
          }
        }else{
            \Yii::$app->session->setFlash('success', "Salary Successfully Deleted");
            $transaction->commit();
        }
        return $this->redirect(['extra-salary']);
    }
    
    public function actionExtraSalary(){
        $searchModel = new EmployeeExtraSalarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->identity->isSelf()){
            $dataProvider->query->where(['id'=>Yii::$app->user->identity->employee->id]);
        }
        return $this->render('extra-salary', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionGetExtraSalary($employee_id,$month){
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if( !$employee_id ||  !$month){
            return ['status'=>false,'data'=>[]];
        }
        $month = date('Y-m-t',strtotime('01-'.$month)); 
        $getSalary = EmployeeSalary::find()->where(['employee_id'=>$employee_id])->andWhere(['<=','month',$month])->orderBy(['month'=>SORT_DESC])->one();
        //echo $getSalary->createCommand()->getRawSql();die();
        if( !empty($getSalary) ){
            $data['days'] = $getSalary->extra_work_days;
            $data['salary'] = $getSalary->extra_salary;
        }
        
        $employeeAllowance = EmployeeExtraSalaryAllowance::loadData(Null,$employee_id);
        $data['allowance'] = $this->renderAjax('form/_extra_salary_allowance',['employeeAllowance'=>$employeeAllowance]);
        $response = ['status'=>true,'data'=>$data];
        return $response;
        
    }
    
    public function actionExtraSalaryForm($id = null){
        $model = new EmployeeExtraSalary();
        $employeeAllowance = new EmployeeExtraSalaryAllowance;
        if($id){
            $model = EmployeeExtraSalary::find()->where(['id'=>$id])->one();
            $employeeAllowance = EmployeeExtraSalaryAllowance::find()->where(['employee_id'=>$model->employee_id,'salary_id'=>$model->id])->all();
        }
        if(Yii::$app->request->isPost){
        //echo "<pre>";print_r(Yii::$app->request->post());die();
            $formatter = Yii::$app->formatter;
            $month = "01-".Yii::$app->request->post()['EmployeeExtraSalary']['month'];
            $employee_id = Yii::$app->request->post()['EmployeeExtraSalary']['employee_id'];
            $month = $formatter->asDate($month,'php:Y-m-d');
            $model->month = date("Y-m-t",strtotime($month));
            $model = EmployeeExtraSalary::find()->where(['employee_id'=>$employee_id,'month'=>$model->month])->one();
            $employee = Employee::findOne($employee_id);
            
            if(empty($model)){
                $model = new EmployeeExtraSalary();
            }
            $allowances = Yii::$app->request->post()['allowance'];
            $employeeAllowance = EmployeeExtraSalaryAllowance::loadData($allowances);
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();  
            $model->load(Yii::$app->request->post()); 
            $model->month = date("Y-m-t",strtotime($month));
            if($model->save()){
                $flag = true;
                if(!empty($allowances))
                foreach($allowances as $allowance){
                   $dataModel = EmployeeExtraSalaryAllowance::find()->where(['allowance_id'=>$allowance['EmployeeExtraSalaryAllowance']['allowance_id'],'salary_id'=>$model->id])->one();
                   if(empty($dataModel)){
                       $dataModel = new EmployeeExtraSalaryAllowance;
                   } 
                   $dataModel->load($allowance);
                   $dataModel->salary_id = $model->id;
                   $dataModel->employee_id = $employee->id;
                   if(!$dataModel->save()){
                      $flag = false;
                   }
                }
                $ledger = new Ledger;
                if(!$ledger->saveLedger($model->month,$employee->id,$model->id,"Extra Salary",0,$model->salary_with_allowance,$ledger::INOUT_DEBIT,$ledger::TYPE_EMPLOYEE,$model->company_id,$model->session,$ledger::FROM_EMPLOYEE_EXTRA_SALARY_PAGE)){
                     $flag = false;
                }
                if($flag){
                   //$model->createPdf();    
                   \Yii::$app->session->setFlash('success', 'Salary has been Created Successfully.');
                   $transaction->commit(); 
                   return $this->redirect('extra-salary');
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
        return $this->render('form/extra-salary-form',[
           'model' => $model,
           'employeeAllowance' => $employeeAllowance,
        ]);
    }
    
    public function actionBulkSalaryPdf($message){
        
        $models = EmployeeSalary::find()->all();
        foreach($models as $model){
            $model->createPdf();
        }
       
    }
    
    public function actionGenerateSalary($month=Null){
        $searchModel = new EmployeeSalarySearch();
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
        $month = date("Y-m-t",strtotime($month));
        $endmonth = date("Y-m-t",strtotime($month));
        if(strtotime($month)>=strtotime(date("Y-m-01"))){
          \Yii::$app->session->setFlash('error',"You can not generate current month or future salary.");
          return $this->render('generate-salary',[
             'model'=>$searchModel,
          ]);
        }
        if(strtotime($month)<=strtotime( date("Y-m-d",strtotime( date("Y-m-01")." -1 Month" )) )){
          \Yii::$app->session->setFlash('error',"You can not generate previous month salary.");
          return $this->render('generate-salary',[
             'model'=>$searchModel,
          ]);
        }
        
        $daysInMonth = date("t",strtotime($month));
        $employees = Employee::find()->where(['status'=>Employee::STATUS_ACTIVE])->all();
        foreach($employees as $employee){
            $employee_id = $employee->id;
            $employeeDates = $employee->getLatestDate();
            $joining_date = $employee->joining_date;
            if( !empty( $employeeDates['joining_date'] ) ){
                $joining_date = $employeeDates['joining_date'];
            }
            $end_date = null;
            if( !empty( $employeeDates['end_date'] ) && strtotime($employeeDates['joining_date']) < strtotime($employeeDates['end_date']) ){
                $end_date = $employeeDates['end_date'];
            }
            if( 
                strtotime( date("Y-m-01",strtotime( $joining_date )) ) > strtotime( $month )
                || ( !empty($end_date) && strtotime( date("Y-m-t",strtotime( $end_date )) ) < strtotime( $endmonth ) )
            ){
                continue;
            }
            if(in_array(date("m",strtotime($month)),["01","02","03"])){
                $lastSalary = EmployeeSalary::find()->where(["BETWEEN","month",(date("Y",strtotime($month))-1)."-04-01",date("Y-",strtotime($month))."04-01"])
                                        ->andWhere(['<','month',date("Y-m-d",strtotime($month))])->andWhere(['employee_id'=>$employee->id])->orderBy(['month'=>SORT_DESC])->one();
            }else{
                $lastSalary = EmployeeSalary::find()->where([">","DATE_FORMAT(month,'%Y-%m')",date("Y-",strtotime($month))."03"])
                            ->andWhere(['<','month',date("Y-m-d",strtotime($month))])->andWhere(['employee_id'=>$employee->id])->orderBy(['month'=>SORT_DESC])->one();
            }
        
            $extra_work = 0;
            if($lastSalary){
                $extra_work = $lastSalary->extra_work_days;
                $paidExtra = EmployeeExtraSalary::find()->where(['employee_id'=>$employee_id])
                            ->andWhere(['month'=>$lastSalary->month])->sum('days');
                $extra_work -= $paidExtra;
            }
        
           $model = EmployeeSalary::find()->where(['month'=>$month,'employee_id'=>$employee->id])->orderBy(['id'=>SORT_ASC])->one();
           if(empty($model)){
             $model = new EmployeeSalary;
           }
           $getAllotedDays = $employee->leaves?json_decode($employee->leaves,true):[];
           $actualLeave = $this->getLeave($month,$getAllotedDays,Null,$employee->fixed_leave);
           $holidays = $this->getHolidays($month,Null);
           $totalAllotedLeave = $this->getAllotedLeave($month,$getAllotedDays,Null,$employee->fixed_leave);
           $getLeave = $this->getEmployeeLeave($employee_id,$month,$getAllotedDays);
           if(date("m-Y",strtotime($joining_date)) == date("m-Y",strtotime($month))){
               $totalAllotedLeave = $this->getAllotedLeave($month,$getAllotedDays,$employee->joining_date,$employee->fixed_leave);
               $actualLeave = $this->getLeave($month,$getAllotedDays,$employee->joining_date,$employee->fixed_leave);
               $holidays = $this->getHolidays($month,$employee->joining_date);
               $getLeave = $this->getEmployeeLeave($employee_id,$month,$getAllotedDays,$employee->joining_date);
           }
           
           $monthDays = date("t",strtotime($month)); 
           $model->actual_working_days = $monthDays-$actualLeave; 
           $model->working_days = $monthDays-$totalAllotedLeave;
           if( date("Y-m",strtotime($employee->joining_date)) == date("Y-m",strtotime($month)) ){
             $data['working_days'] = $monthDays-date("d",strtotime($employee->joining_date))-$totalAllotedLeave+1; 
             $model->working_days = $monthDays-date("d",strtotime($employee->joining_date))-$totalAllotedLeave+1;
           }
           $model->holidays = $holidays;
           $model->base_salary = $employee->salary;
           $model->per_day_salary = round($employee->salary/$model->actual_working_days,2);
           $model->month = date("Y-m-t",strtotime($month));
           $model->employee_id = $employee->id;
           $model->company_id = $employee->emp_company;
           $model->session = \app\models\Session::getCurrentSession();
           $model->leave = $totalAllotedLeave >= $getLeave?0:($getLeave - $totalAllotedLeave);
           $model->extra_work_days = $extra_work;
           if($extra_work && $model->leave ){
               $model->balanced_leave = $extra_work - $model->leave<=0?$extra_work:$model->leave;
               $extra_work -= $model->balanced_leave;
               $model->leave = $extra_work - $model->leave<=0?$model->leave-$model->balanced_leave:0;
               $model->extra_work_days = $extra_work;
           }else{
               $model->extra_work_days += $model->leave>0?0:$totalAllotedLeave-$getLeave;
           }
           
           $actualWorkingDays = $model->leave?($model->working_days - $model->leave):$model->working_days;
           $model->extra_salary = round($model->extra_salary);
           $model->salary = $actualWorkingDays*$model->per_day_salary;
           $model->salary = round($model->salary);
           $model->allowance = EmployeeAllowance::find()->where(['employee_id'=>$employee->id])->sum('value');
           $perDayAllownace = $model->allowance/$model->working_days;
           $totalAllowance = $actualWorkingDays*$perDayAllownace;
           //echo "<pre>";print_r( $actualWorkingDays );die();
           $model->extra_salary = $actualWorkingDays?($model->extra_work_days?round($model->extra_work_days*$model->per_day_salary+$model->extra_work_days*$totalAllowance/$actualWorkingDays):0):0;
           $model->salary_with_allowance = $totalAllowance + $model->salary;
           $employeeDeduction = 0;
           if($employee->is_deduction == $employee::DEDUCTION_YES){
              $employeeDeduction = DeductionMaster::find()->where(['type'=>1,'deduction_type'=>1])->sum($model->salary.'*`rate`/100');
           }
           if($employee->is_esi == $employee::DEDUCTION_YES){
              $employeeDeduction += DeductionMaster::find()->where(['type'=>1,'deduction_type'=>2])->sum($model->salary.'*`rate`/100');
           }
           $model->employee_deduction = $employeeDeduction;
           
           $model->payable_salary = round($model->salary_with_allowance - $model->employee_deduction);
           $employerDeduction = 0;
           $model->employer_deduction = 0;
           if($employee->is_deduction == $employee::DEDUCTION_YES){
              $employerDeduction += DeductionMaster::find()->where(['type'=>2,'deduction_type'=>1])->sum($model->salary.'*`rate`/100');
           }
           if($employee->is_esi == $employee::DEDUCTION_YES){
              $employerDeduction += DeductionMaster::find()->where(['type'=>2,'deduction_type'=>2])->sum($model->salary.'*`rate`/100');
           }
           $model->employer_deduction = $employerDeduction;
           
           $model->net_salary = round($model->salary_with_allowance + $model->employer_deduction);
          // echo "<pre>";print_r( $model );die();
           if($model->save()){
 
              $allowances = EmployeeAllowance::find()->where(['employee_id'=>$employee->id])->andWhere(['!=','value',0])->all();
              foreach($allowances as $allowance){
                 $allowanceModel = EmployeeSalaryAllowance::find()->where(['salary_id'=>$model->id,'allowance_id'=>$allowance->allowance_id])->one();
                 if(empty($allowanceModel)){
                    $allowanceModel = new EmployeeSalaryAllowance;
                 }
                 $allowanceModel->employee_id = $model->employee_id;
                 $allowanceModel->salary_id = $model->id;
                 $allowanceModel->allowance_id = $allowance->allowance_id;
                 $allowanceModel->per_day = $allowance->value/$model->actual_working_days;
                 $allowanceModel->actual_amount = $allowanceModel->per_day*$actualWorkingDays;
                 $allowanceModel->amount = $allowance->value;
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
              
              if($employee->is_deduction == $employee::DEDUCTION_YES){
                 $deductions = DeductionMaster::find()->where(['deduction_type'=>1])->all();
                 foreach($deductions as $deduction){
                    $deductionModel = EmployeeSalaryDeduction::find()->where(['salary_id'=>$model->id,'deduction_id'=>$deduction->id])->one();
                    if(empty($deductionModel)){
                       $deductionModel = new EmployeeSalaryDeduction;
                    }
                    $deductionModel->employee_id = $model->employee_id;
                    $deductionModel->salary_id = $model->id;
                    $deductionModel->deduction_id = $deduction->id;
                    $deductionModel->rate = $deduction->rate;
                    $deductionModel->type = $deduction->type;
                    $deductionModel->amount = $model->salary*$deduction->rate/100;
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
              
              if($employee->is_esi == $employee::DEDUCTION_YES){
                 $deductions = DeductionMaster::find()->where(['deduction_type'=>2])->all();
                 foreach($deductions as $deduction){
                    $deductionModel = EmployeeSalaryDeduction::find()->where(['salary_id'=>$model->id,'deduction_id'=>$deduction->id])->one();
                    if(empty($deductionModel)){
                       $deductionModel = new EmployeeSalaryDeduction;
                    }
                    $deductionModel->employee_id = $model->employee_id;
                    $deductionModel->salary_id = $model->id;
                    $deductionModel->deduction_id = $deduction->id;
                    $deductionModel->rate = $deduction->rate;
                    $deductionModel->type = $deduction->type;
                    $deductionModel->amount = $model->salary*$deduction->rate/100;
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
              if(!$ledger->saveLedger($model->month,$model->employee_id,$model->id,"Salary",0,$model->payable_salary,$ledger::INOUT_DEBIT,$ledger::TYPE_EMPLOYEE,$model->company_id,$model->session,$ledger::FROM_EMPLOYEE_SALARY_PAGE)){
                  $transaction->rollback();
                  return $this->render('salary-record',[
                     'model'=>$searchModel,
                  ]);
              }else{
                  $model->createPdf();
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
    
    public function actionGetBaseSalary($employee_id,$month){
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $month = "01-".$month;
        $model = $this->findModel($employee_id);
        $joining_date = $model->joining_date;
        if(in_array(date("m",strtotime($month)),["01","02","03"])){
          $lastSalary = EmployeeSalary::find()->where(["BETWEEN","month",(date("Y",strtotime($month))-1)."-04-01",date("Y-",strtotime($month))."04-01"])
                                        ->andWhere(['<','month',date("Y-m-d",strtotime($month))])->andWhere(['employee_id'=>$employee_id])->orderBy(['month'=>SORT_DESC])->one();
        }else{
          $lastSalary = EmployeeSalary::find()->where([">","DATE_FORMAT(month,'%Y-%m')",date("Y-",strtotime($month))."03"])
                        ->andWhere(['<','month',date("Y-m-d",strtotime($month))])->andWhere(['employee_id'=>$employee_id])->orderBy(['month'=>SORT_DESC])->one();
        }
        
        $extra_work = 0;
        if($lastSalary){
            $extra_work = $lastSalary->extra_work_days;
            $paidExtra = EmployeeExtraSalary::find()->where(['employee_id'=>$employee_id])
            ->andWhere(['month'=>$lastSalary->month])->sum('days');
            $extra_work -= $paidExtra;
        }
        $getAllotedDays = $model->leaves?json_decode($model->leaves,true):[];
        if(date("m-Y",strtotime($joining_date)) == date("m-Y",strtotime($month))){
            $totalAllotedLeave = $this->getAllotedLeave($month,$getAllotedDays,$model->joining_date,$model->fixed_leave);
            $actualLeave = $this->getLeave($month,$getAllotedDays,$model->joining_date,$model->fixed_leave);
            $holidays = $this->getHolidays($month,$model->joining_date);
            $getLeave = $this->getEmployeeLeave($employee_id,$month,$getAllotedDays,$model->joining_date);
        }else{
            $actualLeave = $this->getLeave($month,$getAllotedDays,Null,$model->fixed_leave);
            $holidays = $this->getHolidays($month,Null);
            $totalAllotedLeave = $this->getAllotedLeave($month,$getAllotedDays,Null,$model->fixed_leave);
            $getLeave = $this->getEmployeeLeave($employee_id,$month,$getAllotedDays);
        }
        $monthDays = date("t",strtotime($month)); 
        $data['actual_working_days'] = $monthDays-$actualLeave; 
        $data['working_days'] = $monthDays-$totalAllotedLeave;
        if( date("Y-m",strtotime($model->joining_date)) == date("Y-m",strtotime($month)) ){
            $data['working_days'] = $monthDays-date("d",strtotime($model->joining_date))-$totalAllotedLeave+1; 
        }
        $data['holidays'] = $holidays; 
        $data['base_salary'] = $model->salary;
        $data['per_day_salary'] = round($model->salary/$data['actual_working_days'],2);
        $data['leave'] = $totalAllotedLeave >= $getLeave?0:($getLeave - $totalAllotedLeave);
        
        $data['extra_work_days'] = $extra_work;
        //echo "<pre>";print_r($data);die();
        if($extra_work && $data['leave']){
            $data['balanced_leave'] = $extra_work - $data['leave']<=0?$extra_work:$data['leave'];
            $extra_work -= $data['balanced_leave'];
            $data['leave'] = $extra_work - $data['leave']<=0?$data['leave']-$data['balanced_leave']:0;
            $data['extra_work_days'] = $extra_work;
        }else{
            $data['extra_work_days'] += $data['leave']>0?0:$totalAllotedLeave-$getLeave;
        }
        $data['salary'] = ($data['working_days'] - $data['leave'])*$data['per_day_salary'];
        $data['salary'] = round($data['salary']);
        return $data;
    }

    public function actionAdditionalSalary($employee_id){
        $model = $this->findModel($employee_id);
        $employeeAllowance = EmployeeSalaryAllowance::loadData(Null,$employee_id);
        $employeeDeduction = [];
        $employerDeduction = [];
        if($model->is_deduction == $model::DEDUCTION_YES){
           $employeeDeduction = EmployeeSalaryDeduction::loadData(1,1,Null,$employee_id);
           $employerDeduction = EmployeeSalaryDeduction::loadData(1,2,Null,$employee_id);
        }
        if($model->is_esi == $model::DEDUCTION_YES){
           $employeeDeduction = array_merge($employeeDeduction,EmployeeSalaryDeduction::loadData(2,1,Null,$employee_id));
           $employerDeduction = array_merge($employerDeduction,EmployeeSalaryDeduction::loadData(2,2,Null,$employee_id));
        }
        $employeeDeduction = array_filter($employeeDeduction);
        $employerDeduction = array_filter($employerDeduction);
        
        return $this->renderAjax('form/_additional_salary', [
            'model' => $model,
            'employeeAllowance' => $employeeAllowance,
            'employeeDeduction' => $employeeDeduction,
            'employerDeduction' => $employerDeduction,
        ]);
    }

    public function actionSalaryForm($id = Null){
        $model = new EmployeeSalary();
        $employeeAllowance = new EmployeeSalaryAllowance;
        $employeeDeduction = new EmployeeSalaryDeduction;
        $employerDeduction = new EmployeeSalaryDeduction;
        if($id){
           $model = EmployeeSalary::find()->where(['id'=>$id])->one();
            $employeeAllowance = EmployeeSalaryAllowance::find()->where(['employee_id'=>$model->employee_id,'salary_id'=>$model->id])->all();
            $employeeDeduction = EmployeeSalaryDeduction::find()->where(['employee_id'=>$model->employee_id,'salary_id'=>$model->id,'type'=>1])->all();
            $employerDeduction = EmployeeSalaryDeduction::find()->where(['employee_id'=>$model->employee_id,'salary_id'=>$model->id,'type'=>2])->all();
        }
        if(Yii::$app->request->isPost){
            $formatter = Yii::$app->formatter;
            $month = "01-".Yii::$app->request->post()['EmployeeSalary']['month'];
            $employee_id = Yii::$app->request->post()['EmployeeSalary']['employee_id'];
            $month = $formatter->asDate($month,'php:Y-m-d');
            $model->month = date("Y-m-t",strtotime($month));
            $model = EmployeeSalary::find()->where(['employee_id'=>$employee_id,'month'=>$model->month])->one();
            $employee = Employee::findOne($employee_id);
            
            if(empty($model)){
                $model = new EmployeeSalary();
            }
            $allowances = Yii::$app->request->post()['allowance'];
            $employeeAllowance = EmployeeSalaryAllowance::loadData($allowances);
            $deductions = Yii::$app->request->post()['deduction'];
            if($employee->is_deduction == $employee::DEDUCTION_YES){
               $employeeDeduction = EmployeeSalaryDeduction::loadData(1,1,$deductions);
               $employerDeduction = EmployeeSalaryDeduction::loadData(1,2,$deductions);
            }
            if($employee->is_esi == $employee::DEDUCTION_YES){
               $employeeDeduction = array_merge($employeeDeduction,EmployeeSalaryDeduction::loadData(2,1,$deductions));
               $employerDeduction = array_merge($employerDeduction,EmployeeSalaryDeduction::loadData(2,2,$deductions));
            }
            if(!is_object($employeeDeduction))
            $employeeDeduction = array_filter($employeeDeduction);
            if(!is_object($employerDeduction))
            $employerDeduction = array_filter($employerDeduction);
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();  
            $model->load(Yii::$app->request->post()); 
            $model->month = date("Y-m-t",strtotime($month));
            if($model->save()){
                $flag = true;
                       EmployeeSalaryAllowance::deleteAll(['salary_id'=>$model->id]);
                if(!empty($allowances))
                foreach($allowances as $allowance){
                   $dataModel = EmployeeSalaryAllowance::find()
                   ->where(['allowance_id'=>$allowance['EmployeeSalaryAllowance']['allowance_id'],'salary_id'=>$model->id])
                   ->one();
                   if(empty($dataModel)){
                       $dataModel = new EmployeeSalaryAllowance;
                   } 
                   $dataModel->load($allowance);
                   $dataModel->salary_id = $model->id;
                   $dataModel->employee_id = $employee->id;
                   if(!$dataModel->save()){
                      $flag = false;
                   }
                }
                if(!empty($deductions))
                foreach($deductions as $deduction){
                   $dataModel = EmployeeSalaryDeduction::find()->where(['deduction_id'=>$deduction['EmployeeSalaryDeduction']['deduction_id'],'salary_id'=>$model->id])->one();
                   if(empty($dataModel)){
                        $dataModel = new EmployeeSalaryDeduction;
                   } 
                   $dataModel->load($deduction);
                   $dataModel->salary_id = $model->id;
                   $dataModel->employee_id = $employee->id;
                   if(!$dataModel->save()){
                      $flag = false;
                   }
                }
                $ledger = new Ledger;
                if(!$ledger->saveLedger($model->month,$employee->id,$model->id,"Salary",0,$model->payable_salary,$ledger::INOUT_DEBIT,$ledger::TYPE_EMPLOYEE,$model->company_id,$model->session,$ledger::FROM_EMPLOYEE_SALARY_PAGE)){
                     $flag = false;
                }
                if($flag){
                   $model->createPdf();    
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
            'employeeAllowance' => $employeeAllowance,
            'employeeDeduction' => $employeeDeduction,
            'employerDeduction' => $employerDeduction,
            'id'=>$id,
        ]);
    }

    public function actionSalaryRecord(){
        $searchModel = new EmployeeSalarySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->identity->isSelf()){
            $dataProvider->query->where(['id'=>Yii::$app->user->identity->employee->id]);
        }
        $dataProvider->query->orderBy(['id'=>SORT_DESC]);
        return $this->render('salary-record', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function getLeave($month,$days = [],$joining_date = null,$fixed_leave = 0){
        $totalDays = 0;
        $weekdays = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
        foreach($days as $day){
           $weekday = $weekdays[$day];
           $start = date("d",strtotime("first $weekday of ".date("M",strtotime($month))." ".date("Y",strtotime($month))));
           //if($joining_date){
           //    $start = $day != date("w",strtotime($joining_date))?date("d",strtotime($joining_date." next ".$weekday)):date("d",strtotime($joining_date));
           //}
           $end = date("t",strtotime($month));
           for($i = $start;$i <= $end;$i+=7){
               $totalDays++;
           }
        }
        return $totalDays;
    }
    
    public function getAllotedLeave($month,$days = [],$joining_date = null,$fixed_leave = 0){
        $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->count('*');
        if(date("m",strtotime($joining_date)) == date("m",strtotime($month))){
            $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->andWhere(['>','date',$joining_date])->count('*');
        }
        $totalDays = $fixed_leave+$holidays;
        $weekdays = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
        
        $employee = $this->actionParams['employee_id'];
        $leaves = EmployeeLeave::find()->where(['employee_id'=>$employee,'DATE_FORMAT(month,"%d-%m-%Y")'=>$month])->one();
       // echo $leaves->createCommand()->getRawSql();
        $leaves = json_decode( $leaves->leave, true );
        foreach($days as $day){
           $weekday = $weekdays[$day];
           $start = date("d",strtotime("first $weekday of ".date("M",strtotime($month))." ".date("Y",strtotime($month))));
           if($joining_date){
               $start = $day != date("w",strtotime($joining_date))?date("d",strtotime($joining_date." next ".$weekday)):date("d",strtotime($joining_date));
           }
           $end = date("t",strtotime($month));
           for($i = $start;$i <= $end;$i+=7){
               $totalDays++;
               //$before = date( "Y-m-d", strtotime("-1 day ". $i.date( "-m-Y",strtotime( $month ) ) ) );
               //$after = date( "Y-m-d", strtotime("+1 day". $i.date( "-m-Y",strtotime( $month ) ) ) );
               //if( !empty( $leaves ) && in_array(  $before,$leaves ) && in_array( $after ,$leaves ) ){
                 //  $totalDays--;
               //}
           }
        }
        return $totalDays;
    }
    
    public function getHolidays($month,$joining_date = null){
        $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->count('*');
        if(date("m",strtotime($joining_date)) == date("m",strtotime($month))){
            $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->andWhere(['>','date',$joining_date])->count('*');
        }
        return $holidays;
    }
    
    public function getEmployeeLeave($employee_id,$month,$days = [],$joining_date = null){
       
        $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->asArray()->all();
        if(date("m",strtotime($joining_date)) == date("m",strtotime($month))){
            $holidays = Holidays::find()->where(['DATE_FORMAT(date,"%Y-%m")'=>date("Y-m",strtotime($month))])->andWhere(['>','date',$joining_date])->asArray()->all();
        }
        $weekdays = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
        $weekoff = [];
        $end = date("t",strtotime($month));
        foreach($days as $day){
           $weekday = $weekdays[$day];
           $start = date("Y-m-d",strtotime("first $weekday of ".date("M",strtotime($month))." ".date("Y",strtotime($month))));
           if($joining_date){
               $start = $day != date("Y-m-d",strtotime($joining_date))?date("d",strtotime($joining_date." next ".$weekday)):date("d",strtotime($joining_date));
           }
           for($i = date("d",strtotime($start));$i <= $end;$i+=7){
               $weekoff[] = date("Y-m-",strtotime($month)).sprintf('%02d',$i);
           }
        }
        
        usort($holidays, array($this,"compareDates")); 
        usort($weekoff, array($this,"compareDates")); 
        
        $leave = employeeLeave::find()->where(['employee_id'=>$employee_id,'DATE_FORMAT(month,"%Y-%m")'=>date("Y-m",strtotime($month))])->one();
       
        $totalLeave = 0;
        $leaves = [];
        if(!empty($leave)){
           $leaves = json_decode($leave->comments,true);
           $leaveArray = [];
           if( !empty( $leaves ) ){
                $leaveArray = array_filter($leaves,function( $value,$key ) use ( $joining_date ){
                   return strtotime( $key ) > strtotime( $joining_date );
                },ARRAY_FILTER_USE_BOTH);
           }
           $totalLeave = count($leaveArray);
        }
        /*
        usort($leaves, array($this,"compareDates"));
        $offInWeek = count($days)+1;
        if($leaves && $weekoff){
          $start = date("d",strtotime($leaves[0]));
          while($start <= $end){
            $date = date("Y-m-",strtotime($month)).sprintf("%02d",$start);
            if(in_array($date,$leaves)){
              for($i = 1;$i <= $offInWeek; $i++){
                  $nextDate = date("Y-m-d",strtotime($date." +1 day"));
                  if($i == $offInWeek){
                     if(in_array($nextDate,$holidays)){
                        $offInWeek++; 
                     }else if(in_array($nextDate,$leaves)){
                        $totalLeave+=count($days); 
                     }
                  }
                  if(!in_array($nextDate,$weekoff)) break;
              }
            }  
            $start++;   
          }
        }
        */
        return $totalLeave;
    }
    
    public function compareDates($date1, $date2){
      if (strtotime($date1) < strtotime($date2))
         return -1;
      else if (strtotime($date1) > strtotime($date2))
         return 1;
      else
         return 0;
   }

    public function actionAddAllowance($employee_id){
     
        if(Yii::$app->request->isPost){
            
            $postData = Yii::$app->request->post()['EmployeeAllowance'];
            $flag = true;
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction(); 
            foreach($postData as $data){
                $allowance = new \app\models\EmployeeAllowance;
                if($data['EmployeeAllowance']['id']){
                    $allowance = \app\models\EmployeeAllowance::find()->where(['id'=>$data['EmployeeAllowance']['id']])->one();
                }
                $allowance->load($data);
                if(!$allowance->save()) $flag = false;
            }
            if($flag){
                $transaction->commit();
            	\Yii::$app->session->setFlash('success', 'Allowances added successfully.');
                return $this->redirect(['view', 'id' => $employee_id]);
            }else{
                $transaction->rollback();
            	\Yii::$app->session->setFlash('error', 'Errors');
                return $this->redirect(['view', 'id' => $employee_id]);
            }
            
        }
        
    }
    
    public function actionAjaxRecord($id){
        
        $model = $this->findModel($id);
        $document = new Document;
        $account = new Account;
        $credential = User::find()->where(['id'=>$model->user_id])->one();
        $allowances = $model->employeeAllowances;
        $permission = $model->getPermission();
        $erpmeta = new Erpmeta;

        return $this->renderAjax('view', [
            'model' => $model,
            'document'=>$document,
            'account'=>$account,
            'credential' => $credential,
            'permission' => $permission,
            'allowances' => $allowances,
            'erpmeta' => $erpmeta,
        ]);
        
    }
    
    public function actionCredential(){
      
      if (Yii::$app->request->post()) {
         
         $newData = Yii::$app->request->post()['User'];
         $employeeId = Yii::$app->request->post()['Employee']['id'];
         $model = User::findOne($newData['id']);
         $model->username = $newData['username'];
         $model->role = $newData['role'];
         
         if(!empty($newData['access_company'])){
            $model->access_company = json_encode($newData['access_company']);
         }else{
            $model->access_company = Null;
         }

         if(!empty($newData['password_hash'])){
            $model->setPassword($newData['password_hash']);
         }
         $model->save();
         $this->redirect(['employee/view','id'=>$employeeId]);
      }
        
    }
    
    public function actionPermission($user_id){
      
        if (Yii::$app->request->post()) {

            $permissionData = Yii::$app->request->post()['Permission'];
            //echo "<pre>";print_r($permissionData);die();
            $employee = Employee::find()->where(['user_id'=>$user_id])->one();

            foreach ($permissionData as $key => $value) {
                $permission = Permission::find()->where(['user_id'=>$user_id,'controller'=>$value['controller']])->one();
                if(empty($permission)){
                   $permission = new Permission;
                }
                $permission->user_id = $user_id;
                $permission->controller = $value['controller'];
                if(!empty($value['action'])){
                  $permission->action = json_encode($value['action']);
                }else{
                  $permission->action = NULL;    
                }

                $permission->save();
               
            }

            $this->redirect(['employee/view','id'=>$employee->id]);

        }
        
    }

    /**
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->identity->isSelf()){
            $dataProvider->query->where(['id'=>Yii::$app->user->identity->employee->id]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Employee model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $document = new Document;
        $account = new Account;
        $credential = User::find()->where(['id'=>$model->user_id])->one();
        $permission = $model->getPermission();
        $allowances = $model->employeeAllowances;
        $erpmeta = new Erpmeta;

        
        return $this->render('view', [
            'model' => $model,
            'document'=>$document,
            'account'=>$account,
            'credential' => $credential,
            'permission' => $permission,
            'allowances' => $allowances,
            'erpmeta' => $erpmeta,
        ]);
    }

    /**
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		
        $model = new Employee();

		$formatter = Yii::$app->formatter;
		
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 

        if ($model->load(Yii::$app->request->post())) {
			$model->emp_code = $model->employeeCode();

			$model->dob = $formatter->asDate($model->dob,"php:Y-m-d");
			$model->joining_date = $formatter->asDate($model->joining_date,"php:Y-m-d");
			if(!empty($model->leaves)){
			    $model->leaves = json_encode($model->leaves);
			}else{
			    $model->leaves = null;
			}
			
            $signup = $model->signup();
            
			if($model->validate() && $model->save()){
			    
			    $ledger = new \app\models\Ledger;
			    
                $type = $ledger::TYPE_EMPLOYEE;
			    $ledger_transaction1 = false;
			    $ledger_transaction2 = false;
			    
			    if($model->expense_type == $model::TRANSACTION_CREDIT)
			    $ledger_transaction1 = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Expense Opening Balance", $model->expense_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);
            		   
			    else $ledger_transaction1 = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Expense Opening Balance", 0, $model->expense_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);
				
			    if($model->personal_type == $model::TRANSACTION_CREDIT)
			    $ledger_transaction2 = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Personal Opening Balance", $model->personal_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);
            		   
			    else $ledger_transaction2 = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Personal Opening Balance", 0, $model->personal_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);

            	if($ledger_transaction1 && $ledger_transaction2 && $signup){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Employee has been Created Successfully. Employee Code is '.$model->emp_code);
                        return $this->redirect(['view', 'id' => $model->id]);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			}else{
				$errores = $model->getErrors();
                print_r($errores);die();
			}
			
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

		$formatter = Yii::$app->formatter;
		
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
		
        if ($model->load(Yii::$app->request->post())) {
			
			$model->dob = $formatter->asDate($model->dob,"php:Y-m-d");
			$model->joining_date = $formatter->asDate($model->joining_date,"php:Y-m-d");
			if(!empty($model->leaves)){
			    $model->leaves = json_encode($model->leaves);
			}else{
			    $model->leaves = null;
			}
			if($model->validate() && $model->save()){
			    
			    $ledger = new \app\models\Ledger;
			    $type = $ledger::TYPE_EMPLOYEE;
			    
			    $ledger_transaction = false;
			    
			    if($model->expense_type == $model::TRANSACTION_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Expense Opening Balance", $model->expense_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Expense Opening Balance", 0,$model->expense_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);
				
			    if($model->personal_type == $model::TRANSACTION_CREDIT)
			    $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Personal Opening Balance", $model->personal_balance, 0,
            		   $ledger::INOUT_CREDIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);
            		   
			    else $ledger_transaction = $ledger->saveLedger($model->joining_date, $model->id, $model->id, "Personal Opening Balance", 0,$model->personal_balance,
            		   $ledger::INOUT_DEBIT, $type,$model->emp_company, $model->session,$ledger::FROM_EMPLOYEE_PAGE);
            		   
            	if($ledger_transaction){
            	    
            	        $transaction->commit();
            	    	\Yii::$app->session->setFlash('success', 'Employee has been Updated Successfully. Employee Code is '.$model->emp_code);
                        return $this->redirect(['view', 'id' => $model->id]);
                        
            	}else{
            	    
            	      $transaction->rollback();
            	      
            	}	   
				
			
			}else{
				$errores = $model->getErrors();
                print_r($errores);die();
			}
			
        }
        if($model->joining_date){
            $model->joining_date = Yii::$app->formatter->asDate($model->joining_date,'php:d-m-Y');
        }
        if($model->dob){
            $model->dob = Yii::$app->formatter->asDate($model->dob,'php:d-m-Y');
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {

        $model = $this->findModel($id);
        $model->status = $model::STATUS_DELETE;
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionDeleteSalary($id)
    {

        $model = EmployeeSalary::findOne($id);

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction(); 
        $model->delete();
        $ledger = Ledger::find()->where(['transaction_id'=>$model->id,'entry_from'=>Ledger::FROM_EMPLOYEE_SALARY_PAGE])->one();
        if(!$ledger->delete()){
          $transaction->rollback();
          foreach($ledger->getErrors() as $error){
             \Yii::$app->session->setFlash('error', $error[0]);
          }
        }else{
            \Yii::$app->session->setFlash('success', "Salary Successfully Deleted");
            $transaction->commit();
        }
        return $this->redirect(['salary-record']);
    }

    public function actionStatus($id)
    {
        $employee = $this->findModel($id);
        $status = $employee->status == $employee::STATUS_ACTIVE?$employee::STATUS_DEACTIVE:$employee::STATUS_ACTIVE;
        $statusLabel = $employee->status == $employee::STATUS_ACTIVE?'Deactived':'Actived';
        $employee->status = $status;
        $employee->save();
        \Yii::$app->session->setFlash('success', $employee->emp_name.'-'.$employee->emp_code.' is '.$statusLabel);
        return $this->redirect(['view', 'id' => $employee->id]);
                        
    }


    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
