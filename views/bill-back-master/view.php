<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $model app\models\BillBackMaster */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bill Back Masters'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bill-back-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
          <?php foreach($model as $back){?>
            <tr>
                <td><?= $back->sno?></td>
                <td><?= $back->description?></td>
            </tr>
           <?php }?>
        </tbody>
    </table>

</div>
