<?php
$tickets=$tickets->showTickets();
$tickets = $tickets['data'];
//  echo "<pre>".print_r($tickets,true);
?>

<table id="listTickets" class="table table-bordered table-striped">
			<thead>
				<tr>
					<!-- <th>S/N</th> -->
					<th>Ticket ID</th>
					<th>Subject</th>
					<th>Department</th>
					<th>Created By</th>					
					<th>Created</th>	
					<th>Status</th>
					<th></th>
					<th></th>
					<th></th>					
				</tr>
			</thead>
			<tbody>
				<?php
				//$sno=count($tickets);
				foreach($tickets as $ticket) {					
					echo '<tr>					
					<td>'.$ticket['uniqid'].'</td>
					<td>'.$ticket['title'].'</td>
					<td>'.$ticket['department'].'</td>
					<td>'.$ticket['creater'].'</td>
					<td>'.$ticket['date'].'</td>
					<td>'.$ticket['status'].'</td>
					<td>'.$ticket['view'].'</td>
					<td>'.$ticket['edit'].'</td>
					<td>'.$ticket['delete'].'</td>
					</tr>';
					// $sno--;
				}
				?>
			</tbody>
		</table>
		<script>
			$('#listTickets').DataTable();
		</script>