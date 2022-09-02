<script type="text/javascript">
$(document).ready(function(){
    load_request();
}); 
const load_request =()=>{

     $('#spinner').css('display','block');
     
     var name = document.getElementById('name').value;
     var process = document.getElementById('process').value;
     
           $.ajax({
                url: '../../process/admin/request.php',
                type: 'POST',
                cache: false,
                data:{
                    method: 'fetch_request',
                    name:name,
					process:process,
                  
                },success:function(response){
                    document.getElementById('list_of_request').innerHTML = response;
                    $('#spinner').fadeOut(function(){                       
                    });
                }
            }); 
}	
</script>