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
                $modifythRefcount = 0;
                $modifythDedcount = 0;
                $totalRefundAmount = 0;
                $totalDedAmount = 0;

                $gtotalstepencre =  collect($datas)->sum('add_step_incre');
                $gtotalnbcdiff =  collect($datas)->sum('add_nbc_diff');
                $gtotalsaldiff =  collect($datas)->sum('add_sal_diff');
            @endphp
            @foreach ($tablemodifyRef as $th)
                @if ($th->totalAmount > 0)
                        @php
                            $modifythRefcount++;
                        @endphp
                @endif
            @endforeach
            @foreach ($tablemodifyDed as $th)
                @if ($th->totalAmount > 0)
                    @php
                        $modifythDedcount++;
                    @endphp
                @endif
            @endforeach
            @foreach ($tablemodifyRef as $thref)
                @if ($thref->action === 'Refund')
                    @php
                        $totalRefundAmount += $thref->amount;
                    @endphp
                @endif
            @endforeach
                @foreach ($tablemodifyRef as $thded)
                @if ($thded->action === 'Deduction')
                    @php
                        $totalDedAmount += $thded->amount;
                    @endphp
                @endif
            @endforeach
            @php
                $totalSumRef = collect($datas)->sum('sumRef');
                $totalSumDed = collect($datas)->sum('sumDed');
            @endphp
            @php
                $column1sumref = array_sum(array_column($datas, 'column1sumRef'));
                $column2sumref = array_sum(array_column($datas, 'column2sumRef'));
                $column3sumref= array_sum(array_column($datas, 'column3sumRef'));
                $column4sumref = array_sum(array_column($datas, 'column4sumRef'));
                $column5sumref = array_sum(array_column($datas, 'column5sumRef'));
                $column6sumref = array_sum(array_column($datas, 'column6sumRef'));
                $column7sumref= array_sum(array_column($datas, 'column7sumRef'));

                $column1sumded = array_sum(array_column($datas, 'column1sumDed'));
                $column2sumded = array_sum(array_column($datas, 'column2sumDed'));
                $column3sumded= array_sum(array_column($datas, 'column3sumDed'));
                $column4sumded = array_sum(array_column($datas, 'column4sumDed'));
                $column5sumded = array_sum(array_column($datas, 'column5sumDed'));
                $column6sumded = array_sum(array_column($datas, 'column6sumDed'));
                $column7sumded= array_sum(array_column($datas, 'column7sumDed'));

                $no = 1;
                $uniqueGroupByValues = array_unique(array_column($datas, 'offid', 'office_name')); 
                $totalrowEarnperiod = 0;
                $totalGroups = count($uniqueGroupByValues);
                $currentGroupIndex = 0;            
            @endphp
            @foreach ($uniqueGroupByValues as $officeAbbr => $groupValue)
            @php
                $rowcolamountrefsub1 = 0;
                $rowcolamountrefsub2 = 0;
                $rowcolamountrefsub3 = 0;
                $rowcolamountrefsub4 = 0;
                $rowcolamountrefsub5 = 0;
                $rowcolamountrefsub6 = 0;
                $rowcolamountrefsub7 = 0;

                $rowcolamountdedsub1 = 0;
                $rowcolamountdedsub2 = 0;
                $rowcolamountdedsub3 = 0;
                $rowcolamountdedsub4 = 0;
                $rowcolamountdedsub5 = 0;
                $rowcolamountdedsub6 = 0;
                $rowcolamountdedsub7 = 0;

                $grouptotalRef = 0; 
                $grouptotalDed = 0;

                $rowEarnSum = 0;
                $rowEarnsArray = [];
            @endphp
            <div class="table-responsive">
              <strong style="font-size: 12px;">{{ $officeAbbr }}</strong>@if ($officeAbbr !== 'GEN. ADMIN & SUPPORT STAFF') <br><br> @endif
              @if ($officeAbbr == 'GEN. ADMIN & SUPPORT STAFF')
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <tr>
                  <th colspan="{{ 14 + $modifythRefcount + $modifythDedcount }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                </tr>
                <tr>
                  <th colspan="{{ 14 + $modifythRefcount + $modifythDedcount }}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                </tr>
              </table>
              @endif
              <table class="table table-bordered" style="margin-top: -17px;">
                <thead>
                    <tr>
                        <th colspan="8"></th>
                        @if($modifythRefcount > 0)<th colspan="{{ $modifythRefcount }}">Additional</th>@endif
                        <th colspan="2"></th>
                        @if($modifythDedcount > 0)<th colspan="{{ $modifythDedcount }}">Deduction</th>@endif
                        <th colspan="4"></th>
                    </tr>
                    <th width="2%">NO.</th>
                    <th width="10%">Name</th>
                    <th width="7%">Position On Title</th>
                    <th width="5.75%">SG-Step</th>
                    <th width="5.75%">Monthly<br>Salary</th>
                    <th width="5.75%">SSL Salary <br>Differential</th>
                    <th width="5.75%">NBC 451 Salary <br> Differential 2023</th>
                    <th width="5.75%">Step <br>Increment</th>
                    
                    @foreach ($tablemodifyRef as $thref)
                        @if ($thref->totalAmount > 0)
                            <th width="5.75%">{{ $thref->label }}</th>
                        @endif
                    @endforeach
                    
                    <th width="5.75%">Less <br>Absences </th>
                    <th width="5.75%">Earned For <br>The Period</th>

                    @foreach ($tablemodifyDed as $thded)
                        @if ($thded->totalAmount > 0)
                            <th width="5.75%">{{ $thded->label }}</th>
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
                  {{-- @php 
                    $grouptotalRef = 0; 
                    $grouptotalDed = 0; 
                  @endphp --}}
                  @if ($data->offid === $groupValue)
                    @php
                    $saltype = $data->sal_type;
                    $pstatus = $data->status;
                    
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
                    
                    if ($pstatus == 1) {
                        $rowEarn = $rowEarns / 2;
                        
                        if ($decimalPoint % 2 === 0) {
                            $rowEarn = round($rowEarn, 2);
                        } else {
                            $rowEarn = round($rowEarn, 3);
                            $rowEarn = floor($rowEarn * 100) / 100;
                        }
                    } elseif ($pstatus == 2) {
                        $rowEarn = $rowEarns;
                    }

                    $rowEarn = isset($rowEarn) ? $rowEarn : 0.00;
                    $rowEarnsArray[] = $rowEarn === null ? '0.00' : $rowEarn;
                    $rowEarnSum = array_sum($rowEarnsArray);
                    $rowtotal = $rowEarn + $data->sumRef - $data->sumDed; 
                    $secondhalftotal = round($rowEarnSum, 2);
                    
                    $rowcolamountrefsub1  += $data->rowcolamountref1;
                    $rowcolamountrefsub2  += $data->rowcolamountref2;
                    $rowcolamountrefsub3  += $data->rowcolamountref3;
                    $rowcolamountrefsub4  += $data->rowcolamountref4;
                    $rowcolamountrefsub5  += $data->rowcolamountref5;
                    $rowcolamountrefsub6  += $data->rowcolamountref6;
                    $rowcolamountrefsub7  += $data->rowcolamountref7;

                    $rowcolamountdedsub1  += $data->rowcolamountded1;
                    $rowcolamountdedsub2  += $data->rowcolamountded2;
                    $rowcolamountdedsub3  += $data->rowcolamountded3;
                    $rowcolamountdedsub4  += $data->rowcolamountded4;
                    $rowcolamountdedsub5  += $data->rowcolamountded5;
                    $rowcolamountdedsub6  += $data->rowcolamountded6;
                    $rowcolamountdedsub7  += $data->rowcolamountded7;

                    $grouptotalDed += $data->sumDed;
                    $grouptotalRef += $data->sumRef;
                    @endphp
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ ucwords(strtolower($data->lname)) }} {{ ucwords(strtolower($data->fname)) }}</td>
                      <td>{{ $data->emp_pos }}</td>
                      <td>{{ $data->sg }}</td>
                      <td>{{ number_format($monthlysalary, 2) }}</td>
                      <td>{{ ($data->add_sal_diff > 0) ? number_format($data->add_sal_diff, 2) : ''}}</td>
                      <td>{{ ($data->add_nbc_diff > 0) ? number_format($data->add_nbc_diff, 2) : ''}}</td>
                      <td>{{ ($data->add_step_incre > 0) ? number_format($data->add_step_incre, 2) : ''}}</td>


                        @if($column1sumref > 0)
                        <td>{{ ($data->rowcolamountref1 > 0) ? number_format($data->rowcolamountref1, 2) : ''  }} </td>
                        @endif
                        @if($column2sumref > 0)
                        <td>{{ ($data->rowcolamountref2 > 0) ? number_format($data->rowcolamountref2, 2) : '' }}</td>
                        @endif
                        @if($column3sumref > 0)
                        <td>{{ ($data->rowcolamountref3 > 0) ? number_format($data->rowcolamountref3, 2) : '' }}</td>
                        @endif
                        @if($column4sumref > 0)
                        <td>{{ ($data->rowcolamountref4 > 0) ? number_format($data->rowcolamountref4, 2) : '' }}</td>
                        @endif
                        @if($column5sumref > 0)
                        <td>{{ ($data->rowcolamountref5 > 0) ? number_format($data->rowcolamountref5, 2) : '' }}</td>
                        @endif
                        @if($column6sumref > 0)
                        <td>{{ ($data->rowcolamountref6 > 0) ? number_format($data->rowcolamountref6, 2) : '' }}</td>
                        @endif
                        @if($column7sumref > 0)
                        <td>{{ ($data->rowcolamountref7 > 0) ? number_format($data->rowcolamountref7, 2) : '' }}</td>
                        @endif

                      <td>{{ number_format($data->add_less_abs1, 2) }}</td>
                        @php $pstatus != 3 ? $totalrowEarnperiod += round($data->sumRef, 2) : '0.00' @endphp
                        <td >{{{ $pstatus != 3 ? number_format($rowEarn + ($data->sumRef - $absences1), 2) : '0.00' }}}</td>
                        @if($column1sumded > 0)
                        <td>{{ ($data->rowcolamountded1 > 0) ? number_format($data->rowcolamountded1, 2) : ''  }}</td>
                        @endif
                        @if($column2sumded > 0)
                        <td>{{ ($data->rowcolamountded2 > 0) ? number_format($data->rowcolamountded2, 2) : '' }}</td>
                        @endif
                        @if($column3sumded > 0)
                        <td>{{ ($data->rowcolamountded3 > 0) ? number_format($data->rowcolamountded3, 2) : '' }}</td>
                        @endif
                        @if($column4sumded > 0)
                        <td>{{ ($data->rowcolamountded4 > 0) ? number_format($data->rowcolamountded4, 2) : '' }}</td>
                        @endif
                        @if($column5sumded > 0)
                        <td>{{ ($data->rowcolamountded5 > 0) ? number_format($data->rowcolamountded5, 2) : '' }}</td>
                        @endif
                        @if($column6sumded > 0)
                        <td>{{ ($data->rowcolamountded6 > 0) ? number_format($data->rowcolamountded6, 2): '' }}</td>
                        @endif
                        @if($column7sumded > 0)
                        <td>{{ ($data->rowcolamountded7 > 0) ? number_format($data->rowcolamountded7, 2) : '' }}</td>
                        @endif
                      
                      <td>{{ ($data->sumDed > 0) ? number_format($data->sumDed, 2) : '' }}</td>
                      <td >{{ $pstatus != 3 ? number_format($rowEarn + ($data->sumRef - $absences1) - ($data->sumDed), 2) : '0.00' }}</td>
                      <td></td>
                      <td >{{ $pstatus != 3 ? number_format($rowEarn + ($data->sumRef - $absences1) - ($data->sumDed), 2) : '0.00' }}</td>
                      </tr>
                      @endif
                  @endforeach
                  @php
                    $grandTotalMonthlySal[] = $totalmonthlysalary;
                    $grandtotalsaldiff[] = $totalsaldiff;
                    $grandtotalnbcdiff[] = $totalnbcdiff;
                    $grandtotalstepencre[] = $totalstepencre;
                    $grandtotalabsences[] = $totalabsences1;
                    $grantotalrowEarnperiod[] = $totalrowEarnperiod;
                    $grandearn_for_the_period[] = $rowEarnSum;
                  @endphp
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($totalmonthlysalary, 2) }}</td>
                        <td>{{ ($totalsaldiff > 0) ? number_format($totalsaldiff, 2) : ''}}</td>
                        <td>{{ ($totalnbcdiff > 0) ?number_format($totalnbcdiff, 2) : ''}}</td>
                        <td>{{ ($totalstepencre > 0) ? number_format($totalstepencre, 2) : ''}}</td>

                        @if($column1sumref > 0)
                            <td>{{ ($rowcolamountrefsub1) ? number_format($rowcolamountrefsub1, 2)  : ''}}</td>
                        @endif
                        @if($column2sumref > 0)
                            <td>{{ ($rowcolamountrefsub2 > 0) ? number_format($rowcolamountrefsub2, 2) : ''}}</td>
                        @endif
                        @if($column3sumref > 0)
                            <td>{{ ($rowcolamountrefsub3 > 0) ? number_format($rowcolamountrefsub3, 2) : ''}}</td>
                        @endif
                        @if($column4sumref > 0)
                            <td>{{ ($rowcolamountrefsub4 > 0) ? number_format($rowcolamountrefsub4, 2) : ''}}</td>
                        @endif
                        @if($column5sumref > 0) 
                            <td>{{ ($rowcolamountrefsub5 > 0) ? number_format($rowcolamountrefsub5, 2) : ''}}</td>
                        @endif
                        @if($column6sumref > 0)
                            <td>{{ ($rowcolamountrefsub6 > 0) ? number_format($rowcolamountrefsub6, 2) : ''}}</td>
                        @endif
                        @if($column7sumref > 0)
                            <td>{{ ($rowcolamountrefsub7 > 0) ? number_format($rowcolamountrefsub7, 2) : ''}}</td>
                        @endif

                        <td>{{ number_format($totalabsences1, 2) }}</td>
                        <td>{{ number_format($secondhalftotal + (array_sum($grantotalrowEarnperiod) - $totalabsences1), 2) }}</td>

                        @if($column1sumded > 0)
                            <td>{{ ($rowcolamountdedsub1 > 0) ? number_format($rowcolamountdedsub1, 2) : '' }}</td>
                        @endif
                        @if($column2sumded > 0)
                            <td>{{ ($rowcolamountdedsub2 > 0) ? number_format($rowcolamountdedsub2, 2) : '' }}</td>
                        @endif
                        @if($column3sumded > 0)
                            <td>{{ ($rowcolamountdedsub3 > 0) ? number_format($rowcolamountdedsub3, 2) : '' }}</td>
                        @endif
                        @if($column4sumded > 0)
                            <td>{{ ($rowcolamountdedsub4 > 0) ? number_format($rowcolamountdedsub4, 2) : ''}}</td>
                        @endif
                        @if($column5sumded > 0) 
                            <td>{{ ($rowcolamountdedsub5 > 0) ? number_format($rowcolamountdedsub5, 2) : '' }}</td>
                        @endif
                        @if($column6sumded > 0)
                            <td>{{ ($rowcolamountdedsub6 > 0) ? number_format($rowcolamountdedsub6, 2) : ''}}</td>
                        @endif
                        @if($column7sumded > 0)
                            <td>{{ ($rowcolamountdedsub7 > 0) ? number_format($rowcolamountdedsub7, 2) : ''}}</td>
                        @endif
                    
                        <td>{{ ($grouptotalDed > 0) ? number_format($grouptotalDed, 2) : ''}}</td>
                        <td>{{ number_format($secondhalftotal + ($grouptotalRef - $totalabsences1) - ($grouptotalDed), 2) }}</td>
                        <td></td>
                        <td>{{ number_format($secondhalftotal + ($grouptotalRef - $totalabsences1) - ($grouptotalDed), 2) }}</td>
                    </tr>
                    @if ($loop->last)
                    <tr> 
                        <td colspan="4"></td>
                        <td>{{ number_format(array_sum($grandTotalMonthlySal), 2) }}</td>
                        <td>{{ number_format(array_sum($grandtotalsaldiff), 2) }}</td>
                        <td>{{ number_format(array_sum($grandtotalnbcdiff ),2) }}</td>
                        <td>{{ number_format(array_sum($grandtotalstepencre ),2) }}</td>
                        @if($column1sumref > 0)
                        <td>{{ number_format($column1sumref, 2)  }}</td>
                        @endif
                        @if($column2sumref > 0)
                        <td>{{ number_format($column2sumref, 2) }}</td>
                        @endif
                        @if($column3sumref > 0)
                        <td>{{ number_format($column3sumref, 2) }}</td>
                        @endif
                        @if($column4sumref > 0)
                        <td>{{ number_format($column4sumref, 2) }}</td>
                        @endif
                        @if($column5sumref > 0)
                        <td>{{ number_format($column5sumref, 2) }}</td>
                        @endif
                        @if($column6sumref > 0)
                        <td>{{ number_format($column6sumref, 2) }}</td>
                        @endif
                        @if($column7sumref > 0)
                        <td>{{ number_format($column7sumref, 2) }}</td>
                        @endif
                        <td width="5.75%">{{ number_format(array_sum($grandtotalabsences), 2); }}</td>
                        {{-- <td width="5.75%">{{ number_format((array_sum($grandearn_for_the_period) + $totalRefundAmount) - (array_sum($grandtotalabsences)),2) }}</td> 
                              --}}
                        <td width="5.75%">{{ number_format((array_sum($grandearn_for_the_period) + $totalSumRef),2) }}</td>
                        @if($column1sumded > 0)
                        <td>{{ number_format($column1sumded, 2)  }}</td>
                        @endif
                        @if($column2sumded > 0)
                        <td>{{ number_format($column2sumded, 2) }}</td>
                        @endif
                        @if($column3sumded > 0)
                        <td>{{ number_format($column3sumded, 2) }}</td>
                        @endif
                        @if($column4sumded > 0)
                        <td>{{ number_format($column4sumded, 2) }}</td>
                        @endif
                        @if($column5sumded > 0)
                        <td>{{ number_format($column5sumded, 2) }}</td>
                        @endif
                        @if($column6sumded > 0)
                        <td>{{ number_format($column6sumded, 2) }}</td>
                        @endif
                        @if($column7sumded > 0)
                        <td>{{ number_format($column7sumded, 2) }}</td>
                        @endif
                        <td>{{ number_format($totalSumDed, 2) }}</td>
                        <td width="5.75%">{{ number_format((array_sum($grandearn_for_the_period) + ($totalSumRef - $totalSumDed)) - (array_sum($grandtotalabsences)),2) }}</td> 
                        <td width="5.75%"></td> 
                        <td width="5.75%">{{ number_format((array_sum($grandearn_for_the_period) + ($totalSumRef - $totalSumDed)) - (array_sum($grandtotalabsences)),2) }}</td> 
                    </tr>
                    @endif
                </tbody>      
              </table>
              @endforeach
              
              <table class="table table-bordered" style="table-layout: auto; width: 100%; max-width: 110%;">
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
                    <td colspan="4" style="border-right: ;">
                      <div>CERTIFIED CORRECT: Services have been duly rendered as stated.</div><br><br>
                      <div class="div-signature"><strong>FREIA  L. VARGAS, Ph.D.</strong></div>
                      <div class="div-signature">Adminstrative Officer V. HRMO III</div><br>
                      <div>NOTED: </div><br>
                      <div class="div-signature"><strong>HENRY C. BOLINAS, Ph.D.</strong></div>
                      <div class="div-signature">Chief Administartive Officer</div><br>
                      <div >CERTIFIED: Funds available in the amount of P <strong style="border-bottom: solid #232629 1px;"> &emsp;&emsp;&emsp;P {{ number_format((array_sum($grandearn_for_the_period) + ($totalSumRef - $totalSumDed)) - (array_sum($grandtotalabsences)),2) }}</strong></div><br><br>
                      <div class=""><strong>ELFRED M. SUMONGSONG, CPA</strong></div>
                      <div class="div-signature">Accountant III</div><br>
                      <div >PREPARED BY:</div><br><br>
                      <div class="div-signature"><strong>CHRISTINE V. TAGUBILIN</strong></div>
                      <div >Administrative Officer II - Payroll In-Charge</div><br> 
                    </td>
                    <td colspan="3" style="border-left: ; text-align: left;">
                      <br><div><strong>RECAPITULATION</strong></div><br>
                      {{ $code->wages_code }} Salariest & Wages<br>
                     
                      @php $rowCountAdd = 0; @endphp
                      @php
                          $groupedData = $modify1->groupBy('column')->sortBy('column');
                      @endphp
                      
                      @foreach($groupedData as $group => $items)
                          @php $first = true; @endphp
                          @foreach($items as $item)
                              @if($first && 
                                    stripos($item->label, 'Step') === false &&
                                    stripos($item->label, 'step') === false &&
                                    stripos($item->label, 'Diff') === false &&
                                    stripos($item->label, 'diff') === false &&
                                    stripos($item->label, 'prom') === false &&
                                    stripos($item->label, 'Prom') === false &&
                                    stripos($item->label, 'Nbc') === false &&
                                    stripos($item->label, 'nbc') === false)

    @if (collect(['phil', 'health'])->contains(function ($keyword) use ($item) {
      return stripos($item->label, $keyword) !== false;
    }))
      {{ $code->ph_code }} {{ $item->label }}<br>
    @elseif (collect(['eml', 'policy', 'Consol', 'educ', 'asst', 'mpl loan', 'rlip', 'dfal', 'computer', 'help'])->contains(function ($keyword) use ($item) {
      return strpos(strtolower($item->label), $keyword) !== false;
    }))
      {{ $code->gsis_code }} {{ $item->label }}<br>
    @elseif (collect(['pagibig mpl', 'prem', 'clamity', 'mp2', 'housing'])->contains(function ($keyword) use ($item) {
      return strpos(strtolower($item->label), $keyword) !== false;
    }))
      {{ $code->pagibig_code }} {{ $item->label }}<br>
    @else
      {{ $code->otherpayable_code }} Other payable ({{ $item->label }})<br>
    @endif


                                
                                  @php $first = false; @endphp
                              @endif
                          @endforeach
                      @endforeach
                  
                      {{ $code->due_offemp }} Due to Office & Employees<br>
                    </td>
                    <td style="border-right:;"></td>
                    <td colspan="3" style="border-left:;">
                        <br><div><strong>DEBIT</strong></div><br>
                        @php
                            $groupedDataFilter = $modify1->where('action', 'Refund')->filter(function($item) {
                                return (stripos($item->label, 'Step') !== false ||
                                        stripos($item->label, 'Diff') !== false ||
                                        stripos($item->label, 'prom') !== false ||
                                        stripos($item->label, 'Prom') !== false ||
                                        stripos($item->label, 'Nbc') !== false ||
                                        stripos($item->label, 'nbc') !== false);
                            });
                        
                            $sumFilterDeb = $groupedDataFilter->sum('amount');
                        @endphp
                        @php $totalAddDebit = round($totalsaldiff + $totalnbcdiff + $totalstepencre, 2) @endphp
                        {{ number_format($sumFilterDeb + $totalAddDebit, 2 ) }} <br>
                    
                        @foreach($groupedData as $group => $items)
                            @php $sum = 0; $displayTotal = true; @endphp
                            @foreach($items as $item)
                                @if($item->action == "Refund")
                                @if(stripos($item->label, 'Step') === false &&
                                    stripos($item->label, 'Diff') === false &&
                                    stripos($item->label, 'prom') === false &&
                                    stripos($item->label, 'Prom') === false &&
                                    stripos($item->label, 'Nbc') === false &&
                                    stripos($item->label, 'nbc') === false)
                                    @php $sum += $item->amount; @endphp
                                @else
                                    @php $displayTotal = false; @endphp
                                @endif
                                @endif
                            @endforeach
                            @if($displayTotal)
                              {{ number_format($sum, 2) }}<br>
                            @endif
                        @endforeach
                        {{ number_format((array_sum($grandearn_for_the_period)) - (array_sum($grandtotalabsences)) - ($totalAddDebit),2) }}<br>
                    </td>    
                    <td colspan="4">
                        <br><div><strong>CREDIT</strong></div><br>
                        @php
                            $groupedDataFilter = $modify1->where('action', 'Deduction')->filter(function($item) {
                                return (stripos($item->label, 'step') !== false ||
                                        stripos($item->label, 'Diff') !== false ||
                                        stripos($item->label, 'prom') !== false ||
                                        stripos($item->label, 'Prom') !== false ||
                                        stripos($item->label, 'Nbc') !== false ||
                                        stripos($item->label, 'nbc') !== false);
                            });
                        
                            $sumFilterCred = $groupedDataFilter->sum('amount');
                        @endphp
                        
                        {{ number_format($sumFilterCred, 2) }} <br>

                        @foreach($groupedData as $group => $items)
                        @php $sum = 0; $displayTotal = true; @endphp
                            @foreach($items as $item)
                                @if($item->action == "Deduction")
                                @if(stripos($item->label, 'Step') === false &&
                                    stripos($item->label, 'Diff') === false &&
                                    stripos($item->label, 'prom') === false &&
                                    stripos($item->label, 'Prom') === false &&
                                    stripos($item->label, 'Nbc') === false &&
                                    stripos($item->label, 'nbc') === false)
                                    @php $sum += $item->amount; @endphp
                                @else
                                    @php $displayTotal = false; @endphp
                                @endif
                                @endif
                            @endforeach
                            @if($displayTotal)
                              {{ number_format($sum, 2) }}<br>
                            @endif
                        @endforeach

                        {{ number_format((array_sum($grandearn_for_the_period) + ($totalSumRef) - ($totalSumDed)),2) }}
                    </td>
                    <td colspan="6">
                      <div><strong>Approved for Payment:</strong></div><br><br><br><br>
                      <div class="div-signature" style="width: 100%;"><strong>ALADINO C. MORACA, Ph.D.</strong></div>
                      <div class="div-signature" style="width: 100%;">SUC President II</div><br><br><br>
                      <div><strong>CERTIFIED</strong>: That each employee whose name appears above has been paid the amount indicated through direct<br><span style="margin-left: 53px;">credit to their respective accounts.</span></div><br><br><br><br>
                      <div style="width: 100%;">
                        <div style="float: left; width: 50%; text-align: center;">
                          <div><strong>ERNIE C. ONGAO</strong></div>
                          <div>Administrative Officer V - Cashier Designate</div><br>
                        </div>
                        <div style="float: left; width: 50%; text-align: center;">
                          <div>________________</div>
                          <div>Date</div>
                        </div>
                      </div>
                    </td>               
                  </tr>
                  <tr>
                    <td colspan="4" style="border-right: ;"></td>
                    <td colspan="3" style="border-left: ; text-align: left;"></td>
                    <td style="border-right:;"></td>
                    <td colspan="3" style="border-left:;"><strong>{{ number_format((array_sum($grandearn_for_the_period) + $totalSumRef),2) }}</strong></td>
                    <td colspan="4" style="text-align"><strong>{{ number_format((array_sum($grandearn_for_the_period) + $totalSumRef),2) }}</strong></td>
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


