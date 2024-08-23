<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
$statuses = $model->billStatuses;
?>

<br>
<div class="row">
    <div class="col-md-12">
        <div class="history-details box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Status History')?></h3>
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
                            <th>Last Status</th>
                            <th>Status</th>
                            <th>Time</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $sn = 1; 
                          foreach($statuses as $status):
                        ?>
                        <tr>
                            <td><?= $sn++;?></td>
                            <td><?= $status->lastStatus?></td>
                            <td><?= $status->statusLabel?></td>
                            <td><?= $formatter->asDate($status->created_at,'php:d-m-Y h:i:s')?></td>
                            <td><?= $status->createdBy->username;?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>

            </div>
                  
			</div>   
		</div>   
	</div>   
</div>   