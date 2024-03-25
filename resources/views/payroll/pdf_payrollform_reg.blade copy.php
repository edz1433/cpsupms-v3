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

      </style>
  </head>
  <body>
    <body>
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="table-responsive">
              @php
                $no = 1;
                $uniqueGroupByValues = array_unique(array_column($datas, 'group_by', 'office_name')); 
              @endphp
              
              @foreach ($uniqueGroupByValues as $officeAbbr => $groupValue)
              @php
                $totalmonthlysal = 0;
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
                $rowEarntotal = 0;
                $rowEarntotal1 = 0;
                $rowEarn1total = 0;
                $rowEarn2total = 0;
              @endphp
                  <strong style="font-size: 12px;">{{ $officeAbbr }}</strong>
              
                  <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                      <thead>
                          <tr>
                              <th colspan="19" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{ $fulldate }}</th>
                          </tr>
                          <tr>
                              <th colspan="19" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                          </tr>
                          <tr>
                            <th width="5">NO.</th>
                            <th width="60">Name</th>
                            <th width="60">Position On Title</th>
                            <th width="20">SG-Step</th>
                            <th width="40">Monthly<br>Salary</th>
                            <th width="40">SSL Salary <br>Differential</th>
                            <th width="40">NBC 451 Salary <br> Differential 2023</th>
                            <th width="40">Step <br>Increment</th>
                            <th width="40">Less <br>Absences </th>
                            <th width="40">Earned For <br>The Period</th>
                            <th width="40">Total<br>GSIS<br>Deductions</th>
                            <th width="40">Total<br>PAG-IBIG<br>Deductions</th>
                            <th width="40">PHIL<br>HEALTH</th>
                            <th width="40">With <br>Holding<br>Tax</th>
                            <th width="40">Total <br>Other<br> Payables</th>
                            <th width="40">Total <br>Overall<br> Deductions</th>
                            <th width="40">Net<br>Ammount<br>Received</th>
                            <th width="40">{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $firstHalf) !!}</th>
                            <th width="40">{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $secondHalf) !!}</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($datas as $data)
                              @if ($data->group_by === $groupValue)
                                  @php
                                  $dataid = $data->id;
                                  $saltype = $data->sal_type;
                                  $monthlysal = $data->salary_rate;
                                  $total_additional = $data->add_sal_diff + $data->add_nbc_diff + $data->add_step_incre - $data->add_less_abs;
                                  $total_gsis_deduction = $data->eml + $data->pol_gfal + $data->consol + $data->ed_asst_mpl + $data->loan + $data->rlip + $data->gfal + $data->computer + $data->health;
                                  $total_pugibig_deduction = $data->mpl + $data->prem + $data->calam_loan + $data->mp2 + $data->house_loan;
                                  $total_other_payables = $data->lbp + $data->cauyan + $data->projects + $data->nsca_mpc + $data->med_deduction + $data->grad_guarantor + $data->cfi + $data->csb + $data->fasfeed + $data->dis_unliquidated;
                                  $total_deduction = $total_gsis_deduction + $total_pugibig_deduction + $data->philhealth + $data->holding_tax + $total_other_payables;
                                  $earnperiod = $total_additional + $data->salary_rate;
                              
                                  $totalmonthlysal += $monthlysal;
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

                                  // $rowEarn1total = 0;

                                  $rowEarns = round($data->salary_rate + $total_additional - $total_deduction, 2);
                                  $decimalPoint = ($rowEarns - floor($rowEarns)) * 100;
                                  $decimalPoint = round($decimalPoint);
                                  if ($saltype == 1) {
                                      $rowEarn = $rowEarns / 2;
                                      $rowEarnings = $rowEarns / 2;
                                  }elseif ($saltype == 2) {
                                    $rowEarn1 = $rowEarns;
                                    $rowEarnsArray1[] = $rowEarn1;
                                    $rowEarnSum1 = array_sum($rowEarnsArray1);
                                  }else {
                                    $rowEarn2 = $rowEarns;
                                    $rowEarnsArray2[] = $rowEarn2;
                                    $rowEarnSum2 = array_sum($rowEarnsArray2);
                                  }

                                  // $rowEarn = isset($rowEarn) ? $rowEarn : 0.00;
                                  // $rowEarnsArray[] = $rowEarn;
                                  // $rowEarnSum = array_sum($rowEarnsArray);

                                  // $rowEarnSum = isset($rowEarnSum) ? $rowEarnSum : 0.00;
                                  // $rowEarnSum1 = isset($rowEarnSum1) ? $rowEarnSum1 : 0.00;
                                  // $rowEarnSum2 = isset($rowEarnSum2) ? $rowEarnSum2 : 0.00;

                                  // $firsthalftotal = round($rowEarnSum + $rowEarnSum1, 2);
                                  // $secondhalftotal = round($rowEarnSum + $rowEarnSum2, 2);
                                  @endphp
                                  <tr>
                                      <td >{{ $no++ }}</td>
                                      <td>{{ $data->lname }} {{ $data->fname }}</td>
                                      <td>{{ $data->emp_pos }}</td>
                                      <td>{{ $data->sg }}</td>
                                      <td>{{ number_format($data->salary_rate, 2) }}</td>
                                      <td>{{ number_format($data->add_sal_diff, 2) }}</td>
                                      <td>{{ number_format($data->add_nbc_diff, 2) }}</td>
                                      <td>{{ number_format($data->add_step_incre, 2) }}</td>
                                      <td>{{ number_format($data->add_less_abs, 2) }}</td>
                                      <td>{{ number_format($earnperiod, 2) }}</td>
                                      <td>{{ number_format($total_gsis_deduction, 2) }}</td>
                                      <td>{{ number_format($total_pugibig_deduction, 2) }}</td>
                                      <td>{{ number_format($data->philhealth, 2) }}</td>
                                      <td>{{ number_format($data->holding_tax, 2) }}</td>
                                      <td>{{ number_format($total_other_payables, 2) }}</td>
                                      <td>{{ number_format($total_deduction, 2) }}</td>
                                      <td @if($data->salary_rate + $total_additional - $total_deduction < 3001) style="color: red;" @endif>{{ number_format($data->salary_rate + $total_additional - $total_deduction, 2) }}</td>
                                      <td>
                                          @if($saltype == 1)
                                              @php  
                                                $rowEarn = round($rowEarn, 2); 
                                                $rowEarntotal += $rowEarn; 
                                              @endphp
                                              {{ number_format($rowEarn, 2) }}
                                          @elseif($saltype == 2)
                                            @php $rowEarn1total += $rowEarn1 @endphp
                                              {{ number_format($rowEarn1, 2) }}
                                          @else
                                              0.00
                                          @endif
                                      </td>
                                    
                                      <td>
                                        @if($saltype == 1)
                                          @php
                                          if ($decimalPoint % 2 === 0) {
                                              $rowEarnings = $rowEarnings;
                                          } else {
                                              $rowEarnings = round($rowEarnings, 3);
                                              $rowEarnings = floor($rowEarnings * 100) / 100;
                                          }
                                          $rowEarntotal1 += $rowEarnings; 
                                          @endphp
                                            {{ number_format($rowEarnings, 2) }}
                                        @elseif($saltype == 3)
                                          @php $rowEarn2total += $rowEarn2 @endphp
                                            {{ number_format($rowEarn2, 2) }}
                                        @else
                                            0.00
                                        @endif
                                    </td>
                                  </tr>
                              @endif
                          @endforeach
                      </tbody>
                      <tfoot>
                        @php
                          $grandTotalMonthlySal[] = $totalmonthlysal;
                          $grandtotalsaldiff[] = $totalsaldiff;
                          $grandtotalnbcdiff[] = $totalnbcdiff;
                          $grandtotalstepencre[] = $totalstepencre;
                          $grandtotalabsences[] = $totalabsences;
                          $grandearn_for_the_period[] = $earn_for_the_period;
                          $grandalltotalgsis[] = $alltotalgsis;
                          $grandalltotalpagibig[] = $alltotalpagibig;
                          $grandtotal_philhealth[] = $total_philhealth;
                          $grandtotal_withholdingtax[] = $total_withholdingtax;
                          $grandtotal_payables[] = $total_payables;
                          $grandtotalalldeduct[] = $totalalldeduct + $total_withholdingtax;
                          $grandnetamout[] = $netamout;
                          $grandfirsthalftotal[] = $rowEarntotal + $rowEarn1total;
                          $grandrowEarntotal[] = $rowEarntotal1 + $rowEarn2total;
                        @endphp
                        <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td width="40">{{ number_format($totalmonthlysal, 2) }}</td>
                          <td width="40">{{ number_format($totalsaldiff, 2) }}</td>
                          <td width="40">{{ number_format($totalnbcdiff, 2) }}</td>
                          <td width="40">{{ number_format($totalstepencre, 2) }}</td>
                          <td width="40">{{ number_format($totalabsences, 2) }}</td>
                          <td width="40">{{ number_format($earn_for_the_period, 2) }}</td>
                          <td width="40">{{ number_format($alltotalgsis, 2) }}</td>
                          <td width="40">{{ number_format($alltotalpagibig, 2) }}</td>
                          <td width="40">{{ number_format($total_philhealth, 2) }}</td>
                          <td width="40">{{ number_format($total_withholdingtax, 2) }}</td>
                          <td width="40">{{ number_format($total_payables, 2) }}</td>
                          <td width="40">{{ number_format($totalalldeduct + $total_withholdingtax, 2) }}</td>
                          <td width="40">{{ number_format($netamout, 2) }}</td>
                          <td width="40">{{ number_format($rowEarntotal + $rowEarn1total, 2)}}</td>
                          <td width="40">{{ number_format($rowEarntotal1 + $rowEarn2total, 2)}}</td>
                        </tr>
                      </tfoot>
                      {{-- Grand Total Columns End --}}
                  </table>
              @endforeach
              
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                @php
                  $grandTotalMonthlySal = array_sum($grandTotalMonthlySal);
                  $grandtotalsaldiff = array_sum($grandtotalsaldiff);
                  $grandtotalnbcdiff = array_sum($grandtotalnbcdiff);
                  $grandtotalstepencre = array_sum($grandtotalstepencre);
                  $grandtotalabsences = array_sum($grandtotalabsences);
                  $grandearn_for_the_period = array_sum($grandearn_for_the_period);
                  $grandalltotalgsis = array_sum($grandalltotalgsis);
                  $grandalltotalpagibig = array_sum($grandalltotalpagibig);
                  $grandtotal_philhealth = array_sum($grandtotal_philhealth);
                  $grandtotal_withholdingtax = array_sum($grandtotal_withholdingtax);
                  $grandtotal_payables = array_sum($grandtotal_payables);
                  $grandtotalalldeduct = array_sum($grandtotalalldeduct);
                  $grandnetamout = array_sum($grandnetamout);
                  $grandfirsthalftotal = array_sum($grandfirsthalftotal);
                  $grandrowEarntotal = array_sum($grandrowEarntotal);
                @endphp
                <tbody>
                  <tr>
                    <td width="12" style="border-right: none; border-left: none;"></td>
                    <td width="58"></td>
                    <td width="60"></td>
                    <td width="21"></td>
                    <td width="40">{{ number_format($grandTotalMonthlySal, 2) }}</td>
                    <td width="40">{{ number_format($grandtotalsaldiff, 2) }}</td>
                    <td width="40">{{ number_format($grandtotalnbcdiff ,2) }}</td>
                    <td width="40">{{ number_format($grandtotalstepencre ,2) }}</td>
                    <td width="40">{{ number_format($grandtotalabsences ,2) }}</td>
                    <td width="40">{{ number_format($grandearn_for_the_period ,2) }}</td>
                    <td width="40">{{ number_format($grandalltotalgsis ,2) }}</td>
                    <td width="40">{{ number_format($grandalltotalpagibig ,2) }}</td>
                    <td width="40">{{ number_format($grandtotal_philhealth ,2) }}</td>
                    <td width="40">{{ number_format($grandtotal_withholdingtax ,2) }}</td>
                    <td width="40">{{ number_format($grandtotal_payables ,2) }}</td>
                    <td width="40">{{ number_format($grandtotalalldeduct ,2) }}</td>
                    <td width="40">{{ number_format($grandnetamout ,2) }}</td>
                    <td width="40">{{ number_format($grandfirsthalftotal ,2) }}</td>
                    <td width="40">{{ number_format($grandrowEarntotal ,2) }}</td>
                  </tr>
                </tbody>
              </table>
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <tbody class="last-page">
                  <tr>
                    <td colspan="5" style="border-right: ;">
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

