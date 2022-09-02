<script type="text/javascript">
const load_history =()=>{

     $('#spinner').css('display','block');
     
     var name = document.getElementById('name_history').value;
     var process = document.getElementById('process_history').value;
     
           $.ajax({
                url: '../../process/admin/history.php',
                type: 'POST',
                cache: false,
                data:{
                    method: 'fetch_history',
                    name:name,
					process:process,
                  
                },success:function(response){
                    document.getElementById('list_of_history').innerHTML = response;
                    $('#spinner').fadeOut(function(){                       
                    });
                }
            }); 
}		

function export_history(table_id, separator = ',') {
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
    var filename = 'List_of_History'+ '_' + new Date().toLocaleDateString() + '.csv';
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