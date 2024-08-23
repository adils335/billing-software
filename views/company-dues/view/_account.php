<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
$accounts = $model->account;
?>

<br>
<div class="row">
    <div class="col-md-12">
        <div class="employee-details box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Account')?></h3>
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
                            <th>Bank Name</th>
                            <th>Branch Name</th>
                            <th>Account No</th>
                            <th>Ifsc Code</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $sn = 1; 
                          foreach($accounts as $account):
                        ?>
                        <tr>
                            <td><?= $sn++;?></td>
                            <td><?= $account->name;?></td>
                            <td><?= $account->bank_name;?></td>
                            <td><?= $account->branch_name;?></td>
                            <td><?= $account->account_no;?></td>
                            <td><?= $account->ifsc_code;?></td>
                            <td>
                                <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>',['account/update','id'=>$account->id],['title'=>'Update','aria-label'=>'Update','data-pjax'=>'0']);?>
                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',['account/delete','id'=>$account->id],['title'=>'Delete','aria-label'=>'Delete','data-pjax'=>'0','data-confirm'=>'Are you sure you want to delete this item?','data-method'=>'post']);?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>

            </div>
                  
			</div>   
		</div>   
	</div>   
</div>   