<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
$formatter = \Yii::$app->formatter;
/* @var $this yii\web\View */
/* @var $model app\models\Employee */

\yii\web\YiiAsset::register($this);
?>
<?php $form = ActiveForm::begin([
      'id' => 'cancel-irn-form',
      'action' => Url::to(['einvoice/cancel-irn'])]);
?>
<div class="row">
    <div class="col-md-12">
        <h3>Invoice No:<?= $model->invoiceNo;?></h3>
        <h3>Invoice date:<?= $formatter->asDate( $model->invoice_date,'php:d-m-Y' );?></h3>
    </div>
    <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'irn_no')->hiddenInput()->label(false); ?>
    <div class="col-md-12">
         <?= $form->field($model, 'cancel_reason')->dropdownList($model::buildCancelReason(),['prompt'=>'select']); ?>
    </div>     
    <div class="col-md-12">
         <?= $form->field($model, 'cancel_remarks')->textInput(['maxlength' => true]); ?>
    </div> 
</div>
<div class="form-group">
    <?php echo Html::a('<i class="fa fa-times" aria-hidden="true"></i>Cancel',
        ['#'],['bill_id'=>$model->id ,'class'=>'cancel-irn-btn btn btn-danger','title'=>'Cancel Irn']);
    ?>
</div>
<?php ActiveForm::end(); ?>