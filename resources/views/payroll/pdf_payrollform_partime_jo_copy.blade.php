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
            <div class="table-responsive">
              @php
                $modifyth = array_fill_keys(['Column1', 'Column2', 'Column3', 'Column4', 'Column5'], 0);

                $sumjosss = array_sum(array_column($datas, 'jo_sss'));
                $sumprojects = array_sum(array_column($datas, 'projects'));
                $sumnscampc = array_sum(array_column($datas, 'nsca_mpc'));
                $sumgradscl = array_sum(array_column($datas, 'grad_guarantor'));
              @endphp

              @foreach ($modify1 as $mody)
                @if ($mody->action == 'Additionals' && array_key_exists($mody->column, $modifyth))
                    @php
                        $modifyth[$mody->column] += $mody->amount;
                        $modifyth[$mody->column] = ($modifyth[$mody->column] >= 1) ? 1 : 0;
                    @endphp
                @endif
              @endforeach
              
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <thead>
                  <tr>
                    <th colspan="{{ 13 + array_sum($modifyth) + ($sumjosss > 0 ? 1 : 0) + ($sumprojects > 0 ? 1 : 0) + ($sumnscampc > 0 ? 1 : 0) + ($sumgradscl > 0 ? 1 : 0) }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>PART-TIME LOAD - {{ $campusname }}<br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                  </tr>
				          <tr>
                    <th colspan="{{ 13 + array_sum($modifyth) + ($sumjosss > 0 ? 1 : 0) + ($sumprojects > 0 ? 1 : 0) + ($sumnscampc > 0 ? 1 : 0) + ($sumgradscl > 0 ? 1 : 0)}}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                  </tr>
                    <th width="3%">NO.</th>
                    <th width="10%">Name</th>
                    <th width="7%">Designation</th>
                    <th width="5%">No. of Hours per month</th>
                    <th width="5%">Rate per hour</th>
                    @php
                    $columns_jo = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0];
                    @endphp
                    
                    @foreach ($modify1 as $mody)
                        @if ($mody->action === 'Additionals' && array_key_exists($mody->column, $columns_jo))
                            @php
                                $columns_jo[$mody->column] += $mody->amount;
                            @endphp
                        @endif
                    @endforeach
                    
                    @foreach ($columns_jo as $column => $total)
                        @if ($total != 0.00)
                        @foreach ($modify1 as $mody)
                            @if ($mody->column === $column)
                                @php
                                    $label = preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $mody->label);
                                @endphp
                                <th width="5%">{!! $label !!}</th>
                                @break
                            @endif
                        @endforeach
                        @endif
                    @endforeach                   
                    <th width="5%">Deduction Absent</th>
                    <th width="5%">Deduction Late</th>
                    <th width="5%">Earned for the period</th>
                    <th width="5%">TAX 3%</th>
                    <th width="5%">TAX 2%</th>
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
                  $totalgrossincome = 0;
                  $totalalldeduction = 0;
                  $totalgrossincome1st = 0;
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
                  @if($offid  != 'All' ? $data->offid == $offid : $data->offid != 0) 
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
                    
                    @endphp
                    <tr>
                      <td style="text-align: center">{{ $no++ }}</td>
                      <td>{{ $data->lname }} {{ $data->fname }}</td>
                      <td>Part-Time Instructor</td>
                      <td>{{ number_format($data->number_hours, 2) }}</td>
                      <td>{{ number_format($salary, 2) }}</td>
                      @php
                        $totaljoAdd = 0; 
                      @endphp
                      
                      @foreach ($modify1 as $mody)
                          @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Additionals' && array_key_exists($mody->column, $columns_jo))
                              @php
                                  $columns_jo[$mody->column] += $mody->amount;
                              @endphp
                          @endif
                      @endforeach
                      
                      @foreach ($modify1 as $mody)
                          @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $columns_jo))
                              @php
                                  $modjoTotalAmount = $columns_jo[$mody->column];
                              @endphp
                              @if ($modjoTotalAmount != 0.00)
                                  <td>{{ $mody->action === 'Additionals' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                  @if ($mody->action === 'Additionals')
                                      @php
                                          $totaljoAdd += $mody->amount;
                                          $modcoltotal[$mody->column] = isset($modcoltotal[$mody->column]) ? $modcoltotal[$mody->column] + $mody->amount : $mody->amount;
                                      @endphp
                                  @endif
                              @endif
                          @endif
                      @endforeach
                      <td>{{ number_format($data->add_less_abs, 2) }}</td>
                      <td>{{ number_format($data->less_late, 2) }}</td>
                      <td>{{ number_format(($earnperiod + $totaljoAdd) - ($absent + $late), 2) }}</td>
                      <td>{{ number_format($tax1, 2) }}</td>
                      <td>{{ number_format($tax2, 2) }}</td>
                      @if($sumjosss > 0)<td>{{ number_format($jo_sss, 2) }}</td>@endif
                      @if($sumprojects > 0)<td>{{ number_format($projects, 2) }}</td>@endif
                      @if($sumnscampc > 0)<td>{{ number_format($nsca_mpc, 2) }}</td>@endif
                      @if($sumgradscl > 0)<td>{{ number_format($grad_guarantor, 2) }}</td>@endif
                      <td>{{ number_format($totaldeduction, 2) }}</td>
                      <td>{{ number_format(($earnperiod + $totaljoAdd) - ($absent + $late) - $totaldeduction, 2) }}</td>
                      <td></td>
                      </tr>
                    @endif
                  @endforeach 
                </tbody>   
                <tfoot>
                  <tr>
                    <td colspan="5"></td>
                    @php $modcoltotalrow = 0; @endphp
                    @if(isset($modcoltotal))
                    @foreach ($columns_jo as $column => $totalAmount)
                        @if (array_key_exists($column, $modcoltotal) && $modcoltotal[$column] > 0)
                            <td><strong>{{ number_format($modcoltotal[$column], 2) }}</strong></td>
                            @php
                                $modcoltotalrow += $modcoltotal[$column];
                            @endphp
                        @endif
                    @endforeach
                    @endif
                    <td><strong>{{ number_format($totalabsences,2) }}</strong></td>
                    <td><strong>{{ number_format($totallate,2) }}</strong></td>
                    <td><strong>{{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}}</strong></td>
                    <td><strong>{{ number_format($totaltax1,2)}}</strong></td>
                    <td><strong>{{ number_format($totaltax2,2) }}</strong></td>
                    @if($sumjosss > 0)<td><strong>{{ number_format($totaljo_sss, 2) }}</td></strong>@endif
                    @if($sumprojects > 0)<td><strong>{{ number_format($totalprojects, 2) }}</td></strong>@endif
                    @if($sumnscampc > 0)<td><strong>{{ number_format($totalnsca_mpc, 2) }}</td></strong>@endif
                    @if($sumgradscl > 0)<td><strong>{{ number_format($totalgrad_guarantor, 2) }}</td></strong>@endif
                    <td><strong>{{ number_format($totalalldeduction,2) }}</strong></td>
                    <td><strong>{{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences) - $totalalldeduction,2) }}</strong></td>
                    <td></td>  
                  </tr>  
                </tfoot>         
              </table>
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
                      <div style="width: 100%;">CERTIFIED: Funds available in the amount of  <strong style="border-bottom: solid #232629 1px;"> &emsp;&emsp;&emsp;P {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}}</strong></span></div> <br><br>
                      <div class="div-signature" style="width: 100%;"><strong>ELFRED M. SUMONGSONG, CPA</strong></div>
                      <div class="div-signature" style="width: 100%;">Accountant III</div><br>
                      <div style="width: 100%;">PREPARED BY:</div><br>
                      <div class="div-signature" style="width: 100%;"><strong>CHRISTINE V. TAGUBILIN</strong></div>
                      <div class="div-signature" style="width: 100%;">Admin Aide III-Payroll In-Charge</div><br>
                    </td>
                    <td>
                      <span style="width:100%; text-align: left; float: right;">
                        {{ $code->wages_code }} Labor and Wages<br>
                        {{ $code->bir_code }} Due to BIR<br>
                        @if($sumjosss > 0) {{ $code->otherpayable_code }} Other Payables (SSS) <br>@endif
                        @if($sumprojects > 0){{ $code->otherpayable_code }} Other Payables (Project) <br>@endif
                        @if($sumnscampc > 0){{ $code->otherpayable_code }} Other Payables (NSCA MPC) <br>@endif
                        @if($sumgradscl > 0){{ $code->otherpayable_code }} Other Payables (Graduate School) <br>@endif
                        {{ $code->bank_code }} Cash in Bank-LC, LBP<br>
                      </span>
                    </td>
                      <td>
                        <span style="width:100%; text-align: left; float: left;">
                          {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}}
                        </span>
                      </td>
                      <td>
                        <span style="width:100%; text-align: right; float: left;"><br>
                          {{ number_format($totaltax1 + $totaltax2,2)}}<br>
                          @if($sumjosss > 0) {{ number_format($sumjosss) }}<br>@endif
                          @if($sumprojects > 0) {{ number_format($sumprojects) }}<br>@endif
                          @if($sumnscampc > 0) {{ number_format($sumnscampc) }}<br>@endif
                          @if($sumgradscl > 0) {{ number_format($sumgradscl) }}<br>@endif
                          {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences) - ($totalalldeduction),2) }}
                      </td>
                    <td colspan="6">
                      <span style="width:100%; text-align: left; float: left; font-size: 10px;">
                        <strong><span style="margin-right: 63%;">Approved for Payment:</span></strong>  {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences) - $totalalldeduction,2) }}<br><br><br>
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
                    <td style="text-align: center;"><strong>{{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}}</strong></td>
                    <td style="text-align: center;"><strong >{{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences) - ($totalalldeduction) + ($totalalldeduction),2) }}</strong>
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


