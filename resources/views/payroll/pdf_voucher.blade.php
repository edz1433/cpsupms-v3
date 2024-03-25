<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">

    <title>Payroll Voucher</title>
    <style>
      /* Default table styling */
      .table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
        border-collapse: collapse; /* Added to collapse borders */
        font-size: 11px;
        font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
      }

      .table th, .table td {
        border: 1px solid black;
        padding: 0.3rem;
        vertical-align: top;
        font-size: 11px;
      }

      .table thead th {
        vertical-align: bottom;
      }

    .flex-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .flex-item {
        flex: 1;
      }

      .line {
        flex: 1;
        border-bottom: 1px solid black;
      }

      .checkbox-container {
        display: inline-block;
        vertical-align: middle;
        margin-right:6%; 
        margin-top: 4px;
      }

      .checkbox-container input[type="checkbox"] {
        vertical-align: middle;
        transform: scale(1.2);
      }

      .checkbox-container label {
        vertical-align: middle; 
      }

      .v-align{
        justify-content: center;
        align-items: center; /* Optional: centers vertically as well */
      }
    </style>
  </head>
  <body>
    <body>
      <div class="container">
        <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th colspan="6" style="text-align: center;">
                    <div style="text-align: center;"><br>
                      <div style="display: inline-block;">
                        <img src="{{ asset('template/img/CPSU_L.png') }}" style="margin-top: 2px;" alt="" width="45">
                      </div>
                      <div style="display: inline-block;">
                        <div style="font-weight: bold; margin-bottom: 6px; font-size: 13px;">CENTRAL PHILIPPINES STATE UNIVERSITY<br>Kabankalan City, Negros Occidental</div>
                      </div>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="6" style="text-align: center;"><b>DISBURSEMENT VOUCHER<b></td>
                </tr>
                <tr>
                  <td colspan="2">
                    <div class="float-left"><b>Fund Cluster:</b></div>
                    <div class="float-right line" style="margin-left: 31%;"></div>
                  </td>
                  <td colspan="2">
                    <div class="float-left"><b>DV No:</b></div>
                    <div class="float-right line" style="margin-left: 14%;"></div>
                  </td>
                  <td colspan="2">
                    <div class="float-left"><b>Date:</b></div>
                    <div class="float-right line" style="margin-left: 17%;"></div>
                  </td>
                </tr>          
                @php $certifiedArray = explode(',', $voucher->certified); @endphp
                <tr>
                  <td width="60"><b>Mode of Payment</b></td>  
                  <td colspan="5">
                    <div class="checkbox-container">
                      @if($voucher->mode == 1)
                        <div style="background-color: #ba1369; border: 1px solid black;  width: 14.5px; height: 14.5px; display: inline-block; margin-top: 6px;"></div>
                      @else
                        <input type="checkbox" id="MDS Check">
                      @endif
                      <label for="MDS Check">MDS Check</label>
                    </div>
                    <div class="checkbox-container">
                      @if($voucher->mode == 2)
                        <div style="background-color: #ba1369; border: 1px solid black;  width: 14.5px; height: 14.5px; display: inline-block; margin-top: 6px;"></div>
                      @else
                        <input type="checkbox" id="Commercial Check">
                      @endif
                      <label for="commercial-check">Commercial Check</label>
                    </div>
                    <div class="checkbox-container">
                      @if($voucher->mode == 3)
                        <div style="background-color: #ba1369; border: 1px solid black;  width: 14.5px; height: 14.5px; display: inline-block; margin-top: 6px;"></div>
                      @else
                        <input type="checkbox" id="ADA">
                      @endif
                      <label for="ADA">ADA</label>
                    </div>
                    <div class="checkbox-container">
                      @if($voucher->mode == 4)
                        <div style="background-color: #ba1369; border: 1px solid black;  width: 14.5px; height: 14.5px; display: inline-block; margin-top: 6px;"></div>
                      @else
                        <input type="checkbox" id="Others">
                      @endif
                      <label for="others">Others (Please specify)</label>
                    </div>
                  </td>
                </tr>     
                <tr>
                  <td style="height: 30px"><b>Payee</b></td>
                  <td colspan="2" class="v-align" style="text-align: center; vertical-align: middle;">
                    <div style="font-size: 13px;">
                      <b>{{ strtoupper($voucher->fname) }}</b>  <b>{{ strtoupper($voucher->mname) }}</b>  <b>{{ strtoupper($voucher->lname) }}</b>
                    </div>  
                  </td>  
                  <td colspan="2"></td>  
                  <td width="78">ORS/BURS No.:</td>  
                </tr> 
                <tr>
                  <td><b>Address</b></td>
                  <td colspan="3"></td>
                  <td colspan="2"><b>Date:</b></td>
                </tr>
                <tr>
                  <th colspan="3" class="v-align">Particulars</th>
                  <th class="v-align" width="90">Responsibility Center</th>
                  <th class="v-align">MFO/PAP</th>
                  <th class="v-align">Account</th>
                </tr>
                <tr>
                  <td colspan="3">
                    <div style="margin-top: 35px; ">Payment for Salary as Associate Professor II for the period of <br>{{ $firstHalf }}, in the total amount of ......</div>
                    <div style="margin-top: 25px; margin-left: 20px;"> <div style="float: left;">{{ $firstHalf }}</div> <div style="float: right;"><u>{{ number_format($voucher->amount, 2) }}</u><br><u style="padding-bottom: 2px; border-bottom: 1px solid black;;">{{ number_format($voucher->amount, 2) }}</u></div> </div>
                    <div style="margin-top: 40px; margin-left: 160px;"> <b>Amount Due</b> </div> 
                  </td>
                  <td></td>
                  <td>
                    <div style="margin-top: 35px; text-align: center;">
                      301000000
                    </div>
                  </td>
                  <td>
                    <div style="margin-top: 35px; text-align: right;">
                      {{ number_format($voucher->amount, 2) }}
                    </div>
                  </td>
                </tr>
                <tr>
                  <td colspan="6">
                    <span style="border: 1px solid black; padding: 3px;">A.</span><b style="font-size: 12px;"> Certified: Expenses/Cash Advance necessary, lawful and incured under my direct supervision.</b>
                    <div style="text-align: center; margin-top: 25px;"><u><b>MARC ALEXEI CAESAR B. BADAJOS, Ph. D. -VPAF</b></u><br><span style="font-size: 10px;">Printed Name, Designation and Signature of Supervisor</span></div>
                  </td>
                </tr>
                <tr>
                  <td colspan="6">
                    <span style="border: 1px solid black; padding: 3px;">B.</span><b> Accounting Entry:</b>
                  </td>
                </tr>
                <tr>
                  <th colspan="3" class="v-align">Account Title</th>
                  <th class="v-align">UACS Code</th>
                  <th class="v-align">Debit</th>
                  <th class="v-align">Credit</th>
                </tr>
                <tr>
                  <td colspan="3" style="text-align: left;  font-size: 10px;">
                    <div>Due to Officers and Employees</div><br>
                    <div>PERA</div><br>
                    <div>Clothing Allowance</div><br>
                    <div>Due to BIR</div><br>
                    <div>Due to PAG-IBIG (Premium)</div><br>
                    <div>Due to Philhealth</div><br>
                    <div style="text-align: center;">Cash Modified Disbursement System - Regular</div>
                  </td>
                  <td style="text-align: center;  font-size: 10px;">
                    <div>2 &nbsp; 01 &nbsp; 01 &nbsp; 020 &nbsp; 00</div><br>
                    <div>5 &nbsp; 01 &nbsp; 02 &nbsp; 010 &nbsp; 01</div><br>
                    <div>5 &nbsp; 01 &nbsp; 02 &nbsp; 040 &nbsp; 01</div><br>
                    <div>2 &nbsp; 02 &nbsp; 01 &nbsp; 010 &nbsp; 00</div><br>
                    <div>2 &nbsp; 02 &nbsp; 01 &nbsp; 020 &nbsp; 00</div><br>
                    <div>2 &nbsp; 02 &nbsp; 01 &nbsp; 040 &nbsp; 00</div><br>
                    <div>1 &nbsp; 01 &nbsp; 04 &nbsp; 040 &nbsp; 00</div>
                    <br>
                  </td>
                  <td style="text-align: right;">
                    {{ number_format($voucher->amount, 2) }}
                  </td>
                  <td style="text-align: right;">
                    <div>-</div><br>
                    <div>-</div><br>
                    <div>-</div><br>
                    <div>-</div><br>
                    <div>-</div><br><br>
                    <div><b>{{ number_format($voucher->amount, 2) }}</b></div>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <div style="text-align: center; font-size: 9px;"><i>The record payment for Salary as Associate Professor II for the period of {{ $firstHalf }}</i></div>
                  </td>
                  <td><b>TOTAL</b></td>
                  <td style="text-align: right;"><b>{{ number_format($voucher->amount, 2) }}</b></td>
                  <td style="text-align: right;"><b>{{ number_format($voucher->amount, 2) }}</b></td>
                </tr>
                <tr>
                  <td colspan="3">
                    <span style="border: 1px solid black; padding: 3px;">C.</span><b> Certified:</b>
                  </td>
                  <td colspan="3">
                    <span style="border: 1px solid black; padding: 3px;">D.</span><b> Approved Payment</b>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <div class="checkbox-container">
                      @if($certifiedArray[0] != 0)
                        <div style="background-color: #ba1369; border: 1px solid black;  width: 14.5px; height: 14.5px; display: inline-block; margin-top: 6px;"></div>
                      @else
                        <input type="checkbox" id="Others">
                      @endif
                      <label>Cash Available</label>
                    </div><br>
                    <div class="checkbox-container">
                      @if($certifiedArray[1] != 0)
                        <div style="background-color: #ba1369; border: 1px solid black;  width: 14.5px; height: 14.5px; display: inline-block; margin-top: 6px;"></div>
                      @else
                        <input type="checkbox" id="Others">
                      @endif
                      <label>Subject to Authority to Debit Account (when applicable)</label>
                    </div><br>
                    <div class="checkbox-container">
                      @if($certifiedArray[2] != 0)
                        <div style="background-color: #ba1369; border: 1px solid black;  width: 14.5px; height: 14.5px; display: inline-block; margin-top: 6px;"></div>
                      @else
                        <input type="checkbox" id="Others">
                      @endif
                      <label>Supporting documents compete and amount claimed proper</label>
                    </div>
                  </td>
                  <td colspan="3" style="text-align: center; vertical-align: middle; font-size: 12px;">
                    <b>{{ ucwords($voucher->amount_txt) }}</b>
                  </td>
                </tr>
                <tr>
                  <td>Signature</td>
                  <td colspan="2"></td>
                  <td>Signature</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td>Printed Name</td>
                  <td colspan="2" style="text-align: center; font-size: 13px;"><b>ELFRED M. SUMONGSONG, CPA</b></td>
                  <td>Printed Name</td>
                  <td colspan="2" style="text-align: center; font-size: 13px;"><b>ALADINO C. MORACA, Ph.D</b></td>
                </tr>
                <tr>
                  <td>Position</td>
                  <td colspan="2" style="font-size: 9px; text-align: center;">Head, Accounting Unit/Authorized Representative</td>
                  <td>Position</td>
                  <td colspan="2" style="font-size: 9px; text-align: center;">Agency head/Authorize Representative</td>
                </tr>
                <tr>
                  <td>Date</td>
                  <td colspan="2"></td>
                  <td>Date</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="5">
                    <span style="border: 1px solid black; padding: 3px;">E.</span><b> Receipt of Payment</b>
                  </td>
                  <td>
                    JEV No.
                  </td>
                </tr>
                <tr>
                  <td style="text-align: center;">Check/ADA No.: </td>
                  <td></td>
                  <td>Date:</td>
                  <td colspan="2">Bank Name & Account Number</td>
                  <td></td>
                </tr>
                <tr>
                  <td rowspan="2"style="text-align: center; vertical-align: middle;">Signature </td>
                  <td rowspan="2"></td>
                  <td rowspan="2">Date:</td>
                  <td rowspan="2" colspan="2">Printed Name <div style="color: white;">.</div>
                    <div style="text-align: center; font-size: 13px;">
                      <b>{{ strtoupper($voucher->fname) }}</b>  <b>{{ strtoupper($voucher->mname) }}</b>  <b>{{ strtoupper($voucher->lname) }}</b>
                    </div>
                  </td>
                  <td>Date</td>
                </tr>
                <tr>
                  <td rowspan="2"></td>
                </tr>
                <tr>
                  <td colspan="5">Official Receipt No. & Date/Other Documents</td>
                </tr>
              </tbody>
            </table>
            <div style="width: 100%; text-align: center; font-size: 11px; margin-top: -10px;">
              <b>
              <span style="text-align: center; margin-right: 15px;">CPSU-F-ACC-01</span>
              <span style="text-align: center; margin-right: 15px;">Effective Date: November 20, 2018</span>
              <span style="text-align: center; margin-right: 15px;">Page No: 1 of 1</span>
            </div>
        </div>
      </div>    
    </body>    
</html>

