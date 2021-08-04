<?php

include_once("../session.php");
include_once("../admin/body.php");
include_once("../functions.php");
include_once("../config.php");
include_once("../func-xss.php");
include_once("init.php");



$_SESSION['username_logged'] = "test";
$_SESSION['active'] = "y";
$_SESSION['loggedin'] = 'y';

if ((isset($_SESSION['username_logged'])) && ($_SESSION['active'] == 'y') && ($_SESSION['loggedin'] == 'y')) $username_logged = filter_var($_SESSION['username_logged'], FILTER_SANITIZE_STRING);
else header('Location: ./');


$status=$msg ="";
$p = extractPost('p');
if ($p == "") $p = extractGet('p');

$id = extractGet('id');
if ($id == 0) $id = extractPost('id');



$page = "tickets.php";
switch ($p) {
	case "create-ticket":
		$page = "create_ticket.php";
	break;
	case "ticket-detail":
	case "ticket-reply":
		$page = "ticket_detail.php";
	break;
	default: 
	break;
}
BodyHeader("Tickets", '', '');
include_once("inc/header.php");
?>

<script src="./js/jquery.dataTables.min.js"></script>
<script src="./js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="./css/dataTables.bootstrap.min.css" />
<!-- <script src="./js/ajax.js"></script> -->
<link rel="stylesheet" href="./css/style.css" />

<div class="container">
	<div class="row">
		<?php include_once('menus.php'); ?>
	</div>
	<div class="content">
		<?php include_once "pages/$page"; ?>
	</div>	
</div>
<?php 
BodyFooter();
?>