<script>
    function updateSetting(id, val, code){
        $.ajax({
        type: "GET",
        url: "{{ route('updatePercent') }}",
        data: {
            id: id,
            val: val,
            code: code
            },
            success: function(data){
                toastr.options = {
                    "closeButton":true,
                    "progressBar":true,
                    "positionClass": 'toast-bottom-right',
                }
            }
        });
    }
</script>