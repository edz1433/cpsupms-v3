@php
    $current_route=request()->route()->getName();
@endphp
@if($current_route == "viewPayroll")
<div class="modal fade" id="modal-addpayroll">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Add New
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form  class="form-horizontal" method="POST" action="{{ route('createPayroll') }}">
                    @csrf
                    <input type="hidden" name="campID" value="{{ $campId }}">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">Statuses:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-info-circle"></i>
                                        </span>
                                    </div>
                                    <select class="form-control" id="stat-name" name="statName" onchange="checkStat(this.value)" required>
                                        <option value=""> --- Select Here --- </option>
                                        @foreach ($status as $stat)
                                            <option value="{{ $stat->id }}">{{ $stat->status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">Date Start:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control float-right" name="PayrollDateStart" id="dateStart"  onchange="calculateDays()" required>
                                </div>
                                <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left PayrollDateStart_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">Date End:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control float-right" name="PayrollDateEnd" id="dateEnd"  onchange="calculateDays()" required>
                                </div>
                                <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left PayrollDateEnd_error"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" id="jo-type">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">Type:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-info-circle"></i>
                                        </span>
                                    </div>
                                    <select class="form-control" name="jo_type">
                                        <option value="1">Monthly Bases</option>
                                        <option value="2">Daily Bases</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group" id="funding">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">Funding:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-info-circle"></i>
                                        </span>
                                    </div>
                                    <select class="form-control" name="fund">
                                        <option>Income</option>
                                        <option>Yearbook</option>
                                        <option>MDS</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">Working Days:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="number_days" step="any" id="working-days" class="form-control float-right" required readonly>
                                </div>
                                <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left PayrollDateStart_error"></span>
                            </div>
                        </div>
                    </div>
                   <!-- First date input -->

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" name="btn-submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                            
                        </div>
                    </div>   
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
@endif
@if($current_route == "storepayroll" || $current_route == "storepayroll-jo" || $current_route == "storepayroll-partime-jo" || $current_route == "storepayroll-partime")
<style>
    .btn-group-toggle .btn {
        background-color: #6c757d;
        color: #fff;
    }

    .btn-group-toggle .btn.active {
        background-color: green;
    }

    .custom-input-group {
        display: flex;
        justify-content: center;
     }

    .custom-input-group .btn input[type="radio"]:checked + span {
        background-color: #FF0000;
    }

    input[type="checkbox"]:checked + .btn {
        background-color: green !important;
        color: white; 
    }

    .btn-secondary.active, .btn-secondary:active {
        background-color: #6c757d;
    }

</style>

<div class="modal fade" id="modal-importPayrollsTwo">
    <div class="modal-dialog modal-m">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Add New
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('importPayrollsTwo', [$payrollID, $statID]) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">Employees</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-user"></i>
                                        </span>
                                    </div>
                                    <select class="form-control select2" name="emp_ID" style="width: 91%;" required>
                                        <option value=""> --- Select Employee --- </option>
                                        @foreach($employee as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->emp_ID }} - {{ $emp->lname }} {{ $emp->fname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left Status_error"></span>
                            </div>
                        </div>
                    </div> 
                    @if($statID == 1 || $statID == 4)
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="exampleInputName">No. of Days</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="number" name="number_hours" step="any" min="1" value="{{ $days }}" placeholder="No. of working Days" class="form-control" required>
                                       </div>    
                                    <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left FirstName_error"></span>
                                </div>
                            </div>
                        </div>
                    @elseif($statID == 3)
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="exampleInputName">No. of Hours</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clock"></i>
                                            </span>
                                        </div>
                                        <input type="number" name="number_hours" step="any" min="1" placeholder="No. of Hours" class="form-control" required>
                                       </div>    
                                    <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left FirstName_error"></span>
                                </div>
                            </div>
                        </div>
                    @elseif($statID == 2) 
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label for="exampleInputName">No. of Working Days</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="number_hours" step="any" step="any" min="1" placeholder="Working Days" class="form-control" required>
                                   </div>    
                                <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left FirstName_error"></span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" name="btn-submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-voucher">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fa-solid fa-ticket"></i> Voucher
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('voucherCreate') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <div class="form-row">
                            <div class="alert alert-warning mb-2" role="alert" style="font-size: 14px;">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>
                                    Saving a voucher will update the employee's status to indicate compliance. 
                                    Remember: Once saved, the employee's compliance status will be updated.
                                </span>
                            </div>
                            <div class="col-md-8">
                                <label for="exampleInputName">Employees</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-user"></i>
                                        </span>
                                    </div>
                                    <select class="form-control select2" name="pfile_id" style="width: 91%;" onchange="incomStat(this.value)" required>
                                        <option value=""> --- Select Employee --- </option>
                                        @isset($pfiles)
                                            @foreach($pfiles as $p)
                                                @if($p->status == 3 && $p->voucher != 1)
                                                    <option value="{{ $p->pid }}">{{ $p->lname }} {{ $p->fname }} {{ $p->mname }} </option>
                                                @endif
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputName">Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far">₱</i>
                                        </span>
                                    </div>
                                    <input class="form-control form-control-sm"  type="text" name="amount" id="amount" readonly>
                                    <input class="form-control form-control-sm"  type="text" name="amount_txt" id="amount_txt" hidden>
                                </div>    
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="exampleInputName">Mode of Payment</label><br>
                                <div class="btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-secondary custom-width" style="width: 189px; height: 35px;">
                                        <input type="radio" name="mode" id="mode1" value="1" required> MDS Check
                                    </label>
                                    <label class="btn btn-secondary custom-width" style="width: 189px; height: 35px;">
                                        <input type="radio" name="mode" id="mode2" value="2" required> Commercial Check
                                    </label>
                                    <label class="btn btn-secondary custom-width" style="width: 189px; height: 35px;">
                                        <input type="radio" name="mode" id="mode3" value="3" required> ADA
                                    </label>
                                    <label class="btn btn-secondary custom-width" style="width: 189px; height: 35px;">
                                        <input type="radio" name="mode" id="mode4" value="4" required> Others
                                    </label>     
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for="exampleInputName">Certified</label><br>
                                <label class="btn btn-secondary custom-width" style="width: 150px; height: 35px;" id="cashLabel">
                                    <input type="checkbox" name="certified[]" value="1"> Cash Available
                                </label>
                                <label class="btn btn-secondary custom-width" style="width: 304px; height: 35px;" id="bankTransferLabel">
                                    <input type="checkbox" name="certified[]" value="2"> Subject to Authority to Debit Account 
                                </label>
                                <label class="btn btn-secondary custom-width" style="width: 304px; height: 35px;" id="creditCardLabel">
                                    <input type="checkbox" name="certified[]" value="3"> Supporting Documents complete and claimed proper
                                </label>
                            </div>                                
                        </div>
                    </div> 
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="submit" name="btn-submit" class="btn btn-success">
                                    <i class="fa-solid fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-codes">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Code
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('update-code') }}" method="POST">
                    @csrf
                    <div class="row">
                        @if($statID == 1 || $statID == 4)
                        <div class="col-md-4">
                            <label for="exampleInputName">@if($statID == 1)Salaries & Wages - Civillian @else Labor and Wages @endif</label>
                            <div class="input-group">
                                <input type="text" name="id" class="form-control" value="{{ $codes->id }}" hidden>
                                <input type="text" name="wages_code" class="form-control" value="{{ $codes->wages_code }}">
                            </div>
                        </div>
                        @endif
                        @if($statID == 1)
                        <div class="col-md-4">
                            <label for="exampleInputName">GSIS Payable</label>
                            <div class="input-group">
                                <input type="text" name="gsis_code" class="form-control" value="{{ $codes->gsis_code }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="exampleInputName">Due to PAG-IBIG</label>
                            <div class="input-group">
                                <input type="text" name="pagibig_code" class="form-control" value="{{ $codes->pagibig_code }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="exampleInputName">Due to PhilHealth</label>
                            <div class="input-group">
                                <input type="text" name="ph_code" class="form-control" value="{{ $codes->ph_code }}">
                            </div>
                        </div>
                        @endif
                        @if($statID == 2 || $statID == 3)
                        <div class="col-md-4">
                            <label for="exampleInputName">Other Professional Services</label>
                            <div class="input-group">
                                <input type="text" name="otherprof_code" class="form-control" value="{{ $codes->otherprof_code }}">
                            </div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <label for="exampleInputName">Due to BIR</label>
                            <div class="input-group">
                                <input type="text" name="bir_code" class="form-control" value="{{ $codes->bir_code }}">
                            </div>
                        </div>
                        @if($statID == 4)
                        <div class="col-md-4">
                            <label for="exampleInputName">Other Receivable</label>
                            <div class="input-group">
                                <input type="text" name="otherrec_code" class="form-control" value="{{ $codes->otherrec_code }}">
                            </div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <label for="exampleInputName">Other Payable</label>
                            <div class="input-group">
                                <input type="text" name="otherpayable_code" class="form-control" value="{{ $codes->otherpayable_code }}">
                            </div>
                        </div>
                        @if($statID == 1)
                        <div class="col-md-4">
                            <label for="exampleInputName">Due to Office & Employees</label>
                            <div class="input-group">
                                <input type="text" name="due_offemp" class="form-control" value="{{ $codes->due_offemp }}">
                            </div>
                        </div>
                        @endif
                        @if($statID != 1)
                        <div class="col-md-4">
                            <label for="exampleInputName">Cash in Bank-LC, LBP</label>
                            <div class="input-group">
                                <input type="text" name="bank_code" class="form-control" value="{{ $codes->bank_code }}">
                            </div>
                        </div>
                        @endif
                        <div class="col-md-12 mt-3">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" name="btn-submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-deductions">
    <div id="modal-deduct" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Deductions <span class="date-deduct"></span>
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form class="form-horizonta" action="{{ route('deductions-update') }}" method="POST">
                    <input type="hidden" id="payroll_id" name="payroll_id" class="form-control">
                    @csrf

                    {{-- Job-Order --}}
                    @if($statID != 1)
                    <div class="form-group">
                        <h4><strong>BIR DEDUCTIONS</strong></h4>
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="exampleInputName">TAX 1</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="tax_one" name="tax_one" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right" readonly>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputName">TAX 2</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="tax_two" name="tax_two" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right" readonly>
                                </div>
                            </div>
                            <div class="cold-md-1">
                                <label for="exampleInputName"></label>
                                <div class="input-group">
                                    <input style="width: 38px; height: 38px; margin-top: 8px;" type="checkbox" id="twocheckbox" name="twocheckbox" class="form-control form-control-lg float-right" value="1">
                                </div>
                            </div>
                    @endif
                    
                    {{-- Regular --}}
                    {{-- Storepayroll --}}
                    @if($statID == 1)
                        <div class="form-group">
                            <h4><strong>GSIS DEDUCTIONS</strong></h4>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="exampleInputName">EML</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="hidden" name="cat" id="catd">
                                        <input type="number" id="eml" name="eml" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">POLICY</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="pol_gfal" name="pol_gfal" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Consol</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="consol" name="consol" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">EDUC. ASST</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="ed_asst_mpl" name="ed_asst_mpl" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">MPL loan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="loan" name="loan" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">RLIP</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="rlip" name="rlip" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">GFAL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="gfal" name="gfal" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Computer</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="computer" name="computer" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Help</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="health" name="health" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                            </div>
                            <h4 class="pt-3"><strong>PAG-IBIG DEDUCTIONS</strong></h4>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="exampleInputName">MPL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="mpl" name="mpl" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">PREM.</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="prem" name="prem" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Calamity Loan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="calam_loan" name="calam_loan" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">MP2.</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="mp2" name="mp2" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Housing Loan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="house_loan" name="house_loan" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <h4 class="pt-2"><strong>OTHER PAYABLES</strong></h4>
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="exampleInputName">Philhealth</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="philhealth" name="philhealth" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Withholding Tax</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="holding_tax" name="holding_tax" step="any" min="0"onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">LBP</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="lbp" name="lbp" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Cauyan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="cauyan" name="cauyan" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                @endif
                                @if($statID == 3 || $statID == 4)
                                    <h4 class="pt-2"><strong>OTHER DEDUCTIONS</strong></h4>
                                    <div class="form-row">
                                @endif
                                <div class="col-md-{{$statID == 1 ? '4' : '6'}}">
                                    <label for="exampleInputName">Projects</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="projects" name="projects" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-{{$statID == 1 ? '4' : '6'}}">
                                    <label for="exampleInputName">NSCA MPC</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="nsca_mpc" name="nsca_mpc" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>

                                @if($statID == 1)
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="exampleInputName">Medical Deduction</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="med_deduction" name="med_deduction" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                            @endif
                                <div class="col-md-{{$statID == 1 ? '4' : '6'}}">
                                    <label for="exampleInputName">Graduate Shool {{$statID == 1 ? '/ Guarantor' : ''}}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="grad_guarantor" name="grad_guarantor" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                @if($statID == 1)
                                <div class="col-md-4">
                                    <label for="exampleInputName">CFI</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="cfi" name="cfi" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="exampleInputName">CSB</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="csb" name="csb" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">FASFEED</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="fasfeed" name="fasfeed" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputName">Disallow ANCE / UNLIQUIDATED</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="dis_unliquidated" name="dis_unliquidated" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                            @endif
                                @if($statID != 1)
                                <div class="col-md-6">
                                    <label for="exampleInputName">SSS</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="jo_sss" name="jo_sss" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                @endif
                                @if($statID == 4)
                                <div class="col md 6">
                                    <label for="exampleInputName">SMLF Loan</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"> 
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="jo_smlf_loan" name="jo_smlf_loan" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-{{$statID == 1 ? '4' : '6'}}">
                                    <label for="exampleInputName">Less Absences @if($statID == 1) 1-15 @endif</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="add_less_abs" name="add_less_abs" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                @if($statID == 1)
                                <div class="col-md-{{$statID == 1 ? '4' : '6'}}" hidden>
                                    <label for="exampleInputName">Less Absences @if($statID == 1) 2nd @endif</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="add_less_abs1" name="add_less_abs1" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                @endif
                                @if($statID != 1)
                                <div class="col-md-{{$statID == 1 ? '4' : '6'}}">
                                    <label for="exampleInputName">Less Late</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-clipboard"></i>
                                            </span>
                                        </div>
                                        <input type="number" id="less_late" name="less_late" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                    </div>
                                </div>
                                @endif
                        @if($statID == 1)
                            </div>
                        </div>
                        @endif
                        @if($statID != 1)
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12 ml-3">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="submit" name="btn-submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-additional">
    <div id="modal-deduct" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Additional <span class="date-add"></span>
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('additional-update') }}" method="POST">
                    <input type="hidden" id="payroll_idd" name="payroll_id" class="form-control">
                    @csrf
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="exampleInputName">SSL Salary Differential</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="hidden" name="cat" id="cat">
                                    <input type="number" id="add_sal_diff" name="add_sal_diff" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputName">NBC 461 Salary Differential {{ date('Y'); }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="add_nbc_diff" name="add_nbc_diff" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputName">Step Increment</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="add_step_incre" name="add_step_incre" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" class="form-control float-right">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" name="btn-submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-modification">
    <div id="modal-deduct" class="modal-dialog modal-xl">
        <div class="modal-content">
            
            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="fas fa-plus"></i> Additional Columns  <span class="date-add"></span>
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('modifyUpdate') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <div class="form-row modify-show">
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" name="btn-submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer justify-content-between">
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pdfoffice">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-12">
                            <label for="exampleInputName">Office</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-user"></i>
                                    </span>
                                </div>
                                <input type="text" id="halfmon" hidden>
                                <select class="form-control select2" name="emp_ID" style="width: 91%;" onchange="redirectToTargetPage(event)" required>
                                    <option value=""> --- Select Office --- </option>
                                    @foreach($office as $off)
                                        <option data-payrollId="{{ $payrollID }}" data-statId="{{ $statID }}" value="{{ $off->id }}">{{ $off->office_name }}</option>
                                    @endforeach
                                </select>                                                                                     
                            </div>
                            <span style="color: #FF0000; font-size: 10pt;" class="form-text text-left Status_error"></span>
                        </div>
                    </div>
                </div> 
            </div>
            
        </div>
    </div>
</div>

@endif


