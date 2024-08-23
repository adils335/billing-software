<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var app\models\AgreementProduct $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="agreement-product-form">

    <?php $form = ActiveForm::begin(['id'=>'DynamicForm']); ?>

    <div class = "row">
        <div class="col-md-6 col-sm-6 col-sm-12">
            <?= $form->field($model, 'state_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\State::find()->orderBy('id')->asArray()->all(), 'id', 'state'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>
        <div class="col-md-6 col-sm-6 col-sm-12">
            <?= $form->field($model, 'district_id')->widget(Select2::classname(), [
                'data' => \yii\helpers\ArrayHelper::map(\app\models\District::find()->orderBy('id')->asArray()->all(), 'id', 'district'),
                'options' => ['placeholder' => 'Select ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?> 
        </div>
    </div>

    <div class = "row">
        <div class="col-md-6 col-sm-6 col-sm-12">
            <?= $form->field($model, 'billing_company_id')->widget(Select2::classname(), [
                    'data' =>\yii\helpers\ArrayHelper::map(\app\models\ContractCompany::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?> 
        </div>
        <div class="col-md-6 col-sm-6 col-sm-12">
            <?= $form->field($model, 'agreement_id')->widget(Select2::classname(), [
                    'data' =>\yii\helpers\ArrayHelper::map(\app\models\Agreement::find()->orderBy('id')->asArray()->all(), 'id', 'agreement_no'),
                    'options' => ['placeholder' => 'Select ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
            ]); ?> 
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

?>
