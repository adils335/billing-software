<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use kartik\nav\NavX;
use yii\helpers\Url;
//use \yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $wizard app\components\Wizard */
 
$first_step = $wizard->steps['information']['title'];
$second_step = $wizard->steps['rate-schedule']['title'];
//$third_step = $wizard->steps['sites']['title'];
$forth_step = $wizard->steps['tax']['title'];
//$fifth_step = $wizard->steps['vendor']['title'];

if(!empty($_GET['id'])) 
  $agreement=\app\models\Agreement::findOne($_GET['id']);
else $agreement= new \app\models\Agreement;
$flag=false;

$url = Url::to(['quotation/create-quotation']);
if(!empty($_GET['id'])){
    $url = Url::to(['quotation/create-quotation','id'=>$agreement->id]);
}
if(!empty($_GET['step']))
	 $url = Url::to(['quotation/create-quotation','step'=>$_GET['step'],'id'=>$agreement->id]);
            $stepData = $wizard->_getSession('stepData');
?>
<div class="agreement-create">
    <div class="agreement-form">
        
        <?php $form = ActiveForm::begin([
		            'id' => $wizard->currentFormId,
					'action' => $url,
			]); ?>
		<div class="agreement-nav-pills">
			<ul id="navigation-pills" class="nav nav-pills">
				<li class="<?=($wizard->currentStep=='information')?'active':''?>">
					<?= Html::submitButton($first_step, ['class' => 'btn btn-default btn-flat ', 'name' => 'step', 'value' => 'information']);
					?>
					
				</li>
				<li class="<?=($wizard->currentStep=='rate-schedule')?'active':''?>">
					<?= Html::submitButton($second_step, ['class' => 'btn btn-default btn-flat ', 'name' => 'step', 'value' => 'rate-schedule']);
					?>
				</li>
				<li class="<?=($wizard->currentStep=='tax')?'active':''?>">
				    <?= Html::submitButton($forth_step, ['class' => 'btn btn-default btn-flat', 'name' => 'step', 'value' => 'tax']);
				?>
				<!--
				<li class="<?//=($wizard->currentStep=='sites')?'active':''?>">
					<?//= Html::submitButton($third_step, ['class' => 'btn  btn-flat ', 'name' => 'step', 'value' => 'sites']);
					?>
				</li>
				

				<li class="<?//=($wizard->currentStep=='vendor')?'active':''?>">
				    <?//= Html::submitButton($fifth_step, ['class' => 'btn btn-success btn-flat', 'name' => 'step', 'value' => 'vendor']);?>
				</li>-->
			</ul>        
		</div>

        <div class="box box-primary">
            <?= $this->render('/'.$wizard->getView().'//'.$wizard->steps[$wizard->currentStep]['view'], array_merge($wizard->data, ['form'=>$form, 'current_step'=>$wizard->currentStep, 'stepData' => $stepData])) ?>
        </div>
        <div class="box-footer"> 
            <?php
                echo Html::beginTag('div', ['class' => 'form-row buttons']); 
                echo Html::a('<button type="button" class="btn btn-danger btn-flat"><i class="fa fa-times"></i>&nbsp;'.Yii::t('app', 'Cancel').'</button>', ['quotation/view','id'=>$wizard->data['quotation']->id]);
                echo "&nbsp;";
                echo Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp;'.Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-flat submit', 'name' => 'save-step', 'value' => 'save']);
                if($wizard->currentStep != 'information'){
                    
                    echo "&nbsp;";
                    echo Html::submitButton('<i class="fa fa-arrow-circle-left"></i>&nbsp;'.Yii::t('app', 'Previous'), ['class' => 'btn btn-info btn-flat', 'name' => 'prev', 'value' => 'Previous']);
                }  
                //echo Html::submitButton(Yii::t('app', 'Prev'), ['class' => 'btn btn-success btn-flat', 'name' => 'prev', 'value' => 'prev']);
                if($wizard->currentStep == 'tax'){
                    //echo Html::beginTag('div', ['class' => 'form-row submit-buttons pull-right']); 
					if($flag){
				?>
					<button type="button" class="btn btn-success btn-flat " data-toggle="modal" data-target="#myModal"><i class="fa fa-floppy-o"></i>&nbsp;<?=Yii::t('app', 'Save Quotation')?></button>
				<?php 
					}else
                    echo "&nbsp;";
                    echo Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp;'.Yii::t('app', 'Save Quotation'), ['class' => 'btn btn-success btn-flat price-submit', 'name' => 'save', 'value' => 'save']);
                }else {

                    echo "&nbsp;";
                    echo Html::submitButton('<i class="fa fa-arrow-circle-right"></i>&nbsp;'.Yii::t('app', 'Next'), ['class' => 'btn btn-primary btn-flat pull-right', 'name' => 'next', 'value' => 'next']);
                }  
                
                //echo Html::endTag('div');
            ?> 
        </div>
	  <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS
$('#location-address_form').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
}); 
JS;
$this->registerJs($script); 