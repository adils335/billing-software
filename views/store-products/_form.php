<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var app\models\StoreProducts $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="store-products-form">
<?php $form = ActiveForm::begin(['id' => 'dynamicform']); ?>

<?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 10,
        'min' => 0,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $model[0],
        'formId' => 'dynamicform',
        'formFields' => [
            'name',
            'uom_id',
            'company_id',
        ],
    ]); ?>

    <div class="container-items"><!-- widgetContainer -->
        <?php foreach ($model as $index => $items): ?>
            <div class="item panel"><!-- widgetBody -->

                <div class="">
                    <?php
                        // necessary for update action.
                        if (!$items->isNewRecord) {
                            echo Html::activeHiddenInput($items, "[{$index}]id");
                        }
                    ?>

                    <div class="row">
                        
                        <div class="col-md-3">
                            <?= $form->field($items, "[{$index}]name")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-3">
                            <?= $form->field($items, "[{$index}]uom_id")->widget(Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(\app\models\Uom::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                                'options' => ['placeholder' => 'Select a Uom ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ]); 
                            ?>
                        </div>
                        <div class="col-md-3">
                        <?= $form->field($items, "[{$index}]company_id")->widget(Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(\app\models\Company::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                                'options' => ['placeholder' => 'Select a Company ...'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                                ]); 
                            ?>
                        </div>
                        <div class="col-lg-1">
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>

                </div>
            </div>
    </div>
<?php endforeach; ?>
</div>
    <div class="row">
        <div class="col-md-12 ">
            <button type="button" class="add-item btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i>Add Items</button>
        </div>
    </div>
    <br>
    <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
