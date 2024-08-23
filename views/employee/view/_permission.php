<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
$permissions = $model->permission;
?>

<br>
<div class="row">
    <div class="col-md-12">
        <div class="employee-details box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'Permissions')?></h3>
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
                            <th>Controller</th>
                            <th>Permission</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $sn = 1; 
                          foreach($permissions as $permission):
                        ?>
                        <tr>
                            <td><?= $sn++;?></td>
                            <td><?= $permission['controller'];?></td>
                            <?php 
                              $action = [];
                              if($permission['action'])
                              foreach ($permission['action'] as $key => $value) {
                                  $action[] = (new \app\models\ControllerAction)->getActionLabel($value);
                              }
                              else $action[] = "Deny";

                            ?>
                            <td><?= implode(",",$action);?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>

            </div>
                  
			</div>   
		</div>   
	</div>   
</div>   