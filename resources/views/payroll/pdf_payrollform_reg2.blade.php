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
        font-size: 9px;
        table-layout: fixed; /* Fixed table layout */
      }
      
      .table td,
      .table th {
        padding: 0.3rem;
        vertical-align: top;
        border-top: 1px solid #000408;
        font-size: 11px;
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
            @php
                $no = 1;
                $uniqueGroupByValues = array_unique(array_column($datas, 'offid', 'office_name')); 
                $totalrowEarnperiod = 0;
                $totalGroups = count($uniqueGroupByValues);
                $currentGroupIndex = 0;
                $columns_regtotal = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
                $columns_regtotalded = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];               
           @endphp
            @foreach ($uniqueGroupByValues as $officeAbbr => $groupValue)
            @php
              $rowEarnSum = 0;
              $rowEarnsArray = [];
              $modrefcoltotal = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
              $moddedcoltotal = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
            @endphp
            <div class="table-responsive">
                  @php
                  $modifythRefund = array_fill_keys(['Column1', 'Column2', 'Column3', 'Column4', 'Column5', 'Column6', 'Column7'], 0);
                  $modifythDeduction = array_fill_keys(['Column1', 'Column2', 'Column3', 'Column4', 'Column5', 'Column6', 'Column7'], 0);
                  @endphp
              
                  @foreach ($modify1 as $mody)
                      @if ($mody->action == 'Refund' && array_key_exists($mody->column, $modifythRefund))
                          @php
                              $modifythRefund[$mody->column] += $mody->amount;
                              $modifythRefund[$mody->column] = ($modifythRefund[$mody->column] >= 1) ? 1 : 0;
                          @endphp
                      @endif
              
                      @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $modifythDeduction))
                          @php
                              $modifythDeduction[$mody->column] += $mody->amount;
                              $modifythDeduction[$mody->column] = ($modifythDeduction[$mody->column] >= 1) ? 1 : 0;
                          @endphp
                      @endif
                  @endforeach
              <strong style="font-size: 12px;">{{ $officeAbbr }}</strong>@if ($officeAbbr !== 'GEN. ADMIN & SUPPORT STAFF') <br><br> @endif
              @if ($officeAbbr == 'GEN. ADMIN & SUPPORT STAFF')
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <tr>
                  <th colspan="{{ 14 + array_sum($modifythRefund) + array_sum($modifythDeduction) }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                </tr>
                <tr>
                  <th colspan="{{ 14 + array_sum($modifythRefund) + array_sum($modifythDeduction) }}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                </tr>
              </table>
              @endif
              <table class="table table-bordered" style="margin-top: -17px;">
                <thead>
                    <tr>
                    <th colspan="8"></th>
                        @if(array_sum($modifythRefund) > 0)<th colspan="{{ $pid == 2 ? array_sum($modifythRefund) : '' }}">Additional</th>@endif
                        <th colspan="2"></th>
                        @if(array_sum($modifythDeduction) > 0)<th colspan="{{ $pid == 2 ? array_sum($modifythDeduction) : '' }}">Deduction</th>@endif
                        <th colspan="4"></th>
                    </tr>
                    <th width="2%">NO.</th>
                    <th width="6.75%">Name</th>
                    <th width="6.75%">Position On Title</th>
                    <th width="5.75%">SG-Step</th>
                    <th width="5.75%">Monthly<br>Salary</th>
                    <th width="5.75%">SSL Salary <br>Differential</th>
                    <th width="5.75%">NBC 451 Salary <br> Differential 2023</th>
                    <th width="5.75%">Step <br>Increment</th>
                    @php
                    $columns_reg = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
                    @endphp
                    
                    @foreach ($modify1 as $mody)
                        @if ($mody->action === 'Refund' && array_key_exists($mody->column, $columns_reg))
                            @php
                                $columns_reg[$mody->column] += $mody->amount;
                            @endphp
                        @endif
                    @endforeach
                    
                    @foreach ($columns_reg as $column => $total)
                        @if ($total != 0.00)
                        @foreach ($modify1 as $mody)
                            @if ($mody->column === $column)
                                <th width="5.75%">{{ $mody->label }}</th>
                                @break
                            @endif
                        @endforeach
                        @endif
                    @endforeach  
                    <th width="5.75%">Less <br>Absences </th>
                    <th width="5.75%">Earned For <br>The Period</th>
                    @php
                    $columns_reg = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
                    @endphp
                    
                    @foreach ($modify1 as $mody)
                        @if ($mody->action === 'Deduction' && array_key_exists($mody->column, $columns_reg))
                            @php
                                $columns_reg[$mody->column] += $mody->amount;
                            @endphp
                        @endif
                    @endforeach
                    
                    @foreach ($columns_reg as $column => $total)
                        @if ($total != 0.00)
                        @foreach ($modify1 as $mody)
                            @if ($mody->column === $column)
                                <th width="5.75%">{{ $mody->label }}</th>
                                @break
                            @endif
                        @endforeach
                        @endif
                    @endforeach  
                    <th width="5.75%">Total <br>Overall<br> Deductions</th>
                    <th width="5.75%">Net<br>Ammount<br>Received</th>
                    <th width="5.75%">{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $firstHalf) !!}</th>
                    <th width="5.75%">{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $secondHalf) !!}</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  $earn_for_the_period = 0;
                  $alltotalgsis = 0;
                  $alltotalpagibig = 0;
                  $total_payables = 0;
                  $total_philhealth = 0;
                  $total_withholdingtax = 0;
                  $totalalldeduct = 0;
                  $netamout = 0;

                  $totalmonthlysalary = 0;
                  $totalabsences = 0;
                  $totalabsences1 = 0;
                  $totalstepencre = 0;
                  $totalnbcdiff = 0;
                  $totalsaldiff = 0;
                  $totalhalft = 0;

                  @endphp
                
                @foreach ($datas as $data)
                  @php 
                    $modrefcoltotalrow = 0; 
                    $moddedcoltotalrow = 0; 
                  @endphp
                  @if ($data->offid === $groupValue)
                    @php
                    $saltype = $data->sal_type;
                    
                    $monthlysalary = $data->salary_rate;
                    $salary = $data->salary_rate;
                    $absences =  $data->add_less_abs;
                    $absences1 =  $data->add_less_abs1;
                    $total_additional = round(($data->add_sal_diff + $data->add_nbc_diff + $data->add_step_incre) - ($absences), 2);
                    $total_gsis_deduction = $data->eml + $data->pol_gfal + $data->consol + $data->ed_asst_mpl + $data->loan + $data->rlip + $data->gfal + $data->computer + $data->health;
                    $total_pugibig_deduction = $data->mpl + $data->prem + $data->calam_loan + $data->mp2 + $data->house_loan;
                    $total_other_payables = $data->lbp + $data->cauyan + $data->projects + $data->nsca_mpc + $data->med_deduction + $data->grad_guarantor + $data->cfi + $data->csb + $data->fasfeed + $data->dis_unliquidated;
                    $total_deduction = round($total_gsis_deduction + $total_pugibig_deduction + $data->philhealth + $data->holding_tax + $total_other_payables, 2);
                    $earnperiod = $salary + $total_additional;
                
                    $totalmonthlysalary += $monthlysalary;
                    $earn_for_the_period += $earnperiod;
                    $alltotalgsis += $total_gsis_deduction;
                    $alltotalpagibig += $total_pugibig_deduction;
                    $total_philhealth += $data->philhealth;
                    $total_payables += $total_other_payables;
                    $total_withholdingtax += $data->holding_tax;
                    $totalalldeduct += $total_deduction;
                    $netamout += $salary + $total_additional - $total_deduction;
                    $totalabsences += $data->add_less_abs;
                    $totalabsences1 += $data->add_less_abs1;
                    $totalstepencre += $data->add_step_incre;
                    $totalnbcdiff += $data->add_nbc_diff;
                    $totalsaldiff += $data->add_sal_diff;

                    $totalhalft += $salary + $total_additional - $total_deduction; 

                    $rowEarn = 0;
                    $rowEarns = round($salary + $total_additional - $total_deduction, 2);
                    $decimalPoint = ($rowEarns - floor($rowEarns)) * 100;
                    $decimalPoint = round($decimalPoint);
                    
                    if ($saltype == 1) {
                        $rowEarn = $rowEarns / 2;
                        
                        if ($decimalPoint % 2 === 0) {
                            $rowEarn = round($rowEarn, 2);
                        } else {
                            $rowEarn = round($rowEarn, 3);
                            $rowEarn = floor($rowEarn * 100) / 100;
                        }
                    } elseif ($saltype == 3) {
                        $rowEarn = $rowEarns;
                    }
                    
                    $rowEarn = isset($rowEarn) ? $rowEarn : 0.00;
                    $rowEarnsArray[] = $rowEarn === null ? '0.00' : $rowEarn;
                    $rowEarnSum = array_sum($rowEarnsArray);
                    $rowtotal = $rowEarn + $data->sumRef - $data->sumDed;
                    $secondhalftotal = round($rowEarnSum, 2);
                    @endphp
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ ucwords(strtolower($data->lname)) }} {{ ucwords(strtolower($data->fname)) }}</td>
                      <td>{{ $data->emp_pos }}</td>
                      <td>{{ $data->sg }}</td>
                      <td>{{ number_format($monthlysalary, 2) }}</td>
                      <td>{{ number_format($data->add_sal_diff, 2) }}</td>
                      <td>{{ number_format($data->add_nbc_diff, 2) }}</td>
                      <td>{{ number_format($data->add_step_incre, 2) }}</td>
                      {{-- Adjustments Refund --}}
                      @php
                      $rowTotalRef = 0; // Initialize row total for each row
                      @endphp

                      @foreach ($modify1 as $mody)
                          @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Refund' && array_key_exists($mody->column, $columns_regtotal))
                              @php
                                  $columns_regtotal[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
                      
                      @foreach ($modify1 as $mody)
                          @if ($mody->action == 'Refund' && array_key_exists($mody->column, $columns_regtotal))
                              @php
                                  $columns_regtotal[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach

                      @foreach ($modify1 as $mody)
                          @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $columns_regtotal))
                              @php
                                  $modrefregTotalAmount = $columns_regtotal[$mody->column];
                              @endphp
                              @if ($modrefregTotalAmount != 0.00)
                                  <td>{{ $mody->action === 'Refund' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                  @if ($mody->action === 'Refund')
                                      @php
                                          $modrefcoltotal[$mody->column] = isset($modrefcoltotal[$mody->column]) ? $modrefcoltotal[$mody->column] + $mody->amount : $mody->amount;
                                          $rowTotalRef += $mody->amount;
                                      @endphp
                                  @endif
                              @endif
                          @endif
                      @endforeach
                      <td>{{ number_format($data->add_less_abs1, 2) }}</td>
                      @php $saltype == 1 || $saltype == 3 ? $totalrowEarnperiod += round($rowTotalRef, 2) : '0.00' @endphp
                      <td >{{ $saltype == 1 || $saltype == 3 ? number_format($rowEarn + ($rowTotalRef - $absences1), 2) : '0.00' }}</td>
                      {{-- Adjustments Deduction --}}  
                      @php
                      $rowTotalDed = 0; // Initialize row total for each row
                      @endphp
                      @foreach ($modify1 as $mody)
                          @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Deduction' && array_key_exists($mody->column, $columns_regtotalded))
                              @php
                                  $columns_regtotalded[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
          
                      @foreach ($modify1 as $mody)
                          @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $columns_regtotalded))
                              @php
                                  $columns_regtotalded[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
          
                      @foreach ($modify1 as $mody)
                          @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $columns_regtotalded))
                              @php
                                  $modedregTotalAmount = $columns_regtotalded[$mody->column];
                              @endphp
                              @if ($modedregTotalAmount != 0.00)
                                  <td>{{ $mody->action === 'Deduction' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                  @if ($mody->action === 'Deduction')
                                      @php
                                          $moddedcoltotal[$mody->column] = isset($moddedcoltotal[$mody->column]) ? $moddedcoltotal[$mody->column] + $mody->amount : $mody->amount;
                                          $rowTotalDed += $mody->amount; // Add amount to the row total
                                      @endphp
                                  @endif
                              @endif
                          @endif
                      @endforeach
                      {{-- Adjustments Deduction End --}}
                      <td>{{ number_format($rowTotalDed, 2) }}</td>
                      <td >{{ $saltype == 1 || $saltype == 3 ? number_format($rowEarn + ($rowTotalRef - $absences1) - ($rowTotalDed), 2) : '0.00' }}</td>
                      <td></td>
                      <td >{{ $saltype == 1 || $saltype == 3 ? number_format($rowEarn + ($rowTotalRef - $absences1) - ($rowTotalDed), 2) : '0.00' }}</td>
                      </tr>
                      @endif
                  @endforeach
                  @php
                    $grandTotalMonthlySal[] = $totalmonthlysalary;
                    $grandtotalsaldiff[] = $totalsaldiff;
                    $grandtotalnbcdiff[] = $totalnbcdiff;
                    $grandtotalstepencre[] = $totalstepencre;
                    $grandtotalabsences[] = $totalabsences1;
                    $grandtotarefund[] = $rowTotalRef;
                    $grandearn_for_the_period[] = $rowEarnSum;
                  @endphp
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($totalmonthlysalary, 2) }}</td>
                    <td>{{ number_format($totalsaldiff, 2) }}</td>
                    <td>{{ number_format($totalnbcdiff, 2) }}</td>
                    <td>{{ number_format($totalstepencre, 2) }}</td>
                    @php     
                      $grandcolumns_regtotal = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
                    @endphp

                    @foreach ($modify1 as $mody)
                      @if ($mody->action === 'Refund' && array_key_exists($mody->column, $grandcolumns_regtotal))
                          @php
                              if ($mody->amount > 0) {
                                  $grandcolumns_regtotal[$mody->column] = $mody->label;
                              }
                          @endphp
                      @endif
                    @endforeach

                    @foreach ($grandcolumns_regtotal as $column => $label)
                      @if (!empty($label))
                          <td style="color: red;" width="5.75%">{{ $label }}</td>
                      @endif
                    @endforeach


                    {{-- @if(isset($modrefcoltotal))
                    @foreach ($columns_reg as $column => $totalAmount)
                            @if (array_key_exists($column, $modrefcoltotal))
                                @if($modrefcoltotal[$column] > 0)
                                    <td>{{ number_format($modrefcoltotal[$column], 2) }}</td>
                                    @php
                                        $modrefcoltotalrow += $modrefcoltotal[$column];
                                    @endphp
                                @elseif($modrefcoltotal[$column] !== 0)
                                    <td>0.00</td>
                                @endif
                            @endif
                        @endforeach
                    @endif --}}
                    <td>{{ number_format($totalabsences1, 2) }}</td>
                    <td>{{ number_format($secondhalftotal + ($modrefcoltotalrow - $totalabsences1), 2) }}</td>
                    @if(isset($moddedcoltotal))
                    @php     
                        $grandcolumns_regtotaldedKey = $groupValue . '_grandcolumns_regtotalded';
                        $$grandcolumns_regtotaldedKey = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
                    @endphp
                    
                    @foreach ($modify1 as $mody)
                        @if ($mody->action === 'Deduction' && array_key_exists($mody->column, $$grandcolumns_regtotaldedKey))
                            @php
                                $$grandcolumns_regtotaldedKey[$mody->column] += $mody->amount;
                            @endphp
                        @endif
                    @endforeach
                    
                    @foreach ($$grandcolumns_regtotaldedKey as $column => $label)
                        <td style="color: red;" width="5.75%">{{ $label }}</td>
                    @endforeach
                
                
                
                        {{-- @foreach ($columns_reg as $column => $totalAmount)
                            @if (array_key_exists($column, $moddedcoltotal))
                                @if($moddedcoltotal[$column] > 0)
                                  <td>{{ number_format($moddedcoltotal[$column], 2) }}</td>
                                  @php
                                      $moddedcoltotalrow += $moddedcoltotal[$column];
                                  @endphp
                                @elseif($moddedcoltotal[$column] !== 0)
                                    <td>0.00</td>
                                @endif
                            @endif
                        @endforeach --}}
                    @endif
                
                    <td>{{ number_format($moddedcoltotalrow, 2) }}</td>
                    <td>{{ number_format($secondhalftotal + ($modrefcoltotalrow - $totalabsences1) - ($moddedcoltotalrow), 2) }}</td>
                    <td></td>
                    <td>{{ number_format($secondhalftotal + ($modrefcoltotalrow - $totalabsences1) - ($moddedcoltotalrow), 2) }}</td>
                  </tr>
                </tbody>      
                <tfoot>
                    <tr>
                      <td width="2%"></td>
                      <td width="5.75%"></td>
                      <td width="5.75%"></td>
                      <td width="5.75%"></td>
                      <td width="5.75%">{{ number_format(array_sum($grandTotalMonthlySal), 2) }}</td>
                      <td width="5.75%">{{ number_format(array_sum($grandtotalsaldiff), 2) }}</td>
                      <td width="5.75%">{{ number_format(array_sum($grandtotalnbcdiff ),2) }}</td>
                      <td width="5.75%">{{ number_format(array_sum($grandtotalstepencre ),2) }}</td>
                      @php
                        $grandcolumns_regtotal = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
                        $totalRefundAmount = 0;
                      @endphp
                      
                      @foreach ($modify1 as $mody)
                          @if ($mody->action === 'Refund' && array_key_exists($mody->column, $grandcolumns_regtotal))
                              @php
                                  $grandcolumns_regtotal[$mody->column] += $mody->amount;
                                  $totalRefundAmount += $mody->amount; 
                              @endphp
                          @endif
                      @endforeach
                      
                      @foreach ($grandcolumns_regtotal as $column => $totalAmount)
                          @if ($totalAmount > 0)
                              <td width="5.75%">{{ number_format($totalAmount, 2) }}</td>
                              @php  @endphp
                          @endif

                      @endforeach

                      <td width="5.75%">{{ number_format(array_sum($grandtotalabsences), 2); }}</td>
                      <td width="5.75%">{{ number_format((array_sum($grandearn_for_the_period) + $totalRefundAmount) - (array_sum($grandtotalabsences)),2) }}</td>

                      @php
                        $grandcolumns_dedtotal = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0];
                        $totalDeductAmount = 0;
                      @endphp
                      
                      @foreach ($modify1 as $mody)
                          @if ($mody->action === 'Deduction' && array_key_exists($mody->column, $grandcolumns_dedtotal))
                              @php
                                  $grandcolumns_dedtotal[$mody->column] += $mody->amount;
                                  $totalDeductAmount += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
                      
                      @foreach ($grandcolumns_dedtotal as $column1 => $totalAmount1)
                          @if ($totalAmount1 > 0)
                              <td width="5.75%">{{ number_format($totalAmount1, 2) }}</td>
                          @endif
                      @endforeach
                      <td></td>
                      <td width="5.75%">
                        {{ number_format((array_sum($grandearn_for_the_period) + $totalRefundAmount) - (is_array($grandtotalabsences) ? array_sum($grandtotalabsences) : $grandtotalabsences) - (is_array($totalDeductAmount) ? array_sum($totalDeductAmount) : $totalDeductAmount), 2) }}
                      </td>
                      <td width="5.75%"></td>
                      <td width="5.75%">
                          {{ number_format((array_sum($grandearn_for_the_period) + $totalRefundAmount) - (is_array($grandtotalabsences) ? array_sum($grandtotalabsences) : $grandtotalabsences) - (is_array($totalDeductAmount) ? array_sum($totalDeductAmount) : $totalDeductAmount), 2) }}
                      </td>
                    </tr>
                  </tfoot>
              </table>
              @endforeach
                @php
                  $grandTotalMonthlySal = array_sum($grandTotalMonthlySal);
                  $grandtotalsaldiff = array_sum($grandtotalsaldiff);
                  $grandtotalnbcdiff = array_sum($grandtotalnbcdiff);
                  $grandtotalstepencre = array_sum($grandtotalstepencre);
                  $grandtotalabsences = array_sum($grandtotalabsences);
                  $grandtotarefund = array_sum($grandtotarefund);
                  $grandearn_for_the_period = array_sum($grandearn_for_the_period);
                @endphp
              <table class="table table-bordered" style="table-layout: auto; width: 100%; max-width: none;">
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
         
                    </td>
                    <td style="border-right:;"></td>
                    <td colspan="3" style="border-left:;">
                        <br><div><strong>Salaries & Wedges - Civilian</strong></div><br>
          
                    </td>    
                    <td colspan="4">
                        <br><div><strong>Salaries & Wedges - Civilian</strong></div><br>
          
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
            </div>
          </div>
        </div>
      </div>    
    </body>    
</html>


