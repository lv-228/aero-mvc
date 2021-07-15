<?php

$data = json_decode(file_get_contents('php://input'), true);

//$liform = '<li><div class="uk-margin"><label class="uk-form-label">Document</label><input placeholder="____ _______" form="ticket_form" id="doc_reg" class="uk-input uk-form-blank uk-form-width-small" type="text" name="regdoc" maxlength="11" required=""></div><div class="uk-margin"><label class="uk-form-label">First name</label><input form="ticket_form" class="uk-input uk-form-blank uk-form-width-large" type="text" name="fname" placeholder="__________" required=""></div><div class="uk-margin"><label class="uk-form-label">Second name</label><input form="ticket_form" class="uk-input uk-form-blank uk-form-width-large" type="text" name="sname" placeholder="__________" required=""></div><div class="uk-margin"><label class="uk-form-label">Last name</label><input form="ticket_form" class="uk-input uk-form-blank uk-form-width-large" type="text" name="lname" placeholder="__________" required=""></div></li>';

$liswitcher = ['<li><a href="#">', '</a></li>'];
$answer = ['liform' => '', 'liswitcher' => ''];
for($i = 0; $i < $data['cnt']; $i++)
{
	$answer['liform'] .= '<li><div class="uk-margin"><label class="uk-form-label">Document</label><input placeholder="____ _______" form="ticket_form' . $data["flight_id"] . '" id="doc_reg" class="uk-input uk-form-blank uk-form-width-small" type="text" name=\'' . 'tickets[' . $i . ']' . '[regdoc]' . '\' maxlength="11" required=""></div><div class="uk-margin"><label class="uk-form-label">First name</label><input name=\'' . 'tickets[' . $i . ']' . '[fname]' . '\' form="ticket_form' . $data["flight_id"] . '" class="uk-input uk-form-blank uk-form-width-large" type="text" placeholder="__________" required=""></div><div class="uk-margin"><label class="uk-form-label">Second name</label><input form="ticket_form' . $data["flight_id"] . '" class="uk-input uk-form-blank uk-form-width-large" type="text" name=\'' . 'tickets[' . $i . ']' . '[sname]' . '\' placeholder="__________" required=""></div><div class="uk-margin"><label class="uk-form-label">Last name</label><input form="ticket_form' . $data["flight_id"] . '" class="uk-input uk-form-blank uk-form-width-large" type="text" name=\'' . 'tickets[' . $i . ']' . '[lname]' . '\' placeholder="__________" required=""></div></li>';
	$answer['liswitcher'] .= $liswitcher[0] . 'Ticket ' . ($i + 1) . $liswitcher[1];
}

echo stripslashes(json_encode($answer, JSON_HEX_QUOT));