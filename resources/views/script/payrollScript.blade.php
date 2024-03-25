@php $curr_route = request()->route()->getName(); @endphp
<script>
    function start(val){
        document.getElementById("dateEnd").value="";
        document.getElementById("dateEnd").setAttribute("min", val); 
    }
    
    function calculateDays() {
    const stat = document.getElementById("stat-name").value;
    const inputDateStart = document.getElementById("dateStart").value;
    const inputDateEnd = document.getElementById("dateEnd").value;

    const date1 = new Date(inputDateStart);
    const date2 = new Date(inputDateEnd);

    // Calculate the number of days between the two dates
    const millisecondsPerDay = 1000 * 60 * 60 * 24; // number of milliseconds in one day
    const timeDiff = Math.abs(date2.getTime() - date1.getTime());
    const numDays = Math.ceil(timeDiff / millisecondsPerDay); // round up to include both start and end dates

    let numWeekdays = 0;
    for (let i = 0; i < numDays; i++) {
        const currentDate = new Date(date1.getTime() + i * millisecondsPerDay);
        if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) { // 0 is Sunday, 6 is Saturday
            numWeekdays++;
        }
    }

    // Calculate the total number of weekdays in the whole month from the first selected date
    const firstDateMonth = new Date(date1.getFullYear(), date1.getMonth(), 1);
    const lastDateMonth = new Date(date1.getFullYear(), date1.getMonth() + 1, 0);
    let numMonthWeekdays = 0;
    for (let i = 0; i <= lastDateMonth.getDate() - firstDateMonth.getDate(); i++) {
        const currentDate = new Date(firstDateMonth.getTime() + i * millisecondsPerDay);
        if (currentDate.getDay() !== 0 && currentDate.getDay() !== 6) { // 0 is Sunday, 6 is Saturday
            numMonthWeekdays++;
        }
    }

    const totalDays = numDays;
    const totalMonthWeekdays = numMonthWeekdays;

    var regular = totalDays + 1;
    var monthWeekdays = totalMonthWeekdays;
    
    if (stat == 1) {
        document.getElementById("working-days").value = regular;
    } else if (stat == 2) {
        document.getElementById("working-days").value = monthWeekdays;
    } else {
        document.getElementById("working-days").value = numWeekdays;
    }

}



    $(".add-formpayroll").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
        
        $.ajax({
            type:"POST",
            url:"{{ route('createPayroll') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){
                 console.log(response);
                if(response.status == 200){
                    $('.add-formpayroll')[0].reset();
                    $('#modal-addpayroll').modal('hide');
                    toastr.options = {
                         "closeButton":true,
                         "progressBar":true,
                        'positionClass': 'toast-bottom-right',
                    }
                    toastr.success("Successfully Added");
                    // $('.tablepayroll').DataTable().ajax.reload();
                    console.log(response);
                    setInterval(function () {
                        location.reload();
                    }, 2000);
                }
                if(response.status == 400){
                    $.each(response.error, function (prefix, val) { 
                        $('span.'+prefix+'_error').text(val[0]);
                    });
                }
            }
        });
    });
    
    //Delete
    $(document).on('click', '.payroll-delete', function(e){
        var id = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed){
                $.ajax({
                    type: "GET",
                    url: "{{ route('deletePayroll', ':id') }}".replace(':id', id),
                    success: function (response) {  
                        $("#tr-"+id).fadeOut(1000);
                        Swal.fire({
                        title:'Deleted!',
                        text:'Your file has been deleted.',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 1000
                        });
                    }
                });
            }
        })
    });

    $(document).on('click', '.deletePayrollFiles', function(e){
        var id = $(this).val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });

        Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed){
                $.ajax({
                    type: "GET",
                    url: "{{ route('deletePayrollFiles', ':id') }}".replace(':id', id),
                    success: function (response) {  
                        console.log(response.data);
                        $(".tr-"+id).fadeOut(1000);
                        Swal.fire({
                        title:'Deleted!',
                        text:'Your file has been deleted.',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 1000
                        });
                    }
                });
            }
        })
    });

    $(document).on('click', '.deductions, .ad', function(e) {
        e.preventDefault();
        var cat = $(this).val();
        var id = $(this).attr("id");
        var date = $(this).attr("data-date");
        var stat = $(this).attr("data-stat");

        const dateSpan = document.querySelector('.date-deduct');
        dateSpan.textContent = date;
        $("#payroll_id").val(id);
        
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        if(cat == 1){
            $('#modal-deductions').modal('show');
            $.ajax({
                type: "POST",
                url: "{{ route('deductions-edit') }}",
                data: {
                    id: id,
                    cat: cat,
                    stat: stat
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response){
                    console.log(response);
                    if(response.status == 200){
                        var deductions = response.data;
                        $("#tax_one").val(deductions.tax1);
                        $("#tax_two").val(deductions.tax2);
                        $("#catd").val(cat);
                        $("#jo_sss").val(deductions.jo_sss);
                        $("#jo_smlf_loan").val(deductions.jo_smlf_loan);
                        $("#eml").val(deductions.eml);
                        $("#pol_gfal").val(deductions.pol_gfal);
                        $("#consol").val(deductions.consol);
                        $("#ed_asst_mpl").val(deductions.ed_asst_mpl);
                        $("#loan").val(deductions.loan);
                        $("#rlip").val(deductions.rlip);
                        $("#gfal").val(deductions.gfal);
                        $("#mpl").val(deductions.mpl);
                        $("#computer").val(deductions.computer);
                        $("#health").val(deductions.health);
                        $("#prem").val(deductions.prem);
                        $("#calam_loan").val(deductions.calam_loan);
                        $("#mp2").val(deductions.mp2);
                        $("#house_loan").val(deductions.house_loan);
                        $("#philhealth").val(deductions.philhealth);
                        $("#holding_tax").val(deductions.holding_tax);
                        $("#lbp").val(deductions.lbp);
                        $("#cauyan").val(deductions.cauyan);
                        $("#projects").val(deductions.projects);
                        $("#nsca_mpc").val(deductions.nsca_mpc);
                        $("#med_deduction").val(deductions.med_deduction);
                        $("#grad_guarantor").val(deductions.grad_guarantor);
                        $("#cfi").val(deductions.cfi);
                        $("#csb").val(deductions.csb);
                        $("#fasfeed").val(deductions.fasfeed);
                        $("#dis_unliquidated").val(deductions.dis_unliquidated);
                        $("#add_less_abs").val(deductions.add_less_abs);
                        $("#add_less_abs1").val(deductions.add_less_abs1);
                        $("#less_late").val(deductions.less_late);
                        if(deductions.tax2 > 0){
                            $("#twocheckbox").prop("checked", true);
                        }
                    }
                    
                }
            });
        }
        if(cat == 2){
            $('#modal-modification').modal('show');
            $.ajax({
                type: "POST",
                url: "{{ route('modifyShow') }}",
                data:{ 
                    id : id,
                    stat : stat
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('.form-row.modify-show').empty();
                    console.log(response.data);
                    response.data.forEach(function(mody) {

                        var formElement = `
                            <div class="col-md-{{ $curr_route != 'storepayroll' ? 3 : 2 }}">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="${mody.payroll_id}">
                                    <input type="hidden" name="idd" id="idd" value="${mody.pay_id}">
                                    <input type="hidden" name="curr_route" id="curr_route" value="{{$curr_route}}">
                                    <input type="text" name="${mody.column}" step="any" min="0" value="${mody.column}" class="form-control float-right" readonly>
                                </div>
                            </div>
                            <div class="col-md-{{ $curr_route != 'storepayroll' ? 3 : 2 }}">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="${mody.payroll_id}">
                                    <input type="text" name="${mody.column}_label" value="${mody.label}" class="form-control float-right">
                                </div>
                            </div>
                            @if($curr_route !== "storepayroll")
                            <div class="col-md-3">
                                <div class="input-group text-center custom-input-group">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn ${mody.action === 'Additionals' ? 'active' : ''}" onclick="toggleActive(1)">
                                            <input type="radio" style="padding-left: 4px;" name="${mody.column}_action" id="option_b${mody.id}" value="Additionals" ${mody.action === 'Additionals' ? 'checked' : ''}> &emsp;&emsp;Additional &emsp;&emsp;
                                        </label>
                                        <label class="btn ${mody.action === 'Deduction' ? 'active' : ''}" onclick="toggleActive(2)">
                                            <input type="radio" name="${mody.column}_action" id="option_b${mody.id}" value="Deduction" autocomplete="off" ${mody.action === 'Deduction' ? 'checked' : ''}> &emsp;Deduction&emsp;
                                        </label>
                                    </div>
                                </div>                                
                            </div>
                            @endif
                            @if($curr_route == "storepayroll")
                            <div class="col-md-3">
                                <div class="input-group text-center custom-input-group">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn ${mody.action === 'Refund' ? 'active' : ''}" onclick="toggleActive(1)">
                                            <input type="radio" style="padding-left: 4px;" name="${mody.column}_action" id="option_b${mody.id}" value="Refund" ${mody.action === 'Refund' ? 'checked' : ''}> &emsp;&emsp;Refund &emsp;&emsp;
                                        </label>
                                        <label class="btn ${mody.action === 'Deduction' ? 'active' : ''}" onclick="toggleActive(2)">
                                            <input type="radio" name="${mody.column}_action" id="option_b${mody.id}" value="Deduction" autocomplete="off" ${mody.action === 'Deduction' ? 'checked' : ''}> &emsp;Deduction&emsp;
                                        </label>
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-md-3 }}">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <select type="text" id="affected" name="${mody.column}_affected" class="form-control float-right">
                                        <option value="">Affected Columns</option>
                                        <option value='eml' ${mody.affected == 'eml' ? 'selected' : ''}> EML</a>
                                        <option value='pol_gfal' ${mody.affected == 'pol_gfal' ? 'selected' : ''}> POLICY</a>
                                        <option value='consol' ${mody.affected == 'consol' ? 'selected' : ''}> Consol</a>
                                        <option value='ed_asst_mpl' ${mody.affected == 'ed_asst_mpl' ? 'selected' : ''}> MPL loan</a>
                                        <option value='rlip' ${mody.affected == 'rlip' ? 'selected' : ''}> RLIP</a>
                                        <option value='gfal' ${mody.affected == 'gfal' ? 'selected' : ''}> GFAL</a>
                                        <option value='computer' ${mody.affected == 'computer' ? 'selected' : ''}> Computer</a>
                                        <option value='health' ${mody.affected == 'health' ? 'selected' : ''}> Health</a>
                                        <option value='mpl' ${mody.affected == 'mpl' ? 'selected' : ''}> MPL</a>
                                        <option value='prem' ${mody.affected == 'prem' ? 'selected' : ''}> PREM.</a>
                                        <option value='calam_loan' ${mody.affected == 'calam_loan' ? 'selected' : ''}> Calamity Loan</a>
                                        <option value='mp2' ${mody.affected == 'mp2' ? 'selected' : ''}> MP2.</a>
                                        <option value='house_loan' ${mody.affected == 'house_loan' ? 'selected' : ''}> Housing Loan</a>
                                        <option value='philhealth' ${mody.affected == 'philhealth' ? 'selected' : ''}> Philhealth</a>
                                        <option value='holding_tax' ${mody.affected == 'holding_tax' ? 'selected' : ''}> Withholding Tax</a>
                                        <option value='lbp' ${mody.affected == 'lbp' ? 'selected' : ''}> LBP</a>
                                        <option value='cauyan' ${mody.affected == 'cauyan' ? 'selected' : ''}> Cauyan</a>
                                        <option value='projects' ${mody.affected == 'projects' ? 'selected' : ''}> Projects</a>
                                        <option value='nsca_mpc' ${mody.affected == 'nsca_mpc' ? 'selected' : ''}> NSCA MPC</a>
                                        <option value='med_deduction' ${mody.affected == 'med_deduction' ? 'selected' : ''}> Medical Deduction</a>
                                        <option value='grad_guarantor' ${mody.affected == 'grad_guarantor' ? 'selected' : ''}> Graduate Sch. / Guar.</a>
                                        <option value='cfi' ${mody.affected == 'cfi' ? 'selected' : ''}> CFI</a>
                                        <option value='csb' ${mody.affected == 'csb' ? 'selected' : ''}> CSB</a>
                                        <option value='fasfeed' ${mody.affected == 'fasfeed' ? 'selected' : ''}> FASFEED</a>
                                    </select>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-{{ $curr_route != 'storepayroll' ? 3 : 2 }}">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-clipboard"></i>
                                        </span>
                                    </div>
                                    <input type="number" id="add_step_incre" name="${mody.column}_amount" step="any" min="0" onchange="if (this.value <= 0) {this.value = '0.00';}" value="${mody.amount}" class="form-control float-right">
                                </div>
                            </div>
                        `;

                        $('.form-row.modify-show').append(formElement);
                    });
                }
            });

        }

    });

    $(document).on('click', '.additional', function(e){    
        e.preventDefault();
        var cat = $(this).val();
        var id = $(this).attr("id");
        var date = $(this).attr("data-date");
        var modes = $(this).attr("data-modes");
        var stat = $(this).attr("data-stat");
        // const dateSpan = document.querySelector('.date-add');
        // dateSpan.textContent = date;

        $("#payroll_idd").val(id);
        $('#modal-additional').modal('show');

        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "POST",
            url: "{{ route('deductions-edit') }}",
            data: {
                id: id,
                cat: cat,
                modes: modes,
                stat: stat,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response){
                var deductions = response.data;
                $("#cat").val(cat);
                $("#add_sal_diff").val(deductions.add_sal_diff);
                $("#add_nbc_diff").val(deductions.add_nbc_diff);
                $("#add_step_incre").val(deductions.add_step_incre);
                // console.log(response);
            }
            
        });
    });

    $(".additional-update").submit(function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        });
        
        $.ajax({
            type:"POST",
            url:"{{ route('additional-update') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response){
                if(response.status == 200){

                    $('#addition-'+response.id).text(response.addition);
                    $('#deduct-'+response.id).text(response.deduct);
                    $('#net-'+response.id).text(response.net);

                    toastr.options = {
                        "closeButton":true,
                        "progressBar":true,
                        "positionClass": 'toast-bottom-right',
                    }
                    toastr.success("Successfully Update Deductions");
                    $('#modal-additional').modal('hide');

                    // console.log(response);
                }
                else{

                }

            }
        });
    });

    function updateCode(input) {
    const id = input.id;
    const code = input.value;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $.ajax({
        type: "GET",
        url: "{{ route('update-code') }}",
        data: {
            id: id,
            code: code,
            type: 'code'
            },
            success: function(response){
                // console.log(response);
                toastr.options = {
                    "closeButton":true,
                    "progressBar":true,
                    "positionClass": 'toast-bottom-right',
                }
                toastr.success("Update Successfully");
            }
        });
    }

    function updateCheckbox(input) {
    const id = input.id;
    const status = input.value;
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    });

    $.ajax({
        type: "GET",
        url: "{{ route('update-code') }}",
        data: {
            id: id,
            status: status,
            type: 'checkbox'
            },
            success: function(response){
                console.log(response);
                toastr.options = {
                    "closeButton":true,
                    "progressBar":true,
                    "positionClass": 'toast-bottom-right',
                }
                toastr.success("Update Successfully");
            }
        });
        
    }
       
