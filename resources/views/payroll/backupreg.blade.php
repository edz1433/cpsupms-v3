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
                @php 
                  $noo = 1;
                  $uniqueGroupByValues = array_unique(array_column($datas, 'group_by', 'office_name')); 
                @endphp
                @php
                  $modifythRefund = array_fill_keys(['Column1', 'Column2', 'Column3', 'Column4', 'Column5', 'Column6', 'Column7'], 0);
                  $modifythDeduction = array_fill_keys(['Column1', 'Column2', 'Column3', 'Column4', 'Column5', 'Column6', 'Column7'], 0);
                @endphp
                @foreach ($uniqueGroupByValues as $officeAbbr => $groupValue)
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
                    
                    $totalRefundAmount = 0; 
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
              <strong style="font-size: 12px;">{{ $officeAbbr }}</strong>
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <thead>
                  <tr>
                    <th colspan="{{ 14 + array_sum($modifythRefund) + array_sum($modifythDeduction) }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                  </tr>
				          <tr>
                    <th colspan="{{ 14 + array_sum($modifythRefund) + array_sum($modifythDeduction) }}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                  </tr>
                    <tr>
                    <th colspan="8"></th>
                        @if(array_sum($modifythRefund) > 0)<th colspan="{{ $pid == 2 ? array_sum($modifythRefund) : '' }}">Refund</th>@endif
                        <th colspan="2"></th>
                        @if(array_sum($modifythDeduction) > 0)<th colspan="{{ $pid == 2 ? array_sum($modifythDeduction) : '' }}">Deduction</th>@endif
                        <th colspan="4"></th>
                    </tr>
                    <th>NO.</th>
                    <th>Name</th>
                    <th width="45">Position <br>On Title</th>
                    <th>SG-Step</th>
                    <th>Monthly<br>Salary</th>
                    <th>SSL Salary <br>Differential</th>
                    <th width="45">NBC 451 Salary <br> Differential 2023</th>
                    <th>Step <br>Increment</th>
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
                                <th>{{ $mody->label }}</th>
                                @break
                            @endif
                        @endforeach
                        @endif
                    @endforeach  
                    <th>Less <br>Absences </th>
                    <th>Earned For <br>The Period</th>
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
                                <th>{{ $mody->label }}</th>
                                @break
                            @endif
                        @endforeach
                        @endif
                    @endforeach  
                    <th>Total <br>Overall<br> Deductions</th>
                    <th>Net<br>Ammount<br>Received</th>
                    <th>{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $firstHalf) !!}</th>
                    <th>{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $secondHalf) !!}</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($datas as $data)
                  @if ($data->group_by === $groupValue)
                    @php
                    $saltype = $data->sal_type;
                    
                    $monthlysalary = $data->salary_rate;
                    $salary = $data->salary_rate;
                    $absences =  $data->add_less_abs;
                    $absences1 =  $data->add_less_abs1;
                    $total_additional = ($data->add_sal_diff + $data->add_nbc_diff + $data->add_step_incre) - ($absences);
                    $total_gsis_deduction = $data->eml + $data->pol_gfal + $data->consol + $data->ed_asst_mpl + $data->loan + $data->rlip + $data->gfal + $data->computer;
                    $total_pugibig_deduction = $data->mpl + $data->prem + $data->calam_loan + $data->mp2;
                    $total_other_payables = $data->lbp + $data->cauyan + $data->projects + $data->nsca_mpc + $data->med_deduction + $data->grad_guarantor + $data->cfi + $data->csb + $data->fasfeed + $data->dis_unliquidated;
                    $total_deduction = $total_gsis_deduction + $total_pugibig_deduction + $data->philhealth + $data->holding_tax + $total_other_payables;
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

                    $rowEarnSum = 0;

                    $rowEarns = round($salary + $total_additional - $total_deduction, 2);
                    $decimalPoint = ($rowEarns * 100) % 100;
                    $decimalPoint = round($decimalPoint);
                    if($saltype == 1){

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
                    $secondhalftotal = round($rowEarnSum, 2);
                    @endphp
                    <tr>
                      <td>{{ $noo++ }}</td>
                      <td>{{ $data->lname }} {{ $data->fname }}</td>
                      <td>{{ $data->emp_pos }}</td>
                      <td>{{ $data->sg }}</td>
                      <td>{{ number_format($monthlysalary, 2) }}</td>
                      <td>{{ number_format($data->add_sal_diff, 2) }}</td>
                      <td>{{ number_format($data->add_nbc_diff, 2) }}</td>
                      <td>{{ number_format($data->add_step_incre, 2) }}</td>
                      {{-- Adjustments Refund --}}
                      @php
                        $columns_reg = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0]; 
                      @endphp

                      @foreach ($modify1 as $mody)
                          @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Refund' && array_key_exists($mody->column, $columns_reg))
                              @php
                                  $columns_reg[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
                      
                      @foreach ($modify1 as $mody)
                          @if ($mody->action == 'Refund' && array_key_exists($mody->column, $columns_reg))
                              @php
                                  $columns_reg[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
                      @foreach ($modify1 as $mody)
                          @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $columns_reg))
                              @php
                                  $modrefregTotalAmount = $columns_reg[$mody->column];
                              @endphp
                              @if ($modrefregTotalAmount != 0.00)
                                  <td>{{ $mody->action === 'Refund' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                  @if ($mody->action === 'Refund')
                                      @php
                                          $totalRefundAmount += $mody->amount;
                                          $modrefcoltotal[$mody->column] = isset($modrefcoltotal[$mody->column]) ? $modrefcoltotal[$mody->column] + $mody->amount : $mody->amount;
                                      @endphp
                                  @endif
                              @endif
                          @endif
                      @endforeach
                      <td>{{ number_format($data->add_less_abs1, 2) }}</td>
                      <td @if($rowEarn < 3001) style="color: red;" @endif>{{ $saltype == 1 || $saltype == 3 ? number_format($rowEarn + ($totalRefundAmount - $absences1), 2) : '0.00' }}</td>
                      {{-- Adjustments Deduction --}}
                      @php
                        $totalDeductAmount = 0; 
                        $columns_reg = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0, 'Column6' => 0, 'Column7' => 0]; 
                      @endphp

                      @foreach ($modify1 as $mody)
                          @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Deduction' && array_key_exists($mody->column, $columns_reg))
                              @php
                                  $columns_reg[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
                      
                      @foreach ($modify1 as $mody)
                          @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $columns_reg))
                              @php
                                  $columns_reg[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
                      @foreach ($modify1 as $mody)
                          @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $columns_reg))
                              @php
                                  $modedregTotalAmount = $columns_reg[$mody->column];
                              @endphp
                              @if ($modedregTotalAmount != 0.00)
                                  <td>{{ $mody->action === 'Deduction' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                  @if ($mody->action === 'Deduction')
                                      @php
                                          $totalDeductAmount += $mody->amount;
                                          $moddedcoltotal[$mody->column] = isset($moddedcoltotal[$mody->column]) ? $moddedcoltotal[$mody->column] + $mody->amount : $mody->amount;
                                      @endphp
                                  @endif
                              @endif
                          @endif
                      @endforeach
                      {{-- Adjustments Deduction End --}}
                      <td>{{ number_format($totalDeductAmount, 2) }}</td>
                      <td @if($rowEarn < 3001) style="color: red;" @endif>{{ $saltype == 1 || $saltype == 3 ? number_format($rowEarn + ($totalRefundAmount - $absences1) - ($totalDeductAmount), 2) : '0.00' }}</td>
                      <td></td>
                      <td @if($rowEarn < 3001) style="color: red;" @endif>{{ $saltype == 1 || $saltype == 3 ? number_format($rowEarn + ($totalRefundAmount - $absences1) - ($totalDeductAmount), 2) : '0.00' }}</td>
                      </tr>
                      @endif
                 @endforeach   
                </tbody> 
                <tfoot>
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ number_format($totalmonthlysalary, 2) }}</td>
                    <td>{{ number_format($totalsaldiff, 2) }}</td>
                    <td>{{ number_format($totalnbcdiff, 2) }}</td>
                    <td>{{ number_format($totalstepencre, 2) }}</td>
                    @php $modrefcoltotalrow = 0; @endphp
                    @if(isset($modrefcoltotal))
                    @foreach ($columns_reg as $column => $totalAmount)
                        @if (array_key_exists($column, $modrefcoltotal))
                            <td>{{ number_format($modrefcoltotal[$column], 2) }}</td>
                            @php
                                $modrefcoltotalrow += $modrefcoltotal[$column];
                            @endphp
                        @endif
                    @endforeach
                    @endif
                    <td>{{ number_format($totalabsences1, 2) }}</td>
                    <td>{{ number_format($secondhalftotal + ($modrefcoltotalrow - $totalabsences1), 2) }}</td>
                    @php
                    $moddedcoltotalrow = 0;
                    @endphp
                    
                    @if (isset($moddedcoltotal))
                        @foreach ($columns_reg as $column => $totalAmount)
                            @if (array_key_exists($column, $moddedcoltotal))
                                <td>{{ number_format($moddedcoltotal[$column], 2) }}</td>
                                @php
                                    $moddedcoltotalrow += $moddedcoltotal[$column];
                                @endphp
                            @endif
                        @endforeach
                    @endif
                    
                    <td>{{ number_format($moddedcoltotalrow, 2) }}</td>
                    <td>{{ number_format($secondhalftotal + ($modrefcoltotalrow - $totalabsences1) - ($moddedcoltotalrow), 2) }}</td>
                    <td></td>
                    <td>{{ number_format($secondhalftotal + ($modrefcoltotalrow - $totalabsences1) - ($moddedcoltotalrow), 2) }}</td>
                  </tr>  
                </tfoot>  
              {{-- Grand Total Columns --}}
              </table>
              @endforeach
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
            </div>
          </div>
        </div>
      </div>    
    </body>    
</html>

