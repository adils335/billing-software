<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\VendorWorkRate */

$this->title = $record->vendor->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Work Rates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="vendor-work-rate-view">

<div class="row">
    <div class="col-md-12">
        <div class="employee-details box">
            <div class="box-header with-border">
                
               
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'vendor_id' => $record->vendor_id,'work_type'=> $record->work_type], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'vendor_id' => $record->vendor_id,'work_type'=> $record->work_type], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>


                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
             <div class="box-body">  
                  
                  <div class="row">
                     <div class="col-md-12">
                         <div class="col-md-6"><strong>Vendor Name : </strong><?= $record->vendor->name?></div>
                         <div class="col-md-6"><strong>Work Type : </strong><?= $record->workType->name?></div>
                     </div>
                  </div>

                  <br>
                  
                  <? foreach($model as $key => $work):?>
                  <div class="row">
                     <div class="col-md-12">
                         <div class="col-md-6"> Work Name : <?= $work->workName->name?> </div>
                         <div class="col-md-6"> Rate : <?= $work->rate?> </div>
                     </div>
                  </div>
              <? endforeach;?>

            </div>   

        </div>   
    </div>   
</div>

</div>