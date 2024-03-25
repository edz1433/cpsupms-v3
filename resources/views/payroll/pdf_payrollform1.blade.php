<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Payroll Form</title>
    <style>
      /* Default table styling */
      .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        border-collapse: collapse;
        font-size: 8.3px;
      }
      
      .table td,
      .table th {
        padding: 0.3rem;
        vertical-align: top;
        border-top: 1px solid #000408;
        font-size: 8.3px;
      }
  
      .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #000408;
      }
  
      .table tbody + tbody { 
        border-top: 2px solid #000408;
      }
  
      .table-bordered {
        border: 1px solid #000408;
      }
  
      .table-bordered td,
      .table-bordered th {
        border: 1px solid #000408;
      }
  
      .table-bordered thead td,
      .table-bordered thead th {
        border-bottom-width: 2px;
      }
  
      
  
      /* Responsive table styling */
      @media screen and (orientation: landscape) {
        .table-responsive {
          height: 500px; 
        }
        
        .table {
          width: auto;
        }
        
        .table td,
        .table th {
          white-space: nowrap; 
        }
      }

      .div-signature{
       width: 50%; 
       text-align: center;
      }

      .td{
        text-align: center;
      }
  
      </style>
  </head>
  <body>
    <body>
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="table-responsive">
              @foreach($chunkedDatas as $chunk)
              @if ($pid == 2)
                @php
                    $refTotal = array_fill_keys(['Project', 'Net_MPC', 'Graduate', 'Philhealth', 'Pag_ibig', 'Gsis', 'Csb'], 0);
                    $dedTotal = array_fill_keys(['Project', 'Net_MPC', 'Graduate', 'Philhealth', 'Pag_ibig', 'Gsis', 'Csb'], 0);
                @endphp
        
                @foreach ($modifyref1 as $mody)
                    @if ($mody->action == 'Refund' && array_key_exists($mody->column, $refTotal))
                        @php
                            $refTotal[$mody->column] += $mody->amount;
                            $refTotal[$mody->column] = ($refTotal[$mody->column] >= 1) ? 1 : 0;
                        @endphp
                    @endif
                @endforeach

                @foreach ($modifyded1 as $mody)
                    @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $dedTotal))
                        @php
                            $dedTotal[$mody->column] += $mody->amount;
                            $dedTotal[$mody->column] = ($dedTotal[$mody->column] >= 1) ? 1 : 0;
                        @endphp
                    @endif
                @endforeach
              @endif
        
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <thead>
                  <tr>
                    <th colspan="{{ $pid == 1 ? 19 : 14 + array_sum($refTotal) + array_sum($dedTotal) }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                  </tr>
				          <tr>
                    <th colspan="{{ $pid == 1 ? 19 : 14 + array_sum($refTotal) + array_sum($dedTotal) }}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                  </tr>
                  @if ($pid == 2)
                    <tr>
                    <th colspan="{{ $pid == 1 ? 8 : 8 }}"></th>
                        @if(array_sum($refTotal) > 0)<th colspan="{{ $pid == 2 ? array_sum($refTotal) : '' }}">Refund</th>@endif
                        <th colspan="2"></th>
                        @if(array_sum($dedTotal) > 0)<th colspan="{{ $pid == 2 ? array_sum($dedTotal) : '' }}">Deduction</th>@endif
                        <th colspan="4"></th>
                    </tr>
                  @endif
                    <th>NO.</th>
                    <th>Name</th>
                    <th width="70">Position On Title</th>
                    <th>SG-Step</th>
                    <th>Monthly<br>Salary</th>
                    <th>SSL Salary <br>Differential</th>
                    <th>NBC 451 Salary <br> Differential 2023</th>
                    <th>Step <br>Increment</th>
                      @if ($pid == 2)
                        @php
                            $refTotals = [
                                'Project' => 0,
                                'Net_MPC' => 0,
                                'Graduate' => 0,
                                'Philhealth' => 0,
                                'Pag_ibig' => 0,
                                'Gsis' => 0,
                                'Csb' => 0,
                            ];
                        @endphp
                    
                        @foreach ($modify1 as $mody)
                            @if ($mody->action === 'Refund' && array_key_exists($mody->column, $refTotals))
                                @php
                                    $refTotals[$mody->column] += $mody->amount; 
                                @endphp
                            @endif
                        @endforeach
                    
                        @foreach ($refTotals as $column => $total)
                            @if ($total != 0.00)
                                <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                            @endif
                        @endforeach
                    @endif
                    <th>Less <br>Absences </th>
                    <th>Earned For <br>The Period</th>
                    @if ($pid == 2)
                        @php
                            $refTotals = [
                                'Project' => 0,
                                'Net_MPC' => 0,
                                'Graduate' => 0,
                                'Philhealth' => 0,
                                'Pag_ibig' => 0,
                                'Gsis' => 0,
                                'Csb' => 0,
                            ];
                        @endphp
                    
                        @foreach ($modify1 as $mody)
                            @if ($mody->action === 'Deduction' && array_key_exists($mody->column, $refTotals))
                                @php
                                    $refTotals[$mody->column] += $mody->amount; 
                                @endphp
                            @endif
                        @endforeach
                    
                        @foreach ($refTotals as $column => $total)
                            @if ($total != 0.00)
                                <th>{{ ucfirst(str_replace('_', ' ', $column)) }}</th>
                            @endif
                        @endforeach
                    @endif
                    @if($pid == 1)
                    <th>Total<br>GSIS<br>Deductions</th>
                    <th>Total<br>PAG-IBIG<br>Deductions</th>
                    <th>PHIL<br>HEALTH</th>
                    <th>With <br>Holding<br>Tax</th>
                    <th>Total <br>Other<br> Payables</th>
                    @endif
                    <th>Total <br>Overall<br> Deductions</th>
                    <th>Net<br>Ammount<br>Received</th>
                    <th>{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $firstHalf) !!}</th>
                    <th>{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $secondHalf) !!}</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  $no = 1;
                  $earn_for_the_period = 0;
                  $alltotalgsis = 0;
                  $alltotalpagibig = 0;
                  $total_payables = 0;
                  $total_philhealth = 0;
                  $total_withholdingtax = 0;
                  $totalalldeduct = 0;
                  $netamout = 0;

                  $totalabsences = 0;
                  $totalstepencre = 0;
                  $totalnbcdiff = 0;
                  $totalsaldiff = 0;
                  $totalhalft = 0;
                  @endphp
                
                  @foreach ($chunk as $data)
                    @if($data->offid == $offid)
                    @php
                    $saltype = $data->sal_type;

                    $total_additional = $data->add_sal_diff + $data->add_nbc_diff + $data->add_step_incre - $data->add_less_abs;
                    $total_gsis_deduction = $data->eml + $data->pol_gfal + $data->consol + $data->ed_asst_mpl + $data->loan + $data->rlip + $data->gfal + $data->computer;
                    $total_pugibig_deduction = $data->mpl + $data->prem + $data->calam_loan + $data->mp2;
                    $total_other_payables = $data->lbp + $data->cauyan + $data->projects + $data->nsca_mpc + $data->med_deduction + $data->grad_guarantor + $data->cfi + $data->csb + $data->fasfeed + $data->dis_unliquidated;
                    $total_deduction = $total_gsis_deduction + $total_pugibig_deduction + $data->philhealth + $data->holding_tax + $total_other_payables;
                    $earnperiod = $total_additional + $data->salary_rate;
                
                    $earn_for_the_period += $earnperiod;
                    $alltotalgsis += $total_gsis_deduction;
                    $alltotalpagibig += $total_pugibig_deduction;
                    $total_philhealth += $data->philhealth;
                    $total_payables += $total_other_payables;
                    $total_withholdingtax += $data->holding_tax;
                    $totalalldeduct += $total_deduction;
                    $netamout += $data->salary_rate + $total_additional - $total_deduction;
                    $totalabsences += $data->add_less_abs;
                    $totalstepencre += $data->add_step_incre;
                    $totalnbcdiff += $data->add_nbc_diff;
                    $totalsaldiff += $data->add_sal_diff;

                    $totalhalft += $data->salary_rate + $total_additional - $total_deduction; 

                    $rowEarnSum = 0;
                    $rowEarnSum1 = 0;
                    $rowEarnSum2 = 0;
                    $rowEarnSum3 = 0;

                    $rowEarns = round($data->salary_rate + $total_additional - $total_deduction, 2);
                    $decimalPoint = ($rowEarns * 100) % 100;
                    
                    if ($pid == 1) {
                      if($saltype == 1 ){
                        $rowEarn = $rowEarns / 2;
                      } 
                      elseif ($saltype == 2) {
                          $rowEarn = $rowEarns;
                      } elseif ($saltype == 3) {
                          $rowEarn = 0;
                      }
                      $rowEarn = isset($rowEarn) ? $rowEarn : null;

                      $rowEarnsArray[] = $rowEarn === null ? '0.00' : $rowEarn;

                      $rowEarnSum = array_sum($rowEarnsArray);
                      
                      $firsthalftotal = round($rowEarnSum, 2);
                    }

                    if ($pid == 2) {
                        if($saltype == 1){
                          $rowEarns = round($data->salary_rate + $total_additional - $total_deduction, 2);
                          $decimalPoint = ($rowEarns * 100) % 100;

                          $rowEarn = $rowEarns / 2;

                          if ($decimalPoint % 2 === 0) {
                              $rowEarn = round($rowEarn, 2);
                          } else {
                              $rowEarn = round($rowEarn, 3);
                              $rowEarn = floor($rowEarn * 100) / 100;
                          }
                        }
                        if($saltype == 3){
                          $rowEarn = $rowEarns;
                        }
                        
                        $rowEarn = isset($rowEarn) ? $rowEarn : 0.00;

                        $rowEarnsArray[] = $rowEarn === null ? '0.00' : $rowEarn;

                        $rowEarnSum = array_sum($rowEarnsArray);

                        $rowtotal = $rowEarn + $data->sumRef - $data->sumDed;
                        $secondhalftotal = round($rowEarnSum + $totalref - $totalded, 2);
                    }
                    @endphp
                    
                    @php
                    $totref = 0;
                    $totded = 0;
                    @endphp
                    
                    @foreach ($modifyref as $moref)
                        @if ($moref->payroll_id == $data->pid)
                            @php 
                              $totref += $moref->amount; 
                              $modtotref = $moref->amount; 
                            @endphp
                        @endif
                    @endforeach
                    
                    @foreach ($modifyded as $moded)
                        @if ($moded->payroll_id == $data->pid)
                            @php 
                              $totded += $moded->amount; 
                              $modtotded = $moded->amount; 
                            @endphp
                        @endif
                    @endforeach
   
                    {{-- Sub Total Columns --}}
                    @php
                    $refTotalCol = array_fill_keys(['Project', 'Net_MPC', 'Graduate', 'Philhealth', 'Pag_ibig', 'Gsis', 'Csb'], 0);
                    @endphp

                    @foreach ($modifyref1 as $mody)
                        @if ($mody->action == 'Refund' && array_key_exists($mody->column, $refTotalCol))
                            @php
                                $refTotalCol[$mody->column] += $mody->amount;
                            @endphp
                        @endif
                    @endforeach

                    @php
                    $dedTotalCol = array_fill_keys(['Project', 'Net_MPC', 'Graduate', 'Philhealth', 'Pag_ibig', 'Gsis', 'Csb'], 0);
                    @endphp

                    @foreach ($modifyded1 as $mody)
                        @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $dedTotalCol))
                            @php
                                $dedTotalCol[$mody->column] += $mody->amount;
                            @endphp
                        @endif
                    @endforeach
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $data->lname }} {{ $data->fname }}</td>
                      <td>{{ $data->emp_pos }}</td>
                      <td>{{ $data->sg }}</td>
                      <td>{{ number_format($data->salary_rate, 2) }}</td>
                      <td>{{ number_format($data->add_sal_diff, 2) }}</td>
                      <td>{{ number_format($data->add_nbc_diff, 2) }}</td>
                      <td>{{ number_format($data->add_step_incre, 2) }}</td>
                      {{-- Adjustments Refund --}}
                      @if ($pid == 2)
                      @php
                            $refTotals = [
                                'Project' => 0,
                                'Net_MPC' => 0,
                                'Graduate' => 0,
                                'Philhealth' => 0,
                                'Pag_ibig' => 0,
                                'Gsis' => 0,
                                'Csb' => 0
                            ];
                            $totalRefundAmount = 0; // Initialize the total refund amount
                        @endphp
                        
                        @foreach ($modifyref as $mody)
                            @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Refund' && array_key_exists($mody->column, $refTotals))
                                @php
                                    $refTotals[$mody->column] += $mody->amount;
                                @endphp
                            @endif
                        @endforeach
                        
                        @foreach ($modifyref1 as $mody)
                            @if ($mody->action == 'Refund' && array_key_exists($mody->column, $refTotals))
                                @php
                                    $refTotals[$mody->column] += $mody->amount;
                                @endphp
                            @endif
                        @endforeach
                        
                        @foreach ($modify as $mody)
                            @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $refTotals))
                                @php
                                    $refTotalAmount = $refTotals[$mody->column];
                                @endphp
                                @if ($refTotalAmount != 0.00)
                                    <td>{{ $mody->action === 'Refund' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                    @if ($mody->action === 'Refund')
                                        @php
                                            $totalRefundAmount += $mody->amount; // Add the refund amount to the total
                                        @endphp
                                    @endif
                                @endif
                            @endif
                        @endforeach
                      @endif
                      {{-- Adjustments Refund End --}}
                      <td>{{ number_format($data->add_less_abs, 2) }}</td>
                      <td>{{ $pid == 2 ? number_format($rowEarn + $totalRefundAmount, 2) : number_format($earnperiod, 2) }}</td>
                      {{-- Adjustments Deduction --}}
                      @if ($pid == 2)
                        @php
                        $dedTotals = [
                            'Project' => 0,
                            'Net_MPC' => 0,
                            'Graduate' => 0,
                            'Philhealth' => 0,
                            'Pag_ibig' => 0,
                            'Gsis' => 0,
                            'Csb' => 0
                        ];
                        $totalDeductionAmount = 0; // Initialize the total deduction amount
                        @endphp
                        
                        @foreach ($modifyded as $mody)
                            @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Deduction' && array_key_exists($mody->column, $dedTotals))
                                @php
                                    $dedTotals[$mody->column] += $mody->amount;
                                @endphp
                            @endif
                        @endforeach
                        
                        @foreach ($modifyded1 as $mody)
                            @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $dedTotals))
                                @php
                                    $dedTotals[$mody->column] += $mody->amount;
                                @endphp
                            @endif
                        @endforeach
                        
                        @foreach ($modify as $mody)
                            @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $dedTotals))
                                @php
                                    $dedTotalAmount = $dedTotals[$mody->column];
                                @endphp
                                @if ($dedTotalAmount != 0.00)
                                    <td>{{ $mody->action === 'Deduction' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                    @if ($mody->action === 'Deduction')
                                        @php
                                            $totalDeductionAmount += $mody->amount; // Add the deduction amount to the total
                                        @endphp
                                    @endif
                                @endif
                            @endif
                        @endforeach
                  
                      @endif
                      {{-- Adjustments Deduction End --}}
                      @if($pid == 1)
                      <td>{{ number_format($total_gsis_deduction, 2) }}</td>
                      <td>{{ number_format($total_pugibig_deduction, 2) }}</td>
                      <td>{{ number_format($data->philhealth, 2) }}</td>
                      <td>{{ number_format($data->holding_tax, 2) }}</td>
                      <td>{{ $pid == 1 ? number_format($total_other_payables, 2) : '00'}}</td>
                      @endif
                      <td>{{ $pid == 1 ? number_format($total_deduction, 2) : number_format($totded, 2) }}</td>
                      <td>{{ $pid == 1 ? number_format($data->salary_rate + $total_additional - $total_deduction, 2) : number_format($rowtotal, 2) }}</td>
                      <td>{{ $pid == 1 ? $saltype == 1 || $saltype == 2 ? number_format($rowEarn, 2) : '0.00' : '' }}</td>
                      <td @if($rowEarn < 3001) style="color: red;" @endif>{{ $pid == 2 ? $saltype == 1 || $saltype == 3 ? number_format($rowtotal, 2) : '0.00' : '' }}</td>
                      </tr>
                    @endif
                  @endforeach
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($totalsaldiff, 2) }}</td>
                    <td>{{ number_format($totalnbcdiff, 2) }}</td>
                    <td>{{ number_format($totalstepencre, 2) }}</td>
                    @if ($pid == 2)
                        @php
                            $refTotal = array_fill_keys(['Project', 'Net_MPC', 'Graduate', 'Philhealth', 'Pag_ibig', 'Gsis', 'Csb'], 0);
                        @endphp

                        @foreach ($modifyref as $mody)
                            @if ($mody->action == 'Refund' && array_key_exists($mody->column, $refTotal))
                                @php
                                    $refTotal[$mody->column] += $mody->amount;
                                @endphp
                            @endif
                        @endforeach

                        @foreach ($refTotal as $column => $total)
                            @php
                                $correspondingColTotal = $refTotalCol[$column];
                            @endphp
                        
                            @if ($correspondingColTotal > 0)
                                <td>{{ number_format($total, 2) }}</td>
                            @else
                                
                            @endif
                        @endforeach
                    @endif
                    <td>{{ number_format($totalabsences, 2) }}</td>
                    <td>{{ $pid == 2 ? number_format($secondhalftotal, 2) : number_format($earn_for_the_period, 2) }}</td>
                    @if($pid == 1)
                    <td>{{ number_format($alltotalgsis, 2) }}</td>
                    <td>{{ number_format($alltotalpagibig, 2) }}</td>
                    <td>{{ number_format($total_philhealth, 2) }}</td>
                    <td>{{ number_format($total_withholdingtax, 2) }}</td>
                    <td>{{ number_format($total_payables, 2) }}</td>
                    @else
                      @php
                      $dedTotal = array_fill_keys(['Project', 'Net_MPC', 'Graduate', 'Philhealth', 'Pag_ibig', 'Gsis', 'Csb'], 0);
                      @endphp

                      @foreach ($modifyded as $mody)
                          @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $dedTotal))
                              @php
                                  $dedTotal[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach

                      @foreach ($dedTotal as $column => $total)
                            @php
                                $correspondingColTotal = $dedTotalCol[$column];
                            @endphp
                        
                            @if ($correspondingColTotal > 0)
                                <td>{{ number_format($total, 2) }}</td>
                            @else
                                
                            @endif
                      @endforeach
                    @endif
                    <td>{{ $pid == 1 ? number_format($totalalldeduct + $total_withholdingtax, 2) : number_format($totalded, 2) }}</td>
                    <td>{{ $pid == 1 ? number_format($netamout, 2) : number_format($secondhalftotal, 2) }}</td>
                    <td>{{ $pid == 1 ? number_format($firsthalftotal, 2) : '' }}</td>
                    <td>{{ $pid == 2 ? number_format($secondhalftotal, 2) : ''}}</td>
                  </tr>
                </tbody>            
              @endforeach
              {{-- Grand Total Columns --}}
                @if($offid == 63)
                @php
                $earn_for_the_period = 0;
                $alltotalgsis = 0;
                $alltotalpagibig = 0;
                $total_payables = 0;
                $total_philhealth = 0;
                $total_withholdingtax = 0;
                $totalalldeduct = 0;
                $netamout = 0;

                $totalabsences = 0;
                $totalstepencre = 0;
                $totalnbcdiff = 0;
                $totalsaldiff = 0;
                $totalhalft = 0;
                @endphp
                <tfoot>
                  @foreach($chunkedDatas as $chunk)

                  @foreach($chunk as $data)
                  @php

                  $saltype = $data->sal_type;

                  $total_additional = $data->add_sal_diff + $data->add_nbc_diff + $data->add_step_incre - $data->add_less_abs;
                  $total_gsis_deduction = $data->eml + $data->pol_gfal + $data->consol + $data->ed_asst_mpl + $data->loan + $data->rlip + $data->gfal + $data->computer;
                  $total_pugibig_deduction = $data->mpl + $data->prem + $data->calam_loan + $data->mp2;
                  $total_other_payables = $data->lbp + $data->cauyan + $data->projects + $data->nsca_mpc + $data->med_deduction + $data->grad_guarantor + $data->cfi + $data->csb + $data->fasfeed + $data->dis_unliquidated;
                  $total_deduction = $total_gsis_deduction + $total_pugibig_deduction + $data->philhealth + $data->holding_tax + $total_other_payables;
                  $earnperiod = $total_additional + $data->salary_rate;
                
                  $earn_for_the_period += $earnperiod;
                  $alltotalgsis += $total_gsis_deduction;
                  $alltotalpagibig += $total_pugibig_deduction;
                  $total_philhealth += $data->philhealth;
                  $total_payables += $total_other_payables;
                  $total_withholdingtax += $data->holding_tax;
                  $totalalldeduct += $total_deduction;
                  $netamout += $data->salary_rate + $total_additional - $total_deduction;
                  $totalabsences += $data->add_less_abs;
                  $totalstepencre += $data->add_step_incre;
                  $totalnbcdiff += $data->add_nbc_diff;
                  $totalsaldiff += $data->add_sal_diff;

                  $totalhalft += $data->salary_rate + $total_additional - $total_deduction; 

                  $rowEarnSum = 0;
                  $rowEarnSum1 = 0;
                  $rowEarnSum2 = 0;
                  $rowEarnSum3 = 0;

                  $rowEarns = round($data->salary_rate + $total_additional - $total_deduction, 2);
                  $decimalPoint = ($rowEarns * 100) % 100;
                  
                  if ($pid == 1) {
                    if($saltype == 1 ){
                      $rowEarn = $rowEarns / 2;
                    } 
                    elseif ($saltype == 2) {
                        $rowEarn1 = $rowEarns;
                    } elseif ($saltype == 3) {
                        $rowEarn1 = 0;
                    }
                    $rowEarn1 = isset($rowEarn1) ? $rowEarn1 : null;

                    $rowEarnsArray[] = $rowEarn1 === null ? '0.00' : $rowEarn1;

                    $rowEarnSum = array_sum($rowEarnsArray);
                    
                    $firsthalftotal = round($rowEarnSum, 2);
                  }

                  if ($pid == 2) {
                      if($saltype == 1){
                        $rowEarns = round($data->salary_rate + $total_additional - $total_deduction, 2);
                        $decimalPoint = ($rowEarns * 100) % 100;

                        $rowEarn1 = $rowEarns / 2;

                        if ($decimalPoint % 2 === 0) {
                            $rowEarn1 = round($rowEarn1, 2);
                        } else {
                            $rowEarn1 = round($rowEarn1, 3);
                            $rowEarn1 = floor($rowEarn1 * 100) / 100;
                        }
                      }
                      if($saltype == 3){
                        $rowEarn1 = $rowEarns;
                      }
                      
                      $rowEarn1 = isset($rowEarn1) ? $rowEarn1 : 0.00;

                      $rowEarnsArray[] = $rowEarn1 === null ? '0.00' : $rowEarn1;

                      $rowEarnSum = array_sum($rowEarnsArray);

                      $rowtotal = $rowEarn1 + $data->sumRef - $data->sumDed;
                      $secondhalftotal = round($rowEarnSum + $totalref - $totalded, 2);
                  }
                  @endphp
                  
                  @php
                  $totref = 0;
                  $totded = 0;
                  @endphp
                  
                  @foreach ($modifyref1 as $moref)
                      @if ($moref->payroll_id == $data->pid)
                          @php 
                            $totref += $moref->amount; 
                            $modtotref = $moref->amount; 
                          @endphp
                      @endif
                  @endforeach
                  
                  @foreach ($modifyded1 as $moded)
                      @if ($moded->payroll_id == $data->pid)
                          @php 
                            $totded += $moded->amount; 
                            $modtotded = $moded->amount; 
                          @endphp
                      @endif
                  @endforeach
                  
                  @endforeach
                  <tr>
                    <td style="text-align: right" colspan="4">GRAND TOTAL AMOUNT:</td>
                    <td></td>
                    <td>{{ number_format($totalsaldiff, 2) }}</td>
                    <td>{{ number_format($totalnbcdiff, 2) }}</td>
                    <td>{{ number_format($totalstepencre, 2) }}</td>
                    @if ($pid == 2)
                        @foreach ($refTotalCol as $column => $total)
                            @if ($total >= 1)
                                <td>{{ number_format($total, 2) }}</td>
                            @else
                            
                            @endif
                        @endforeach
                    @endif
                    <td>{{ number_format($totalabsences, 2) }}</td>
                    <td>{{ $pid == 2 ? number_format($secondhalftotal, 2) : number_format($earn_for_the_period, 2) }}</td>
                    @if($pid == 1)
                    <td>{{ number_format($alltotalgsis, 2) }}</td>
                    <td>{{ number_format($alltotalpagibig, 2) }}</td>
                    <td>{{ number_format($total_philhealth, 2) }}</td>
                    <td>{{ number_format($total_withholdingtax, 2) }}</td>
                    <td>{{ $pid == 1 ? number_format($total_payables, 2) : '' }}</td>
                    @else
                      @foreach ($dedTotalCol as $column => $total)
                          @if ($total >= 1)
                              <td>{{ number_format($total, 2) }}</td>
                          @else
                              
                          @endif
                      @endforeach
                    @endif
                    <td>{{ $pid == 1 ? number_format($totalalldeduct + $total_withholdingtax, 2) : number_format($totalded, 2) }}</td>
                    <td>{{ $pid == 1 ? number_format($netamout, 2) : number_format($secondhalftotal, 2) }}</td>
                    <td>{{ $pid == 1 ? number_format($firsthalftotal, 2) : '' }}</td>
                    <td>{{ $pid == 2 ? number_format($secondhalftotal, 2) : ''}}</td>
                  </tr> 
                  @endforeach
                </tfoot>
                {{-- Grand Total Columns End --}}
              </table>
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <tbody class="last-page">
                  <tr>
                    <td colspan="4" style="border-right: ;">
                      <div>CERTIFIED CORRECT: Services have been duly rendered as stated.</div><br><br>
                      <div class="div-signature">FREIA  L. VARGAS, Ph.D.</div>
                      <div class="div-signature">Adminstrative Officer V. HRMO III</div><br>
                      <div>NOTED: </div><br>
                      <div class="div-signature">HENRY C. BOLINAS, Ph.D.</div>
                      <div class="div-signature">Chief Administartive Officer</div><br>
                      <div >CERTIFIED: Funds available in the amount of P</div><br><br>
                      <div class="div-signature">ELFRED M. SUMONGSONG, CPA</div>
                      <div class="div-signature">Accountant III</div><br>
                      <div >PREPARED BY:</div><br>
                      <div class="div-signature">CHRISTINE V. TAGUBILIN</div>
                      <div class="div-signature">Admin Aide III-Payroll In-Charge</div><br>
                    </td>
                    <td colspan="3" style="border-left: ; text-align: center;">
                      <br><div><strong>RECAPITULATION</strong></div><br>
                      @foreach($code as $c)
                      @if($c->status == "on") {{ $c->code }} @endif<br>
                      @endforeach
                    </td>
                    <td style="border-right:;"></td>
                    <td colspan="3" style="border-left:;">
                        <br><div><strong>Salaries & Wedges - Civilian</strong></div><br>
                        @foreach($code as $c)
                        @if($c->status == "on") {{ $c->code_name }} @endif<br>
                        @endforeach
                    </td>    
                    <td colspan="4">
                        <br><div><strong>Salaries & Wedges - Civilian</strong></div><br>
                        @foreach($code as $c)
                        @if($c->status == "on") {{ $c->code_name }} @endif<br>
                        @endforeach
                    </td>
                    <td colspan="6">
                      <div>Approved for Payment:</div><br><br><br><br>
                      <div class="div-signature" style="width: 100%;">ALADINO C. MORACA, Ph.D.</div>
                      <div class="div-signature" style="width: 100%;">SUC President II</div><br><br><br>
                      <div>CERTIFIED: That each employee whose name appears above has been paid the amount indicated through direct<br><span style="margin-left: 53px;">credit to their respective accounts.</span></div><br><br><br><br>
                      <div style="width: 100%;">
                        <div style="float: left; width: 50%; text-align: center;">
                          <div> ERNIE C. ONGAO</div>
                          <div>Administrative Officer I/Cashier Designate</div><br>
                        </div>
                        <div style="float: left; width: 50%; text-align: center;">
                          <div>________________</div>
                          <div>Date</div>
                        </div>
                      </div>
                    </td>               
                  </tr>
                </tbody>
              </table>
              @endif
            </div>
          </div>
        </div>
      </div>    
    </body>    
</html>


