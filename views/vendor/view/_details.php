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
                <h3 class="box-title"><?=Yii::t('app', 'Vendor Details')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Code : </strong> <?= $model->code?></div>
                      
                      <div class="col-md-3"><strong>Name : </strong> <?= $model->name?></div>
                      
                      <div class="col-md-3"><strong>Company : </strong> <?= $model->company->name?></div>
                      
                      <div class="col-md-3"><strong>Date : </strong> <?= $formatter->asDate($model->created_at,'php:d.m.Y') ?></div>
                      
                  </div>
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Status : </strong> <?= $model->statusLabel?></div>
                      
                      <div class="col-md-3"><strong>Session : </strong> <?= $model->session?></div>
                      
                      <div class="col-md-3"><strong>Balance : </strong> <?= $model->last_balance." ".$model->BalanceTypeLabel?></div>
                      
                      <div class="col-md-3"></div>

                  </div>
                  
                  <div class="row">
                      
                      <div class="col-md-3"><strong>Father : </strong> <?= $model->father_name?></div>
                      
                      <div class="col-md-3"><strong>Mobile : </strong> <?= $model->mobile?></div>
                      
                      <div class="col-md-3"><strong>Email : </strong> <?= $model->email?></div>

                      <div class="col-md-3"><strong>Pancard No : </strong> <?= $model->pancard_no?></div>
                      
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