<?php 
include '../conn.php';
 
$method = $_POST['method'];

if ($method == 'fetch_history') {
	$name = $_POST['name'];
	$process = $_POST['process'];
	$c = 0;
	$query = "SELECT * FROM trs_renewal_history WHERE name LIKE '$name%' AND process LIKE '$process%' AND exam_status != 'Ongoing'";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$reason = $j['reason'];
			$final_status = $j['final_status'];
			$c++;
			
			if ($final_status == 'Retain') {
			echo '<tr style="color:red;">';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
				echo '<td>'.$j['batch_no'].'</td>';
				echo '<td>'.$j['status'].'</td>';
				if ($reason = 'n/a') {
					echo '<td></td>';
				}else{
					echo '<td>'.$j['reason'].'</td>';
				}
				echo '<td>'.$j['attendance_status'].'</td>';
				echo '<td>'.$j['exam_status'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['shift'].'</td>';
				echo '<td>'.$j['examiner'].'</td>';
				echo '<td>'.$j['final_status'].'</td>';
			echo '</tr>';
			}else{
			echo '<tr>';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['code'].'</td>';
				echo '<td>'.$j['process'].'</td>';
				echo '<td>'.$j['expiration_on_month'].'</td>';
				echo '<td>'.$j['authorization_no'].'</td>';
				echo '<td>'.$j['name'].'</td>';
				echo '<td>'.$j['falp_id_no'].'</td>';
				echo '<td>'.$j['sp_id_no'].'</td>';
				echo '<td>'.$j['batch_no'].'</td>';
				echo '<td>'.$j['status'].'</td>';
				if ($reason = 'n/a') {
					echo '<td></td>';
				}else{
					echo '<td>'.$j['reason'].'</td>';
				}
				echo '<td>'.$j['attendance_status'].'</td>';
				echo '<td>'.$j['exam_status'].'</td>';
				echo '<td>'.$j['start_date'].'</td>';
				echo '<td>'.$j['shift'].'</td>';
				echo '<td>'.$j['examiner'].'</td>';
				echo '<td>'.$j['final_status'].'</td>';
			echo '</tr>';
			}
		}
	}else{
			echo '<tr>';
				echo '<td colspan="11" style="color:red;">No Result!<td>';
			echo '</tr>';
	}
}

$conn = NULL;
?>