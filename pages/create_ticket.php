<?php
if($_POST){
	if($id==0) {
		list($status,$msg)=$tickets->createTicket();
		if($status==200) {
			// echo "<script>setTimeout(function(){ alert('asdfad');},2000);</script>";exit;
		}
	}else {

	}
}

?>
<div class="modal-dialog-">
		<form method="post" id="ticketForm-">
			<div class="modal-content-">
				<div class="modal-header">					
					<h4 class="modal-title"><i class="fa fa-plus"></i> <?php echo $id==0 ? "Add Ticket" : "Update Ticket";?></h4>
					<?php if(!empty($status)){ ?>
					<div class="alert <?php echo $status==200 ? 'alert-success' : 'alert-danger'; ?>">
						<?php echo $msg; 
						if($status==200) {?>
						<script>setTimeout(function(){ window.location="./";},2000);</script>
						<?php }
						?>
					</div>
					<?php } ?>
				</div>
				<div class="modal-body">
					<div class="form-group"
						<label for="subject" class="control-label">Ticket Subject</label>
						<input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>			
					</div>
					<div class="form-group">
						<label for="department" class="control-label">Choose Related Department</label>							
						<select id="department" name="department" class="form-control" placeholder="Department...">					
							<?php $tickets->getDepartments(); ?>
						</select>						
					</div>						
					<div class="form-group">
						<label for="message" class="control-label">Your Message</label>							
						<textarea class="form-control" rows="5" id="message" name="message"></textarea>							
					</div>	
					<div class="form-group">
						<label for="status" class="control-label">Ticket Status</label>							
						<label class="radio-inline">
							<input type="radio" name="status" id="open" value="0" checked required>Open
						</label>
						<?php if(isset($_SESSION["admin"])) { ?>
							<label class="radio-inline">
								<input type="radio" name="status" id="close" value="1" required>Close
							</label>
						<?php } ?>	
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="p" id="p" value="<?= $p; ?>"/>	
					<input type="hidden" name="Id" id="Id" value="<?= $id; ?>"/>
					<input type="submit" name="save" id="save" class="btn btn-primary" value="Save" />
					<a href="./index.php" class="btn btn-default">Close</a>
				</div>
			</div>
		</form>
	</div>
