<script type="text/javascript">
$(document).ready(function(){
    load_announcement();
});    
const register_announcements =()=>{
    var date = document.getElementById('announcement_date').value;
    var content = document.getElementById('content').value;
    if (date == '') {
        swal('Information','Please Select Date','info');
    }else if(content == ''){
        swal('Information','Please Input Content','info')
    }else{
     $.ajax({
                url: '../../process/admin/announcement.php',
                type: 'POST',
                cache: false,
                data:{
                    method: 'register_announcement',
                    date:date,
                    content:content
                },success:function(response){
                    // console.log(response);
                if (response == 'Already Exist') {
                    swal('Information', 'Already Exist','info');
                    $('#content').val('');
                    load_announcement();
                }else if(response == 'success'){
                    swal('Success','Successfully Announce!','success');
                    $('#content').val('');
                    load_announcement();
                }else{
                    swal('Error','error','info');
                    $('#content').val('');
                    load_announcement();
                }
                }
            });
    }

}

const load_announcement =()=>{
    $.ajax({
      url: '../../process/admin/announcement.php',
                type: 'POST',
                cache: false,
                data:{
                    method: 'fetch_announcement',
                },success:function(response){
                   document.getElementById('list_of_announcement').innerHTML = response;
                   $('#spinner').fadeOut(function(){                       
                    });
                }
   }); 
}

const get_update_announcement_details =(param)=>{
    var string = param.split('~!~');
    var id = string[0];
    var announcement_date = string[1];
    var content = string[2];

document.getElementById('id_announcement').value = id;
document.getElementById('announcement_date_update').value = announcement_date;
document.getElementById('content_update').value = content;
}

const update_announcement =()=>{
 var id = document.getElementById('id_announcement').value;
 var announcement_date = document.getElementById('announcement_date_update').value;
 var content = document.getElementById('content_update').value;

  $.ajax({
        url: '../../process/admin/announcement.php',
        type: 'POST',
        cache: false,
        data:{
            method: 'update_announcement',
                id:id,
                announcement_date: announcement_date,
                content: content
           
        },success:function(response){
           if(response == 'already'){
            swal('Information','Already Exist','info');
            load_announcement();            
           }else if(response == 'success'){
            swal('Information','Successfully Updated','success');
            load_announcement();
           }else{
            swal('Error','Error','error');
           }
           

        }
    });
}

const delete_announcement =()=>{
    var id = document.getElementById('id_announcement').value;
    $.ajax({
        url: '../../process/admin/announcement.php',
        type: 'POST',
        cache: false,
        data:{
            method: 'delete_announcement',
                id:id
           
        },success:function(response){
           if (response == 'success') {
            swal('Information','Successfully Deleted','info');
            load_announcement();
           }else{
            swal('Error','Error','error');
            load_announcement();
           }
           

        }
    });
}
</script>