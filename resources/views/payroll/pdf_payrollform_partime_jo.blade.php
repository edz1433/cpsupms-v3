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
      }
      
      .table td,
      .table th {
        padding: 0.3rem;
        vertical-align: top;
        border-top: 1px solid #000408;
        font-size: 9px;
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
                $totalAddAmount = 0;
                $totalDedAmount = 0;

                $sumjosss = array_sum(array_column($datas, 'jo_sss'));
                $sumprojects = array_sum(array_column($datas, 'projects'));
                $sumnscampc = array_sum(array_column($datas, 'nsca_mpc'));
                $sumgradscl = array_sum(array_column($datas, 'grad_guarantor'));
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
                @if ($thref->action === 'Additionals')
                    @php
                        $totalAddAmount += $thref->amount;
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

                $column1sumded = array_sum(array_column($datas, 'column1sumDed'));
                $column2sumded = array_sum(array_column($datas, 'column2sumDed'));
                $column3sumded= array_sum(array_column($datas, 'column3sumDed'));
                $column4sumded = array_sum(array_column($datas, 'column4sumDed'));
                $column5sumded = array_sum(array_column($datas, 'column5sumDed'));

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

                $rowcolamountdedsub1 = 0;
                $rowcolamountdedsub2 = 0;
                $rowcolamountdedsub3 = 0;
                $rowcolamountdedsub4 = 0;
                $rowcolamountdedsub5 = 0;

                $grouptotalRef = 0; 
                $grouptotalDed = 0;

                $rowEarnSum = 0;
                $rowEarnsArray = [];
            @endphp
            <div class="table-responsive">
              <strong style="font-size: 12px;">{{ $officeAbbr }}</strong>
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <thead>
                  <tr>
                    <th colspan="{{ 13 + $modifythRefcount + $modifythDedcount + ($sumjosss > 0 ? 1 : 0) + ($sumprojects > 0 ? 1 : 0)+ ($sumnscampc > 0 ? 1 : 0) + ($sumgradscl > 0 ? 1 : 0) }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>PART-TIME LOAD - {{ $campusname }}<br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                  </tr>
				          <tr>
                    <th colspan="{{ 13 + $modifythRefcount + $modifythDedcount + ($sumjosss > 0 ? 1 : 0) + ($sumprojects > 0 ? 1 : 0)+ ($sumnscampc > 0 ? 1 : 0) + ($sumgradscl > 0 ? 1 : 0)}}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                  </tr>
                  <tr>
                    <th width="3%">NO.</th>
                    <th width="10%">Name</th>
                    <th width="7%">Designation</th>
                    <th width="5%">No. of Hours per month</th>
                    <th width="5%">Rate per hour</th>
                    
                    @foreach ($tablemodifyRef as $thref)
                        @if ($thref->totalAmount > 0)
                            <th width="5%">{{ $thref->label }}</th>
                        @endif
                    @endforeach
                                  
                    <th width="5%">Deduction <br> Absent</th>
                    <th width="5%">Deduction <br> Late</th>
                    <th width="5%">Earned for the period</th>
                    <th width="5%">TAX 3%</th>
                    <th width="5%">TAX 2%</th>

                    @foreach ($tablemodifyDed as $thded)
                        @if ($thded->totalAmount > 0)
                            <th width="5.75%">{{ $thded->label }}</th>
                        @endif
                    @endforeach
                    
                    @if($sumjosss > 0)<th width="5%">SSS</th>@endif
                    @if($sumprojects > 0)<th width="5%">Project</th>@endif
                    @if($sumnscampc > 0)<th width="5%">NSCA MPC</th>@endif
                    @if($sumgradscl > 0)<th width="5%">Graduate School</th>@endif

                    <th width="5%">Total<br>Deductions</th>
                    <th width="5%">Net<br>Ammount<br>Received</th>
                    <th width="5%">Signature</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                  $no = 1;
                  $totalhours = 0;
                  $totalgrossincome = 0;
                  $totalalldeduction = 0;
                  $totalabsences = 0;
                  $totallate = 0;
                  $totaltax1 = 0;
                  $totaltax2 = 0;
                  $totalnsca_mpc = 0;
                  $totalprojects = 0;
                  $totaljo_sss = 0;
                  $totalgrad_guarantor = 0;
                  $totalearnperiod = 0;


                  @endphp
                  @foreach ($datas as $data)
                    @if ($data->offid === $groupValue)
                    @php
                    $saltype = $data->sal_type;

                    $absent = $data->add_less_abs;
                    $late = $data->less_late;
                    $tax1 = $data->tax1;
                    $tax2 = $data->tax2;
                    $nsca_mpc = $data->nsca_mpc; 
                    $grad_guarantor = $data->grad_guarantor;
                    $projects = $data->projects;
                    $jo_sss = $data->jo_sss;

                    $salary = $data->salary_rate;
                    
                    $totaldeduction = $projects + $nsca_mpc + $grad_guarantor + $tax1 + $tax2 + $jo_sss;
                    $earnperiod = $salary * $data->number_hours;
                    $netamountrec = ($earnperiod) - $totaldeduction;


                    $totalhours += $data->number_hours;
                    $totalgrossincome += $salary;
                    $totalalldeduction += $totaldeduction;
                    $totalabsences += $absent;
                    $totallate += $late;
                    $totalearnperiod += $earnperiod; 
                    $totaltax1 += $tax1;
                    $totaltax2 += $tax2;
                    $totalnsca_mpc += $nsca_mpc;
                    $totalprojects += $projects;
                    $totaljo_sss += $jo_sss;
                    $totalgrad_guarantor += $grad_guarantor;

                    $rowEarnSum = 0;

                    $rowEarns = round(($salary) - $totaldeduction, 2);
                    $decimalPoint = ($rowEarns * 100) % 100;
                    
                    $rowEarn = $rowEarns;
                  
                    $rowEarn = isset($rowEarn) ? $rowEarn : null;

                    $rowEarnsArray[] = $rowEarn === null ? '0.00' : $rowEarn;

                    $rowEarnSum = array_sum($rowEarnsArray);
                    
                    $halftotal = round($rowEarnSum, 2);

                    $rowcolamountrefsub1  += $data->rowcolamountref1;
                    $rowcolamountrefsub2  += $data->rowcolamountref2;
                    $rowcolamountrefsub3  += $data->rowcolamountref3;
                    $rowcolamountrefsub4  += $data->rowcolamountref4;
                    $rowcolamountrefsub5  += $data->rowcolamountref5;

                    $rowcolamountdedsub1  += $data->rowcolamountded1;
                    $rowcolamountdedsub2  += $data->rowcolamountded2;
                    $rowcolamountdedsub3  += $data->rowcolamountded3;
                    $rowcolamountdedsub4  += $data->rowcolamountded4;
                    $rowcolamountdedsub5  += $data->rowcolamountded5;

                    $grouptotalRef += $data->sumRef;
                    $grouptotalDed += $data->sumDed;
                    
                    @endphp
                    <tr>
                      <td style="text-align: center">{{ $no++ }}</td>
                      <td>{{ $data->lname }} {{ $data->fname }}</td>
                      <td>Part-Time Instructor</td>
                      <td>{{ number_format($data->number_hours, 2) }}</td>
                      <td>{{ number_format($salary, 2) }} </td>
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
                      <td>{{ ($data->add_less_abs > 0) ? number_format($data->add_less_abs, 2) : ''  }}</td>
                      <td>{{ ($data->less_late > 0) ? number_format($data->less_late, 2) : ''  }}</td>
                      <td>{{ number_format(($earnperiod + $data->sumRef) - ($absent + $late), 2) }}</td>
                      <td>{{ number_format($tax1, 2) }}</td>
                      <td>{{ number_format($tax2, 2) }}</td>
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
                      <td>{{ number_format($totaldeduction, 2) }}</td>
                      <td>{{ number_format(($earnperiod + $data->sumRef) - ($absent + $late) - ($totaldeduction + $data->sumDed), 2) }}</td>
                      <td></td>
                    <tr>
                    @endif
                  @endforeach
                  @php
                    $grandtotalabsences[] = $totalabsences;
                    $grandtotallate[] = $totallate;
                    $grandtotalearnperiod[] =  $totalearnperiod;
                    $grandtotaltax1[] = $totaltax1;
                    $grandtotaltax2[] = $totaltax2;
                  @endphp
                  <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

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

                    <td>{{ ($totalabsences > 0) ? number_format($totalabsences, 2) : ''}}</td>
                    <td>{{ ($totallate > 0) ? number_format($totallate, 2) : ''}}</td>
                    <td>{{ ($totalearnperiod > 0) ? number_format($totalearnperiod + $grouptotalRef, 2) : ''}}</td>
                    <td>{{ ($totaltax1 > 0) ? number_format($totaltax1, 2) : ''}}</td>
                    <td>{{ ($totaltax2 > 0) ? number_format($totaltax2, 2) : ''}}</td>

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
                    
                    <td>{{ number_format($totalalldeduction + $grouptotalDed, 2) }}</td>
                    <td>{{ number_format(($totalearnperiod + $grouptotalRef) - ($totalalldeduction + $grouptotalDed), 2) }}</td>
                    <td></td>
                  </tr>
                  @if ($loop->last)
                  <tr> 
                      <td colspan="5"></td>
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
                      <td>{{ number_format(array_sum($grandtotalabsences), 2); }}</td>
                      <td>{{ number_format(array_sum($grandtotallate), 2); }}</td>
                      <td>{{ number_format(array_sum($grandtotalearnperiod) + $totalSumRef, 2); }}</td>
                      <td>{{ number_format(array_sum($grandtotaltax1), 2); }}</td>
                      <td>{{ number_format(array_sum($grandtotaltax2), 2); }}</td>
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
                      <td>{{ number_format($totalSumDed, 2) }}</td>
                      <td>{{ number_format((array_sum($grandtotalearnperiod) + $totalSumRef) - ($totalSumDed), 2); }}</td> 
                      <td></td> 
                  </tr>
                  @endif
                </tbody>
              </table>
               @endforeach
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <tbody class="last-page">
                  <tr>
                    <td colspan="7"></td>
                    <td colspan="1" style="text-align: center;">RECAPITULATION</td>
                    <td style="text-align: center;">Debit</td>
                    <td style="text-align: center;">Credit</td>
                    <td colspan="6"></td>
                  </tr>
                  <tr>
                    <td colspan="7">
                      <div>CERTIFIED CORRECT: Services have been duly rendered as stated.</div><br><br>
                      <div class="div-signature" style="width: 100%;"><strong>FREIA  L. VARGAS, Ph.D.</strong></div>
                      <div class="div-signature" style="width: 100%;">Adminstrative Officer V. HRMO III</div><br>
                      <div>NOTED: </div><br>
                      <div class="div-signature" style="width: 100%;"><strong>HENRY c. BOLINAS, Ph.D.</strong></div>
                      <div class="div-signature" style="width: 100%;">Chief Administartive Officer</div><br>
                      <div style="width: 100%;">CERTIFIED: Funds available in the amount of  <strong style="border-bottom: solid #232629 1px;"> &emsp;&emsp;&emsp;P 
                        {{ number_format(array_sum($grandtotalearnperiod) + $totalSumRef, 2); }}
                      </strong></span></div> <br><br>
                      <div class="div-signature" style="width: 100%;"><strong>ELFRED M. SUMONGSONG, CPA</strong></div>
                      <div class="div-signature" style="width: 100%;">Accountant III</div><br>
                      <div style="width: 100%;">PREPARED BY:</div><br>
                      <div class="div-signature" style="width: 100%;"><strong>CHRISTINE V. TAGUBILIN</strong></div>
                      <div class="div-signature" style="width: 100%;">Admin Aide III-Payroll In-Charge</div><br>
                    </td>
                    <td>
                      <span style="width:100%; text-align: left; float: right;">
                        {{-- {{ $code->wages_code }} Labor and Wages<br>
                        {{ $code->bir_code }} Due to BIR<br>
                        @if($sumjosss > 0) {{ $code->otherpayable_code }} Other Payables (SSS) <br>@endif
                        @if($sumprojects > 0){{ $code->otherpayable_code }} Other Payables (Project) <br>@endif
                        @if($sumnscampc > 0){{ $code->otherpayable_code }} Other Payables (NSCA MPC) <br>@endif
                        @if($sumgradscl > 0){{ $code->otherpayable_code }} Other Payables (Graduate School) <br>@endif
                        {{ $code->bank_code }} Cash in Bank-LC, LBP<br> --}}
                      </span>
                    </td>
                      <td>
                        <span style="width:100%; text-align: left; float: left;">
                          {{-- {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}} --}}
                        </span>
                      </td>
                      <td>
                        <span style="width:100%; text-align: right; float: left;"><br>
                          {{-- {{ number_format($totaltax1 + $totaltax2,2)}}<br>
                          @if($sumjosss > 0) {{ number_format($sumjosss) }}<br>@endif
                          @if($sumprojects > 0) {{ number_format($sumprojects) }}<br>@endif
                          @if($sumnscampc > 0) {{ number_format($sumnscampc) }}<br>@endif
                          @if($sumgradscl > 0) {{ number_format($sumgradscl) }}<br>@endif
                          {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences) - ($totalalldeduction),2) }} --}}
                      </td>
                    <td colspan="6">
                      <span style="width:100%; text-align: left; float: left; font-size: 10px;">
                        <strong><span style="margin-right: 63%;">Approved for Payment:</span></strong>  
                        {{ number_format((array_sum($grandtotalearnperiod) + $totalSumRef) - ($totalSumDed), 2); }}
                        <br><br><br>
                        <strong><span style="margin-left: 35%; margin-right: 35%;">ALADINO C. MORACA, Ph.D.</span></strong>
                        <span style="margin-left: 40%; margin-right: 35%;">SUC President II</span><br><br><br>
                        <strong>CERTIFIED</strong>: That each employee whose name appears above has been paid the amount indicated through direct<br>credit to their respective accounts.<br><br><br><br><br><br>

                        <strong><span style="margin-left: 10%; margin-right: 10%;">ERNIE C. ONGAO</span></strong> <span style="float: right;">________________</span><br>
                        <span style="margin-left: 4%; margin-right: 10%;">Administrative Officer I/Cashier Designate</span> <span style="float: right; margin-right: 5%;">Date</span><br><br><br>
            
                      <span>
                    </td>               
                  </tr>
                  <tr>
                    <td colspan="7"></td>
                    <td colspan="1" style="text-align: right;"><strong>TOTAL :</strong></td>
                    <td style="text-align: center;"><strong>
                      {{-- {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}} --}}
                    </strong></td>
                    <td style="text-align: center;"><strong >
                      {{-- {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences) - ($totalalldeduction) + ($totalalldeduction),2) }} --}}
                    </strong>
                    </td>
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


