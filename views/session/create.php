<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Session */

$this->title = Yii::t('app', 'Create Session');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sessions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="session-create">
<div class="session-index box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

     </div>
	 
   </div>

</div>
