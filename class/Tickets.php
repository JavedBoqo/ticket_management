<?php

class Tickets extends Database {  
    private $ticketTable = 'hd_tickets';
	private $ticketRepliesTable = 'hd_ticket_replies';
	private $departmentsTable = 'hd_departments';
	private $dbConnect = false;
	function __construct(){		
        $this->dbConnect = $this->dbConnect();
    } 
	function showTickets(){
		$sqlWhere = '';	
		if(!isset($_SESSION["admin"])) {
			$sqlWhere .= " WHERE t.user = '".$this->getLoggedUserId()."' ";
			if(!empty($_POST["search"]["value"])){
				$sqlWhere .= " and ";
			}
		} else if(isset($_SESSION["admin"]) && !empty($_POST["search"]["value"])) {
			$sqlWhere .= " WHERE ";
		} 		
		$time = new time;  			 
		$sqlQuery = "SELECT t.id, t.uniqid, t.title, t.init_msg as message, t.date, t.last_reply, t.resolved, u.nick_name as creater, d.name as department, u.user_group, t.user, t.user_read, t.admin_read
			FROM hd_tickets t 
			LEFT JOIN hd_users u ON t.user = u.id 
			LEFT JOIN hd_departments d ON t.department = d.id $sqlWhere ";
		if(!empty($_POST["search"]["value"])){
			$sqlQuery .= ' (uniqid LIKE "%'.$_POST["search"]["value"].'%" ';					
			$sqlQuery .= ' OR title LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR resolved LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR last_reply LIKE "%'.$_POST["search"]["value"].'%") ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY t.id DESC ';
		}
		if($_POST["length"] != -1){
			//$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}	
		
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$ticketData = array();	
		while( $ticket = mysqli_fetch_assoc($result) ) {		
			$ticketRows = array();			
			$status = '';
			if($ticket['resolved'] == 0)	{
				$status = '<span class="label label-success">Open</span>';
			} else if($ticket['resolved'] == 1) {
				$status = '<span class="label label-danger">Closed</span>';
			}	
			$title = $ticket['title'];
			if((isset($_SESSION["admin"]) && !$ticket['admin_read'] && $ticket['last_reply'] != $_SESSION["userid"]) || (!isset($_SESSION["admin"]) && !$ticket['user_read'] && $ticket['last_reply'] != $ticket['user'])) {
				$title = $this->getRepliedTitle($ticket['title']);			
			}
			$disbaled = '';
			if(!isset($_SESSION["admin"])) {
				$disbaled = 'disabled';
			}			
			$ticketRows['id'] = $ticket['id'];
			$ticketRows['uniqid'] = $ticket['uniqid'];
			$ticketRows['title'] = $title;
			$ticketRows['department'] = $ticket['department'];
			$ticketRows['creater'] = $ticket['creater']; 			
			$ticketRows['date'] = $time->ago($ticket['date']);
			$ticketRows['status'] = $status;
			$ticketRows['view'] = '<a href="index.php?p=ticket-detail&id='.$ticket["uniqid"].'" class="btn btn-success btn-xs update">View Ticket</a>';	
			$ticketRows['edit'] = '<button type="button" name="update" id="'.$ticket["id"].'" class="btn btn-warning btn-xs update" '.$disbaled.'>Edit</button>';
			$ticketRows['delete'] = '<button type="button" name="delete" id="'.$ticket["id"].'" class="btn btn-danger btn-xs delete"  '.$disbaled.'>Close</button>';
			$ticketData[] = $ticketRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$ticketData
		);
		return $output;
	}	
	function getRepliedTitle($title) {
		$title = $title.'<span class="answered">Answered</span>';
		return $title; 		
	}
	function createTicket() {      
		if(!empty($_POST['subject']) && !empty($_POST['message'])) {                
			$date = new DateTime();
			$date = $date->getTimestamp();
			$uniqid = uniqid();                
			$message = strip_tags($_POST['subject']);              
			
			$sql = "INSERT INTO ".$this->ticketTable." (uniqid, user, title, init_msg, department, date, last_reply, user_read, admin_read, resolved)".chr(10);
    		$sql .= "VALUES(?,?,?,?,?,?,?,?,?,?)";
    		ExecuteInsertUpdateQuery($sql, "sissisiiii", array($uniqid,$this->getLoggedUserId(), $_POST['subject'], $message, $_POST['department'], $date,$this->getLoggedUserId(), 0, 0,$_POST['status']));
			
			return [200, "Ticket Created Successfully",$uniqid];
		} else {			
			return [403, "Please fill all required fields"];
		}
	}	
	function getTicketDetails(){
		if($_POST['ticketId']) {	
			$sqlQuery = "
				SELECT * FROM ".$this->ticketTable." 
				WHERE id = '".$_POST["ticketId"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);	
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			echo json_encode($row);
		}
	}
	function updateTicket() {
		if($_POST['ticketId']) {	
			$updateQuery = "UPDATE ".$this->ticketTable." 
			SET title = '".$_POST["subject"]."', department = '".$_POST["department"]."', init_msg = '".$_POST["message"]."', resolved = '".$_POST["status"]."'
			WHERE id ='".$_POST["ticketId"]."'";
			$isUpdated = mysqli_query($this->dbConnect, $updateQuery);		
		}	
	}		
	function closeTicket(){
		if($_POST["ticketId"]) {
			$sqlDelete = "UPDATE ".$this->ticketTable." 
				SET resolved = '1'
				WHERE id = '".$_POST["ticketId"]."'";		
			mysqli_query($this->dbConnect, $sqlDelete);		
		}
	}	
	function getDepartments() {       
		$sqlQuery = "SELECT * FROM ".$this->departmentsTable;
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		while($department = mysqli_fetch_assoc($result) ) {       
            echo '<option value="' . $department['id'] . '">' . $department['name']  . '</option>';           
        }
    }	    
    function ticketInfo($id) {  		
		$sql = "SELECT t.id, t.uniqid, t.title, t.user, t.init_msg as message, t.date, t.last_reply, t.resolved, u.nick_name as creater, d.name as department 
			FROM ".$this->ticketTable." t 
			LEFT JOIN hd_users u ON t.user = u.id 
			LEFT JOIN hd_departments d ON t.department = d.id 
			WHERE t.uniqid = ?";
			$tickets = ExecuteGetQuery($sql,"i",[$id]);	
        return $tickets;
    }    
	function saveTicketReplies () {
		if($_POST['message']) {
			$date = new DateTime();
			$date = $date->getTimestamp();
			$sql = "INSERT INTO ".$this->ticketRepliesTable." 
					(user, text, ticket_id, date) 
					VALUES(?,?,?,?)";
			ExecuteInsertUpdateQuery($sql,"isis",[$this->getLoggedUserId(),$_POST['message'],$_POST['id'],$date]);
			echo $_POST['message']."".$_POST['id']."".$date;
			$sql = "UPDATE ".$this->ticketTable." 
					SET last_reply = ?, user_read = '0', admin_read = '0' 
					WHERE id = ?";
			ExecuteInsertUpdateQuery($sql,"ii",[$this->getLoggedUserId(),$_POST['id']]);
			return [200,"success"];
		}else return [403,"Please enter your messge"]; 
	}	
	function getTicketReplies($id) {  		
		$sql = "SELECT r.id, r.text as message, r.date, u.nick_name as creater, d.name as department, u.user_group  
			FROM ".$this->ticketRepliesTable." r
			LEFT JOIN ".$this->ticketTable." t ON r.ticket_id = t.id
			LEFT JOIN hd_users u ON r.user = u.id 
			LEFT JOIN hd_departments d ON t.department = d.id 
			WHERE r.ticket_id = ?";	
			$data = ExecuteGetQuery($sql,"i",[$id]);
			
        	return $data;
    }
	function updateTicketReadStatus($ticketId) {
		$updateField = '';
		if(isset($_SESSION["admin"])) $updateField = "admin_read = '1'";
		else $updateField = "user_read = '1'";
		
		$sql = "UPDATE ".$this->ticketTable." SET $updateField WHERE id = ?";
		ExecuteInsertUpdateQuery($sql,"i",[$ticketId]);
	}
}