<?php

namespace app\controllers;

use Yii;
use app\models\EmployeeLeave;
use app\models\Employee;
use app\models\Search\EmployeeLeave as EmployeeLeaveSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmployeeLeaveController implements the CRUD actions for EmployeeLeave model.
 */
class EmployeeLeaveController extends Controller
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
                ],
            ],
        ];
    }
    public function actionAjaxMarkLeave($employee,$date,$comment){
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response['status'] = "error";
        $response['error'] = "Comment Should be filled";
        if(!empty($employee) && !empty($date) && !empty($comment)){
             $response['status'] = "success";
             $leaveModel = EmployeeLeave::find()->where(['employee_id'=>$employee,'month'=>date("Y-m-01",strtotime($date))])->one();
             if(!empty($leaveModel)){
                if(!empty($leaveModel->leave)){
                   $leave = json_decode($leaveModel->leave,true); 
                   $leave[] = $date;
                   $leave = array_unique($leave);
                   $leaveModel->leave = json_encode($leave);
                   $commentArray = json_decode($leaveModel->comments,true); 
                   $commentArray[$date] = $comment;
                   $leaveModel->comments = json_encode($commentArray);
                   $leaveModel->save();
                }else{
                   $leaveModel->leave = json_encode([$date]);
                   $leaveModel->comments = json_encode([$date=>$comment]);
                   $leaveModel->save();
                }
             }else{
                $leaveModel = new EmployeeLeave;
                $leaveModel->employee_id = $employee;
                $leaveModel->month = date("Y-m-01",strtotime($date));
                $leaveModel->leave = json_encode([$date]);
                $leaveModel->comments = json_encode([$date=>$comment]);
                $leaveModel->save();
             }
        }
        return $response;
    }
    
    public function actionAjaxRemoveLeave($employee,$date){
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response['status'] = "error";
        $response['error'] = "Employee Should be selected";
        if(!empty($employee) && !empty($date)){
             $response['status'] = "success";
             $leaveModel = EmployeeLeave::find()->where(['employee_id'=>$employee,'month'=>date("Y-m-01",strtotime($date))])->andWhere(['IS NOT','leave',NULL])->one();
             if(!empty($leaveModel)){
                $leave = json_decode($leaveModel->leave,true); 
                $leave = array_diff($leave,[$date]);
                if(!empty($leave))
                  $leaveModel->leave = json_encode($leave);
                else $leaveModel->leave = NULL;  
                
                $comments = json_decode($leaveModel->comments,true); 
                unset($comments[$date]);
                if(!empty($comments)){
                  $leaveModel->comments = json_encode($comments);
                  $leaveModel->save();
                }else $leaveModel->delete();
             }
        }
        return $response;
    }
    
    public function actionAjaxComment($employee,$date){
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $response['status'] = "error";
        $response['error'] = "Employee Should be selected";
        if(!empty($employee) && !empty($date)){
             $response['status'] = "success";
             $response['comment'] = "";
             $getComment = EmployeeLeave::find()->where(['employee_id'=>$employee,'month'=>date("Y-m-01",strtotime($date))])->andWhere(['IS NOT','leave',NULL])->one();
             if(!empty($getComment)){
                $comments = json_decode($getComment->comments,true); 
                if(!empty($comments[$date]))
                $response['comment'] = $comments[$date];
             }
        }
        return $response;
    }
    public function actionAjaxFilterLeave($id,$start_month = null,$end_month = null){
        
        $formatter = Yii::$app->formatter;
        if(!empty($start_month)) $start_month = $formatter->asDate("01-".$start_month,'php:Y-m-d');
        if(!empty($end_month)) $end_month = $formatter->asDate("01-".$end_month,'php:Y-m-d');
        $model = EmployeeLeave::filter_leave($id,$start_month,$end_month);
        $employee = \app\models\Employee::find()->all();
        return $this->renderAjax('filter-leave',[
               'model' => $model,
               'employees'=>$employee,
        ]); 
    }
    
    public function actionAjaxDefaultLeave($id){
        Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
        $current = date("Y-m-01");
        $secondLast = date("Y-m-01",strtotime($month." -2 month"));
        $last = date("Y-m-01",strtotime($month." -1 month"));
        $next = date("Y-m-01",strtotime($month." +1 month"));
        $secondNext = date("Y-m-01",strtotime($month." +2 month"));
        $month = [$secondLast,$last,$current,$next,$secondNext];
        $models = EmployeeLeave::find()->where(['employee_id'=>$id,'month'=>$month])->andWhere(['IS NOT','leave',NULL])->all();
        $leaves = [];
        foreach($models as $model){
            $leaves = array_merge($leaves,json_decode($model->leave,1));
        }
        if(!empty($leaves)){
            return $leaves;
        }
        return Null;
    }
    
    public function actionMarkLeave(){
      $postData = Yii::$app->request->post()['EmployeeLeave'];
      $monthlyLeaves = EmployeeLeave::month_wise_leave($postData['leave']);
      $employee_id = $postData['employee_id'];
      $comment = !empty($postData['comments'])?$postData['comments']:Null;
      //EmployeeLeave::resetLeave($employee_id);
      
      $connection = \Yii::$app->db;
      $transaction = $connection->beginTransaction(); 
      $flag = true;
      foreach($monthlyLeaves as $month => $leavesArray){
          $leaves = [];
          $comments = [];
          $leaveModel = EmployeeLeave::find()->where(['employee_id'=>$employee_id,'month'=>$month])->one();
          if(empty($leaveModel)){
              $leaveModel = new EmployeeLeave;
          }else{
              $leaves = json_decode($leaveModel->leave,true);
              $comments = json_decode($leaveModel->comments,true);
          }
          foreach($leavesArray as $leave){
              if(empty($comments[$leave])){
                $comments[$leave] = $comment;
                $leaves[] = $leave;
              }
          }
          $leaves = json_encode($leaves);
          $comments = json_encode($comments);
          $loadArray['EmployeeLeave'] = ['employee_id'=>$employee_id,'month'=>$month,'leave'=>$leaves,'comments'=>$comments];
          $leaveModel->load($loadArray);
          if(!$leaveModel->save()){
              $flag = false;
          }
      }
      if($flag){
         $transaction->commit(); 
         Yii::$app->session->setFlash("success","Marked leave successfully.");
         $this->redirect(['employee-leave/index']);
      }else{
         $transaction->rollback(); 
         Yii::$app->session->setFlash("error",json_encode($leaveModel->errors));
         $this->redirect(['employee-leave/index']);
      }
    }
    
    public function actionLeave(){
        
        $employees = Employee::find()->where(['status'=>Employee::STATUS_ACTIVE])->all();
        $employeeLeave = new EmployeeLeave;
        
        return $this->render('leave', [
            'employees' => $employees,
            'model' => $employeeLeave,
        ]);
    }
    
    /**
     * Lists all EmployeeLeave models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeLeaveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(Yii::$app->user->identity->isSelf()){
            $dataProvider->query->where(['employee_id'=>Yii::$app->user->identity->employee->id]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EmployeeLeave model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new EmployeeLeave model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EmployeeLeave();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EmployeeLeave model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EmployeeLeave model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EmployeeLeave model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EmployeeLeave the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EmployeeLeave::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
