<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm; 
use kartik\select2\Select2;
use yii\helpers\URL;
use yii\web\JsExpression;
?>
			   
<div class="row">
	<div class="col-md-3">
		<div class="callout callout-success callout-custom">
		   <p><strong><?=$model->getAttributeLabel('file_no')?>: <?=$model->file_no?></strong></p>
		</div>
	</div>
	<div class="col-md-3">
		<div class="callout callout-warning callout-custom">
		   <p><strong><?=$model->getAttributeLabel('status')?>: <?=$model->getStatusLabel()?></strong></p>
		</div>
	</div>
	<div class="col-md-3">
		<div class="callout callout-info callout-custom">
		   <p><strong><?=Yii::t('app', 'Session')?>: <?= $model->session?></strong></p>
		</div>
	</div>
	<div class="col-md-3">
		<div class="callout callout-primary callout-custom">
		   <p><strong><?=Yii::t('app', 'Date')?>: <?=Yii::$app->formatter->asDate($model->date, 'php:d.m.Y')?></strong></p>
		</div>
	</div>
</div>

<h4> <i class="fa fa-user margin-r-5"></i><?=Yii::t('app', 'Quotation Information')?></h4>       

<div class="row">
	
	<div class="col-md-3">
		<label for="">Quotation Name</label> : <?= $model->agreement_no?>
	</div>
	<div class="col-md-3">
		<label for=""><?=$model->getAttributeLabel('Contract Company')?></label> : <?= $model->contractCompany->name?>
	</div>
	<div class="col-md-3">
		<label for=""><?=$model->getAttributeLabel('State')?></label> : <?=$model->state->state?>
	</div>
	
	<div class="col-md-3">
		<label for=""><?=$model->getAttributeLabel('GST No')?></label> : <?=  $model->contract_company_gst?>
	</div>
</div>