</script>

<script>
    $(document).ready(function() {
        $('#J-work').hide();
        $('#funding').hide();
        $('#jo-type').hide();
    });

    function dayHours(val){
        $('#input-nh').val("");
        if(val == "Days"){
            $('#J-work').show();
            $('#nh').html('No. of Working Days');
            $('#input-nh')
            .attr('placeholder', 'No. of Working Days')
            .attr('min', '1');
            //.attr('max', {{ $days ?? '' }});
        }
        if(val == "Hours"){
            $('#J-work').show();
            $('#nh').html('No. of Working Hours');
            $('#input-nh')
            .attr('placeholder', 'No. of Working Hours')
            .attr('min', '1')
            .attr('max', '');
        }
    }
</script>

<script>
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function calculateDays1(startDate, endDate, stat) {
        const oneDay = 24 * 60 * 60 * 1000;
        let totalDays = Math.floor((endDate - startDate) / oneDay) + 1;

        if (stat != 1) {
            const startDay = startDate.getDay(); 
            const endDay = endDate.getDay(); 
            
            const weekendDays = Math.floor((totalDays + startDay) / 7) + Math.floor((totalDays + endDay) / 7);
            totalDays -= weekendDays;
        }

        document.getElementById('working-days').value = totalDays;
    }

    function checkStat(val) {
        if (val == 1 || val == 4) {
            const firstDayOfMonth = new Date();
            firstDayOfMonth.setDate(1);
            document.getElementById('dateStart').value = formatDate(firstDayOfMonth);

            const lastDayOfMonth = new Date();
            lastDayOfMonth.setMonth(lastDayOfMonth.getMonth() + 1);
            lastDayOfMonth.setDate(0);
            document.getElementById('dateEnd').value = formatDate(lastDayOfMonth);

            calculateDays1(firstDayOfMonth, lastDayOfMonth, val);
            if(val == 4){
                $("#funding").show();
                $("#jo-type").show();
            }
            else{
                $("#funding").hide();
                $("#jo-type").hide();
            }
        }
        else{
            document.getElementById('dateStart').value = "";
            document.getElementById('dateEnd').value = "";
            document.getElementById('working-days').value = "";
        }
    }
