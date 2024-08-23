<?php 
use yii\helpers\Html;
use yii\helpers\Url;

?>


<div class="row">
    <div class="col-md-12">
        <div class="company-sites box box-primary  collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Company Sites')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
                   <table class="table">
					<tr>
						<th>Name</th>
						<th>State</th>
						<th>District</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
					<?php 
						$companySites = $model->sites;
						foreach($companySites as $key=>$value):?>
					<tr>
						<td><?= $value->name;?></td>
						<td><?= $value->state->state;?></td>
						<td><?= $value->district->district;?></td>
						<td><?= $value->statusLabel;?></td>
						<td>
						    <?php 
						    if( $value->status == $value::ACTIVE_STATUS ){
						        $url = Url::to(['sites/archive','company_id'=>$value->company_id, 'id' => $value->id]);
						        echo Html::a('<span class="glyphicon glyphicon-ban-circle text-danger"></span>', $url, [
                                              'title' => Yii::t('app', 'Archive'),
                                              'data-confirm' => Yii::t('yii', 'Are you sure you want to archive?'),
                                              'data-method' => 'post', 'data-pjax' => '0',
                                           ]);
						    }elseif( $value->status == $value::ARCHIVE_STATUS ){
						       $url = Url::to(['sites/un-archive','company_id'=>$value->company_id, 'id' => $value->id]);
						       echo Html::a('<span class="glyphicon glyphicon-ban-circle text-success"></span>', $url, [
                                              'title' => Yii::t('app', 'Archive'),
                                              'data-confirm' => Yii::t('yii', 'Are you sure you want to un-archive?'),
                                              'data-method' => 'post', 'data-pjax' => '0',
                                           ]);
						    }?>
						</td>
					</tr>
					<?php  endforeach;?>
				   </table>
			</div>   
		</div>   
	</div>   
</div>   