<?php
use yii\bootstrap\Html; 
use yii\Helpers\Url; 
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2; 
use wbraganca\dynamicform\DynamicFormWidget;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Bill Back');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agreement'), 'url' => ['view','id'=>$agreement->id]];
$this->params['breadcrumbs'][] = $this->title;

/* @var $this yii\web\View */
/* @var $model app\models\AgreementBillBack */
/* @var $form yii\widgets\ActiveForm */
$isNew = 0;
?>

<div class="bill-back-form">
<div class="bill-back box box-primary">
    <div class="box-header">
        <?php Pjax::begin([
    'id' => 'pjax-bill-back',
    'timeout' => false
]);?>
    <?php $form = ActiveForm::begin([
	  'id' => 'bill-back',
      'options' => ['data-pjax' => true ]
	]); ?>
        <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', 
                'widgetBody' => '.container-items', 
                'widgetItem' => '.item', 
                'limit' => 99, 
                'min' => 1, 
                'insertButton' => '.add-item', 
                'deleteButton' => '.remove-item', 
                'model' => $model[0],
                'formId' => 'bill-back',
                'formFields' => [
                    'agreement_id',
                    'type',
                    'sno',
                    'description',
                ],
            ]); ?>

            <div class="container-items"><!-- widgetContainer -->
            <?php foreach ($model as $i => $rate): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    
                    <div class="panel-body">
                        <?php
                            // necessary for update action.
                            if (! $rate->isNewRecord) {
                                echo Html::activeHiddenInput($rate, "[{$i}]id");
                            }else{
                                $isNew = 1;
                            }
                        ?>
                        <?= $form->field($rate, "[{$i}]agreement_id")->hiddenInput(['value'=>$agreement->id,'class'=>'form-control agreement-id'])->label(false); ?>
                        <div class="row">
                            <div class="col-sm-12 type-div" style="display:<?= $isNew?:"none"?>">
                                <?= $form->field($rate, "[{$i}]type")->widget(Select2::classname(), [
                               'data' => \yii\helpers\ArrayHelper::map(\app\models\BillBackMaster::find()->select(['srmid','type'])->distinct()->orderBy('type')->all(), 'srmid', 'type'),
                               'options' => ['placeholder' => 'Select ...','class'=>'type'],
                               'pluginOptions' => [
                                            'allowClear' => true
                                    ],
                              ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-1">
                             <?= $form->field($rate, "[{$i}]sno")->textInput(['class'=>'sequence sequence-input']); ?>
                            </div>
                            <div class="col-sm-10 no-padding">
							<?= $form->field($rate, "[{$i}]description")->textarea(['rows'=>3,'placeholder'=>'Description'])->label(false); ?>
                            </div>
							<div class="col-sm-1 no-padding">
                                <div class="pull-right">
                                   <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                            </div>
                            
                        </div><!-- .row -->
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="row">
                <div class="col-sm-12">
                     <button type="button" class="add-item btn btn-info pull-right"><i class="glyphicon glyphicon-plus"></i>Add</button>
                </div>
            </div>
            <?php DynamicFormWidget::end(); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

<?php Pjax::end();?>
</div>
</div>
</div>


	<?php 
	   $url = Url::to(['bill-back','agreement_id'=>$agreement->id]);
       $srcipt = <<< JS

       hideType();
         $('.dynamicform_wrapper').on('afterInsert', function (e, item) {
            $('.agreement-id').val($('#agreementbillback-0-agreement_id').val());
            hideType();
             sortItem();
         });
       
        if($isNew){
           $(document).on('change','.type:first', function() {
               var type = encodeURIComponent($(this).val());
               var url = '$url'+'&type='+type;
               $.pjax.reload({container: '#pjax-bill-back',url:url});  //Reload
           });
        }

        $(document).on("pjax:success", function() {
           hideType();
           });

         function hideType(){

            $('.type-div').not(':first').hide();
            var type = $('.type').eq(0).val();
            $('.type').not(':first').val(type).trigger('change');

         }
         sortItem();
        
         $('.container-items').sortable({
              items: '.item:not(.item:first-child)',
              cursor: 'pointer',
              axis: 'y',
              dropOnEmpty: false,
              start: function (e, ui) {
                  ui.item.addClass('selected');
              },
              stop: function (e, ui) {
                  ui.item.removeClass('selected');
                  $(this).find('.item').each(function (index) {
                      if (index > 0) {
                          $(this).find('.sequence').val(index+1);
                      }
                  });
              }
         });
         
         function sortItem(){
            $('.sequence').each(function(index){
               $(this).val(index+1);
            });
         }


       JS;

	   $this->registerJs($srcipt);
	   