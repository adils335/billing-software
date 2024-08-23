<?php 
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
	<div class="col-sm-12">
	   <h2>Items</h2>
	   <hr>
	</div>
</div>

<div class="agreement-items">

<?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper',
        'widgetBody' => '.container-items',
        'widgetItem' => '.item',
        'limit' => 10,
        'min' => 1,
        'insertButton' => '.add-item',
        'deleteButton' => '.remove-item',
        'model' => $items[0],
        'formId' => 'DynamicForm',
        'formFields' => [
            'products_id',
        ],
    ]); ?>

    
    <div class="container-items"><!-- widgetContainer -->
        <?php foreach ($items as $index => $Item): ?>
            <div class="item panel"><!-- widgetBody -->
                    <?php
                        // necessary for update action.
                        if (!$modelStoreIndentsItems->isNewRecord) {
                            echo Html::activeHiddenInput($Item, "[{$index}]id",['class'=>'item_id']);
                        }
                    ?>

                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                        <?= $form->field($Item, "[{$index}]products_id")->widget(Select2::classname(), [
                            'data' => \yii\helpers\ArrayHelper::map(\app\models\StoreProducts::find()->orderBy('id')->asArray()->all(), 'id', 'name'),
                            'options' => ['placeholder' => 'Select a Item ...','class'=>'case-item'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>

                        </div>

                        <div class="col-lg-1 col-md-2 col-sm-3">
                            <button type="button" class="remove-item btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>

                    <!--Dynamic Fields End-->
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="button" class="add-item btn btn-success pull-right"><i class="glyphicon glyphicon-plus"></i>Add Items</button>
        </div>
    </div>
    <?php DynamicFormWidget::end(); ?>

</div>
