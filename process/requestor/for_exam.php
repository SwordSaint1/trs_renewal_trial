<?php 
include '../conn.php';

$method = $_POST['method'];

if ($method == 'fetch_for_exam') {
	$start = $_POST['start'];
	$shift = $_POST['shift'];
	$requested_by = $_POST['requested_by'];
	$c = 0;

	$query = "SELECT trs_renewal_sched.id,trs_renewal_sched.training_code,trs_renewal_sched.shift,trs_renewal_sched.start_date,trs_renewal_sched.end_date,trs_renewal_sched.start_time,TIME_FORMAT(trs_renewal_sched.start_time, '%H:%i:%s') as start_time,trs_renewal_sched.end_time, TIME_FORMAT(trs_renewal_sched.end_time, '%H:%i:%s') as end_time,trs_renewal_sched.location,trs_renewal_sched.trainer,trs_renewal_sched.slot,trs_renewal_request.tr_code FROM trs_renewal_sched LEFT JOIN trs_renewal_request ON trs_renewal_request.tr_code = trs_renewal_sched.training_code WHERE trs_renewal_request.tr_code != '' AND trs_renewal_sched.sched_stat = 1 AND trs_renewal_sched.start_date LIKE '$start%' AND trs_renewal_sched.shift LIKE '$shift%' AND trs_renewal_request.requested_by = '$requested_by' GROUP BY trs_renewal_sched.training_code";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			
			echo '<tr style="cursor:pointer;  class="modal-trigger" data-toggle="modal" data-target="#for_exam" onclick="get_for_exam_details(&quot;'.$j['id'].'~!~'.$j['training_code'].'~!~'.$j['shift'].'~!~'.$j['start_date'].'~!~'.$j['end_date'].'~!~'.$j['start_time'].'~!~'.$j['end_time'].'~!~'.$j['location'].'~!~'.$j['trainer'].'~!~'.$j['slot'].'&quot;)">';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['training_code'].'</td>';
				echo '<td>'.$j['shift'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['end_date'].'</td>';
				echo '<td>'.$j['start_time'].'</td>';
				echo '<td>'.$j['end_time'].'</td>';
				echo '<td>'.$j['location'].'</td>';
				echo '<td>'.$j['trainer'].'</td>';
			echo '</tr>';
		}
	}else{
			echo '<tr>';
				echo '<td colspan="10" style="color:red;">No Result!<td>';
			echo '</tr>';
	}

}

if ($method == 'prev_exam') {
	$tr_code = $_POST['tr_code'];
	$requested_by = $_POST['requested_by'];
	$c = 0;
	$query = "SELECT * FROM trs_renewal_request WHERE exam_status IS NULL OR exam_status = 'Ongoing' AND requested_by = '$requested_by'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$final_status = $j['final_status'];
			$code = $j['tr_code'];
 			if ($code == $tr_code) {
 				if ($final_status == 'Retain') {
			echo '<tr>';
				// echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
				echo '<td>'.$j['tr_code'].'</td>';
				echo '<td>'.$j['examiner'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['shift'].'</td>';
				echo '<td>'.$j['start_time'].'</td>';
				echo '<td>'.$j['exam_status'].'</td>';
			echo '</tr>';
			}else{
			echo '<tr>';
				// echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
				echo '<td>'.$j['tr_code'].'</td>';
				echo '<td>'.$j['examiner'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['shift'].'</td>';
				echo '<td>'.$j['start_time'].'</td>';
				echo '<td>'.$j['exam_status'].'</td>';
			echo '</tr>';
			}
 			}
						
		}
	}else{
			echo '<tr>';
				echo '<td colspan="9" style="color:red;">No Result!<td>';
			echo '</tr>';
	}
}


$conn = NULL;

?>