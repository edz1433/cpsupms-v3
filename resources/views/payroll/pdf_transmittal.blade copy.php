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

      </style>
  </head>
  <body>
    <body>
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="table-responsive">
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
                $rowEarn1total = 0;
                $rowEarn2total = 0;
              @endphp
                  <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 25%; max-width: none;">
                      <thead>
                          <tr>
                            <th colspan="2">
                              Republic of the Philippines<br>
                              CENTRAL PHILIPPINES STATE UNIVERSITY<br>
                              Kabankalan City, Negros Occidental<br><br>
        
                              Remittance List for the month of {{ $dateStartM }}
                            </th>
                          </tr>
                          <tr>
                            <th>Name</th>
                            @if($pid == 1)<th>{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $firstHalf) !!}</th>@endif
                            @if($pid == 2)<th>{!! preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $secondHalf) !!}</th>@endif
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($datas as $data)
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

                                  $rowEarn1total = 0;

                                  $rowEarns = round($data->salary_rate + $total_additional - $total_deduction, 2);
                                  $decimalPoint = ($rowEarns - floor($rowEarns)) * 100;
                                  $decimalPoint = round($decimalPoint);
                                  if ($saltype == 1) {
                                      $rowEarn = $rowEarns / 2;
                                  }elseif ($saltype == 2) {
                                    $rowEarn1 = $rowEarns;
                                    $rowEarnsArray1[] = $rowEarn1;
                                    $rowEarnSum1 = array_sum($rowEarnsArray1);
                                  }else {
                                    $rowEarn2 = $rowEarns;
                                    $rowEarnsArray2[] = $rowEarn2;
                                    $rowEarnSum2 = array_sum($rowEarnsArray2);
                                  }

                                  $rowEarn = isset($rowEarn ) ? $rowEarn : 0.00;
                                  $rowEarnsArray[] = $rowEarn;
                                  $rowEarnSum = array_sum($rowEarnsArray);

                                  $rowEarnSum = isset($rowEarnSum) ? $rowEarnSum : 0.00;
                                  $rowEarnSum1 = isset($rowEarnSum1) ? $rowEarnSum1 : 0.00;
                                  $rowEarnSum2 = isset($rowEarnSum2) ? $rowEarnSum2 : 0.00;

                                  $firsthalftotal = round($rowEarnSum + $rowEarnSum1, 2);
                                  $secondhalftotal = round($rowEarnSum + $rowEarnSum2, 2);
                                  @endphp
                                  <tr>
                                      <td>{{ $data->lname }} {{ $data->fname }}</td>
                                      @if($pid == 1)
                                      <td>
                                          @if($saltype == 1)
                                              {{ number_format($rowEarn, 2) }}
                                          @elseif($saltype == 2)
                                            @php $rowEarn1total += $rowEarn1 @endphp
                                              {{ number_format($rowEarn1, 2) }}
                                          @else
                                              0.00
                                          @endif
                                      </td>
                                      @endif
                                      @if($pid == 2)
                                      <td>
                                          @if($saltype == 1)
                                            @php
                                            if ($decimalPoint % 2 === 0) {
                                                $rowEarn = round($rowEarn + $data->sumRef -  $data->sumDed, 2);
                                            } else {
                                                $rowEarn = round($rowEarn, 3);
                                                $rowEarn = (floor($rowEarn * 100) / 100) + $data->sumRef -  $data->sumDed;
                                            }
                                            $rowEarntotal += $rowEarn; 
                                            @endphp
                                              {{ number_format($rowEarn + $data->sumRef -  $data->sumDed, 2) }}
                                          @elseif($saltype == 3)
                                            @php $rowEarn2total += $rowEarn2 @endphp
                                              {{ number_format($rowEarn2 + $data->sumRef -  $data->sumDed, 2) }}
                                          @else
                                              0.00
                                          @endif
                                      </td>
                                      @endif
                                  </tr>
                          @endforeach
                      </tbody>
                      <tfoot>
                        @php
                          $grandfirsthalftotal[] = $firsthalftotal + $rowEarn1total;
                          $grandrowEarntotal[] = $rowEarntotal + $rowEarn2total;

                          
                          $grandfirsthalftotal = array_sum($grandfirsthalftotal);
                          $grandrowEarntotal = array_sum($grandrowEarntotal);
                        @endphp
                        <tr>
                          <td>GRAND TOTAL</td>
                          @if($pid == 1)<td>{{ number_format($firsthalftotal + $rowEarn1total, 2)}}</td>@endif
                          @if($pid == 2)<td>{{ number_format($rowEarntotal + $rowEarn2total, 2)}}</td>@endif
                        </tr>
                        <tr>
                          <td colspan="2">
                            Certified Correct:<br><br>
                            <strong><span style="margin-left: 60%;">ELFRED M. SUMONGSONG</span><br><span style="margin-left: 65%;">Accountant III</span></strong>
                          </td>
                        </tr>
                      </tfoot>
                      {{-- Grand Total Columns End --}}
                  </table>
            </div>
          </div>
        </div>
      </div>    
    </body>    
</html>

