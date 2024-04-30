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
        font-size: 12px;
      }
      
      .table td,
      .table th {
        padding: 0.3rem;
        vertical-align: top;
        border-top: 1px solid #000408;
        font-size: 12px;
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
                $modifythded = array_fill_keys(['Column1', 'Column2', 'Column3', 'Column4', 'Column5'], 0);
                
                $sumjosss = array_sum(array_column($datas, 'jo_sss'));
                $sumjosmlfloan = array_sum(array_column($datas, 'jo_smlf_loan'));
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
                @if ($mody->action == 'Deduction' && array_key_exists($mody->column, $modifythded))
                    @php
                        $modifythded[$mody->column] += $mody->amount;
                        $modifythded[$mody->column] = ($modifythded[$mody->column] >= 1) ? 1 : 0;
                    @endphp
                @endif
              @endforeach
              
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <thead>
                  <tr>
                    <th colspan="{{ 12 + array_sum($modifyth) + array_sum($modifythded) + ($sumjosss > 0 ? 1 : 0) + ($sumjosmlfloan > 0 ? 1 : 0) + ($sumprojects > 0 ? 1 : 0) + ($sumnscampc > 0 ? 1 : 0) + ($sumgradscl > 0 ? 1 : 0) }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                  </tr>
				          <tr>
                    <th colspan="{{ 12 + array_sum($modifyth) + array_sum($modifythded) + ($sumjosss > 0 ? 1 : 0) + ($sumjosmlfloan > 0 ? 1 : 0) + ($sumprojects > 0 ? 1 : 0) + ($sumnscampc > 0 ? 1 : 0) + ($sumgradscl > 0 ? 1 : 0) }}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                  </tr>
                    <th>NO.</th>
                    <th width="100">Name</th>
                    <th width="70">Designation</th>
                    <th>Gross Income</th>
                    @php
                    $columns_jo = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0];
                    $columns_joded = ['Column1' => 0, 'Column2' => 0, 'Column3' => 0, 'Column4' => 0, 'Column5' => 0];
                    @endphp
                    
                    @foreach ($modify1 as $mody)
                        @if ($mody->action === 'Additionals' && array_key_exists($mody->column, $columns_jo))
                            @php
                                $columns_jo[$mody->column] += $mody->amount;
                            @endphp
                        @endif
                        @if ($mody->action === 'Deduction' && array_key_exists($mody->column, $columns_jo))
                            @php
                                $columns_joded[$mody->column] += $mody->amount;
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
                                <th>{!! $label !!}</th>
                                @break
                            @endif
                        @endforeach
                        @endif
                    @endforeach                   
                    <th>Deduction <br>Absent</th>
                    <th>Deduction <br>Late</th>
                    <th>Earned For <br>The Period</th>
                    <th>TAX 3%</th>
                    <th>TAX 2%</th>
                    @foreach ($columns_joded as $column => $total)
                    @if ($total != 0.00)
                        @foreach ($modify1 as $mody)
                            @if ($mody->column === $column)
                                @php
                                    $label = preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '$1<br>', $mody->label);
                                @endphp
                                <th>{!! $label !!}</th>
                                @break
                            @endif
                        @endforeach
                        @endif
                    @endforeach        
                    @if($sumjosss > 0)<th width="5%">SSS</th>@endif
                    @if($sumjosmlfloan > 0)<th width="5%">SMLF Loan</th>@endif
                    @if($sumprojects > 0)<th width="5%">Project</th>@endif
                    @if($sumnscampc > 0)<th width="5%">NSCA MPC</th>@endif
                    @if($sumgradscl > 0)<th width="5%">Graduate School</th>@endif
                    <th>Total<br>Deductions</th>
                    <th>Net<br>Ammount<br>Received</th>
                    <th>Signature</th>
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
                  $totaljo_sss = 0;
                  $totalsmlfloan = 0;
                  $totalnsca_mpc = 0;
                  $totalprojects = 0;
                  $totalgrad_guarantor = 0;
                  $totalearnperiod = 0;
                  @endphp
                
                  @foreach ($datas as $data)
                    @if($data->status == 1)
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
                      $jo_smlf_loan = $data->jo_smlf_loan;
                      $grossincome = $data->salary_rate / 2;
                      
                      $totaldeduction = $projects + $nsca_mpc + $grad_guarantor + $tax1 + $tax2 + $jo_sss + $jo_smlf_loan;
                      $earnperiod = $grossincome;
                      $netamountrec = ($earnperiod) - $totaldeduction;

                      $totalgrossincome += $grossincome;
                      $totalalldeduction += $totaldeduction;
                      $totalabsences += $absent;
                      $totallate += $late;
                      $totalearnperiod += $earnperiod; 
                      $totaltax1 += $tax1;
                      $totaltax2 += $tax2;
                      $totalnsca_mpc += $nsca_mpc;
                      $totalprojects += $projects;
                      $totaljo_sss += $jo_sss;
                      $totalsmlfloan += $jo_smlf_loan;
                      $totalgrad_guarantor += $grad_guarantor;

                      $rowEarnSum = 0;

                      $rowEarns = round(($grossincome) - $totaldeduction, 2);
                      $decimalPoint = ($rowEarns * 100) % 100;
                      
                      $rowEarn = $rowEarns;
                    
                      $rowEarn = isset($rowEarn) ? $rowEarn : null;

                      $rowEarnsArray[] = $rowEarn === null ? '0.00' : $rowEarn;

                      $rowEarnSum = array_sum($rowEarnsArray);
                      
                      $halftotal = round($rowEarnSum, 2);
                      
                      @endphp
                      <tr>
                        <td style="text-align: center">{{ $no++ }}</td>
                        <td>{{ ucwords(strtolower($data->lname)) }} {{ ucwords(strtolower($data->fname)) }}                      </td>
                        <td>{{ ($data->emp_pos == "Unknown" || $data->emp_pos == "") ? 'Job Order' :  $data->emp_pos }}</td>
                        <td>{{ number_format($grossincome, 2) }}</td>
                        @php
                          $totaljoAdd = 0; 
                          $totaljoDed = 0; 
                        @endphp
                        
                        @foreach ($modify1 as $mody)
                            @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Additionals' && array_key_exists($mody->column, $columns_jo))
                                @php
                                    $columns_jo[$mody->column] += $mody->amount;
                                @endphp
                            @endif
                            @if ($mody->pay_id == $data->payroll_ID && $mody->action == 'Deduction' && array_key_exists($mody->column, $columns_jo))
                                @php
                                    $columns_joded[$mody->column] += $mody->amount;
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
                        @foreach ($modify1 as $mody)
                            @if ($mody->payroll_id == $data->pid && array_key_exists($mody->column, $columns_joded))
                                @php
                                    $modjoTotalAmount = $columns_joded[$mody->column];
                                @endphp
                                @if($modjoTotalAmount != 0.00)
                                    <td>{{ $mody->action === 'Deduction' ? number_format($mody->amount, 2) : '0.00' }}</td>
                                    @if ($mody->action === 'Deduction')
                                        @php
                                            $totaljoDed += $mody->amount;
                                            $modcoltotalded[$mody->column] = isset($modcoltotalded[$mody->column]) ? $modcoltotalded[$mody->column] + $mody->amount : $mody->amount;
                                        @endphp
                                    @endif
                                @endif
                            @endif
                        @endforeach
                        @if($sumjosss > 0)<td>{{ number_format($jo_sss, 2) }}</td>@endif
                        @if($sumjosmlfloan > 0)<td>{{ number_format($jo_smlf_loan, 2) }}</td>@endif
                        @if($sumprojects > 0)<td>{{ number_format($projects, 2) }}</td>@endif
                        @if($sumnscampc > 0)<td>{{ number_format($nsca_mpc, 2) }}</td>@endif
                        @if($sumgradscl > 0)<td>{{ number_format($grad_guarantor, 2) }}</td>@endif
                        <td>{{ number_format($totaldeduction + $totaljoDed, 2) }}</td>
                        <td>{{ number_format(($earnperiod + $totaljoAdd) - ($absent + $late) - ($totaldeduction + $totaljoDed), 2) }}</td>
                        <td></td>
                        </tr>
                      @endif
                    @endif
                  @endforeach 
                </tbody>   
                <tfoot>
                  <tr>
                    <td colspan="3"></td>
                    <td><strong>{{ number_format($totalgrossincome,2) }}</strong></td>
                    @php $modcoltotalrow = 0; $modcoltotalrowded = 0; @endphp
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
                    @if(isset($modcoltotalded))
                    @foreach ($columns_jo as $column => $totalAmount)
                        @if (array_key_exists($column, $modcoltotalded) && $modcoltotalded[$column] > 0)
                            <td><strong>{{ number_format($modcoltotalded[$column], 2) }}</strong></td>
                            @php
                                $modcoltotalrowded += $modcoltotalded[$column];
                            @endphp
                        @endif
                    @endforeach
                    @endif
                    @if($totaljo_sss > 0)<td><strong>{{ number_format($totaljo_sss, 2) }}</strong></td>@endif
                    @if($sumjosmlfloan > 0)<td><strong>{{ number_format($totalsmlfloan, 2) }}</strong></td>@endif
                    @if($sumprojects > 0)<td><strong>{{ number_format($totalprojects, 2) }}</strong></td>@endif
                    @if($sumnscampc > 0)<td><strong>{{ number_format($totalnsca_mpc, 2) }}</strong></td>@endif
                    @if($sumgradscl > 0)<td><strong>{{ number_format($totalgrad_guarantor, 2) }}</strong></td>@endif
                    <td><strong>{{ number_format($totalalldeduction + $modcoltotalrowded,2) }}</strong></td>
                    <td><strong>{{ number_format(($totalearnperiod + $modcoltotalrow - $modcoltotalrowded) - ($totallate + $totalabsences) - $totalalldeduction,2) }}</strong></td>
                    <td></td>  
                  </tr>  
                </tfoot>         
              </table>
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <tbody class="last-page">
                  <tr>
                    <td colspan="7"></td>
                    <td colspan="1" style="text-align: center;"><strong>RECAPITULATION</strong></td>
                    <td style="text-align: center;"><strong>Debit</strong></td>
                    <td style="text-align: center;"><strong>Credit</strong></td>
                    <td colspan="6"></td>
                  </tr>
                  <tr>
                    <td colspan="7">
                      <div>CERTIFIED CORRECT: Services have been duly rendered as stated.</div><br><br>
                      <div class="div-signature" style="width: 100%;"><strong>FREIA  L. VARGAS, Ph.D.</strong></div>
                      <div class="div-signature" style="width: 100%;">Adminstrative Officer V. HRMO III</div><br>
                      <div>NOTED: </div><br>
                      <div class="div-signature" style="width: 100%;"><strong>HENRY C. BOLINAS, Ph.D.</strong></div>
                      <div class="div-signature" style="width: 100%;">Chief Administartive Officer</div><br>
                      <div style="width: 100%;">CERTIFIED: Funds available in the amount of  <strong style="border-bottom: solid #232629 1px;"> &emsp;&emsp;&emsp;P {{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}}</strong></span></div> <br><br>
                      <div class="div-signature" style="width: 100%;"><strong>ELFRED M. SUMONGSONG, CPA</strong></div>
                      <div class="div-signature" style="width: 100%;">Accountant III</div><br>
                      <div style="width: 100%;">PREPARED BY:</div><br>
                      <div class="div-signature" style="width: 100%;"><strong>CHRISTINE V. TAGUBILIN</strong></div>
                      <div class="div-signature" style="width: 100%;">Admin Officer II-Payroll In-Charge</div><br>
                    </td>
                    <td>
                      <span style="width:100%; text-align: left; float: right;">
                        {{ $code->wages_code }} Labor and Wages<br>
                        {{ $code->bir_code }} Due to BIR<br>
                        @if($sumjosss > 0) {{ $code->otherpayable_code }} Other Payables (SSS) <br>@endif
                        @if($sumjosmlfloan > 0){{ $code->otherpayable_code }} Other Payables (SMLF Loan) <br>@endif
                        @if($sumprojects > 0){{ $code->otherpayable_code }} Other Payables (Project) <br>@endif
                        @if($sumnscampc > 0){{ $code->otherpayable_code }} Other Payables (NSCA MPC) <br>@endif
                        @if($sumgradscl > 0){{ $code->otherpayable_code }} Other Payables (Graduate School) <br>@endif
                        @if($modcoltotalrowded > 0) 1030599000 Other Receivable <br>@endif
                        {{ $code->bank_code }} @if($payroll->fund == "MDS") Cash MDS, Regular @else Cash in Bank-LC, LBP @endif<br>
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
                          @if($sumjosss > 0) {{ number_format($sumjosss, 2) }}<br>@endif
                          @if($sumjosmlfloan > 0) {{ number_format($sumjosmlfloan, 2) }}<br>@endif
                          @if($sumprojects > 0) {{ number_format($sumprojects, 2) }}<br>@endif
                          @if($sumnscampc > 0) {{ number_format($sumnscampc, 2) }}<br>@endif
                          @if($sumgradscl > 0) {{ number_format($sumgradscl, 2) }}<br>@endif
                          @if($modcoltotalrowded > 0) {{ number_format($modcoltotalrowded, 2) }}<br>@endif
                          {{ number_format(($totalearnperiod + $modcoltotalrow - $modcoltotalrowded) - ($totallate + $totalabsences) - ($totalalldeduction),2) }}
                       </span>
                      </td>
                      <td colspan="6">
                        <span style="width:100%; text-align: left; float: left; font-size: 12px;">
                          <strong><span style="margin-right: 63%;">Approved for Payment:</span></strong>  {{ number_format(($totalearnperiod + $modcoltotalrow - $modcoltotalrowded) - ($totallate + $totalabsences) - $totalalldeduction,2) }}<br><br><br>
                          <strong><span style="margin-left: 35%; margin-right: 35%;">ALADINO C. MORACA, Ph.D.</span></strong>
                          <span style="margin-left: 40%; margin-right: 35%;">SUC President II</span><br><br><br>
                          <strong>CERTIFIED</strong>: That each employee whose name appears above has been paid the amount indicated through direct<br>credit to their respective accounts.<br><br><br><br><br><br>
  
                          <strong><span style="margin-left: 10%; margin-right: 10%;">ERNIE C. ONGAO</span></strong> <span style="float: right;">________________</span><br>
                          <span style="margin-left: 4%; margin-right: 10%;">Administrative Officer V/Cashier Designate</span> <span style="float: right; margin-right: 5%;">Date</span><br><br><br>
              
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

<script>
  document.addEventListener("keydown", function(e) {
      if (e.ctrlKey && e.key === "p") {
          e.preventDefault();
          alert("Printing is disabled");
      }
  });
</script>
