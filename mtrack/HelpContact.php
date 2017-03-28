<?php
$page = "HelpContact";
include "Header.php";

if(isset($_POST['task'])) { $task = $_POST['task']; } elseif(isset($_GET['task'])) { $task = $_GET['task']; } else { $task = "main"; }


$result = "";
$is_error = 0;
$error_message = "";
$success = 0;

// set default emails
if(!isset($_POST['contact_email'])) { $contact_email = $user->user_info[user_email]; } else { $contact_email = $_POST['contact_email']; }

// send help message
if($task == "dosend") {
  $contact_name = $_POST['contact_name'];
  $contact_subject = $_POST['contact_subject'];
  $contact_message = $_POST['contact_message'];


  if(!is_email_address($contact_email)) {
    $is_error = 1;
    $error_message = $help_contact[18];
  }
  if(str_replace(" ", "", $contact_message) == "") {
    $is_error = 1;
    $error_message = $help_contact[19];
  }
  if(str_replace(" ", "", $contact_name) == "") {
    $is_error = 1;
    $error_message = $help_contact[20];
  }

  // send message to superadmin
  if($is_error == 0) {
    $recepient_query = $database->database_query("SELECT admin_email, admin_name FROM phps_admins ORDER BY admin_id LIMIT 1");
    $recepient_info = $database->database_fetch_assoc($recepient_query);

    // compose subject
    $subject = "$help_contact[1] $contact_subject";

    // compose message
    $message = "$help_contact[2] $recepient_info[admin_name],\n\n$help_contact[3]\n\n$help_contact[4] $contact_email\n$help_contact[5] $contact_name\n$help_contact[6] $contact_subject\n\n$contact_message";

    // send mail
    send_helpcontact($recepient_info[admin_email], $contact_email, $subject, $message);

    // set result
    $result = $help_contact[7];
    $success = 1;
  }

}

$smarty->assign('page', $page);
$smarty->assign('result', $result);
$smarty->assign('is_error', $is_error);
$smarty->assign('success', $success);
$smarty->assign('error_message', $error_message);
$smarty->assign('contact_name', $contact_name);
$smarty->assign('contact_email', $contact_email);
$smarty->assign('contact_subject', $contact_subject);
$smarty->assign('contact_message', $contact_message);
include "Footer.php";
?>