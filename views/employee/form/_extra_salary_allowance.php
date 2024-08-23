<div class="row as">
    	<div class="col-md-12">
    	   <h4>Allowance</h4><hr>
    	</div>
</div>

<div class="row as allowance">
    <?php 
    $totalAllowance = 0;
    $i = 0;
    foreach($employeeAllowance as $allowance):?>
         
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label"><?= $allowance->allowance->name?></label>
                 <input type="text" class="form-control allowance-amount editSalary" name="allowance[<?=$i?>][EmployeeExtraSalaryAllowance][amount]" aria-invalid="false" value="<?= round($allowance->amount,2)?>">
                 <?php $totalAllowance += $allowance->amount;?>
                 <div class="help-block"></div>
                 <?php if(!empty($allowance->id)){?>
                 <input type="hidden" class="form-control" name="allowance[<?= $i?>][EmployeeExtraSalaryAllowance][id]" aria-invalid="false" value="<?=$allowance->id?>">
                 <?php }?>
                 <input type="hidden" class="form-control allowance-id" name="allowance[<?=$i?>][EmployeeExtraSalaryAllowance][allowance_id]" aria-invalid="false" value="<?= $allowance->allowance_id?>">
                 <div class="help-block"></div>
             </div>
         </div>
    <?php $i++; 
         endforeach;?>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Total Allowance</label>
                 <input type="text" class="form-control allowance" name="EmployeeExtraSalary[allowance]" aria-invalid="false" value="<?= round($totalAllowance,2)?>">
                 <div class="help-block"></div>
             </div>
         </div>
         <div class="col-md-2">
            <div class="form-group">
                 <label class="control-label">Salary With Allowance</label>
                 <input type="text" class="form-control salary_with_allowance" name="EmployeeExtraSalary[salary_with_allowance]" aria-invalid="false">
                 <div class="help-block"></div>
             </div>
         </div>
</div>