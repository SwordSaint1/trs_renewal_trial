<?php 
include '../conn.php';

$method = $_POST['method'];

if ($method == 'fetch_sched_failed_req') {
	$start = $_POST['start'];
	$shift = $_POST['shift'];
	$requested_by = $_POST['requested_by'];
	$c = 0;
	// $query = "SELECT *,TIME_FORMAT(start_time, '%H:%i:%s') as start_time, TIME_FORMAT(end_time, '%H:%i:%s') as end_time FROM trs_renewal_sched WHERE start_date LIKE '$start%' AND shift LIKE '$shift%' AND sched_stat = 0";
	$query = "SELECT trs_renewal_sched.id,trs_renewal_sched.training_code,trs_renewal_sched.shift,trs_renewal_sched.start_date,trs_renewal_sched.end_date,trs_renewal_sched.start_time,TIME_FORMAT(trs_renewal_sched.start_time, '%H:%i:%s') as start_time,trs_renewal_sched.end_time, TIME_FORMAT(trs_renewal_sched.end_time, '%H:%i:%s') as end_time,trs_renewal_sched.location,trs_renewal_sched.trainer,trs_renewal_sched.slot,trs_renewal_request.tr_code FROM trs_renewal_sched LEFT JOIN trs_renewal_request ON trs_renewal_request.tr_code = trs_renewal_sched.training_code WHERE trs_renewal_sched.start_date LIKE '$start%' AND trs_renewal_sched.shift LIKE '$shift%' AND trs_renewal_request.ft_status = 1 AND trs_renewal_request.final_status IS NULL AND trs_renewal_request.exam_status = 'Failed' AND trs_renewal_sched.sched_stat = 0 AND trs_renewal_request.requested_by = '$requested_by'  GROUP BY trs_renewal_sched.training_code";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			

			echo '<tr style="cursor:pointer;  class="modal-trigger" data-toggle="modal" data-target="#failed_req" onclick="get_failed_req_details(&quot;'.$j['id'].'~!~'.$j['training_code'].'~!~'.$j['shift'].'~!~'.$j['start_date'].'~!~'.$j['end_date'].'~!~'.$j['start_time'].'~!~'.$j['end_time'].'~!~'.$j['location'].'~!~'.$j['trainer'].'~!~'.$j['slot'].'&quot;)">';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['training_code'].'</td>';
				echo '<td>'.$j['shift'].'</td>';
				echo '<td>'.$j['slot'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['start_time'].'</td>';
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

if ($method == 'fetch_prev_failed_req') {
	$tr_code = $_POST['tr_code'];
	$requested_by = $_POST['requested_by'];
	$c = 0;
	$query = "SELECT * FROM trs_renewal_request WHERE tr_code = '$tr_code' AND exam_status = 'Failed' AND requested_by = '$requested_by' AND ft_status = 1";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
		 	

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
				echo '<td>'.$j['attendance_status'].'</td>';
				echo '<td>'.$j['exam_status'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['examiner'].'</td>';
				echo '<td>'.$j['final_status'].'</td>';
			echo '</tr>';
		}
	}else{
			echo '<tr>';
				echo '<td colspan="10" style="color:red;">No Result!<td>';
			echo '</tr>';
	}
}


if ($method == 'commit_final_status') {
    $id = [];
    $id = $_POST['id'];
    $final_status = $_POST['final_status'];
    $sc_code = $_POST['sc_code'];
    //COUNT OF ITEM TO BE UPDATED
    $count = count($id);
    foreach($id as $x){
    		$query = "SELECT * FROM trs_renewal_request WHERE tr_code = '$sc_code' AND exam_status = 'Failed' AND id = '$x'";
    		$stmt = $conn->prepare($query);
    		if ($stmt->execute()) {
    			foreach($stmt->fetchALL() as $j){
    			 $ft_status = $j['ft_status'];
    			 $final = $j['final_status'];

					if ($final == '' && $ft_status == 1) {
						if ($final_status == 'Retain') { 

							$insert = "INSERT INTO trs_renewal_history(`code`,`process`,`expiration_on_month`,`authorization_no`,`name`,`falp_id_no`,`sp_id_no`,`batch_no`,`status`,`reason`,`attend`,`not_attend`,`remarks`,`examiner`,`location`,`shift`,`start_date`,`end_date`,`start_time`,`end_time`,`tr_code`,`exam_status`,`attendance_status`,`final_status`,`ft_status`,`requested_by`) SELECT code,process,expiration_on_month,authorization_no,name,falp_id_no,sp_id_no,batch_no,status,reason,attend,not_attend,remarks,examiner,location,shift,start_date,end_date,start_time,end_time,tr_code,exam_status,attendance_status,'$final_status',ft_status,requested_by FROM trs_renewal_request WHERE id = '$x'";
							$stmt5 = $conn->prepare($insert);
							if ($stmt5->execute()) {
								$update = "UPDATE trs_renewal_request SET ft_status = 0, final_status = '$final_status',attend = NULL,not_attend = NULL, remarks = NULL, examiner = NULL, location = NULL, shift = NULL, start_date	 = NULL, end_date = NULL, start_time = NULL, end_time = NULL, tr_code = NULL, exam_status = NULL, attendance_status	= NULL WHERE id = '$x'";
	    			 		$stmt2 = $conn->prepare($update);
	    			 		if ($stmt2->execute()) {
	    			 			echo 'success';
	    			 		}else{
	    			 			echo "error";
	    			 		}
							}else{

							}

							
						}elseif($final_status == 'Transfer'){
							$update2 = "UPDATE trs_renewal_request SET ft_status = 0, final_status = '$final_status' WHERE id = '$x'";
							$stmt3 = $conn->prepare($update2);
							if ($stmt3->execute()) {
								// echo "success";
								$insert2 = "INSERT INTO trs_renewal_history(`code`,`process`,`expiration_on_month`,`authorization_no`,`name`,`falp_id_no`,`sp_id_no`,`batch_no`,`status`,`reason`,`attend`,`not_attend`,`remarks`,`examiner`,`location`,`shift`,`start_date`,`end_date`,`start_time`,`end_time`,`tr_code`,`exam_status`,`attendance_status`,`final_status`,`ft_status`,`requested_by`) SELECT code,process,expiration_on_month,authorization_no,name,falp_id_no,sp_id_no,batch_no,status,reason,attend,not_attend,remarks,examiner,location,shift,start_date,end_date,start_time,end_time,tr_code,exam_status,attendance_status,'$final_status',ft_status,requested_by FROM trs_renewal_request WHERE id = '$x'";
								$stmt6 = $conn->prepare($insert2);
								if ($stmt6->execute()) {
									echo "success";
								}else{

								}
							}else{
								echo "error";
							}
						}elseif($final_status == 'Stop_Processing'){
							$update3 = "UPDATE trs_renewal_request SET ft_status = 0, final_status = '$final_status' WHERE id = '$x'";
							$stmt4 = $conn->prepare($update3);
							if ($stmt4->execute()) {
								// echo "success";
								$insert3 = "INSERT INTO trs_renewal_history(`code`,`process`,`expiration_on_month`,`authorization_no`,`name`,`falp_id_no`,`sp_id_no`,`batch_no`,`status`,`reason`,`attend`,`not_attend`,`remarks`,`examiner`,`location`,`shift`,`start_date`,`end_date`,`start_time`,`end_time`,`tr_code`,`exam_status`,`attendance_status`,`final_status`,`ft_status`,`requested_by`) SELECT code,process,expiration_on_month,authorization_no,name,falp_id_no,sp_id_no,batch_no,status,reason,attend,not_attend,remarks,examiner,location,shift,start_date,end_date,start_time,end_time,tr_code,exam_status,attendance_status,'$final_status',ft_status,requested_by FROM trs_renewal_request WHERE id = '$x'";
								$stmt7 = $conn->prepare($insert3);
								if ($stmt7->execute()) {
									echo "success";
								}else{
									
								}
							}else{
								echo "error";
							}
						}
					}else{
						echo "invalid";
					}

    			}
    			 	
    		}
    }
}
    			
$conn = NULL;
?>