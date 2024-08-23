<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Companies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="company-view">
<div class="company-view box box-primary"> 
		
		<div class="box-header with-border"> 
    <h1><?= Html::encode($this->title) ?><span class="pull-right">
        <?= Html::a(Yii::t('app', 'Add GST NO'), ['company-gst', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </span></h1>

    

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'label'=>'Type',
                'value'=>$model->companyType->type,
            ],
            'address:ntext',
            [
                'label'=>'State',
                'value'=>$model->stateName->state,
            ],
            [
                'label'=>'District',
                'value'=>$model->districtName->district,
            ],
            'pincode',
            'person',
            'number',
            'email:email',
            'pancard_no',
            'gst_no',
            [
                'label'=>'Created At',
                'value'=>Yii::$app->formatter->asDate($model->created_at,'php:d-m-Y'),
            ],
            [
                'label'=>'Created By',
                'value'=>$model->createdBy->username,
            ],
            [
                'label'=>'Updated At',
                'value'=>Yii::$app->formatter->asDate($model->updated_at,'php:d-m-Y'),
            ],
            [
                'label'=>'Updated By',
                'value'=>$model->updatedBy->username,
            ],
            [
                'label'=>'Logo',
                'attribute'=>'logo',
                'format' => ['image',['width'=>'100','height'=>'100']],
                'value'=>Yii::getAlias("@web/upload/logo/").$model->logo,
            ],
        ],
    ]) ?>
<?= $this->render('_company_gst', [ 'model' => $model]) ?> 
</div>
</div>
</div>
