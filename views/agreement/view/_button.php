<?php
use yii\helpers\Html;
use yii\helpers\URL;
?>
<p>
<span class="pull-left">
            <?= Html::a('Update',['create-agreement','step'=>'information','id'=>$model->id],['class'=>'btn btn-success']);?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
            <?php 
            $actionUrl = Url::to('../agreement/change-status');
            $html = "<select class='agreement-action' id='" . $model->id . "' url='$actionUrl' refresh='0'>";
            $statuses = $model::buildStatus();
                foreach( $statuses as $key => $status ){
                    $selected = $key == $model->status ? "selected":"";
                    $html .= "<option value='$key' $selected>$status</option>";
                }
            $html .= "</select>";
            echo $html;
            ?>
        
</span>

<span class="pull-right">
             <?= Html::a(Yii::t('app', 'Document'), ['#'], [
                               'class' => 'btn btn-warning',
                               'data-toggle' => 'modal',
                               'data-target' =>'#document-modal',
                     ]) ?> 
            <?= Html::a('View',['agreement/view','id'=>$model->id],['class'=>'btn btn-info']);?>
            <?= Html::a('New Bill',['agreement-bill/create','agreement_id'=>$model->id],['class'=>'btn btn-warning']);?>
            <?= Html::a('Bill Back',['agreement/bill-back','agreement_id'=>$model->id],['class'=>'btn btn-primary']);?>
      <?php if(! $model->agreementRateSchedule){?>
            <?= Html::a('Rate & Schedule',['agreement-rate-schedule/create','agreement_id'=>$model->id],['class'=>'btn btn-info']);?>
	  <?php }else{?>
            <?= Html::a('Rate & Schedule',['agreement-rate-schedule/update','agreement_id'=>$model->id],['class'=>'btn btn-info']);?>
	  <?php }?>		
</span>
</p>	 