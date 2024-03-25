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
              @php
                $firstHalfformated = preg_replace('/(January|February|March|April|May|June|July|August|September|October|November|December)/', '', $firstHalf);
                $dateParts = explode('-', $firstHalfformated);
                $day = (int)$dateParts[0];
                $pid = 1;
                if($day >= 16){
                    $pid = 2;
                }

                list($month, $day_range, $year) = explode(" ", $firstHalf);
                list($start_day, $end_day) = explode("-", $day_range);
                $add = "$month 1-15, $year";
                @endphp
                @php
                $modifyth = array_fill_keys(['Column1', 'Column2', 'Column3', 'Column4', 'Column5'], 0);
                @endphp

                @foreach ($modifyjoth as $mody)
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
                    <th colspan="{{ 15 + array_sum($modifyth) }}" style="border-bottom: none;">CENTRAL PHILIPPINES STAT UNIVERSITY<br>GENERAL PAYROLL<br><br>{{$pid == 1 ? $firstHalf : $secondHalf}}</th>
                  </tr>
				          <tr>
                    <th colspan="{{ 15 + array_sum($modifyth) }}" style="text-align: left; border-top: none;">We acknowledge receipt of the sum shown opposite our names as full compensation for services rendered for the period stated</th>
                  </tr>
                    <th>NO.</th>
                    <th>Name</th>
                    <th width="70">Designation</th>
                    <th>Gross Income</th>
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
                                    <th>{{ $mody->label }}</th>
                                    @break
                                @endif
                            @endforeach
                        @endif
                    @endforeach                   
                    <th>Deduction <br>Absent</th>
                    <th>Deduction <br>Late</th>
                    <th>Earned For <br>The Period</th>
                    <th>TAX 1%</th>
                    <th>TAX 2%</th>
                    <th>NSCA MPC</th>
                    <th>Graduate <br> School</th>
                    <th>Project</th>
                    <th>Total Deductions</th>
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
                  $totalnsca_mpc = 0;
                  $totalprojects = 0;
                  $totalgrad_guarantor = 0;
                  $totalearnperiod = 0;
                  @endphp
                
                  @foreach ($chunk as $data)
                    @php
                    $saltype = $data->sal_type;

                    $absent = $data->add_less_abs;
                    $late = $data->less_late;
                    $tax1 = $data->tax1;
                    $tax2 = $data->tax2;
                    $nsca_mpc = $data->nsca_mpc; 
                    $grad_guarantor = $data->grad_guarantor;
                    $projects = $data->projects;

                    $grossincome = $data->salary_rate / 2;
                    
                    $totaldeduction = $projects + $nsca_mpc + $grad_guarantor + $tax1 + $tax2;
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
                      <td>{{ $no++ }}</td>
                      <td>{{ $data->lname }} {{ $data->fname }}</td>
                      <td>{{ $data->emp_pos }}</td>
                      <td>{{ number_format($grossincome, 2) }}</td>
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
                          @if ($mody->action == 'Additionals' && array_key_exists($mody->column, $columns_jo))
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
                      <td>{{ number_format($nsca_mpc, 2) }}</td>
                      <td>{{ number_format($grad_guarantor, 2) }}</td>
                      <td>{{ number_format($projects, 2) }}</td>
                      <td>{{ number_format($totaldeduction, 2) }}</td>
                      <td>{{ number_format(($earnperiod + $totaljoAdd) - ($absent + $late) - $totaldeduction, 2) }}</td>
                      <td></td>
                      </tr>
                  @endforeach 
                </tbody>   
                <tfoot>
                  <tr>
                    <td colspan="3"></td>
                    <td>{{ number_format($totalgrossincome,2) }}</td>
                    @php $modcoltotalrow = 0; @endphp
                    @foreach ($columns_jo as $column => $totalAmount)
                        @if (array_key_exists($column, $modcoltotal) && $modcoltotal[$column] > 0)
                            <td>{{ number_format($modcoltotal[$column], 2) }}</td>
                            @php
                                $modcoltotalrow += $modcoltotal[$column];
                            @endphp
                        @endif
                    @endforeach
                    <td>{{ number_format($totalabsences,2) }}</td>
                    <td>{{ number_format($totallate,2) }}</td>
                    <td>{{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences),2)}}</td>
                    <td>{{ number_format($totaltax1,2)}}</td>
                    <td>{{ number_format($totaltax2,2) }}</td>
                    <td>{{ number_format($totalnsca_mpc,2) }}</td>
                    <td>{{ number_format($totalgrad_guarantor,2) }}</td>
                    <td>{{ number_format($totalprojects,2) }}</td>
                    <td>{{ number_format($totalalldeduction,2) }}</td>
                    <td>{{ number_format(($totalearnperiod + $modcoltotalrow) - ($totallate + $totalabsences) - $totalalldeduction,2) }}</td>
                    <td></td>  
                  </tr>  
                </tfoot>         
              @endforeach
              </table>
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 100%; max-width: none;">
                <tbody class="last-page">
                  <tr>
                    <td colspan="7"></td>
                    <td colspan="2" style="text-align: center;">RECAPITULATION</td>
                    <td style="text-align: center;">Debit</td>
                    <td style="text-align: center;">Credit</td>
                    <td colspan="6"></td>
                  </tr>
                  <tr>
                    <td colspan="7">
                      <div>CERTIFIED CORRECT: Services have been duly rendered as stated.</div><br><br>
                      <div class="div-signature" style="width: 60%;"><strong>FREIA  L. VARGAS, Ph.D.</strong></div>
                      <div class="div-signature" style="width: 60%;">Adminstrative Officer V. HRMO III</div><br>
                      <div>NOTED: </div><br>
                      <div class="div-signature" style="width: 60%;"><strong>HENRY c. BOLINAS, Ph.D.</strong></div>
                      <div class="div-signature" style="width: 60%;">Chief Administartive Officer</div><br>
                      <div style="width: 30%;" style="width: 60%;">CERTIFIED: Funds available in the amount of P</div><br><br>
                      <div class="div-signature" style="width: 60%;"><strong>ELFRED M. SUMONGSONG, CPA</strong></div>
                      <div class="div-signature" style="width: 60%;">Accountant III</div><br>
                      <div style="width: 25%;" style="width: 60%;">PREPARED BY:</div><br>
                      <div class="div-signature" style="width: 60%;"><strong>CHRISTINE V. TAGUBILIN</strong></div>
                      <div class="div-signature" style="width: 60%;">Admin Aide III-Payroll In-Charge</div><br>
                    </td>
                    <td>
                      <div style="width:100%; text-align: left; float: right;">
                        @foreach($code as $c)
                        @if($c->status == "on") {{ $c->code_name }} @endif<br>
                        @endforeach
                      </div>
                    </td>
                    <td>
                      <div style="width:100%; text-align: left; float: left;">
                        @foreach($code as $c)
                          @if($c->status == "on") {{ $c->code}} @endif<br>
                        @endforeach
                      </div>
                    </td>     
                    <td></td>
                    <td></td>
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


