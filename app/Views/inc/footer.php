<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
<!-- Include jQuery before your script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="path/to/your/script.js"></script> -->
<script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script>
    let table = new DataTable('#myTable');
</script>
</body>
</html>

<script>
$(document).ready(function(){
   $('#import_excel_from').on('submit',function(e){
    e.preventDefault();
    $.ajax({
        url:"<?php base_url()?>/import",
        method:'POST',
        data:new FormData(this),
        contentType:false,
        processData:false,
        beforeSend:function(){
            $('#import').attr('disabled','disabled');
            $('#import').val('Importing............');
        },
        success:function(data){
            $('#message').html(data);
            $('#import_excel_form')[0].reset();
            $('#import').attr('disabled',false);
            $('#import').val('Import')
        }
    })
   })
})
</script>