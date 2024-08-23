<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StoreIssue $model */

$this->title = 'Create Store Issue';
$this->params['breadcrumbs'][] = ['label' => 'Store Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="store-issue-create box box-primary"> 
    <div class="box-header with-border">

    <?= $this->render('_form', [
        'model' => $model,
        'items'=>$items,
    ]) ?>
    </div>

</div>
