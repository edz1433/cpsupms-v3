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
              <table class="table table-striped table-bordered landscape-table" style="table-layout: auto; width: 30%; max-width: none;">
                <tbody>
                  <tr>
                    <th colspan="2">
                      Republic of the Philippines<br>
                      CENTRAL PHILIPPINES STATE UNIVERSITY<br>
                      Kabankalan City, Negros Occidental<br><br>

                      Remittance List - {{ ucwords(str_replace(['_'], ' ', $col)) }}<br>
                      For the month of {{ $dateStart }}
                    </th>
                  </tr>
                </tbody>
                <thead>
                  <th>Name</th>
                  <th>{{ ucwords(str_replace(['_'], ' ', $col)) }}</th>
                </thead>
                <thead>
                  @php $total = 0; @endphp
                  @foreach ($datas as $data)
                    @if($data->$col > 0)
                      <tr>
                          <td style="border-none; border-bottom: none;">{{ ucwords($data->lname) }} {{ ucwords($data->fname) }} {{ ucwords($data->mname) }}</td>
                          <td style="border-left: none;">{{ number_format($data->$col, 2) }}</td>
                      </tr>
                      @php  $total += $data->$col; @endphp
                    @endif
                  @endforeach
                  <tr>
                    <td>TOTAL AMOUNT:</td>
                    <td><strong>{{ number_format($total, 2) }}</strong></td>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <td colspan="2">
                      Certified Correct:<br><br>
                      <strong><span style="margin-left: 60%;">ELFRED M. SUMONGSONG</span><br><span style="margin-left: 65%;">Accountant III</span></strong>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>    
    </body>    
</html>

