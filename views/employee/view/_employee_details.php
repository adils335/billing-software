<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
?>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="employee-details box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Employee Details')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Code : </strong> <?= $model->emp_code?></div>
                      
                      <div class="col-md-3"><strong>Name : </strong> <?= $model->emp_name?></div>
                      
                      <div class="col-md-3"><strong>Company : </strong> <?= $model->empCompany->name?></div>
                      
                      <div class="col-md-3"><strong>Joining Date : </strong> <?= $formatter->asDate($model->joining_date,'php:d.m.Y') ?></div>
                      
                  </div>
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Role : </strong> <?= $model->address?></div>
                      
                      <div class="col-md-3"><strong>Aadhar No : </strong> <?= $model->aadhar?></div>
                      
                      <div class="col-md-3"><strong>Pancard No : </strong> <?= $model->pancard?></div>
                      
                      <div class="col-md-3"><strong>Refference : </strong> <?= $model->refference?></div>
                      
                  </div>
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Status : </strong> <?= $model->statusLabel?></div>
                      
                      <div class="col-md-3"><strong>Salary : </strong> <?= $model->salary?></div>
                      
                      <div class="col-md-3"><strong>Personal : </strong> <?= $model->personal_balance." ".$model->personalTypeLabel?></div>
                      
                      <div class="col-md-3"><strong>Expense : </strong> <?= $model->expense_balance." ".$model->expenseTypeLabel?></div>
                      
                  </div>
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Father : </strong> <?= $model->father_name?></div>
                      
                      <div class="col-md-3"><strong>Mobile : </strong> <?= $model->mobile?></div>
                      
                      <div class="col-md-3"><strong>Email : </strong> <?= $model->email?></div>
                      
                      <div class="col-md-3"><strong>DOB : </strong> <?= $formatter->asDate($model->dob,'php:d.m.Y') ?></div>
                      
                  </div>
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Address : </strong> <?= $model->address?></div>
                      
                      <div class="col-md-3"><strong>District : </strong> <?= $model->state->state?></div>
                      
                      <div class="col-md-3"><strong>State : </strong> <?= $model->district->district?></div>
                      
                      <div class="col-md-3"><strong>Pincode : </strong> <?= $model->pincode?></div>
                      
                  </div>
                  
			</div>   
		</div>   
	</div>   
</div>   