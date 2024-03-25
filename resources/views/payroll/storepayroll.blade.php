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

.custom-legend {
      display: flex;
      align-items: center;
    }

    .label-square {
      display: inline-block;
      width: 20px;
      height: 20px;
      background-color: #28a745;
      margin-right: 10px;
      border-radius: 4px;
    }

    .label-text {
      font-size: 16px;
    }

    .warning-label {
      color: #856404;
      background-color: #fff3cd;
      border: 1px solid #ffeeba;
      border-radius: 0.25rem;
      padding: 0.25em 0.5em;
    }

</style>
<div class="container-fluid">
    <div class="row" style="padding-top: 100px;">
        <div class="col-lg-12">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-5">
                            <h3 class="card-title">
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modal-importPayrollsTwo">
                                    <i class="fas fa-plus"></i> Add New
                                </button>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-codes">
                                    <i class="fas fa-plus"></i> Code
                                </button>
                                <div class="btn-group">
                                    <button id="open-pdf" target="_blank"  class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa-solid fa-money-bill-1"></i> Payroll
                                    </button>
                                    <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                        <a href="{{ route("pdf", ['payrollID' => $payrollID, 'statID' => $statID, 'pid' => 1, 'stat' => 1, 'offid' => $offID]) }}" target="_blank" class="dropdown-item bg-secondary p-2">{{ $firstHalf }}</a>
                                        <a href="{{ route("pdf", ['payrollID' => $payrollID, 'statID' => $statID, 'pid' => 2, 'stat' => 1, 'offid' => $offID]) }}" target="_blank" class="dropdown-item bg-secondary p-2">{{ $secondHalf }}</a>
                                        {{-- <a href="{{ route("pdf", ['payrollID' => $payrollID, 'statID' => $statID, 'pid' => 1, 'stat' => 2, 'offid' => $offID]) }}" target="_blank" class="dropdown-item bg-warning p-2">Late Processing</a> --}}
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button id="open-pdf" target="_blank"  class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-file-pdf"></i> Transmittal
                                    </button>
                                    <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                        <a href="{{ route("transmittal", ['payrollID' => $payrollID, 'statID' => $statID, 'pid' => 1, 'stat' => 1, 'offid' => $offID]) }}" target="_blank" class="dropdown-item bg-secondary p-2">{{ $firstHalf }}</a>
                                        <a href="{{ route("transmittal", ['payrollID' => $payrollID, 'statID' => $statID, 'pid' => 2, 'stat' => 1, 'offid' => $offID]) }}" target="_blank" class="dropdown-item bg-secondary p-2">{{ $secondHalf }}</a>
                                    </div>
                                </div>
                                <div class="btn-group">
                                    <button id="open-pdf" target="_blank"  class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-file-pdf"></i> Remittance
                                    </button>
                                    <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                        <a href="#" target="_blank" class="dropdown-item"></a>
                                        <a href="#" target="_blank" class="dropdown-item"></a>
                                        <a href="#" target="_blank" class="dropdown-item"></a> 
                                        <a href="#" target="_blank" class="dropdown-item"></a>
                                        <a href="#" target="_blank" class="dropdown-item"></a>
                                        <a href="{{ route("remittance", ['col' => 'eml', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> EML</a>
                                        <a href="{{ route("remittance", ['col' => 'pol_gfal', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> POLICY</a>
                                        <a href="{{ route("remittance", ['col' => 'consol', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Consol</a>
                                        <a href="{{ route("remittance", ['col' => 'ed_asst_mpl', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> MPL loan</a>
                                        <a href="{{ route("remittance", ['col' => 'rlip', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> RLIP</a>
                                        <a href="{{ route("remittance", ['col' => 'gfal', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> GFAL</a>
                                        <a href="{{ route("remittance", ['col' => 'computer', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Computer</a>
                                        <a href="{{ route("remittance", ['col' => 'health', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Health</a>
                                        <a href="{{ route("remittance", ['col' => 'mpl', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> MPL</a>
                                        <a href="{{ route("remittance", ['col' => 'prem', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> PREM.</a>
                                        <a href="{{ route("remittance", ['col' => 'calam_loan', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Calamity Loan</a>
                                        <a href="{{ route("remittance", ['col' => 'mp2', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> MP2.</a>
                                        <a href="{{ route("remittance", ['col' => 'house_loan', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Housing Loan</a>
                                        <a href="{{ route("remittance", ['col' => 'philhealth', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Philhealth</a>
                                        <a href="{{ route("remittance", ['col' => 'holding_tax', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Withholding Tax</a>
                                        <a href="{{ route("remittance", ['col' => 'lbp', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> LBP</a>
                                        <a href="{{ route("remittance", ['col' => 'cauyan', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Cauyan</a>
                                        <a href="{{ route("remittance", ['col' => 'projects', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Projects</a>
                                        <a href="{{ route("remittance", ['col' => 'nsca_mpc', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> NSCA MPC</a>
                                        <a href="{{ route("remittance", ['col' => 'med_deduction', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Medical Deduction</a>
                                        <a href="{{ route("remittance", ['col' => 'grad_guarantor', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Graduate Sch. / Guar.</a>
                                        <a href="{{ route("remittance", ['col' => 'cfi', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> CFI</a>
                                        <a href="{{ route("remittance", ['col' => 'csb', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> CSB</a>
                                        <a href="{{ route("remittance", ['col' => 'fasfeed', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> FASFEED</a>
                                        <a href="{{ route("remittance", ['col' => 'dis_unliquidated', 'payrollID' => $payrollID]) }}" target="_blank" class="dropdown-item"> Disallow ANCE / UNLIQUIDATED</a>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modal-voucher">
                                    <i class="fa-solid fa-ticket"></i> Voucher
                                </button>
                            </h3>
                        </div>
                        <div class="col-md-7">
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
                                <li class="breadcrumb-item">{{ $daterange }}</li>
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
                                <div class="custom-legend float-first"><br><br>
                                    <div class="label-square bg-success"></div>
                                    <div class="label-text mr-2">Complete within 1-15</div>
                                    <div class="label-square bg-warning"></div>
                                    <div class="label-text mr-2">Complete within 1-31</div>
                                    <div class="label-square bg-primary"></div>
                                    <div class="label-text mr-2">Complete after 1-31</div>
                                    <div class="label-square bg-danger"></div>
                                    <div class="label-text mr-2">NO DTR & No Voucher</div>
                                    <div class="label-square bg-info"></div>
                                    <div class="label-text mr-2">With DTR & With Vouchers</div>
                                </div><br>
                                <br>
                                <table id="example1" class="table table-bordered table-hover table-pay">
                                    <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th width="6%">Emp. ID</th>
                                                <th width="10%">Full Name</th>
                                                <th>Dept/Office</th>
                                                <th>Position</th>
                                                <th>Monthly Salary</th>
                                                <th>Total Add.</th>
                                                <th>Absent <br> 1-15</th>
                                                <th>Earn for period</th>
                                                <th>Total Ded.</th>
                                                <th>Net amount</th>
                                                <th>{{ $firstHalf }}</th>
                                                <th>{{ $secondHalf }}</th>
                                                {{-- <th>Type</th> --}}
                                                <th>Status</th>
                                                <th width="7%" class="text-center">Action</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        @php 
                                            $no = 1;
                                        @endphp
                                        @foreach ($pfiles as $p)
                                            @php 
                                                $RamountTotal = 0;
                                                $absent = $p->add_less_abs;
                                                // $absent1 = $p->add_less_abs1;
                                                $total_add = round($p->add_sal_diff + $p->add_nbc_diff + $p->add_step_incre - $absent, 2); 
                                                
                                                $total_deduct = round($p->eml + $p->pol_gfal + $p->consol + $p->ed_asst_mpl + $p->loan + $p->rlip + $p->gfal + $p->computer + $p->health
                                                + $p->mpl + $p->prem + $p->calam_loan + $p->mp2 + $p->house_loan + $p->philhealth + $p->holding_tax + $p->lbp + $p->cauyan + $p->projects + $p->nsca_mpc + $p->med_deduction
                                                + $p->grad_guarantor + $p->cfi + $p->csb + $p->fasfeed + $p->dis_unliquidated, 2);

                                                $Rrate_per_day = $p->salary_rate / $days;
                                                $Rrate_per_hour = $Rrate_per_day / 8;
                                                $Ramount = $p->salary_rate;
                                                
                                                $Jrate_per_hour = $p->salary_rate / 8;

                                                $RamountReg = $p->salary_rate + $total_add;

                                                $RamountTotal += $RamountReg;
                                                
                                                $pstatus = $p->status;

                                                $earn = round($Ramount + $total_add - $total_deduct, 2);
                                                $decimalPoint = ($earn - floor($earn)) * 100;
                                                $decimalPoint = round($decimalPoint);
                                                $earns = ($Ramount + $total_add - $total_deduct) / 2;
                                             
                                                if ($pstatus == 1) {
                                                    if ($decimalPoint % 2 === 0) {
                                                    $earns = round($earns, 2);
                                                    
                                                    } else {
                                                        $earns = round($earns, 3);
                                                        $earns = floor($earns * 100) / 100;
                                                    }
                                                    $sechalearn = $earns;
                                                    $sechalearn1 = ($Ramount + $total_add - $total_deduct) /  2;
                                                } else {
                                                    $sechalearn = $Ramount + $total_add - $total_deduct;
                                                    $sechalearn1 = $Ramount + $total_add - $total_deduct;
                                                }
                                            @endphp
                                            <tr id="tr-data" class="tr-data tr-{{ $p->pid }}">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $p->emp_ID }}</td>
                                                <td>{{ $p->lname }} {{ $p->fname }} {{ $p->mname }}</td>
                                                <td>{{ $p->office_abbr }}</td>
                                                <td>{{ $p->position }}</td>
                                                <td>{{ number_format($p->salary_rate, 2) }}</td>
                                                <td id="addition-{{ $p->pid }}">{{ number_format($total_add + $absent, 2); }}</td>
                                                <td>{{ number_format($absent, 2); }}</td>
                                                <td class="text-danger">{{ number_format($RamountReg, 2) }}</td>
                                                <td id="deduct-{{ $p->pid }}">{{ number_format($total_deduct, 2); }}</td>
                                                <td id="net-{{ $p->pid }}" class="@if($Ramount + $total_add - $total_deduct <= 3000)text-danger @endif">{{ number_format($Ramount + $total_add - $total_deduct, 2) }}</td>
                                                <td class="firstHalf">{{$pstatus == 1 ? number_format($sechalearn1, 2) : '0.00' }}</td>
                                                <td class="secondtHalf{{$pstatus == 3 ? '-Incomplete' : '' }}">{{ number_format($sechalearn + $p->sumRef - $p->sumDed, 2);}}</td>
                                                {{-- <td>
                                                    <div class="btn-group">
                                                        <button type="button" style="height:32px; width: 110px;" class="btn btn-{{ $p->sal_type == 1 ? 'success' : ''}}{{ $p->sal_type == 3 ? 'success' : ''}} dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        {{ $p->sal_type == 1 ? 'Allocate' : '' }}{{ $p->sal_type == 3 ? 'Apportion' : '' }}
                                                        </button>
                                                        <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                                            <a href="{{ route('saltypepUp', ['id' => $p->pid, 'val' => '1']) }}" class="dropdown-item bg-success p-2">Allocate</a>
                                                            <a href="{{ route('saltypepUp', ['id' => $p->pid, 'val' => '3']) }}" class="dropdown-item bg-success p-2 mt-1">Apportion</a>
                                                        </div>                                                        
                                                    </div> 
                                                </td> --}}
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" style="height:32px; width: 140px;" class="btn btn-{{$pstatus == 1 ? 'success' : '' }}{{$pstatus == 2 ? 'warning' : '' }}{{$pstatus == 3 && $p->voucher != 1 ? 'danger' : '' }}{{$pstatus == 3 && $p->voucher == 1 ? 'info' : '' }} dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                            {{$pstatus == 1 ? 'Complete' : '' }}
                                                            {{$pstatus == 2 ? 'Complete' : '' }}
                                                            {{$pstatus == 3 && $p->voucher != 1 ? 'NO DTR' : '' }}
                                                            @if ($p->status == 3 && $p->voucher == 1)
                                                                <div style="color:white; margin-top: -6px; font-size: 11px;">NO DTR</div>
                                                                <div style="color:white; margin-left: 28px; font-size: 11px; height: 14px; width: 60px; margin-top: -5px; color: white; border-radius: 5px;">(complied)</div>
                                                            @endif
                                                        </button>                                          
                                                        <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                                            <a href="{{ route('statUpdate', ['id' => $p->pid, 'val' => '1']) }}" class="dropdown-item bg-success p-2 mt-1">Complete </a>
                                                            <a href="{{ route('statUpdate', ['id' => $p->pid, 'val' => '2']) }}" class="dropdown-item bg-warning p-2 mt-1">Complete 2</a>
                                                            <a href="{{ route('statUpdate', ['id' => $p->pid, 'val' => '3']) }}" class="dropdown-item bg-danger p-2 mt-1">NO DTR</a>
                                                        </div>                                                        
                                                    </div> 
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <button type="button" style="height:32px;" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                        </button>
                                                        <div class="dropdown-menu" x-out-of-boundaries="" style="">
                                                        <button id="{{ $p->pid }}" value="1" data-date="{{ $firstHalf }}" data-modes="1" data-stat="{{ $p->stat_ID }}" style="height:32px;" class="dropdown-item additional" title="additionals">Additional {{ $firstHalf }}</button>
                                                        <button id="{{ $p->pid }}" value="1" data-date="{{ $firstHalf }}" data-modes="3" data-stat="{{ $p->stat_ID }}" class="dropdown-item deductions">Deduction {{ $firstHalf }}</button>
                                                        <button id="{{ $p->pid }}" value="2" data-date="{{ $secondHalf }}" data-modes="4" data-stat="{{ $p->stat_ID }}" class="dropdown-item deductions">Adjustments {{ $secondHalf }}</button>
                                                        @if($p->voucher == 1) <a href="{{ route('pdfVoucher', $p->pid) }}" target="_blank" class="dropdown-item">View Voucher</a> @endif
                                                        </div>
                                                    </div>
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
                            {{-- @if($p->status != 3) --}}
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
                            {{-- @endif --}}
                        @endforeach
                        @if(isset($deduction))
                        <div class="col-10 mt-3">
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="9" class="text-center">DEDUCTION(GSIS)</th>
                                        <th></th>
                                        <th colspan="5" class="text-center">DEDUCTION(PAG-IBIG)</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>EML</th>
                                        <th>POLICY</th>
                                        <th>Consol</th>
                                        <th>EDUC. ASST</th>
                                        <th>MPL loan</th>
                                        <th>RLIP</th>
                                        <th>GFAL</th>
                                        <th>Computer</th>
                                        <th>Help</th>
                                        <th class="text-center">Total</th>
                                        <th>MPL loan</th>
                                        <th>PREM.</th>
                                        <th>Calaming Loan</th>
                                        <th>MP2.</th>
                                        <th>House Loan</th>
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
                                        $health = $deduction->sum('health');

                                        $totalGSIS = $eml + $pol_gfal + $consol + $ed_asst_mpl + $loan + $rlip + $gfal + $computer + $health;

                                        $mpl = $deduction->sum('mpl');
                                        $prem = $deduction->sum('prem');
                                        $calam_loan = $deduction->sum('calam_loan');
                                        $mp2 = $deduction->sum('mp2');
                                        $house_loan = $deduction->sum('house_loan');

                                        $totalPagibig = $mpl + $prem + $calam_loan + $mp2 + $house_loan;

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

                                        $totalOtherDed = $lbp + $cauyan + $projects + $nsca_mpc + $med_ded + $grad_schll + $cfi + $csb + $fasfeed + $diss_ance;

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
                                        <td>{{ number_format($health, 2) }}</td>
                                        <td class="text-red text-center" width="7%">{{ number_format($totalGSIS, 2) }}</td>
                                        <td>{{ number_format($mpl, 2) }}</td>
                                        <td>{{ number_format($prem, 2) }}</td>
                                        <td>{{ number_format($calam_loan, 2) }}</td>
                                        <td>{{ number_format($mp2, 2) }}</td>
                                        <td>{{ number_format($house_loan, 2) }}</td>
                                        <td class="text-red text-center" width="7%">{{ number_format($totalPagibig, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table><br>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th colspan="10" class="text-center">DEDUCTION(OTHER PAYABLES)</th>
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
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-red">{{ number_format($philhealth , 2) }}</td>
                                        <td class="text-red">{{ number_format($withtax , 2) }}</td>
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
                                        <td class="text-red text-center" width="7%">{{ number_format($totalOtherDed, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table><br>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="3" class="text-center">Additionals</th>
                                        <th class="text-center">Less</th>
                                        <th></th>
                                        <th colspan="16" class="text-center">Adjustments</th>
                                    </tr>
                                    <tr>
                                        <th width="7%">SSL Sal Diff.</th>
                                        <th width="7%">NBC Sal Diff</th>
                                        <th width="7%">Step Increment</th>
                                        <th width="7%">Less Abs incurr.</th>
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
                                        <td>{{ number_format($less_abs , 2) }}</td>
                                        <td class="text-red text-center" width="7%">{{ number_format($totalAdd - $less_abs, 2) }}</td>
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
                                    @php
                                        $modrefProject = 0;
                                    @endphp
                                    <tr>
                                        <td colspan="5"></td>
                                        <td class="totalRef">{{ number_format($modifyRef->get('Project', 0),2); }}</td>
                                        <td class="totalDed">{{ number_format($modifyDed->get('Project', 0),2); }}</td>
                                        <td class="totalRef">{{ number_format($modifyRef->get('Net_MPC', 0),2); }}</td>
                                        <td class="totalDed">{{ number_format($modifyDed->get('Net_MPC', 0),2); }}</td>
                                        <td class="totalRef">{{ number_format($modifyRef->get('Graduate', 0),2); }}</td>
                                        <td class="totalDed">{{ number_format($modifyDed->get('Graduate', 0),2); }}</td>
                                        <td class="totalRef">{{ number_format($modifyRef->get('Philhealth', 0),2); }}</td>
                                        <td class="totalDed">{{ number_format($modifyDed->get('Philhealth', 0),2); }}</td>
                                        <td class="totalRef">{{ number_format($modifyRef->get('Pag_ibig', 0),2); }}</td>
                                        <td class="totalDed">{{ number_format($modifyDed->get('Pag_ibig', 0),2); }}</td>
                                        <td class="totalRef">{{ number_format($modifyRef->get('Gsis', 0),2); }}</td>
                                        <td class="totalDed">{{ number_format($modifyDed->get('Gsis', 0),2); }}</td>
                                        <td class="totalRef">{{ number_format($modifyRef->get('Csb', 0),2); }}</td>
                                        <td class="totalDed">{{ number_format($modifyDed->get('Csb', 0),2); }}</td>
                                        <td id="totalRefundAll1" class="text-danger"></td>
                                        <td id="totalDeductAll1" class="text-danger"></td>
                                    </tr>
                                </tbody>
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
                                        <th>PHILHEALTH</th>
                                        <td class="text-center">{{ number_format($philhealth, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>WITHHOLDING TAX</th>
                                        <td class="text-center">{{ number_format($withtax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>OTHER PAYABLES</th>
                                        <td class="text-center">{{ number_format($totalOtherDed, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL AMOUNT</th>
                                        <td class="text-red text-center">{{ number_format($totalGSIS + $totalPagibig + $philhealth + $withtax + $totalOtherDed, 2) }}</td>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                            <table class="styled-table">
                                <thead>
                                    <tr>
                                        <th colspan="2" class="text-center">SUMMARRY (ADDITIONALS)</th>
                                    </tr>
                                    <tr>
                                        <th>ADDITIONALS</th>
                                        <td class="text-center">{{ number_format($totalAdd, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Less Abs incurr.</th>
                                        <td class="text-center">{{ number_format($less_abs, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL AMOUNT</th>
                                        <td class="text-red text-center">{{ number_format($totalAdd - $less_abs, 2) }}</td>
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
                                        <td id="totalRefundAll" class="text-red text-center"></td>
                                    </tr>
                                    <tr>
                                        <th>DEDUCTION</th>
                                        <td id="totalDeductAll" class="text-red text-center"></td>
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
