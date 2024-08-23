<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
$allowances = $model->allowances;
?>

<br>
<div class="row">
    <div class="col-md-12">
        <div class="employee-details box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Allowance')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
                  
            <div class="row">
                 
                <table class="table">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $sn = 1; 
                          foreach($allowances as $allowance):
                        ?>
                        <tr>
                            <td><?= $sn++;?></td>
                            <td><?= $allowance->allowance->name;?></td>
                            <td><?= $allowance->value?></td>
                            <td><?= Html::a('<span class="glyphicon glyphicon-trash"></span>',['allowance/delete','id'=>$allowance->id],['title'=>'Delete','aria-label'=>'Delete','data-pjax'=>'0','data-confirm'=>'Are you sure you want to delete this item?','data-method'=>'post']);?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>

            </div>
                  
			</div>   
		</div>   
	</div>   
</div>   