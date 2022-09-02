<?php 
include '../conn.php';

$method = $_POST['method'];

if ($method == 'fetch_sched') {
	$start = $_POST['start'];
	$shift = $_POST['shift'];
	$c = 0;
	$query = "SELECT *,TIME_FORMAT(start_time, '%H:%i:%s') as start_time, TIME_FORMAT(end_time, '%H:%i:%s') as end_time FROM trs_renewal_sched WHERE start_date LIKE '$start%' AND shift LIKE '$shift%' AND slot >=1 AND sched_stat = 1 AND start_date >= '$server_date_only'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$start_time = $j['start_time'];
			$sdate = $j['start_date'];
			 $combine = $sdate.''.' '.$start_time;
			$c++;
			
			if ($combine >= $server_date_time) {
				echo '<tr style="cursor:pointer;  class="modal-trigger" data-toggle="modal" data-target="#sched_details" onclick="get_sched_details(&quot;'.$j['id'].'~!~'.$j['training_code'].'~!~'.$j['shift'].'~!~'.$j['start_date'].'~!~'.$j['end_date'].'~!~'.$j['start_time'].'~!~'.$j['end_time'].'~!~'.$j['location'].'~!~'.$j['trainer'].'~!~'.$j['slot'].'&quot;)">';
				// echo '<td>'.$c.'</td>';
				echo '<td>'.$j['training_code'].'</td>';
				echo '<td>'.$j['shift'].'</td>';
				echo '<td>'.$j['slot'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['end_date'].'</td>';
				echo '<td>'.$j['start_time'].'</td>';
				echo '<td>'.$j['end_time'].'</td>';
				echo '<td>'.$j['location'].'</td>';
				echo '<td>'.$j['trainer'].'</td>';
				echo '<td>'.$j['remarks'].'</td>';
			echo '</tr>';
			}

		}
	}else{
			echo '<tr>';
				echo '<td colspan="10" style="color:red;">No Result!<td>';
			echo '</tr>';
	}
}


if ($method == 'fetch_for_sched_req') {
	$fname = $_POST['fname'];
	$process = $_POST['process'];
	$slot = $_POST['slot'];
	$c = 0;
	$query = "SELECT * FROM trs_renewal_request WHERE name LIKE '$fname%' AND process LIKE '$process%' AND status = 'Qualified' AND tr_code IS NULL AND expiration_on_month >= '$server_date_only' LIMIT $slot ";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$final_status = $j['final_status'];
			$c++;
			
			if ($final_status == 'Retain') {
			echo '<tr style="color:red;">';
				echo '<td>';
                	echo '<p>
                        <label>
                            <input type="checkbox" name="" id="" class="singleCheck" value="'.$j['id'].'">
                            <span></span>
                        </label>
                    </p>';
                echo '</td>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
			echo '</tr>';
			}else{

			echo '<tr>';
				echo '<td>';
                	echo '<p>
                        <label>
                            <input type="checkbox" name="" id="" class="singleCheck" value="'.$j['id'].'">
                            <span></span>
                        </label>
                    </p>';
                echo '</td>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
			echo '</tr>';
			}
		}
	}else{
			echo '<tr>';
				echo '<td colspan="9" style="color:red;">No Result!<td>';
			echo '</tr>';
	}
}

if ($method == 'commit_sched') {
    $id = [];
    $id = $_POST['id'];
    $training_code = $_POST['training_code'];
	$shift = $_POST['shift'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$start_time = $_POST['start_time'];
	$end_time = $_POST['end_time'];
	$location = $_POST['location'];
	$trainer = $_POST['trainer'];
	$requested_by = $_POST['requested_by'];	
	$slot = $_POST['slot'];
    //COUNT OF ITEM TO BE UPDATED
    $count = count($id);

    	$select = "SELECT slot FROM trs_renewal_sched WHERE training_code = '$training_code'";
    	$stmt = $conn->prepare($select);
    	if ($stmt->execute()) {
    		foreach($stmt->fetchALL() as $j){
    				$slot = $j['slot'];
    			if ($slot >= 1) {
    				
    				$commit = "UPDATE trs_renewal_sched SET slot = slot - $count WHERE training_code = '$training_code'";
    				$stmt2 = $conn->prepare($commit);
    				if ($stmt2->execute()) {
    					echo "success";
    				}else{
    					echo "error";
    				}

    			foreach($id as $x){
            	
            	$query = "UPDATE trs_renewal_request SET examiner = '$trainer', location = '$location', shift = '$shift', start_date = '$start_date', end_date = '$end_date', start_time = '$start_time', end_time = '$end_time', tr_code = '$training_code',requested_by = '$requested_by' WHERE id = '$x'";
            	$stmt3 = $conn->prepare($query);
            	if ($stmt3->execute()) {
            	// echo 's';
            	$count = $count - 1;
            	}else{

		            }

		        }
		        if($count == 0){
		            echo 'success';
		        }else{
		            echo 'fail';
		        }

    			}else{
    				echo "error";
    			}
    		}
    	}
    	
    }
        


$conn = NULL;
?>