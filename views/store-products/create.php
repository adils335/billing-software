<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\StoreProducts $model */

$this->title = 'Create Store Products';
$this->params['breadcrumbs'][] = ['label' => 'Store Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-products-create box box-primary"> 
    <div class="box-header with-border"> 

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>

</div>
