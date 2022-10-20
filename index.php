<?php

include('includes/dbconnection.php');
//error_reporting(0);




 
  
  ?>





<!doctype html>
<html lang="en">
  <head>
  	<title>BIFPCL Employee Directory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

	<link href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">

	</head>
	<body>
	<section class="ftco-sections">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">BIFPCL Employee Directory</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-wrap">
						<table class="table table-responsive-xl">
						  <thead>
						    <tr>
						      <th>Name</th>
						      <th>Email</th>
						      <th>Phone</th>
							  <th>RAX</th>
							  <th>Available at Office</th>
						      
						    </tr>
						  </thead>
						  <tbody>

	<?php
	$ret=mysqli_query($con,"SELECT EmpInfo.emp_code, EmpInfo.first_name, EmpInfo.email, EmpInfo.mobile, EmpInfo.office_tel, EmpPost.position_name, EmpPost.position_code, EmpArea.area_id, EmpAreaName.area_code, EmpAreaName.area_name
	FROM personnel_employee AS EmpInfo 
	LEFT JOIN personnel_position AS EmpPost  
	ON EmpInfo.position_id=EmpPost.id 
	LEFT JOIN personnel_employee_area As EmpArea
	ON EmpArea.employee_id = EmpInfo.id 
	LEFT JOIN personnel_area AS EmpAreaName
	ON EmpAreaName.id = EmpArea.area_id 
	where EmpInfo.status  != '100' AND  EmpInfo.SSN IS NOT NULL AND EmpPost.position_code IS NOT NULL GROUP BY EmpInfo.emp_code
	Order by EmpAreaName.area_name ASC, EmpPost.position_code ASC, EmpInfo.emp_code ASC");
	$cnt=1;
	while ($row=mysqli_fetch_array($ret)) {
	$employee_code = $row['emp_code'];
	$punchRecordSql=mysqli_query($con,"select COUNT(punch_time) as punch_count,MAX(time(punch_time)) as lastpunch,TIMEDIFF((MAX(time(punch_time))), (MIN(time(punch_time)))) as officestaying from iclock_transaction where emp_code = $employee_code AND DATE(iclock_transaction.punch_time) = CURRENT_DATE");
	while ($punchRecord=mysqli_fetch_array($punchRecordSql)) {
	$record = $punchRecord['punch_count'];
	$lastrecord = $punchRecord['lastpunch'];
	$recorddiff = $punchRecord['officestaying'];

	}


	?>







						    <tr class="alert" role="alert">
						    	
						      <td class="d-flex align-items-center">
						      	<div class="img" style="background-image: url(http://10.8.215.18:8081/files/photo/<?php echo $employee_code; ?>.jpg );"></div>
						      	<div class="pl-3 email">
						      		<span><?php echo $row['first_name'];?></span>
						      		<span><?php echo $row['position_name'].", ";
                							if (($row['area_name']) == "Head Office") {
                 							 echo "HO";
                							} else {echo "MSTPP";}
		                					?></span>
						      	</div>
						      </td>
						      <td><?php echo $row['email'];?></td>
							  <td><?php echo $row['mobile'];?></td>
							  <td><?php echo $row['office_tel'];?></td>
						      <td class="status">	

						      	<?php if ($record > '0' && ($recorddiff == '00:00:00' || $recorddiff < '01:00:00' )) {echo "<span class='active'>YES</span></td>";} else { echo "<span class='waiting'>NO</span></td>";}?>

						      	

						      	
						      
						    </tr>
						    </tbody>
						    <?php 
$cnt=$cnt+1;
}?>
						    </table>


					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>

	</body>
</html>

<?php   ?>