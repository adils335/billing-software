<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$statusLabel = $model->status == ($model::STATUS_ACTIVE)?"Active":"Deactive";
$statusColor =  $model->status == ($model::STATUS_ACTIVE)?"success":"danger";
?>

	 <div class="row">
	 
		          <div class="col-md-12">
				     <div class="pull-right">
                      <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                     <?= Html::a(Yii::t('app', $statusLabel), ['status', 'id' => $model->id], [
                               'class' => 'btn btn-'.$statusColor,
                               'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure you want to change status?'),
                                        'method' => 'post',
                               ],
                     ]) ?>  
                     <?= Html::a(Yii::t('app', 'Document'), ['#'], [
                               'class' => 'btn btn-warning',
                               'data-toggle' => 'modal',
                               'data-target' =>'#document-modal',
                     ]) ?> 
                     
                     <?= Html::a(Yii::t('app', 'Account'), ['#'], [
                               'class' => 'btn btn-info',
                               'data-toggle' => 'modal',
                               'data-target' =>'#account-modal',
                     ]) ?> 
                      <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                               'class' => 'btn btn-danger',
                               'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                               ],
                     ]) ?> 
					 </div>
				  </div>
			  
	 </div>
