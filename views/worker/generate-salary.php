<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Search\Worker */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Generate Salary');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Workers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="generate-salary">
   <div class="generate-salary box box-primary"> 
        
        <div class="box-header with-border"> 

  <div class="row">
    <?php $form = ActiveForm::begin([
        'action' => ['generate-salary'],
        'method' => 'get',
    ]); ?>

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
           <?= Html::a(Yii::t('app', 'New'), ['salary-form'], ['class' => 'btn btn-success']) ?>
       </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

</div>
</div>
</div>