</script>
@if($curr_route == 'viewPayroll')
<script>
 $(document).ready(function() {
        $('#statusall').on('change', function() {
            var selectedOption = $(this).val();
            var url = "{{ route('viewPayroll', encrypt($campId)) }}?sid=" + selectedOption;

            window.location.href = url;
        });
    });
</script>
@endif
@if($curr_route == 'storepayroll' || $curr_route == 'storepayroll-jo' || $curr_route == 'storepayroll-partime-jo' || $curr_route == 'storepayroll-partime')
    <script>
        function navigateToPage(selectedValue) {
            @if($curr_route == 'storepayroll')
                const targetPageUrl = `{{ route("storepayroll", [":payrollID", ":statID", ":offID"]) }}`;
            @elseif($curr_route == 'storepayroll-jo')
                const targetPageUrl = `{{ route("storepayroll-jo", [":payrollID", ":statID", ":offID"]) }}`;
            @elseif($curr_route == 'storepayroll-partime-jo')
                const targetPageUrl = `{{ route("storepayroll-partime-jo", [":payrollID", ":statID", ":offID"]) }}`;
            @elseif($curr_route == 'storepayroll-partime')
                const targetPageUrl = `{{ route("storepayroll-partime", [":payrollID", ":statID", ":offID"]) }}`;
            @endif
            const formattedUrl = targetPageUrl
                .replace(':payrollID', encodeURIComponent({{ $payrollID }}))
                .replace(':statID', encodeURIComponent({{ $statID }}))
                .replace(':offID', encodeURIComponent(selectedValue)); 

            window.location.href = formattedUrl;
        }

    document.addEventListener('DOMContentLoaded', () => {
    const elementsWithClass = document.querySelectorAll(".firstHalf");
    const elementsWithClass1 = document.querySelectorAll(".secondtHalf");

    let sum = 0;

    elementsWithClass.forEach(element => {
        const valueText = element.textContent.trim().replace(',', ''); 
        const value = parseFloat(valueText);
        
        if (!isNaN(value)) {
            sum += value;
        }
    });

    let sum1 = 0;

    elementsWithClass1.forEach(element => {
        const valueText1 = element.textContent.trim().replace(',', '');
        const value1 = parseFloat(valueText1);
        
        if (!isNaN(value1)) {
            sum1 += value1;
        }    
    });

    var sumtotal = sum + sum1;

    const totalElement = document.getElementById("firstHalfTotal");
    const totalElement1 = document.getElementById("secondtHalfTotal");
    const totalElement2 = document.getElementById("grandtotalnet");

    totalElement.textContent = sum.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    totalElement1.textContent = sum1.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    totalElement2.textContent = sumtotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    //Modifies

    const totalRef = document.querySelectorAll(".totalRef");
    const totalDed = document.querySelectorAll(".totalDed");

    let sumRef = 0;

    totalRef.forEach(element => {
        const valueText3 = element.textContent.trim().replace(',', ''); 
        const value = parseFloat(valueText3);
        
        if (!isNaN(value)) {
            sumRef += value;
        }
    });

    let sumDed = 0;

    totalDed.forEach(element => {
        const valueText4 = element.textContent.trim().replace(',', ''); 
        const value = parseFloat(valueText4);
        
        if (!isNaN(value)) {
            sumDed += value;
        }
    });

    const sumtotalRef = document.getElementById("totalRefundAll");
    const sumtotalDed = document.getElementById("totalDeductAll");
    const sumtotalRef1 = document.getElementById("totalRefundAll1");
    const sumtotalDed1 = document.getElementById("totalDeductAll1");

    sumtotalRef.textContent = sumRef.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    sumtotalDed.textContent = sumDed.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    sumtotalRef1.textContent = sumRef.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    sumtotalDed1.textContent = sumDed.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

});

