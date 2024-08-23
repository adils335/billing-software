<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Employee */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Generate Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Employees'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="generate-salary">
   <div class="generate-salary box box-primary"> 
        
        <div class="box-header with-border"> 

  
    <?php $form = ActiveForm::begin([
        'action' => ['generate-salary'],
        'method' => 'get',
    ]); ?>
    <div class="row">

        <div class="col-md-3">
    
             <?=  $form->field($model, 'month')->widget(DatePicker::classname(), [
                  'options' => ['placeholder' => 'Select Month ...','autocomplete'=>"off"],
                  'pluginOptions' => [
                  'autoclose'=>true,
                        'format'=>'mm-yyyy',
                        'minViewMode'=>'months',
                  ]
             ]); ?>

        </div>
   
	
        <div class="col-md-9"><br>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Generate'), ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
                <?= Html::a(Yii::t('app', 'New Salary'), ['salary-form'], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'Extra Salary'), ['extra-salary'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
</div>
