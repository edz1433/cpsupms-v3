@extends('layouts.master')

@section('body')
<style>
    /* Apply basic styles to the table */
.styled-table {
border-collapse: collapse;
width: 100%; /* Adjust the width as needed */
margin: 2px; /* Add some margin for spacing */
}

/* Apply styles to the table header (thead) */
.styled-table thead {
background-color: #f2f2f2; /* Light gray background for the header */
}

/* Apply styles to the table header cells (th) */
.styled-table th {
padding: 5px;
text-align: left;
border: 1px solid #ddd; /* Add a border around the cells */
}

/* Apply styles to the table body rows (tr) */
.styled-table tr {
background-color: #ffffff; /* White background for the rows */
}

/* Apply styles to the table body cells (td) */
.styled-table td {
padding: 5px;
text-align: left;
border: 1px solid #ddd; /* Add a border around the cells */
}

th{
    font-size: 12px;
}
</style>
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="card-title">
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modal-importPayrollsTwo">
                                    <i class="fas fa-plus"></i> Add New
                                </button>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-codes">
                                    <i class="fas fa-plus"></i> Code
                                </button>
                                <div class="btn-group">
                                    <button id="open-pdf" target="_blank"  class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-print"></i> Print Payroll
                                    </button>
                                    <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                        <a href="{{ route("pdf", ['payrollID' => $payrollID, 'statID' => $statID, 'pid' => 1, 'offid' => $offID]) }}" target="_blank" class="dropdown-item">{{ $firstHalf }}</a>
                                        <a href="{{ route("pdf", ['payrollID' => $payrollID, 'statID' => $statID, 'pid' => 2, 'offid' => $offID]) }}" target="_blank" class="dropdown-item">{{ $secondHalf }}</a>
                                    </div>
                                </div>
                            </h3>
                        </div>
                        <div class="col-md-8">
                            <ol class="breadcrumb float-md-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item">Payroll</li>
                                @foreach($currentcamp as $c)
                                    @php
                                        $encryptedId = encrypt($c->id);
                                    @endphp
                                    <li class="breadcrumb-item"><a href="{{ route('viewPayroll', $encryptedId) }}">{{ $c->campus_abbr }}</a></li>
                                @endforeach
                                <li class="breadcrumb-item active">{{ $empStat }} Payroll</li>
                            </ol>                            
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-9">

                        </div>
                        <div class="col-3">
                            <div class="input-group">
                                <select class="form-control select2" name="offid" onchange="navigateToPage(this.value)" required>
                                    <option value="All">All</option>
                                    @foreach($office as $off)
                                        <option value="{{ $off->id }}" @if($off->id == $offID) selected @endif>{{ $off->office_name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                </div>
                            </div>      
                        </div>                                        
                        
                        <div class="col-12">
                            <div class="table-responsive" style="overflow-y: auto;
                                overflow-x: auto; ">
                                <br>
                                <table id="example1" class="table table-bordered table-hover table-pay">
                                    <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th width="6%">Emp. ID</th>
                                                <th width="10%">Full Name</th>
                                                <th>Dept/Office</th>
                                                <th>Position</th>
                                            @if($empStat == "Job Order" || $empStat == "Part-time")
                                                <th>Rate per day</th>
                                            @elseif($empStat == "Regular")
                                                <th>Monthly Salary</th>
                                            @endif
                                                <th>Rate/Hour</th>
                                                <th>Hours</th>
                                                <th>Earn for period</th>
                                            @if($empStat == "Job Order" || $empStat == "Part-time" || $empStat == "Part-time/JO")
                                            
                                                <th>Tax (1%)</th>
                                                <th>Tax (2%)</th>
                                            @else
                                                <th>Total Add.</th>
                                                <th>Total Ded.</th>
                                            @endif
                                                <th>Net amount</th>
                                            @if($empStat == "Regular")
                                                <th>{{ $firstHalf }}</th>
                                                <th>{{ $secondHalf }}</th>
                                            @endif
                                                <th width="13%">Action</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $no = 1;
                                        @endphp
                                        @foreach ($pfiles as $p)
                                            @php 
                                                $RamountTotal = 0;
                                                $total_add = floatval(sprintf("%.2f",$p->add_sal_diff + $p->add_nbc_diff + $p->add_step_incre, 2)); 
                                                
                                                $total_deduct = floatval(sprintf("%.2f",$p->eml + $p->pol_gfal + $p->consol + $p->ed_asst_mpl + $p->loan + $p->rlip + $p->gfal + $p->computer 
                                                + $p->mpl + $p->prem + $p->calam_loan + $p->mp2 + $p->philhealth + $p->holding_tax + $p->lbp + $p->cauyan + $p->projects + $p->nsca_mpc + $p->med_deduction
                                                + $p->grad_guarantor + $p->cfi + $p->csb + $p->fasfeed + $p->dis_unliquidated + $p->add_less_abs, 2));

                                                $Rrate_per_day = $p->salary_rate / $days;
                                                $Rrate_per_hour = $Rrate_per_day / 8;
                                                $Ramount = $Rrate_per_hour * $p->number_hours;
                                                
                                                $Jrate_per_hour = $p->salary_rate / 8;

                                                $RamountReg = $p->salary_rate + $total_add - $p->add_less_abs;

                                                $RamountTotal += $p->salary_rate + $total_add - $p->add_less_abs;
                                            @endphp
                                            <tr id="tr-data" class="tr-data tr-{{ $p->pid }}">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $p->emp_id }}</td>
                                                <td>{{ $p->lname }} {{ $p->fname }} {{ $p->mname }}</td>
                                                <td>{{ $p->office_abbr }}</td>
                                                <td>{{ $p->position }}</td>
                                                <td>{{ number_format($p->salary_rate, 2) }}</td>
                                                @if($empStat == "Job Order" || $empStat == "Part-time" || $empStat == "Part-time/JO")
                                                    @if($empStat != "Part-time/JO")
                                                    <td>{{ number_format($Jrate_per_hour, 2) }}</td>
                                                    @endif
                                                    <td>{{ $p->number_hours." hr" }}</td>
                                                    <td class="text-danger">{{ number_format($p->total_salary, 2) }}</td>
                                                    <td>{{ $p->tax1 }}</td>
                                                    <td id="tax2-{{ $p->pid }}">{{ number_format($p->tax2, 2) }}</td>
                                                @else
                                                    <td>{{ number_format($Rrate_per_hour, 2) }}</td>
                                                    <td>{{ $p->number_hours." hr" }}</td>
                                                    <td class="text-danger">{{ number_format($RamountReg, 2) }}</td>
                                                    <td id="addition-{{ $p->pid }}">{{ number_format($total_add, 2); }}</td>
                                                    <td id="deduct-{{ $p->pid }}">{{ number_format($total_deduct, 2); }}</td>
                                                @endif
                                                @if($empStat == "Job Order" || $empStat == "Part-time" || $empStat == "Part-time/JO")
                                                    <td id="net-{{ $p->pid }}" class="text-danger">{{ number_format($p->total_salary - $p->tax1 - $p->tax2, 2) }}</td>
                                                @else
                                                    <td id="net-{{ $p->pid }}" class="text-danger">{{ number_format($Ramount + $total_add - $total_deduct, 2) }}</td>
                                                @endif
                                                @if($empStat == "Regular")
                                                <td class="firstHalf">{{ number_format(($Ramount + $total_add - $total_deduct) /  2, 2) }}</td>
                                                @php
                                                $earn = round($Ramount + $total_add - $total_deduct, 2);
                                                $decimalPoint = ($earn * 100) % 100;
                                                $earns = ($Ramount + $total_add - $total_deduct) / 2;
                                             
                                                if ($decimalPoint % 2 === 0) {
                                                    $earns = round($earns, 2);
                                                    
                                                } else {
                                                    $earns = round($earns, 3);
                                                    $earns = floor($earns * 100) / 100;
                                                }

                                                if ($p->saltype == 1) {
                                                    $sechalearn = $earns + $p->sumRef - $p->sumDed;
                                                } else {
                                                    $sechalearn = $Ramount + $total_add - $total_deduct;
                                                }

                                                @endphp
                                            
                                                <td class="secondtHalf @if($earns + $p->sumRef - $p->sumDed <= 3000)text-danger @endif">{{ number_format($sechalearn, 2) }}</td>
                                                @endif 
                                                <td>
                                                @if($empStat == "Regular")
                                                    <div class="btn-group">
                                                        <button type="button" style="height:32px;" class="btn btn-{{ $p->sal_type == 1 ? 'secondary' : ''}}{{ $p->sal_type == 2 ? 'primary' : ''}}{{ $p->sal_type == 3 ? 'success' : ''}} dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="deductions">
                                                        {{ $p->sal_type }}
                                                        </button>
                                                        <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                                            <a href="{{ route('saltypepUp', ['id' => $p->pid, 'val' => '1']) }}" class="dropdown-item">1.) half</a>
                                                            <a href="{{ route('saltypepUp', ['id' => $p->pid, 'val' => '2']) }}" class="dropdown-item">2.) 1st half</a>
                                                            <a href="{{ route('saltypepUp', ['id' => $p->pid, 'val' => '3']) }}" class="dropdown-item">3.) 2nd half</a>
                                                        </div>                                                        
                                                    </div>
                                                    <div class="btn-group">
                                                        <button type="button" style="height:32px;" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false" title="deductions">
                                                        </button>
                                                        <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                                        <button id="{{ $p->pid }}" value="1" data-date="{{ $firstHalf }}" data-modes="1"  style="height:32px;" class="dropdown-item additional" title="additionals">Additional {{ $firstHalf }}</button>
                                                        <button id="{{ $p->pid }}" value="1" data-date="{{ $firstHalf }}" data-modes="3" class="dropdown-item deductions">Deduction {{ $firstHalf }}</button>
                                                        <button id="{{ $p->pid }}" value="2" data-date="{{ $secondHalf }}" data-modes="4" class="dropdown-item deductions">Adjustments {{ $secondHalf }}</button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <button id="{{ $p->pid }}" value="1" type="button" class="btn btn-info btn-sm deductions" title="deductions">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                    </button>
                                                @endif
                                                    <button value="{{ $p->pid }}" type='button' class='btn btn-danger btn-sm deletePayrollFiles'>
                                                        <i class='fas fa-trash'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>   
                        @foreach($pfiles as $p)
                            @php
                                $emltotal = 0;

                                $eml = $p->eml;
                                $policy =$p->eml;
                                $consol =$p->eml;
                                $educ_ass =$p->eml;
                                $loan =$p->eml;
                                $rlip =$p->eml;
                                $gfal =$p->eml;
                                $computer =$p->eml;

                                $emltotal += $eml;
                            @endphp
                        @endforeach
                        @if(isset($deduction))
                        <div class="col-10 mt-3">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="8" class="text-center">DEDUCTION(GSIS)</th>
                                        <th></th>
                                        <th colspan="4" class="text-center">DEDUCTION(PAG-IBIG)</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>EML</th>
                                        <th>POLICY</th>
                                        <th>Consol</th>
                                        <th>EDUC. ASST</th>
                                        <th>loan</th>
                                        <th>RLIP</th>
                                        <th>GFAL</th>
                                        <th>Computer</th>
                                        <th class="text-center">Total</th>
                                        <th>MPL</th>
                                        <th>PREM.</th>
                                        <th>Calaming Loan</th>
                                        <th>MP2.</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $eml = $deduction->sum('eml');
                                        $pol_gfal = $deduction->sum('pol_gfal');
                                        $consol = $deduction->sum('consol');
                                        $ed_asst_mpl = $deduction->sum('ed_asst_mpl');
                                        $loan = $deduction->sum('loan');
                                        $rlip = $deduction->sum('rlip');
                                        $gfal = $deduction->sum('gfal');
                                        $computer = $deduction->sum('computer');

                                        $totalGSIS = $eml + $pol_gfal + $consol + $ed_asst_mpl + $loan + $rlip + $gfal + $computer;

                                        $mpl = $deduction->sum('mpl');
                                        $prem = $deduction->sum('prem');
                                        $calam_loan = $deduction->sum('calam_loan');
                                        $mp2 = $deduction->sum('mp2');

                                        $totalPagibig = $mpl + $prem + $calam_loan + $mp2;

                                        $philhealth = $deduction->sum('philhealth');
                                        $withtax = $deduction->sum('holding_tax');
                                        $lbp = $deduction->sum('lbp');
                                        $cauyan = $deduction->sum('cauyan');
                                        $projects = $deduction->sum('projects');
                                        $nsca_mpc = $deduction->sum('nsca_mpc');
                                        $med_ded = $deduction->sum('med_deduction');
                                        $grad_schll = $deduction->sum('grad_guarantor');
                                        $cfi = $deduction->sum('cfi');
                                        $csb = $deduction->sum('csb');
                                        $fasfeed = $deduction->sum('fasfeed');
                                        $diss_ance = $deduction->sum('dis_unliquidated');
                                        $less_abs = $deduction->sum('add_less_abs');

                                        $totalOtherDed = $philhealth + $withtax + $lbp + $cauyan + $projects + $nsca_mpc + $med_ded + $grad_schll + $cfi + $csb + $fasfeed + $diss_ance + $less_abs;

                                        $add_sal_diff = $deduction->sum('add_sal_diff');
                                        $add_nbc_diff = $deduction->sum('add_nbc_diff');
                                        $add_step_incre = $deduction->sum('add_step_incre');     
                                        
                                        $totalAdd = $add_sal_diff + $add_nbc_diff + $add_step_incre;
                                    @endphp
                                    <tr>
                                        <td>{{ number_format($eml, 2) }}</td>
                                        <td>{{ number_format($pol_gfal, 2) }}</td>
                                        <td>{{ number_format($consol, 2) }}</td>
                                        <td>{{ number_format($ed_asst_mpl, 2) }}</td>
                                        <td>{{ number_format($loan, 2) }}</td>
                                        <td>{{ number_format($rlip, 2) }}</td>
                                        <td>{{ number_format($gfal, 2) }}</td>
                                        <td>{{ number_format($computer, 2) }}</td>
                                        <td class="text-red text-center" width="7%">{{ number_format($totalGSIS, 2) }}</td>
                                        <td>{{ number_format($mpl, 2) }}</td>
                                        <td>{{ number_format($prem, 2) }}</td>
                                        <td>{{ number_format($calam_loan, 2) }}</td>
                                        <td>{{ number_format($mp2, 2) }}</td>
                                        <td class="text-red text-center" width="7%">{{ number_format($totalPagibig, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table><br>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="13" class="text-center">DEDUCTION(OTHER PAYABLES)</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>Philhealth</th>
                                        <th>Withholding Tax</th>
                                        <th>LBP</th>
                                        <th>Cauyan</th>
                                        <th>Projects</th>
                                        <th>NSCA MPC</th>
                                        <th>Medical Ded.</th>
                                        <th>Grad SCH / Guar.</th>
                                        <th>CFI</th>
                                        <th>CSB</th>
                                        <th>FASFEED</th>
                                        <th>Disallow ANCE/UNIQ</th>
                                        <th>Less Abs incur.</th>
                                        <th class="text-center">Total</th>
                              
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($philhealth , 2) }}</td>
                                        <td>{{ number_format($withtax , 2) }}</td>
                                        <td>{{ number_format($lbp , 2) }}</td>
                                        <td>{{ number_format($cauyan , 2) }}</td>
                                        <td>{{ number_format($projects , 2) }}</td>
                                        <td>{{ number_format($nsca_mpc , 2) }}</td>
                                        <td>{{ number_format($med_ded, 2) }}</td>
                                        <td>{{ number_format($grad_schll , 2) }}</td>
                                        <td>{{ number_format($cfi , 2) }}</td>
                                        <td>{{ number_format($csb , 2) }}</td>
                                        <td>{{ number_format($fasfeed , 2) }}</td>
                                        <td>{{ number_format($diss_ance , 2) }}</td>
                                        <td>{{ number_format($less_abs , 2) }}</td>
                                        <td class="text-red text-center" width="7%">{{ number_format($totalOtherDed, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table><br>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center">Additionals</th>
                                        <th></th>
                                        <th colspan="16" class="text-center">Adjustments</th>
                                    </tr>
                                    <tr>
                                        <th width="7%">SSL Sal Diff.</th>
                                        <th width="7%">NBC Sal Diff</th>
                                        <th width="7%">Step Increment</th>
                                        <th class="text-center">Total</th>
                                        <th colspan="2" class="text-center">Project</th>
                                        <th colspan="2" class="text-center">Net MPC</th>
                                        <th colspan="2" class="text-center">Graduate</th>
                                        <th colspan="2" class="text-center">Philhealth</th>
                                        <th colspan="2" class="text-center">Pag-ibig</th>
                                        <th colspan="2" class="text-center">GSIS</th>
                                        <th colspan="2" class="text-center">CSB</th>
                                        <th colspan="2" class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ number_format($add_sal_diff , 2) }}</td>
                                        <td>{{ number_format($add_nbc_diff , 2) }}</td>
                                        <td>{{ number_format($add_step_incre , 2) }}</td>
                                        <td class="text-red text-center" width="7%">{{ number_format($totalAdd, 2) }}</td>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                        <th>Refund</th>
                                        <th>Deduct</th>
                                    </tr>
                                    <tr>
                                        <td colspan="4"></td>
                                        <td class="totalRef">{{ number_format($modify->where('action', 'refund')->where('column', 'project')->sum('amount'), 2) }}</td>
                                        <td class="totalDed">{{number_format($modify->where('action', 'deduction')->where('column', 'project')->sum('amount'), 2) }}</td>
                                        <td class="totalRef">{{ number_format($modify->where('action', 'refund')->where('column', 'Net_MPC')->sum('amount'), 2) }}</td>
                                        <td class="totalDed">{{number_format($modify->where('action', 'deduction')->where('column', 'Net_MPC')->sum('amount'), 2) }}</td>
                                        <td class="totalRef">{{ number_format($modify->where('action', 'refund')->where('column', 'Graduate')->sum('amount'), 2) }}</td>
                                        <td class="totalDed">{{number_format($modify->where('action', 'deduction')->where('column', 'Graduate')->sum('amount'), 2) }}</td>
                                        <td class="totalRef">{{ number_format($modify->where('action', 'refund')->where('column', 'Philhealth')->sum('amount'), 2) }}</td>
                                        <td class="totalDed">{{number_format($modify->where('action', 'deduction')->where('column', 'Philhealth')->sum('amount'), 2) }}</td>
                                        <td class="totalRef">{{ number_format($modify->where('action', 'refund')->where('column', 'Pag_ibig')->sum('amount'), 2) }}</td>
                                        <td class="totalDed">{{number_format($modify->where('action', 'deduction')->where('column', 'Pag_ibig')->sum('amount'), 2) }}</td>
                                        <td class="totalRef">{{ number_format($modify->where('action', 'refund')->where('column', 'Gsis')->sum('amount'), 2) }}</td>
                                        <td class="totalDed">{{number_format($modify->where('action', 'deduction')->where('column', 'Gsis')->sum('amount'), 2) }}</td>
                                        <td class="totalRef">{{ number_format($modify->where('action', 'refund')->where('column', 'Csb')->sum('amount'), 2) }}</td>
                                        <td class="totalDed">{{number_format($modify->where('action', 'deduction')->where('column', 'Csb')->sum('amount'), 2) }}</td>
                                        <td id="totalRefundAll1" class="text-danger"></td>
                                        <td id="totalDeductAll1" class="text-danger"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <table class="styled-table float-right" style="width: 20%;">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">SUMMARRY (ADDITIONALS)</th>
                                    </tr>
                                    <tr>
                                        <th>ADDITIONALS</th>
                                        <td class="text-center">2,400.00</td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL AMOUNT</th>
                                        <td class="text-red text-center">{{ number_format($totalOtherDed, 2) }}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-2 mt-3">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">SUMMARRY (NET AMOUNT)</th>
                                    </tr>
                                    <tr>
                                        <th>{{ $firstHalf }}</th>
                                        <td class="text-center" id="firstHalfTotal"></td>
                                    </tr>
                                    <tr>
                                        <th>{{ $secondHalf }}</th>
                                        <td class="text-center" id="secondtHalfTotal"></td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL AMOUNT</th>
                                        <td id="grandtotalnet" class="text-danger text-center"></td>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">SUMMARRY (DEDUCTION)</th>
                                    </tr>
                                    <tr>
                                        <th>GSIS</th>
                                        <td class="text-center">{{ number_format($totalGSIS, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>PAG-IBIG</th>
                                        <td class="text-center">{{ number_format($totalPagibig, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>OTHER PAYABLES</th>
                                        <td class="text-center">{{ number_format($totalOtherDed, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>ADDITIONALS</th>
                                        <td class="text-center">2,400.00</td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL AMOUNT</th>
                                        <td class="text-red text-center">{{ number_format($totalGSIS + $totalOtherDed + $totalPagibig, 2) }}</td>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">SUMMARRY (ADJUSTMENTS)</th>
                                    </tr>
                                    <tr>
                                        <th>REFUND</th>
                                        <td id="totalRefundAll" class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <th>DEDUCTION</th>
                                        <td id="totalDeductAll" class="text-center"></td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL AMOUNT</th>
                                        <td class="text-red text-center">{{ number_format($totalOtherDed, 2) }}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
@include('payroll.modals')
<!-- /End Modal -->
@endsection