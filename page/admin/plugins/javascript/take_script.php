<script type="text/javascript">
$(document).ready(function(){
    load_take_admin();
});
    
const load_take_admin =()=>{
    $('#spinner').css('display','block');
    var start = document.getElementById('start_date_take_admin').value;
    var shift = document.getElementById('shift_take_admin').value;
    $.ajax({
      url: '../../process/admin/take.php',
                type: 'POST',
                cache: false,
                data:{
                    method: 'fetch_sched_take_admin',
                    start:start,
                    shift:shift
                },success:function(response){
                   document.getElementById('list_of_sched_take_admin').innerHTML = response;
                   $('#spinner').fadeOut(function(){                       
                    });
                }
   });
}

const get_take_admin_details =(param)=>{
    var string = param.split('~!~');
    var id = string[0];
    var training_code = string[1];
    var shift = string[2];
    var start_date = string[3];
    var end_date = string[4];
    var start_time = string[5];
    var end_time = string[6];
    var location = string[7];
    var trainer = string[8];
    var slot = string[9];


document.getElementById('id_take_admin').value = id;
document.getElementById('id_training_code_take_admin').value = training_code;
document.getElementById('shift_take_admins').value = shift;
document.getElementById('start_date_take_admins').value = start_date;
document.getElementById('end_date_take_admin').value = end_date;
document.getElementById('start_time_take_admin').value = start_time;
document.getElementById('end_time_take_admin').value = end_time;
document.getElementById('location_take_admin').value = location;
document.getElementById('trainer_take_admin').value = trainer;
document.getElementById('slot_take_admin').value = slot;
prev_req();
}

const prev_req =()=>{
    var tr_code = document.getElementById('id_training_code_take_admin').value;
    $.ajax({
      url: '../../process/admin/take.php',
                type: 'POST',
                cache: false,
                data:{
                    method: 'fetch_prev_take_admin',
                    tr_code:tr_code
                },success:function(response){
                   document.getElementById('list_of_take_admin').innerHTML = response;
            
                }
   });
}

function export_take_admin(table_id, separator = ',') {
    // Select rows from table_id
    var rows = document.querySelectorAll('table#' + table_id + ' tr');
    // Construct csv
    var csv = [];
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll('td, th');
        for (var j = 0; j < cols.length; j++) {
            var data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ')
            data = data.replace(/"/g, '""');
            // Push escaped string
            row.push('"' + data + '"');
        }
        csv.push(row.join(separator));
    }
    var csv_string = csv.join('\n');
    // Download it
    var filename = 'List_of_3rd_Take_Failed(2x)'+ '_' + new Date().toLocaleDateString() + '.csv';
    var link = document.createElement('a');
    link.style.display = 'none';
    link.setAttribute('target', '_blank');
    link.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv_string));
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>