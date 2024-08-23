<?php
use yii\helpers\Html;
use yii\helpers\URL;
?>

			<span class="pull-right">
			            <?= Html::a('New Bill',['agreement-bill/create','agreement_id'=>$model->id],['class'=>'btn btn-warning']);?>
			      
			            <?= Html::a('Update',['create-quotation','step'=>'information','id'=>$model->id],['class'=>'btn btn-success']);?>
			</span>
	 