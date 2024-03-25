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
        font-size: 11px;
      }
      
      .table td,
      .table th {
        padding: 0.2rem;
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
                $uniqueGroupByValues = array_unique(array_column($datas, 'offid', 'office_name'));  
                $totalGroups = count($uniqueGroupByValues);
                $currentGroupIndex = 0;
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
                  <strong style="font-size: 12px;">{{ $officeAbbr }}</strong>@if ($officeAbbr !== 'GEN. ADMIN & SUPPORT STAFF') <br><br> @endif
                    @if ($officeAbbr == 'GEN. ADMIN & SUPPORT STAFF')
                    <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                      <tr>
                        <th colspan="19" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{ $fulldate }}</th>
                      </tr>
                      <tr>
                          <th colspan="19" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                      </tr>
                    </table>   
                    @endif
                  <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none; margin-top: -17px;">
                      <thead>
                          <tr>
                            <th width="5">NO.</th>
                            <th width="100">Name</th>
                            <th width="65">Position On<br> Title</th>
                            <th width="20">SG-Step</th>
                            <th width="55">Monthly<br>Salary</th>
                            <th width="40">SSL Salary <br>Differential</th>
                            <th width="40">NBC 451 Salary <br> Differential 2023</th>
                            <th width="40">Step <br>Increment</th>
                            <th width="40">Less <br>Absences </th>
                            <th width="48">Earned For <br>The Period</th>
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
                            @php @endphp
                              @if ($data->offid === $groupValue)
                                  @php
                                  $dataid = $data->id;
                                  $pstatus = $data->status;
                                  $monthlysal = $data->salary_rate;
                                  $total_additional = round($data->add_sal_diff + $data->add_nbc_diff + $data->add_step_incre - $data->add_less_abs, 2);
                                  $total_gsis_deduction = $data->eml + $data->pol_gfal + $data->consol + $data->ed_asst_mpl + $data->loan + $data->rlip + $data->gfal + $data->computer + $data->health;
                                  $total_pugibig_deduction = $data->mpl + $data->prem + $data->calam_loan + $data->mp2 + $data->house_loan;
                                  $total_other_payables = $data->lbp + $data->cauyan + $data->projects + $data->nsca_mpc + $data->med_deduction + $data->grad_guarantor + $data->cfi + $data->csb + $data->fasfeed + $data->dis_unliquidated;
                                  $total_deduction = round($total_gsis_deduction + $total_pugibig_deduction + $data->philhealth + $data->holding_tax + $total_other_payables, 2);
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
                                  if ($pstatus == 1) {
                                      $rowEarn = $rowEarns / 2;
                                      $rowEarnings = $rowEarns / 2;
                                  }
                                  else {
                                    $rowEarn2 = $rowEarns;
                                    $rowEarnsArray2[] = $rowEarn2;
                                    $rowEarnSum2 = array_sum($rowEarnsArray2);
                                  }
                                  @endphp
                                  <tr>
                                      <td >{{ $no++ }}</td>
                                      <td>{{ ucwords(strtolower($data->lname)) }} {{ ucwords(strtolower($data->fname)) }}</td>
                                      <td>{{ $data->emp_pos }}</td>
                                      <td>{{ $data->sg }}</td>
                                      <td>{{ number_format($data->salary_rate, 2) }}</td>
                                      <td>{{ ($data->add_sal_diff > 0) ? number_format($data->add_sal_diff, 2) : '' }}</td>
                                      <td>{{ ($data->add_nbc_diff > 0) ? number_format($data->add_nbc_diff, 2) : '' }}</td>
                                      <td>{{ ($data->add_step_incre > 0) ? number_format($data->add_step_incre, 2) : '' }}</td>
                                      <td>{{ ($data->add_less_abs > 0) ? number_format($data->add_less_abs, 2) : '' }}</td>
                                      <td>{{ ($earnperiod > 0) ? number_format($earnperiod, 2) : '' }}</td>
                                      <td>{{ ($total_gsis_deduction > 0) ? number_format($total_gsis_deduction, 2) : '' }}</td>
                                      <td>{{ ($total_pugibig_deduction > 0) ? number_format($total_pugibig_deduction, 2) : '' }}</td>
                                      <td>{{ ($data->philhealth > 0) ? number_format($data->philhealth, 2) : '' }}</td>
                                      <td>{{ ($data->holding_tax > 0) ? number_format($data->holding_tax, 2) : '' }}</td>
                                      <td>{{ ($total_other_payables > 0) ? number_format($total_other_payables, 2) : '' }}</td>
                                      <td>{{ ($total_deduction > 0) ? number_format($total_deduction, 2) : '' }}</td>
                                      <td>{{ number_format($data->salary_rate + $total_additional - $total_deduction, 2) }}</td>
                                      <td>
                                          @if($pstatus == 1)
                                              @php  
                                                $rowEarn = round($rowEarn, 2); 
                                                $rowEarntotal += $rowEarn; 
                                              @endphp
                                              {{ number_format($rowEarn, 2) }}
                                          @endif
                                      </td>
                                    
                                      <td>
                                        @if($pstatus == 1)
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
                                          @else
                                            @php $rowEarn2total += $rowEarn2 @endphp
                                              {{ number_format($rowEarn2, 2) }} 
                                          @endif
                                    </td>
                                  </tr>
                              @endif
                          @endforeach
                      </tbody>
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
                          $grandtotalalldeduct[] = $totalalldeduct;
                          $grandnetamout[] = $netamout;
                          $grandfirsthalftotal[] = $rowEarntotal + $rowEarn1total;
                          $grandrowEarntotal[] = $rowEarntotal1 + $rowEarn2total;
                        @endphp
                        <tr>
                          <td colspan="4"></td>
                          <td width="40">{{ ($totalmonthlysal > 0) ? number_format($totalmonthlysal, 2) : ''}}</td>
                          <td width="40">{{ ($totalsaldiff > 0) ? number_format($totalsaldiff, 2) : '' }}</td>
                          <td width="40">{{ ($totalnbcdiff > 0) ? number_format($totalnbcdiff, 2) : '' }}</td>
                          <td width="40">{{ ($totalstepencre > 0) ? number_format($totalstepencre, 2) : '' }}</td>
                          <td width="40">{{ ($totalabsences > 0) ? number_format($totalabsences, 2) : '' }}</td>
                          <td width="40">{{ ($earn_for_the_period > 0) ? number_format($earn_for_the_period, 2) : '' }}</td>
                          <td width="40">{{ ($alltotalgsis > 0) ? number_format($alltotalgsis, 2) : '' }}</td>
                          <td width="40">{{ ($alltotalpagibig > 0) ? number_format($alltotalpagibig, 2) : '' }}</td>
                          <td width="40">{{ ($total_philhealth > 0) ? number_format($total_philhealth, 2) : '' }}</td>
                          <td width="40">{{ ($total_withholdingtax > 0) ? number_format($total_withholdingtax, 2) : '' }}</td>
                          <td width="40">{{ ($total_payables > 0) ? number_format($total_payables, 2) : '' }}</td>
                          <td width="40">{{ (($totalalldeduct) > 0 ) ? number_format($totalalldeduct, 2) : '' }}</td>
                          <td width="40">{{ number_format($netamout, 2) }}</td>
                          <td width="40">{{ number_format($rowEarntotal + $rowEarn1total, 2) }}</td>
                          <td width="40">{{ number_format($rowEarntotal1 + $rowEarn2total, 2) }}</td>
                        </tr>
                        @if (++$currentGroupIndex === $totalGroups)
                        <tfoot>
                        <tr>
                          <td colspan="4"></td>
                          <td>{{ number_format(array_sum($grandTotalMonthlySal), 2) }}</td>
                          <td>{{ number_format(array_sum($grandtotalsaldiff), 2) }}</td>
                          <td>{{ number_format(array_sum($grandtotalnbcdiff),2) }}</td>
                          <td>{{ number_format(array_sum($grandtotalstepencre ),2) }}</td>
                          <td>{{ number_format(array_sum($grandtotalabsences),2) }}</td>
                          <td>{{ number_format(array_sum($grandearn_for_the_period ),2) }}</td>
                          <td>{{ number_format(array_sum($grandalltotalgsis),2) }}</td>
                          <td>{{ number_format(array_sum($grandalltotalpagibig),2) }}</td>
                          <td>{{ number_format(array_sum($grandtotal_philhealth),2) }}</td>
                          <td>{{ number_format(array_sum($grandtotal_withholdingtax ),2) }}</td>
                          <td>{{ number_format(array_sum($grandtotal_payables),2) }}</td>
                          <td>{{ number_format(array_sum($grandtotalalldeduct),2) }}</td>
                          <td>{{ number_format(array_sum($grandnetamout),2) }}</td>
                          <td>{{ number_format(array_sum($grandfirsthalftotal ),2) }}</td>
                          <td>{{ number_format(array_sum($grandrowEarntotal ),2) }}</td>
                        </tr>
                      </tfoot> 
                      @endif
                  </table>
              @endforeach
              
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
              
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 101%; max-width: none;">
                <tbody class="last-page">
                  @php
                    $totalmpl = 0;
                    $totalprem = 0;
                    $totalph = 0;
                    $totalcamloan = 0;
                    $totahloan = 0;

                    $totallbp = 0;
                    $totalcauyan = 0;
                    $totalprojects = 0;
                    $totalnsca_mpc = 0;
                    $totalmed_deduction = 0;
                    $totalgrad_guarantor = 0;
                    $totalcfi = 0;
                    $totalcsb = 0;
                    $totalfasfeed = 0;
                    $totaldis_unliquidated = 0;

                    $totalmpl = array_sum(array_column($datas, 'mp2'));
                    $totalprem = array_sum(array_column($datas, 'prem'));
                    $totalph = array_sum(array_column($datas, 'philhealth'));
                    $totalcamloan = array_sum(array_column($datas, 'calam_loan'));
                    $totahloan = array_sum(array_column($datas, 'house_loan'));
                    $totallbp = array_sum(array_column($datas, 'lbp'));
                    $totalcauyan = array_sum(array_column($datas, 'cauyan'));
                    $totalprojects = array_sum(array_column($datas, 'projects'));
                    $totalnsca_mpc = array_sum(array_column($datas, 'nsca_mpc'));
                    $totalmed_deduction = array_sum(array_column($datas, 'med_deduction'));
                    $totalgrad_guarantor = array_sum(array_column($datas, 'grad_guarantor'));
                    $totalcfi = array_sum(array_column($datas, 'cfi'));
                    $totalcsb = array_sum(array_column($datas, 'csb'));
                    $totalfasfeed = array_sum(array_column($datas, 'fasfeed'));
                    $totaldis_unliquidated = array_sum(array_column($datas, 'dis_unliquidated'));

                  @endphp
                  <tr>
                    <td colspan="5" style="border-right: ;">
                      <div>CERTIFIED CORRECT: Services have been duly rendered as stated.</div><br><br>
                      <div class="div-signature"><strong>FREIA  L. VARGAS, Ph.D.</strong></div>
                      <div class="div-signature">Adminstrative Officer V. HRMO III</div><br>
                      <div>NOTED: </div><br>
                      <div class="div-signature"><strong>HENRY C. BOLINAS, Ph.D.</strong></div>
                      <div class="div-signature">Chief Administartive Officer</div><br>
                      <div >CERTIFIED: Funds available in the amount of P <strong style="border-bottom: solid #232629 1px;"> &emsp;&emsp;&emsp;P {{ number_format($grandearn_for_the_period, 2) }}</strong></div><br><br>
                      <div class=""><strong>ELFRED M. SUMONGSONG, CPA</strong></div>
                      <div class="div-signature">Accountant III</div><br>
                      <div >PREPARED BY:</div><br><br>
                      <div class="div-signature"><strong>CHRISTINE V. TAGUBILIN</strong></div>
                      <div class="">Administrative Officer II - Payroll In-Charge</div><br>
                    </td>
                    <td colspan="3" style="border-left: ; text-align: left;">
                      <br><div><strong>RECAPITULATION</strong></div><br>
                      {{ $code->wages_code }} Salariest & Wages<br>
                      {{ $code->gsis_code }} GSIS Payable<br>
                      {{ $code->pagibig_code }} Due to PAGIBIG<br>
                      {{-- {{ $code->pagibig_code }} Due to PAGIBIG (Premium)<br>
                      {{ $code->pagibig_code }} Due to PAGIBIG (MP2.)<br>
                      {{ $code->pagibig_code }} Due to PAGIBIG (Calamity loan)<br>
                      {{ $code->pagibig_code }} Due to PAGIBIG (Housing Loan)<br> --}}
                      {{ $code->ph_code }} Due to PhilHealth<br>
                      {{ $code->bir_code }} Due to BIR<br>
                      @if($totallbp > 0) {{ $code->otherpayable_code }} Other Payable (LBP) <br> @endif
                      @if($totalcauyan > 0) {{ $code->otherpayable_code }} Other Payable (Cauyan) <br> @endif
                      @if($totalprojects > 0) {{ $code->otherpayable_code }} Other Payable (Projects) <br> @endif
                      @if($totalnsca_mpc > 0) {{ $code->otherpayable_code }} Other Payable (NSCA MPC) <br> @endif
                      @if($totalmed_deduction > 0) {{ $code->otherpayable_code }} Other Payable (Medical Deduction) <br> @endif
                      @if($totalgrad_guarantor > 0) {{ $code->otherpayable_code }} Other Payable (Grad. Shcl. / Guarantor) <br> @endif
                      @if($totalcfi > 0) {{ $code->otherpayable_code }} Other Payable (CFI) <br> @endif
                      @if($totalcsb > 0) {{ $code->otherpayable_code }} Other Payable (CSB) <br> @endif
                      @if($totalfasfeed > 0) {{ $code->otherpayable_code }} Other Payable (FASFEED) <br> @endif
                      @if($totaldis_unliquidated > 0) {{ $code->otherpayable_code }} Other Payable (Dis. ANCE / UNLIQ.) <br> @endif
                      {{ $code->due_offemp }} Due to Office & Employees<br>
                      {{ $code->due_offemp }} Due to Office & Employees<br>
                    </td>
                    <td style="border-right:;"></td>
                    <td colspan="3" style="border-left:;">
                        <br><div><strong>DEBIT</strong></div><br>
                        {{ number_format($grandearn_for_the_period, 2) }}<br>
                    </td>    
                    <td colspan="4"style="text-align">
                        <br><div><strong>CREDIT</strong></div><br><br>
                        {{ number_format($grandalltotalgsis, 2) }}<br>
                        {{ number_format($grandalltotalpagibig, 2) }}<br>
                        {{ number_format($grandtotal_philhealth, 2) }}<br>
                        {{ number_format($grandtotal_withholdingtax, 2) }}<br>
                        {{-- {{ number_format($totalmpl, 2) }}<br>
                        {{ number_format($totalprem, 2) }}<br>
                        {{ number_format($totalph, 2) }}<br>
                        {{ number_format($totalcamloan, 2) }}<br>
                        {{ number_format($totahloan, 2) }}<br> --}}
                        @if($totallbp > 0){{ number_format($totallbp, 2) }}<br>@endif
                        @if($totalcauyan > 0){{ number_format($totalcauyan, 2) }}<br>@endif
                        @if($totalprojects > 0){{ number_format($totalprojects, 2) }}<br>@endif
                        @if($totalnsca_mpc > 0){{ number_format($totalnsca_mpc, 2) }}<br>@endif
                        @if($totalmed_deduction > 0){{ number_format($totalmed_deduction, 2) }}<br>@endif
                        @if($totalgrad_guarantor > 0){{ number_format($totalgrad_guarantor, 2) }}<br>@endif
                        @if($totalcfi > 0){{ number_format($totalcfi, 2) }}<br>@endif
                        @if($totalcsb > 0){{ number_format($totalcsb, 2) }}<br>@endif
                        @if($totalfasfeed > 0){{ number_format($totalfasfeed, 2) }}<br>@endif
                        @if($totaldis_unliquidated > 0){{ number_format($totaldis_unliquidated, 2) }}<br>@endif
                        {{ number_format($grandfirsthalftotal ,2) }}<br>
                        {{ number_format($grandrowEarntotal ,2) }}<br>
                    </td>
                    <td colspan="6">
                      <span style="width:100%; text-align: left; float: left; font-size: 10px;">
                        <strong><span style="margin-right: 63%;">Approved for Payment:</span></strong> <br><br><br>
                        <strong><span style="margin-left: 35%; margin-right: 35%;">ALADINO C. MORACA, Ph.D.</span></strong>
                        <span style="margin-left: 40%; margin-right: 35%;">SUC President II</span><br><br><br>
                        <strong>CERTIFIED</strong>: That each employee whose name appears above has been paid the amount indicated through direct<br>credit to their respective accounts.<br><br><br><br><br><br>

                        <strong><span style="margin-left: 10%; margin-right: 10%;">ERNIE C. ONGAO</span></strong> <span style="float: right;">________________</span><br>
                        <span style="margin-left: 4%; margin-right: 10%;">Administrative Officer V - Cashier Designate</span> <span style="float: right; margin-right: 5%;">Date</span><br><br><br>
                      <span>
                    </td>             
                  </tr>                  
                  <tr>
                    <td colspan="5" style="border-right: ;"></td>
                    <td colspan="3" style="border-left: ; text-align: left;"></td>
                    <td style="border-right:;"></td>
                    <td colspan="3" style="border-left:;"><strong>{{ number_format($grandearn_for_the_period, 2) }}</strong></td>
                    <td colspan="4" style="text-align"><strong>{{ number_format($grandearn_for_the_period, 2) }}</strong></td>
                    <td colspan="6"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>    
    </body>    
</html>


