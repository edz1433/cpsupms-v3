<script>
    $(function(){
        // $("#drate").hide();
        $("#qualification").hide();
        $("#srate").hide();
    });
        
    function stat(val){
        var id = val;
        if(id == "1"){
            $("#rate-title").html("Monthly Rate:");
            $("#qualification").hide();
            $("#srate").show();
            var insrate = document.getElementById("insrate");
            insrate.placeholder = "Monthly Rate";
            insrate.value = "";
        }
        if(id == 2){
            $("#qualification").show();
            $("#srate").hide();
            var insrate = document.getElementById("insrate");
            insrate.placeholder = "Monthly Rate";
            insrate.value = "";
        }
    
        if(id == 4){
            $("#rate-title").html("Monthly Rate:");
            $("#qualification").hide();
            $("#srate").show();
            $("#rate-title1").html("Monthly Rate:");
            var insrate = document.getElementById("insrate");
            insrate.placeholder = "Monthly Rate";
            insrate.value = "";
        }
    }
    
    function stat1(val){
        var id = val;
        if(id == "1"){
            $("#rate-title1").html("Monthly Rate:");
            $("#qualification1").hide();
            $("#srate1").show();
            var insrate1 = document.getElementById("insrate1");
            insrate1.placeholder = "Monthly Rate";
        }
    
        if(id == 2){
            $("#qualification1").show();
            var insrate1 = document.getElementById("insrate1");
            insrate1.placeholder = "Monthly Rate";
        }
    
        if(id == 4){
            $("#rate-title1").html("Monthly Rate:");
            $("#qualification1").hide();
            var insrate1 = document.getElementById("insrate1");
            insrate1.placeholder = "Monthly Rate";
        }
    }
    function quali(id){
        if(id == "1"){
            $("#srate").show();
            $("#rate-title").html("Partime (MA/Phd) Rate:");
            var insrate = document.getElementById("insrate");
            insrate.placeholder = "Daily Rate";
        }
        if(id == "2"){
            $("#srate").show();
            $("#rate-title").html("Partime(CAR) Rate:");
            var insrate = document.getElementById("insrate");
            insrate.placeholder = "Daily Rate";
        }
        if(id == "3"){
            $("#srate").show();
            $("#rate-title").html("Partime(License) Rate:");
            var insrate = document.getElementById("insrate");
            insrate.placeholder = "Daily Rate";
        }
        if(id == "4"){
            $("#srate").show();
            $("#rate-title").html("Partime(MA unit) Rate:");
            var insrate = document.getElementById("insrate");
            insrate.placeholder = "Daily Rate";
        }
    }
    
    function quali1(id){
        if(id == "1"){
            $("#srate1").show();
            $("#rate-title1").html("Partime (MA/Phd) Rate:");
            var insrate1 = document.getElementById("insrate1");
            insrate1.placeholder = "Daily Rate";
        }
        if(id == "2"){
            $("#srate1").show();
            $("#rate-title1").html("Partime(CAR) Rate:");
            var insrate1 = document.getElementById("insrate1");
            insrate1.placeholder = "Daily Rate";
        }
        if(id == "3"){
            $("#srate1").show();
            $("#rate-title1").html("Partime(License) Rate:");
            var insrate1 = document.getElementById("insrate1");
            insrate1.placeholder = "Daily Rate";
        }
        if(id == "4"){
            $("#srate1").show();
            $("#rate-title1").html("Partime(MA unit) Rate:");
            var insrate1 = document.getElementById("insrate1");
            insrate1.placeholder = "Daily Rate";
        }
    }
    </script>
    
    <script>
        $(document).on('click', '.checkbox-partime', function(e){ 
            e.preventDefault();
            var id = $(this).attr('id');
            var url = "{{ route('empEditRate', ['id' => ':id']) }}";
            url = url.replace(':id', id);
            $('#partime-empid').val(id);
            $('#partime-form').modal('show');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response){
                    $("#PartimeRate").val(response.emp.partime_rate);
                }
            });
    
        });
    
        $(".partime-form").submit(function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });
            
            $.ajax({
                type:"POST",
                url:"{{ route('empPartimeRate') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response){
                    $('span#error').text('');
                    console.log(response);
                    if(response.status == 200){
                        $('.partime-form')[0].reset();
                        $('#partime-form').modal('hide');
                        toastr.options = {
                            "closeButton":true,
                            "progressBar":true,
                            'positionClass': 'toast-bottom-right'
                        }
                        toastr.success("Employee Updated Successfully");
                        location.reload();
                    }
                }
            });
        });
    
    
    
        $(document).on('click', '.employee_edit', function(e){ 
            e.preventDefault();
            var id = $(this).val();
            var url = "{{ route('empEdit', ['id' => ':id']) }}";
            url = url.replace(':id', id);
            $('#modal-employee-edit').modal('show');
            $.ajax({
                type: "GET",
                url: url,
                success: function(response){
                    //console.log(response);
                    $("#edit-id").val(response.emp.id);
                    $("#lname").val(response.emp.lname);
                    $("#fname").val(response.emp.fname);
                    $("#mname").val(response.emp.mname);
                    $("#position").val(response.emp.position);
                    $("#sg_step1").val(response.emp.sg_step);
                    $("#empid").val(response.emp.emp_ID);
                    $("#rate").val(response.emp.emp_salary);
                    $(".select_camp").val(response.emp.camp_id).change();
                    $(".select_stat").val(response.emp.emp_status).change();
                    $(".select_office").val(response.emp.emp_dept).change();
                    $(".select_qual").val(response.emp.qualification).change();
                    $("#insrate1").val(response.emp.emp_salary);
                }
            });
        });
    
        
        $(document).on('click', '.employee_delete', function(e){
            var id = $(this).val();
            var url = "{{ route('empDelete', ['id' => ':id']) }}";
            url = url.replace(':id', id);
            
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
                        url: url,
                        success: function (response) {  
                            $("#tr-"+id).fadeOut(2000);
                            Swal.fire({
                            title:'Deleted!',
                            text:'Your file has been deleted.',
                            type:'success',
                            icon: 'warning',
                            showConfirmButton: false,
                            timer: 1000
                            })
                        }
                    });
                }
            })
        });
        
    </script>
