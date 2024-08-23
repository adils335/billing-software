<?php 
use yii\helpers\Html;

?>


<div class="row">
    <div class="col-md-12">
        <div class="company-gst box box-primary  collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Company Gst')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
                   <table class="table">
					<tr>
						<th>State</th>
						<th>Gst No</th>
						<th>Districts</th>
						<th>Action</th>
					</tr>
					<?php 
						$companyGst = $model->billingCompanyGst;;
						foreach($companyGst as $key=>$value):?>
					<tr>
						<td><?= $value->state->state;?></td>
						<td><?= $value->gst_no;?></td>
						<td><?= $value->district;?></td>
						<td>
						    <?= Html::a("<span class='fa fa-pencil'></span>",['company-gst','id'=>$model->id,'gst_id'=>$value->id],['class'=>'text-info'])?>
							<?= Html::a("<span class='fa fa-trash'></span>",['delete-company-gst','id'=>$value->id,'company_id'=>$model->id],['class'=>'text-danger'])?>
						</td>
					</tr>
					<?php  endforeach;?>
				   </table>
			</div>   
		</div>   
	</div>   
</div>   