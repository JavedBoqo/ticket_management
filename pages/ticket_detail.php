<?php 

if($_POST) {
	if($p=="ticket-reply") {
		$tickets->saveTicketReplies();
	}
}
$ticketDetails = $tickets->ticketInfo($id);
$ticketDetails = $ticketDetails[0];

$ticketReplies = $tickets->getTicketReplies($ticketDetails->id);
// printR($ticketReplies);

$user = $users->getUserInfo();
$tickets->updateTicketReadStatus($ticketDetails->id);
?>	
<link rel="stylesheet" href="css/style.css" />
<div class="container">
	<div class="row home-sections">
	<h2></h2>	
	
	</div> 
	
	<section class="comment-list">          
		<article class="row">      
			<h2><a class="btn btn-primary" href="./?p=tickets">Tickets</a></h2>      
			<div class="col-md-10 col-sm-10">
				<div class="panel panel-default arrow left">
					<div class="panel-heading right">
					<?php if($ticketDetails->resolved) { ?>
					<button type="button" class="btn btn-danger btn-sm">
					  <span class="glyphicon glyphicon-eye-close"></span> Closed
					</button>
					<?php } else { ?>
					<button type="button" class="btn btn-success btn-sm">
					  <span class="glyphicon glyphicon-eye-open"></span> Open
					</button>
					<?php } ?>
					<span class="ticket-title"><?= $ticketDetails->title; ?></span>
					</div>
					<div class="panel-body">						
						<div class="comment-post">
						<p>
						<?= $ticketDetails->message; ?>
						</p>
						</div>                 
					</div>
					<div class="panel-heading right">
						<span class="glyphicon glyphicon-time"></span> <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> <?php echo $time->ago($ticketDetails->date); ?></time>
						&nbsp;&nbsp;<span class="glyphicon glyphicon-user"></span> <?= $ticketDetails->creater; ?>
						&nbsp;&nbsp;<span class="glyphicon glyphicon-briefcase"></span> <?= $ticketDetails->department; ?>
					</div>
				</div>			 
			</div>
		</article>		
		
		<?php 
		if($ticketReplies){
		foreach ($ticketReplies as $replies) { ?>		
			<article class="row">
				<div class="col-md-10 col-sm-10">
					<div class="panel panel-default arrow right">
						<div class="panel-heading">
							<?php if($replies->user_group == 1) { ?>							
								<span class="glyphicon glyphicon-user"></span> <?= $ticketDetails->department; ?>
							<?php } else { ?>
								<span class="glyphicon glyphicon-user"></span> <?= $replies->creater; ?>
							<?php } ?>
							&nbsp;&nbsp;<span class="glyphicon glyphicon-time"></span> <time class="comment-date" datetime="16-12-2014 01:05"><i class="fa fa-clock-o"></i> <?php echo $time->ago($replies->date); ?></time>
						</div>
						<div class="panel-body">						
							<div class="comment-post">
							<p>
							<?= $replies->message; ?>
							</p>
							</div>                  
						</div>
						
					</div>
				</div>            
			</article> 		
		<?php }
		} ?>
		
		<form method="post" action="" name="ticketReply">
			<article class="row">
				<div class="col-md-10 col-sm-10">				
					<div class="form-group">							
						<textarea class="form-control" rows="5" id="message" name="message" placeholder="Type your reply to ticket..." required></textarea>	
					</div>				
			</div>
			</article>  
			<article class="row">
				<div class="col-md-10 col-sm-10">
					<div class="form-group">							
						<input type="submit" name="reply_ticket" id="reply_ticket" class="btn btn-success" value="Reply" />		
						<a class="btn btn-default" href="./?p=tickets">Back</a>
					</div>
				</div>
			</article> 
			<input type="hidden" name="id" id="id" value="<?= $ticketDetails->id; ?>" />	
			<input type="hidden" name="p" id="p" value="ticket-reply"/>	
		</form>
	</section>	
	
</div>