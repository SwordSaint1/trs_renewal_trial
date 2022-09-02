<?php 
include '../conn.php';

$method = $_POST['method'];

if ($method == 'fetch_for_auth') {
	$start = $_POST['start'];
	$shift = $_POST['shift'];
	$c = 0;
	$query = "SELECT *,TIME_FORMAT(start_time, '%H:%i:%s') as start_time, TIME_FORMAT(end_time, '%H:%i:%s') as end_time FROM trs_renewal_sched WHERE start_date LIKE '$start%' AND shift LIKE '$shift%' AND sched_stat = 1 ORDER BY start_date DESC";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			

			echo '<tr style="cursor:pointer;  class="modal-trigger" data-toggle="modal" data-target="#for_authorization" onclick="get_for_authorization_details(&quot;'.$j['id'].'~!~'.$j['training_code'].'~!~'.$j['shift'].'~!~'.$j['start_date'].'~!~'.$j['end_date'].'~!~'.$j['start_time'].'~!~'.$j['end_time'].'~!~'.$j['location'].'~!~'.$j['trainer'].'~!~'.$j['slot'].'&quot;)">';
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

if ($method == 'count_attendees') {
	$tr_code = $_POST['tr_code'];

	$query = "SELECT count(*) as total FROM trs_renewal_request WHERE tr_code = '$tr_code'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		echo $total = $row['total'];
	}
}

if ($method == 'fetch_prev_auth') {
	$tr_code = $_POST['tr_code'];
	$c = 0;
	$query = "SELECT * FROM trs_renewal_request WHERE tr_code = '$tr_code' AND exam_status IS NULL OR exam_status = 'Ongoing' ORDER BY process ASC";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$final_status = $j['final_status'];
			$code = $j['tr_code'];
			$c++;
			
			if ($code == $tr_code) {
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
				// echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
				echo '<td>'.$j['exam_status'].'</td>';
				echo '<td>'.$j['attendance_status'].'</td>';
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
				// echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
				echo '<td>'.$j['exam_status'].'</td>';
				echo '<td>'.$j['attendance_status'].'</td>';
			echo '</tr>';
			}
			}
			
		}
	}else{
			echo '<tr>';
				echo '<td colspan="10" style="color:red;">No Result!<td>';
			echo '</tr>';
	}
}

if ($method == 'count_row') {
	$tr_code = $_POST['tr_code'];

	$query = "SELECT COUNT(*) AS total FROM trs_renewal_request WHERE tr_code = '$tr_code' AND exam_status IN ('NULL','Ongoing')";
	$stmt = $conn->prepare($query);
	if ($stmt->execute()) {
		foreach($stmt->fetchALL() as $j){
			echo $count = $j['total'];
		}
	}
}

if ($method == 'close_schedule') {
	$tr_code = $_POST['tr_code'];

	$query = "UPDATE trs_renewal_sched SET sched_stat = 0 WHERE training_code = '$tr_code'";
	$stmt = $conn->prepare($query);
	if ($stmt->execute()) {
		echo 'success';
	}else{
		echo 'error';
	}
}

if ($method == 'authorization') {
    $id = [];
    $id = $_POST['id'];
   	$attendance_status = $_POST['attendance_status'];
   	$exam_status = $_POST['exam_status'];
    //COUNT OF ITEM TO BE UPDATED
    $count = count($id);
    foreach($id as $x){
    	if ($attendance_status == 'Attend') {
    		$query = "UPDATE trs_renewal_request SET exam_status = '$exam_status', attendance_status = '$attendance_status', attend = 1, not_attend = NULL WHERE id = '$x'";
    		$stmt = $conn->prepare($query);
    		if ($stmt->execute()) {

    			$insert = "INSERT INTO trs_renewal_history(`code`,`process`,`expiration_on_month`,`authorization_no`,`name`,`falp_id_no`,`sp_id_no`,`batch_no`,`status`,`reason`,`attend`,`not_attend`,`remarks`,`examiner`,`location`,`shift`,`start_date`,`end_date`,`start_time`,`end_time`,`tr_code`,`exam_status`,`attendance_status`,`final_status`,`ft_status`,`requested_by`) SELECT code,process,expiration_on_month,authorization_no,name,falp_id_no,sp_id_no,batch_no,status,reason,attend,not_attend,remarks,examiner,location,shift,start_date,end_date,start_time,end_time,tr_code,exam_status,attendance_status,final_status,ft_status,requested_by FROM trs_renewal_request WHERE id = '$x'";
    			$stmt4 = $conn->prepare($insert);
    			if ($stmt4->execute()) {
    				$count = $count - 1;
    			}else{

    			}

    		}else{

    		}
    	}elseif($attendance_status == 'Did_not_Attend'){

    		$insert2 ="INSERT INTO trs_renewal_history(`code`,`process`,`expiration_on_month`,`authorization_no`,`name`,`falp_id_no`,`sp_id_no`,`batch_no`,`status`,`reason`,`attend`,`not_attend`,`remarks`,`examiner`,`location`,`shift`,`start_date`,`end_date`,`start_time`,`end_time`,`tr_code`,`exam_status`,`attendance_status`,`final_status`,`ft_status`,`requested_by`) SELECT code,process,expiration_on_month,authorization_no,name,falp_id_no,sp_id_no,batch_no,status,reason,attend,not_attend,remarks,examiner,location,shift,start_date,end_date,start_time,end_time,tr_code,'$exam_status','$attendance_status',final_status,ft_status,requested_by FROM trs_renewal_request WHERE id = '$x'";
    		$stmt5 = $conn->prepare($insert2);
    		if ($stmt5->execute()) {
    			$query2 = "UPDATE trs_renewal_request SET attend = NULL,not_attend = NULL, remarks = NULL, examiner = NULL, location = NULL, shift = NULL, start_date = NULL, end_date = NULL, start_time = NULL, end_time = NULL, tr_code = NULL, exam_status = NULL, attendance_status	= NULL WHERE id = '$x'";
    		$stmt2 = $conn->prepare($query2);
    		if ($stmt2->execute()) {
    			$count = $count - 1;
    		}else{

    		}
    		}else{

    		}

    		
    	}else{
    		$query3 = "UPDATE trs_renewal_request SET exam_status = '$exam_status', attendance_status = '$attendance_status', attend = 1 WHERE id = '$x'";
    		$stmt3 = $conn->prepare($query3);
    		if ($stmt3->execute()) {
    			
    			$insert3 = "INSERT INTO trs_renewal_history(`code`,`process`,`expiration_on_month`,`authorization_no`,`name`,`falp_id_no`,`sp_id_no`,`batch_no`,`status`,`reason`,`attend`,`not_attend`,`remarks`,`examiner`,`location`,`shift`,`start_date`,`end_date`,`start_time`,`end_time`,`tr_code`,`exam_status`,`attendance_status`,`final_status`,`ft_status`,`requested_by`) SELECT code,process,expiration_on_month,authorization_no,name,falp_id_no,sp_id_no,batch_no,status,reason,attend,not_attend,remarks,examiner,location,shift,start_date,end_date,start_time,end_time,tr_code,exam_status,attendance_status,final_status,ft_status,requested_by FROM trs_renewal_request WHERE id = '$x'";
    			$stmt6 = $conn->prepare($insert3);
    			if ($stmt6->execute()) {
    				$count = $count - 1;
    			}else{
    				
    			}
    		}else{

    		}
    	}

        }
        if($count == 0){
            echo 'success';
        }else{
            echo 'fail';
        }
    }


$conn = NULL;
?>