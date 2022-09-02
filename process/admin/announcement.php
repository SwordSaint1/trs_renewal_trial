<?php 
include '../conn.php';

$method = $_POST['method'];

if ($method == 'register_announcement') {
	$date = $_POST['date'];
	$content = $_POST['content'];

	$check = "SELECT id FROM trs_renewal_announcement WHERE announcement_date = '$date' AND content = '$content'";
	$stmt = $conn->prepare($check);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo "Already Exist";
	}else{
		$query = "INSERT INTO trs_renewal_announcement (`announcement_date`,`content`) VALUES ('$date','$content')";
		$stmt2 = $conn->prepare($query);
		if ($stmt2->execute()) {
			echo 'success';
		}else{
			echo 'error';
		}
	}
}

if ($method == 'fetch_announcement') {
	$c = 0;
	$query = "SELECT * FROM trs_renewal_announcement ORDER BY announcement_date DESC";
	$stmt = $conn->prepare($query);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		foreach($stmt->fetchALL() as $j){
			$c++;
			echo '<tr style="cursor:pointer;  class="modal-trigger" data-toggle="modal" data-target="#update_announcement" onclick="get_update_announcement_details(&quot;'.$j['id'].'~!~'.$j['announcement_date'].'~!~'.$j['content'].'&quot;)">';
				echo '<td>'.$c.'</td>';
				echo '<td>'.$j['content'].'</td>';
				echo '<td>'.$j['announcement_date'].'</td>';
			echo '</tr>';
		}
	}else{
			echo '<tr>';
				echo '<td colspan="3" style="color:red;">No Result!<td>';
			echo '</tr>';
	}
}

if ($method == 'update_announcement') {
	$id = $_POST['id'];
	$announcement_date = $_POST['announcement_date'];
	$content = $_POST['content'];

	$check = "SELECT id FROM trs_renewal_announcement WHERE announcement_date = '$announcement_date' AND content = '$content'";
	$stmt = $conn->prepare($check);
	$stmt->execute();
	if ($stmt->rowCount() > 0) {
		echo 'already';
	}else{
		$query = "UPDATE trs_renewal_announcement SET announcement_date = '$announcement_date', content = '$content' WHERE id = '$id'";
		$stmt2 = $conn->prepare($query);
		if ($stmt2->execute()) {
			echo 'success';
		}else{
			echo 'error';
		}
	}
}

if($method == 'delete_announcement'){
	$id = $_POST['id'];
	$query = "DELETE FROM trs_renewal_announcement WHERE id = '$id'";
	$stmt = $conn->prepare($query);
	if ($stmt->execute()) {
		echo 'success';
	}else{
		echo 'error';
	}
}
$conn = NULL;
?>