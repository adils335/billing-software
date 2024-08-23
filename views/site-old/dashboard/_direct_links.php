<?php
use yii\helpers\Url;
?>
        <div class="employee-leave-details box box-primary collapsed-box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history margin-r-5"></i><?=Yii::t('app', 'Direct Links')?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                </div>   
            </div>   
			 <div class="box-body">  
			     <div class="row">
			         <div class="col-sm-6">
			             <h4>Employee</h4>
			             <a href="<?= Url::to(['employee-leave/index'])?>" class="btn btn-info">Leave</a>
			             <a href="<?= Url::to(['employee/salary-record'])?>" class="btn btn-success">Salary</a>
			         </div>
			         <div class="col-sm-6">
			             <h4>Worker</h4>
			             <a href="<?= Url::to(['worker-leave/index'])?>" class="btn btn-info">Leave</a>
			             <a href="<?= Url::to(['worker/salary-record'])?>" class="btn btn-success">Salary</a>
			         </div>
			     </div>
		    </div>
		</div>    