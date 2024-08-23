<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = $model->ref_no;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payment-view">
   <div class="payment-index box box-primary"> 
		
    <div class="box-body">

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'ref_no' => $model->ref_no], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'ref_no' => $model->ref_no], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
 			   
    <div class="row">
	    <div class="col-md-3">
		   <div class="callout callout-success callout-custom">
		       <p><strong><?=$model->getAttributeLabel('ref_no')?>: <?=$model->ref_no?></strong></p>
		   </div>
        </div>
	    <div class="col-md-5">
		   <div class="callout callout-warning callout-custom">
		       <p><strong><?=$model->getAttributeLabel('company_id')?>: <?=$model->company->name?></strong></p>
		   </div>
	    </div>
	    <div class="col-md-2">
		   <div class="callout callout-info callout-custom">
		       <p><strong><?=Yii::t('app', 'status')?>: <?= $model->status?></strong></p>
		   </div>
	    </div>
	    <div class="col-md-2">
		   <div class="callout callout-warning callout-custom">
		       <p><strong><?=Yii::t('app', 'Session')?>: <?= $model->session?></strong></p>
		   </div>
	    </div>
    </div>

    <h4> <i class="fa fa-user margin-r-5"></i><?=Yii::t('app', 'Payment Information')?></h4>       
  
    <div class="row">
	   <div class="col-md-4">
		  <label for=""><?=$model->getAttributeLabel('Date')?></label> : <?= Yii::$app->formatter->asDate($model->date,"php:d-m-Y")?>
	   </div>
	   <div class="col-md-4">
		  <label for=""><?=$model->getAttributeLabel('from_head')?></label> : <?=$model->fromHeadLabel?>
	   </div>
	   <div class="col-md-4">
		  <label for=""><?=$model->getAttributeLabel('Payment By')?></label> : <?=  $model->fromAccount?>
	   </div>
    </div>
	
    <div class="row">
	  
      <div class="col-md-12">
	
	  <table class="table">
	     <thead>
		    <tr>
			   <th>S.No</th>
			   <th>Head</th>
			   <th>Payment To</th>
			   <th>Particular</th>
			   <th>Amount</th>
			</tr>
		 </thead>
		 <tbody>
	  
	<?php 
	$sn = 1;
	foreach($payment as $paymentTo):?>
	         
		    <tr>
			   <td><?= $sn++;?></td>
			   <td><?=$paymentTo->paymentHeadLabel?></td>
			   <td><?=$paymentTo->toAccount?></td>
			   <td><?=$paymentTo->particular?></td>
			   <td><?=$paymentTo->net_amount?></td>
			</tr>
			<?php
            $histories = $paymentTo->history;
            if( !empty( $histories ) ):
            foreach($histories as $history):
            $meta = json_decode( $history->meta ,true);
            $oldPayment = clone $paymentTo;
            $oldPayment->oldHistory($meta);
            $newPayment = clone $paymentTo;
            $newPayment->newHistory($meta);
            //echo "<pre>";print_r( $meta );die();?>
		        <tr class="bg-warning">
			       <td>&nbsp;</td>
			       <td><?= isset( $meta['to_head'] )?$oldPayment->paymentHeadLabel . ' to ' .$newPayment->paymentHeadLabel : '';?></td>
			       <td><?= isset( $meta['to_account'] )?$oldPayment->toAccount . ' to ' .$newPayment->toAccount : '';?></td>
			       <td><?= isset( $meta['particular'] )?$oldPayment->particular . ' to ' .$newPayment->particular : '';?></td>
			       <td><?= isset( $meta['net_amount'] )?$oldPayment->net_amount . ' to ' .$newPayment->net_amount : '';?></td>
			    </tr>
            <?php endforeach; 
            endif;?>
    <?php endforeach;?>
	
		 </tbody>
      </table>
	  
     </div>
    </div>
</div>
</div>
</div>