function incomStat(val){
    var net = $('#net-'+val).text();
    var netxcomma = net.replace(/,/g, '');
    var netHalf = parseFloat(netxcomma) / 2;
    netTotal = netHalf.toFixed(2);
    netTotalcom = netHalf.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

    var words = convertToWords(netTotal);
    $('#amount').val(netTotalcom);
    $('#amount_txt').val(words);
}
</script>

<script>
function convertToWords(amount) {
    const ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    const teens = ['', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    const tens = ['', 'Ten', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    const magnitudes = ['', 'Thousand', 'Million', 'Billion', 'Trillion', 'Quadrillion', 'Quintillion', 'Sextillion', 'Septillion', 'Octillion', 'Nonillion', 'Decillion', 'Undecillion', 'Duodecillion', 'Tredecillion', 'Quattuordecillion', 'Quindecillion', 'Sexdecillion', 'Septendecillion', 'Octodecillion', 'Novemdecillion', 'Vigintillion'];

    function convertLessThanOneThousand(number) {
        let words = '';
        if (number >= 100) {
            words += ones[Math.floor(number / 100)] + ' Hundred ';
            number %= 100;
        }
        if (number > 0) {
            if (words !== '') words += ' ';
            if (number < 10) words += ones[number];
            else if (number < 20) words += teens[number - 10];
            else {
                words += tens[Math.floor(number / 10)];
                if (number % 10 !== 0) words += '-' + ones[number % 10];
            }
        }
        return words;
    }

    function convertToWordsHelper(amount) {
        let words = '';
        const chunks = [];
        while (amount > 0) {
            chunks.push(amount % 1000);
            amount = Math.floor(amount / 1000);
        }
        for (let i = chunks.length - 1; i >= 0; i--) {
            if (chunks[i] !== 0) {
                words += convertLessThanOneThousand(chunks[i]) + ' ' + magnitudes[i] + ' ';
            }
        }
        return words.trim();
    }

    const dollars = Math.floor(amount);
    const cents = Math.round((amount - dollars) * 100);

    const dollarsInWords = convertToWordsHelper(dollars);
    const centsInWords = convertLessThanOneThousand(cents) + ' Cents';

    let result = dollarsInWords;
    if (cents > 0) {
        result += ' Pesos And ' + centsInWords;
    } else {
        result += ' Pesos';
    }

    return result;
}
</script>
<script>
    $(document).ready(function(){
        $('input[type="radio"]').change(function(){
            $('input[type="radio"]').parent().removeClass('btn-success');
            if($(this).is(':checked')) {
                $(this).parent().addClass('btn-success');
            }
        });
    });
</script>
@endif
