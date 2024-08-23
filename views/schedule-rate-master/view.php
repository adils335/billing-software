<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ScheduleRateMaster */

$this->title = "Schedule Rate Master";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schedule Rate Masters'), 'url' => ['index']];
\yii\web\YiiAsset::register($this);
$count = 0;
?>
<div class="schedule-rate-master-view">
<div class="schedule-rate-master-index box box-primary"> 
        
        <div class="box-header with-border"> 
        <table class="table">
            <thead>
                <tr>
                    <th width="3%">#</th>
                    <th width="85%">Item</th>
                    <th width="6%">Hsn No</th>
                    <th width="6%">Unit</th>
                </tr>
            </thead>
            <thead>
    <?php foreach($model as $rateMaster):?>
        
                <tr>
                    <td><?= ++$count;?></td>
                    <td><?= $rateMaster->item;?></td>
                    <td><?= $rateMaster->hsn_no;?></td>
                    <td><?= $rateMaster->unitName->name;?></td>
                </tr>
    <?php endforeach;?>

            </thead>
        </table>
</div>
</div>
</div>
