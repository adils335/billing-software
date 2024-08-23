<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\PurchaseProduct $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="purchase-product-form">

    <?php $form = ActiveForm::begin(['id'=>'DynamicForm']); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'session')->widget(Select2::classname(), [
               'data' => \yii\helpers\ArrayHelper::map(\app\models\Session::find()->orderBy('id')->asArray()->all(), 'session', 'session'),
               'options' => ['placeholder' => 'Select ...'],
               'pluginOptions' => [
                       'allowClear' => true
               ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'company_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'invoice_no')?>
        </div>
        <div class="col-md-6">
            <? if(!empty($model->invoice_date))
                    $model->invoice_date = Yii::$app->formatter->asDate($model->invoice_date,'php:d-m-Y');

            ?>
            <?= $form->field($model, 'invoice_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Enter Invoice Date '],
                'pluginOptions' => [
                'autoclose'=>true,
                        'format'=>'dd-mm-yyyy'
                    ]
            ]);?>
        </div>
    </div>

    <?=$this->render('form\_item',['items'=>$items,'form'=>$form]);?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 
$script = <<<JS
$(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    $(e.target).find('.container-items').find('.item:last').find('input').val('');
    $(e.target).find('.container-items').find('.item:last').find('select').val('').trigger('change');
});

JS;
$this->registerJs($script);
