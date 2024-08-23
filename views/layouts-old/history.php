<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm; 
use yii\grid\GridView;
$formatter = \Yii::$app->formatter;
$model_name = ucfirst( Yii::$app->controller->id );
if( Yii::$app->controller->id == "payment" ){
    $ref_no = Yii::$app->request->get()['ref_no'];
    $model_id = Null;
}else{
    $model_id = Yii::$app->request->get()['id'];
    $ref_no = Null;
}
$histories = Yii::$app->history->record($model_name,$model_id,$ref_no);
?>

        <div class="history-details box collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><?=Yii::t('app', 'History')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
                  
                 
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Label</th>
                            <th>User</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $sn = 1;
                          if( !empty( $histories ) ):
                          foreach($histories as $history):
                        ?>
                        <tr>
                            <td><?= $formatter->asDate( $history->created_at ,'php:d-m-Y H:i:s')?></td>
                            <td><?= $history->actionStatusLabel?></td>
                            <td><?= $history->createdBy->username;?></td>
                        </tr>
                        <?php endforeach;
                        endif;?>
                    </tbody>
                </table>
                  
			</div>   
		</div>   