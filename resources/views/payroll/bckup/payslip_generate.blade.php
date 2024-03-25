<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Pay Slip Form</title>
  <style>
    body {
      font-size: 9px;
      display: none;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .col-quarter {
      width: 100%;
      box-sizing: border-box;
      padding: 0 5px;
    }

    .table {
      width: 100%;
      max-width: 100%;
      margin-bottom: 1rem;
      background-color: transparent;
      border-collapse: collapse;
      font-family: calibri;
      border: 2px solid black;
    }

    .row:after {
      content: "";
      display: table;
      clear: both;
    }

  </style>
</head>
<body>
  @php
  $payslipsPerPage = 4;
  $payslipCount = count($payslip);
  $totalPages = ceil($payslipCount / $payslipsPerPage);
@endphp

@for ($page = 1; $page <= $totalPages; $page++)
  <div class="container">
    @php
      $startIndex = ($page - 1) * $payslipsPerPage;
      $endIndex = min($startIndex + $payslipsPerPage, $payslipCount);

      // Calculate midpoint for this page
      $midpoint = $startIndex + ($endIndex - $startIndex) / 2;

      // Split the payslips into two halves
      $firstHalf = array_slice($payslip->toArray(), $startIndex, $midpoint - $startIndex);

    @endphp
      <div class="col-quarter">
        <table>
          @foreach ($firstHalf as $pay)
          @php
              $dateTime = new DateTime($pay['payroll_dateStart'] ?? null);
              $formattedDate = $dateTime->format('M, Y');
              $earnperiod = ($pay['salary_rate'] ?? 0) + ($pay['add_sal_diff'] ?? 0) + ($pay['add_nbc_diff'] ?? 0) + ($pay['add_step_diff'] ?? 0);
          @endphp
          <th>
            <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <thead>
                <tr>
                  <th colspan="4" style="border-bottom: none;">Republic of the Philippines<br>CENTRAL PHILIPPINES STATE UNIVERSITY<br>Kabankalan City, Negros Occidental </th>
                </tr>
                <tr>
                  <th colspan="4" style="border-top: none; border-bottom: none;"><span style="font-size: 18px; font-weight: 800px; font">PAY SLIP</span><br>For the month of {{ $formattedDate }}</th>
                </tr>
                <tr>
                  <th colspan="4" style="border-top: none; border-bottom: none;"><span style="float: left;">{{ $pay['lname'] }} {{ $pay['fname'] }} {{ $pay['mname'] }}</span><br></th>
                </tr>
                <tr>
                  <th colspan="4" style="border-top: none; border-bottom: none;">
                    <span style="float: left;">Basic Monthly&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>SSL 4 Differential<br>Less: Deductions&nbsp;&nbsp;</span><span style="float: right; font-size: 12px;">{{ number_format($earnperiod, 2) }}</span>
                  </th>
                </tr>
                </thead>
                <tbody>
                  @php
                  $eml = $pay['eml'];
                  $pol_gfal = $pay['pol_gfal'];
                  $consol = $pay['consol'];
                  $loan = $pay['loan'];
                  $ed_asst_mpl = $pay['ed_asst_mpl'];
                  $rlip = $pay['rlip'];
                  $gfal = $pay['gfal'];
                  $computer = $pay['computer'];
                  $health = $pay['health'];
                  $mpl = $pay['mpl'];
                  $prem = $pay['prem'];
                  $mp2 = $pay['mp2'];
                  $house_loan = $pay['house_loan'];
                  $calam_loan = $pay['calam_loan'];
                  $philhealth = $pay['philhealth'];
                  $holding_tax = $pay['holding_tax'];
                  $lbp = $pay['lbp'];
                  $cauyan = $pay['cauyan'];
                  $nsca_mpc = $pay['nsca_mpc'];
                  $med_deduction = $pay['med_deduction'];
                  $grad_guarantor = $pay['grad_guarantor'];
                  $cfi = $pay['cfi'];
                  $csb = $pay['csb'];
                  $projects = $pay['projects'];
                  $fasfeed = $pay['fasfeed'];
                  $dis_unliquidated = $pay['dis_unliquidated'];
              @endphp

                  @foreach ($modify as $mod)
                      @if ($mod['payroll_id'] == $pay['pfile_id'])
                          @php
                              if ($mod->affected == 'eml') {
                                  $eml = $mod->action == 'Deduction' ? $eml - $mod->amount : $eml + $mod->amount;
                              }
                              elseif ($mod->affected == 'pol_gfal') {
                                  $pol_gfal = $mod->action == 'Deduction' ? $pol_gfal - $mod->amount : $pol_gfal + $mod->amount;
                              }
                              elseif ($mod->affected == 'consol') {
                                  $consol = $mod->action == 'Deduction' ? $consol - $mod->amount : $consol + $mod->amount;
                              }
                              elseif ($mod->affected == 'loan') {
                                  $loan = $mod->action == 'Deduction' ? $loan - $mod->amount : $loan + $mod->amount;
                              }
                              elseif ($mod->affected == 'ed_asst_mpl') {
                                  $ed_asst_mpl = $mod->action == 'Deduction' ? $ed_asst_mpl - $mod->amount : $ed_asst_mpl + $mod->amount;
                              }
                              elseif ($mod->affected == 'rlip') {
                                  $rlip = $mod->action == 'Deduction' ? $rlip - $mod->amount : $rlip + $mod->amount;
                              }
                              elseif ($mod->affected == 'gfal') {
                                  $gfal = $mod->action == 'Deduction' ? $gfal - $mod->amount : $gfal + $mod->amount;
                              }
                              elseif ($mod->affected == 'computer') {
                                  $computer = $mod->action == 'Deduction' ? $computer - $mod->amount : $computer + $mod->amount;
                              }
                              elseif ($mod->affected == 'health') {
                                  $health = $mod->action == 'Deduction' ? $health - $mod->amount : $health + $mod->amount;
                              }
                              elseif ($mod->affected == 'mpl') {
                                  $mpl = $mod->action == 'Deduction' ? $mpl - $mod->amount : $mpl + $mod->amount;
                              }
                              elseif ($mod->affected == 'prem') {
                                  $prem = $mod->action == 'Deduction' ? $prem - $mod->amount : $prem + $mod->amount;
                              }
                              elseif ($mod->affected == 'house_loan') {
                                  $house_loan = $mod->action == 'Deduction' ? $house_loan - $mod->amount : $house_loan + $mod->amount;
                              }
                              elseif ($mod->affected == 'calam_loan') {
                                  $calam_loan = $mod->action == 'Deduction' ? $calam_loan - $mod->amount : $calam_loan + $mod->amount;
                              }
                              elseif ($mod->affected == 'philhealth') {
                                  $philhealth = $mod->action == 'Deduction' ? $philhealth - $mod->amount : $philhealth + $mod->amount;
                              }
                              elseif ($mod->affected == 'holding_tax') {
                                  $holding_tax = $mod->action == 'Deduction' ? $holding_tax - $mod->amount : $holding_tax + $mod->amount;
                              }
                              elseif ($mod->affected == 'lbp') {
                                  $lbp = $mod->action == 'Deduction' ? $lbp - $mod->amount : $lbp + $mod->amount;
                              }
                              elseif ($mod->affected == 'cauyan') {
                                  $cauyan = $mod->action == 'Deduction' ? $cauyan - $mod->amount : $cauyan + $mod->amount;
                              }
                              elseif ($mod->affected == 'nsca_mpc') {
                                  $nsca_mpc = $mod->action == 'Deduction' ? $nsca_mpc - $mod->amount : $nsca_mpc + $mod->amount;
                              }
                              elseif ($mod->affected == 'med_deduction') {
                                  $med_deduction = $mod->action == 'Deduction' ? $med_deduction - $mod->amount : $med_deduction + $mod->amount;
                              }
                              elseif ($mod->affected == 'grad_guarantor') {
                                  $grad_guarantor = $mod->action == 'Deduction' ? $grad_guarantor - $mod->amount : $grad_guarantor + $mod->amount;
                              }
                              elseif ($mod->affected == 'cfi') {
                                  $cfi = $mod->action == 'Deduction' ? $cfi - $mod->amount : $cfi + $mod->amount;
                              }
                              elseif ($mod->affected == 'csb') {
                                  $csb = $mod->action == 'Deduction' ? $csb - $mod->amount : $csb + $mod->amount;
                              }
                              elseif ($mod->affected == 'fasfeed') {
                                  $fasfeed = $mod->action == 'Deduction' ? $fasfeed - $mod->amount : $fasfeed + $mod->amount;
                              } 
                              elseif ($mod->affected == 'dis_unliquidated') {
                                  $dis_unliquidated = $mod->action == 'Deduction' ? $dis_unliquidated - $mod->amount : $dis_unliquidated + $mod->amount;
                              }
                          @endphp
                      @endif
                  @endforeach
                  <tr>
                    <td colspan="3" width="25%"></td>
                    <td width="25%" style="color: white;">.</td>
                  </tr>
                  <tr>
                    <td colspan="3" width="25%"></td>
                    <td width="25%" style="color: white;">.</td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%" style="color: white;">.</td>
                  </tr>
  
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS MPL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['eml'] = number_format($eml, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS Policy Loan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['pol_gfal'] = number_format($pol_gfal, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS CONSOL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['consol'] = number_format($consol, 2) }}</td>
                    <td width="25%"></td>
                  </tr>      
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS MPL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['loan'] =  number_format($loan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS EDUC. ASST</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['ed_asst_mpl'] =  number_format($ed_asst_mpl, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS RLIP</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['rlip'] =  number_format($rlip, 2)  }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS GFAL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['gfal'] = number_format($gfal, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS COMPUTER</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['computer'] = number_format($computer, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS health</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['health'] = number_format($health, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG MPL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['mpl'] = number_format($mpl, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG PREM.</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['prem'] = number_format($prem, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG MP2</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['mp2'] = number_format($mp2, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG House loan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['house_loan'] = number_format($house_loan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Calamity Loan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['calam_loan'] = number_format($calam_loan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Philhealth</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['philhealth'] = number_format($philhealth, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Withholding Tax</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['holding_tax'] = number_format($holding_tax, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">LBP</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['lbp'] = number_format($lbp, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Cauyan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['cauyan'] =  number_format($cauyan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">NSCA MPC</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['nsca_mpc'] = number_format($nsca_mpc, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Medical Deduction</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['med_deduction'] = number_format($med_deduction, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GRAD SCH/GUAR.</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['grad_guarantor'] = number_format($grad_guarantor, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">CFI</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['cfi'] = number_format($cfi, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">CSB</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['csb'] = number_format($csb, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Projects</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['projects'] = number_format($projects, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">FASFEED</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['fasfeed'] = number_format($fasfeed, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Disallow Ance / UNLIQ.</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['dis_unliquidated'] = number_format($dis_unliquidated, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                </tbody>
                <tfoot>
                  @php
                      $totalsWithoutCommas = array_map(function ($value) {
                          return str_replace(',', '', $value);
                      }, $totals);

                      $sumOfTotals = array_sum($totalsWithoutCommas);
                  @endphp
                  <tr>
                    <td width="25%" style="font-weight: 800px;"><strong>TOTAL DEDUCTIONS</strong></td>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%"><span  style="float: right; font-size: 12px;">{{ number_format($sumOfTotals, 2) }}</span></td>
                  </tr>
                  <tr>
                    <td width="25%" style="font-weight: 800px;"><strong>NET TAKE HOME PAY</strong></td>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%"><span  style="float: right; color: red; font-size: 12px;">{{ number_format($earnperiod  - $sumOfTotals, 2) }}</span></td>
                  </tr>
                  <tr>
                    <td colspan="3" style="text-align: right; padding-top: 10px;">Certified Correct:</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: center; padding-top: 10px;">PAYROLL IN-CHARGE</td>
                  </tr>
                </tfoot>
            </table>
          </th>
          @endforeach
        </table>
      </div>
  </div>
  <div class="container">
    @php
      $startIndex = ($page - 1) * $payslipsPerPage;
      $endIndex = min($startIndex + $payslipsPerPage, $payslipCount);

      // Calculate midpoint for this page
      $midpoint = $startIndex + ($endIndex - $startIndex) / 2;

      // Split the payslips into two halves
      $secondHalf = array_slice($payslip->toArray(), $midpoint, $endIndex - $midpoint);

    @endphp
      <div class="col-quarter">
        <table>
          @foreach ($secondHalf as $pay)
          @php
              $dateTime = new DateTime($pay['payroll_dateStart'] ?? null);
              $formattedDate = $dateTime->format('M, Y');
              $earnperiod = ($pay['salary_rate'] ?? 0) + ($pay['add_sal_diff'] ?? 0) + ($pay['add_nbc_diff'] ?? 0) + ($pay['add_step_diff'] ?? 0);
          @endphp
          <th>
            <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <thead>
                <tr>
                  <th colspan="4" style="border-bottom: none;">Republic of the Philippines<br>CENTRAL PHILIPPINES STATE UNIVERSITY<br>Kabankalan City, Negros Occidental </th>
                </tr>
                <tr>
                  <th colspan="4" style="border-top: none; border-bottom: none;"><span style="font-size: 18px; font-weight: 800px; font">PAY SLIP</span><br>For the month of {{ $formattedDate }}</th>
                </tr>
                <tr>
                  <th colspan="4" style="border-top: none; border-bottom: none;"><span style="float: left;">{{ $pay['lname'] }} {{ $pay['fname'] }} {{ $pay['mname'] }}</span><br></th>
                </tr>
                <tr>
                  <th colspan="4" style="border-top: none; border-bottom: none;">
                    <span style="float: left;">Basic Monthly&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>SSL 4 Differential<br>Less: Deductions&nbsp;&nbsp;</span><span style="float: right; font-size: 12px;">{{ number_format($earnperiod, 2) }}</span>
                  </th>
                </tr>
                </thead>
                <tbody>
                  @php
                  $eml = $pay['eml'];
                  $pol_gfal = $pay['pol_gfal'];
                  $consol = $pay['consol'];
                  $loan = $pay['loan'];
                  $ed_asst_mpl = $pay['ed_asst_mpl'];
                  $rlip = $pay['rlip'];
                  $gfal = $pay['gfal'];
                  $computer = $pay['computer'];
                  $health = $pay['health'];
                  $mpl = $pay['mpl'];
                  $prem = $pay['prem'];
                  $mp2 = $pay['mp2'];
                  $house_loan = $pay['house_loan'];
                  $calam_loan = $pay['calam_loan'];
                  $philhealth = $pay['philhealth'];
                  $holding_tax = $pay['holding_tax'];
                  $lbp = $pay['lbp'];
                  $cauyan = $pay['cauyan'];
                  $nsca_mpc = $pay['nsca_mpc'];
                  $med_deduction = $pay['med_deduction'];
                  $grad_guarantor = $pay['grad_guarantor'];
                  $cfi = $pay['cfi'];
                  $csb = $pay['csb'];
                  $projects = $pay['projects'];
                  $fasfeed = $pay['fasfeed'];
                  $dis_unliquidated = $pay['dis_unliquidated'];
              @endphp

                  @foreach ($modify as $mod)
                      @if ($mod['payroll_id'] == $pay['pfile_id'])
                          @php
                              if ($mod->affected == 'eml') {
                                  $eml = $mod->action == 'Deduction' ? $eml - $mod->amount : $eml + $mod->amount;
                              }
                              elseif ($mod->affected == 'pol_gfal') {
                                  $pol_gfal = $mod->action == 'Deduction' ? $pol_gfal - $mod->amount : $pol_gfal + $mod->amount;
                              }
                              elseif ($mod->affected == 'consol') {
                                  $consol = $mod->action == 'Deduction' ? $consol - $mod->amount : $consol + $mod->amount;
                              }
                              elseif ($mod->affected == 'loan') {
                                  $loan = $mod->action == 'Deduction' ? $loan - $mod->amount : $loan + $mod->amount;
                              }
                              elseif ($mod->affected == 'ed_asst_mpl') {
                                  $ed_asst_mpl = $mod->action == 'Deduction' ? $ed_asst_mpl - $mod->amount : $ed_asst_mpl + $mod->amount;
                              }
                              elseif ($mod->affected == 'rlip') {
                                  $rlip = $mod->action == 'Deduction' ? $rlip - $mod->amount : $rlip + $mod->amount;
                              }
                              elseif ($mod->affected == 'gfal') {
                                  $gfal = $mod->action == 'Deduction' ? $gfal - $mod->amount : $gfal + $mod->amount;
                              }
                              elseif ($mod->affected == 'computer') {
                                  $computer = $mod->action == 'Deduction' ? $computer - $mod->amount : $computer + $mod->amount;
                              }
                              elseif ($mod->affected == 'health') {
                                  $health = $mod->action == 'Deduction' ? $health - $mod->amount : $health + $mod->amount;
                              }
                              elseif ($mod->affected == 'mpl') {
                                  $mpl = $mod->action == 'Deduction' ? $mpl - $mod->amount : $mpl + $mod->amount;
                              }
                              elseif ($mod->affected == 'prem') {
                                  $prem = $mod->action == 'Deduction' ? $prem - $mod->amount : $prem + $mod->amount;
                              }
                              elseif ($mod->affected == 'house_loan') {
                                  $house_loan = $mod->action == 'Deduction' ? $house_loan - $mod->amount : $house_loan + $mod->amount;
                              }
                              elseif ($mod->affected == 'calam_loan') {
                                  $calam_loan = $mod->action == 'Deduction' ? $calam_loan - $mod->amount : $calam_loan + $mod->amount;
                              }
                              elseif ($mod->affected == 'philhealth') {
                                  $philhealth = $mod->action == 'Deduction' ? $philhealth - $mod->amount : $philhealth + $mod->amount;
                              }
                              elseif ($mod->affected == 'holding_tax') {
                                  $holding_tax = $mod->action == 'Deduction' ? $holding_tax - $mod->amount : $holding_tax + $mod->amount;
                              }
                              elseif ($mod->affected == 'lbp') {
                                  $lbp = $mod->action == 'Deduction' ? $lbp - $mod->amount : $lbp + $mod->amount;
                              }
                              elseif ($mod->affected == 'cauyan') {
                                  $cauyan = $mod->action == 'Deduction' ? $cauyan - $mod->amount : $cauyan + $mod->amount;
                              }
                              elseif ($mod->affected == 'nsca_mpc') {
                                  $nsca_mpc = $mod->action == 'Deduction' ? $nsca_mpc - $mod->amount : $nsca_mpc + $mod->amount;
                              }
                              elseif ($mod->affected == 'med_deduction') {
                                  $med_deduction = $mod->action == 'Deduction' ? $med_deduction - $mod->amount : $med_deduction + $mod->amount;
                              }
                              elseif ($mod->affected == 'grad_guarantor') {
                                  $grad_guarantor = $mod->action == 'Deduction' ? $grad_guarantor - $mod->amount : $grad_guarantor + $mod->amount;
                              }
                              elseif ($mod->affected == 'cfi') {
                                  $cfi = $mod->action == 'Deduction' ? $cfi - $mod->amount : $cfi + $mod->amount;
                              }
                              elseif ($mod->affected == 'csb') {
                                  $csb = $mod->action == 'Deduction' ? $csb - $mod->amount : $csb + $mod->amount;
                              }
                              elseif ($mod->affected == 'fasfeed') {
                                  $fasfeed = $mod->action == 'Deduction' ? $fasfeed - $mod->amount : $fasfeed + $mod->amount;
                              } 
                              elseif ($mod->affected == 'dis_unliquidated') {
                                  $dis_unliquidated = $mod->action == 'Deduction' ? $dis_unliquidated - $mod->amount : $dis_unliquidated + $mod->amount;
                              }
                          @endphp
                      @endif
                  @endforeach
                  <tr>
                    <td colspan="3" width="25%"></td>
                    <td width="25%" style="color: white;">.</td>
                  </tr>
                  <tr>
                    <td colspan="3" width="25%"></td>
                    <td width="25%" style="color: white;">.</td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%" style="color: white;">.</td>
                  </tr>
  
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS MPL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['eml'] = number_format($eml, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS Policy Loan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['pol_gfal'] = number_format($pol_gfal, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS CONSOL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['consol'] = number_format($consol, 2) }}</td>
                    <td width="25%"></td>
                  </tr>      
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS MPL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['loan'] =  number_format($loan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS EDUC. ASST</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['ed_asst_mpl'] =  number_format($ed_asst_mpl, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS RLIP</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['rlip'] =  number_format($rlip, 2)  }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS GFAL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['gfal'] = number_format($gfal, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS COMPUTER</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['computer'] = number_format($computer, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GSIS health</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['health'] = number_format($health, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG MPL</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['mpl'] = number_format($mpl, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG PREM.</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['prem'] = number_format($prem, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG MP2</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['mp2'] = number_format($mp2, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">PAG-IBIG House loan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['house_loan'] = number_format($house_loan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Calamity Loan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['calam_loan'] = number_format($calam_loan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Philhealth</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['philhealth'] = number_format($philhealth, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Withholding Tax</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['holding_tax'] = number_format($holding_tax, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">LBP</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['lbp'] = number_format($lbp, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Cauyan</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['cauyan'] =  number_format($cauyan, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">NSCA MPC</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['nsca_mpc'] = number_format($nsca_mpc, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Medical Deduction</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['med_deduction'] = number_format($med_deduction, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">GRAD SCH/GUAR.</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['grad_guarantor'] = number_format($grad_guarantor, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">CFI</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['cfi'] = number_format($cfi, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">CSB</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['csb'] = number_format($csb, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Projects</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['projects'] = number_format($projects, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">FASFEED</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['fasfeed'] = number_format($fasfeed, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                  <tr>
                    <td width="25%"></td>
                    <td width="25%">Disallow Ance / UNLIQ.</td>
                    <td width="25%" style="border-bottom: 1px solid #97a8b9;">{{ $totals['dis_unliquidated'] = number_format($dis_unliquidated, 2) }}</td>
                    <td width="25%"></td>
                  </tr>
                </tbody>
                <tfoot>
                  @php
                      $totalsWithoutCommas = array_map(function ($value) {
                          return str_replace(',', '', $value);
                      }, $totals);

                      $sumOfTotals = array_sum($totalsWithoutCommas);
                  @endphp
                  <tr>
                    <td width="25%" style="font-weight: 800px;"><strong>TOTAL DEDUCTIONS</strong></td>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%"><span  style="float: right; font-size: 12px;">{{ number_format($sumOfTotals, 2) }}</span></td>
                  </tr>
                  <tr>
                    <td width="25%" style="font-weight: 800px;"><strong>NET TAKE HOME PAY</strong></td>
                    <td width="25%"></td>
                    <td width="25%"></td>
                    <td width="25%"><span  style="float: right; color: red; font-size: 12px;">{{ number_format($earnperiod  - $sumOfTotals, 2) }}</span></td>
                  </tr>
                  <tr>
                    <td colspan="3" style="text-align: right;">Certified Corret:</td>
                    <td></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: center;">PAYROLL IN-CHARGE</td>
                  </tr>
                </tfoot>
            </table>
          </th>
          @endforeach
        </table>
      </div>
  </div>
  @if ($page < $totalPages)
    <!-- Add page break after each page -->
    <div style="page-break-after: always;"></div>
  @endif
@endfor
</body>
</html>
