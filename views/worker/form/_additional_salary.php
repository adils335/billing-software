<div class="row as">
    	<div class="col-md-12">
    	   <h4>Allowance</h4><hr>
    	</div>
</div>

<div class="row as allowance">
    <?php 
    $totalAllowance = 0;
    $i = 0;
    foreach($workerAllowance as $allowance):?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label"><?= $allowance->allowance->allowance->name?></label>
                 <input type="text" class="form-control allowance-amount" name="allowance[<?=$i?>][WorkerSalaryAllowance][amount]" aria-invalid="false" value="<?= round($allowance->amount,2)?>">
                 <input type="hidden" class="form-control actual-allowance-amount" aria-invalid="false" value="<?= round($allowance->amount,2)?>">
                 <?php $totalAllowance += $allowance->amount;?>
                 <div class="help-block"></div>
                 <?php if(!empty($allowance->id)){?>
                 <input type="hidden" class="form-control" name="allowance[<?= $i?>][WorkerSalaryAllowance][id]" aria-invalid="false" value="<?=$allowance->id?>">
                 <?php }?>
                 <input type="hidden" class="form-control allowance-actual-amount" name="allowance[<?= $i?>][WorkerSalaryAllowance][actual_amount]" aria-invalid="false" value="<?= round($allowance->actual_amount,2)?>">
                 <input type="hidden" class="form-control allowance-per-day" name="allowance[<?= $i?>][WorkerSalaryAllowance][per_day]" aria-invalid="false" value="<?= round($allowance->per_day,2)?>">
                 <input type="hidden" class="form-control allowance-id" name="allowance[<?=$i?>][WorkerSalaryAllowance][allowance_id]" aria-invalid="false" value="<?= $allowance->allowance_id?>">
                 <div class="help-block"></div>
             </div>
         </div>
    <?php $i++; 
         endforeach;?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Total Allowance</label>
                 <input type="text" class="form-control allowance" name="WorkerSalary[allowance]" aria-invalid="false" value="<?= round($totalAllowance,2)?>">
                 <div class="help-block"></div>
             </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Salary With Allowance</label>
                 <input type="text" class="form-control salary_with_allowance" name="WorkerSalary[salary_with_allowance]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
</div>

<div class="row as">
    	<div class="col-md-12">
    	   <h4>Worker Deduction</h4><hr>
    	</div>
</div>

<div class="row worker-deduction as">
    	<?php 
        $i = 0;
    foreach($workerDeduction as $deduction):?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label"><?= $deduction->deduction->name?></label>
                 <input type="text" class="form-control worker-deduction-amount" name="deduction[<?= $i?>][WorkerSalaryDeduction][amount]" aria-invalid="false">
                 <div class="help-block"></div>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][WorkerSalaryDeduction][type]" aria-invalid="false" value="1">
                 <?php if(!empty($deduction->id)){?>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][WorkerSalaryDeduction][id]" aria-invalid="false" value="<?=$deduction->id?>">
                 <?php }?>
                 <input type="hidden" class="form-control worker-per-day" name="deduction[<?= $i?>][WorkerSalaryDeduction][per_day]" aria-invalid="false" value="<?= round($deduction->per_day,2)?>">
                 <input type="hidden" class="form-control worker-deduction-rate" name="deduction[<?= $i?>][WorkerSalaryDeduction][rate]" aria-invalid="false" value="<?= $deduction->rate?>">
                 <input type="hidden" class="form-control worker-deduction-id" name="deduction[<?= $i?>][WorkerSalaryDeduction][deduction_id]" aria-invalid="false" value="<?= $deduction->deduction_id?>">
                 <div class="help-block"></div>
             </div>
         </div>
    <?php $i++;endforeach;?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Worker Deduction</label>
                 <input type="text" class="form-control worker-deduction" name="WorkerSalary[worker_deduction]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Payable Salary</label>
                 <input type="text" class="form-control payable-salary" name="WorkerSalary[payable_salary]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
</div>

<div class="row as">
    	<div class="col-md-12">
    	   <h4>Employer Deduction</h4><hr>
    	</div>
</div>

<div class="row employer-deduction as">
    <?php 
    foreach($employerDeduction as $deduction):?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label"><?= $deduction->deduction->name?></label>
                 <input type="text" class="form-control employer-deduction-amount" name="deduction[<?= $i?>][WorkerSalaryDeduction][amount]" aria-invalid="false">
                 <div class="help-block"></div>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][WorkerSalaryDeduction][type]" aria-invalid="false" value="2">
                 <?php if(!empty($deduction->id)){?>
                 <input type="hidden" class="form-control" name="deduction[<?= $i?>][WorkerSalaryDeduction][id]" aria-invalid="false" value="<?=$deduction->id?>">
                 <?php }?>
                 <input type="hidden" class="form-control employer-per-day" name="deduction[<?= $i?>][WorkerSalaryDeduction][per_day]" aria-invalid="false" value="<?= round($deduction->per_day,2)?>">
                 <input type="hidden" class="form-control employer-deduction-rate" name="deduction[<?= $i?>][WorkerSalaryDeduction][rate]" aria-invalid="false" value="<?= $deduction->rate?>">
                 <input type="hidden" class="form-control employer-deduction-id" name="deduction[<?= $i?>][WorkerSalaryDeduction][deduction_id]" aria-invalid="false" value="<?= $deduction->deduction_id?>">
                 <div class="help-block"></div>
             </div>
         </div>
    <?php $i++;endforeach;?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Employer Deduction</label>
                 <input type="text" class="form-control employer-deduction" name="WorkerSalary[employer_deduction]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>	
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Net Salary</label>
                 <input type="text" class="form-control net-salary" name="WorkerSalary[net_salary]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
</div>