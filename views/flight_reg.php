<?php
	require_once './classes/view.php';
	$tickets_id = array_column($_SESSION['u_tickets'], 'id');

	if(($_SESSION['role'] != 2 && $_SESSION['role'] != 3) && !in_array($_GET['tid'], $tickets_id))
	{
		echo "Forbidden";die;
	}
	$result = $data['flight_data'];
	if(empty($result))
	{
		echo 'Flight not found!';
		return;
	}
	$owner = isset($_GET['uid']) ? $_GET['uid'] : $_SESSION['uid'];
	$transfers   = $data['transfer_data'];
	$ticket_type = $data['ticket'][0]['type'];
	$doc         = $data['ticket'][0]['document'];
 ?>
<div class="uk-card uk-card-default uk-card-large uk-card-body">
	<div class="uk-card-badge uk-label">Class: <?= $ticket_type === 16 ? 'Buiseness' : 'Econom' ?></div>
	<p>About flight</p>
	<ul class="uk-nav-sub">
        <li>Way: <?= $data['flight_data'][0]['IATA1'] ?> => <?= $data['flight_data'][0]['IATA2'] ?></li>
        <li>Date dep: <?= $data['flight_data'][0]['date_d'] ?></li>
        <li>Date arrival: <?= $data['flight_data'][0]['date_a'] ?></li>
        <li>Airplane: <?= $data['flight_data'][0]['plane'] ?> </li>
        <li>Carrier: <?= $data['flight_data'][0]['carrier'] ?> </li>
    </ul>
    <div class="uk-position-center-right uk-overlay uk-overlay-default">
	<h4>Place</h4>
	<p id='place_info'>Choose a place</p>

	<p id='doc_reg'>You document serial/number</p>
	<form action='index.php?ticket=ticket_reg' id='reg_ticket' method="post" onsubmit='return checkPlace();'>
		<input name='sit' type='hidden' id='sit_number' required="">
		<input name='ticket_id' type='hidden' value='<?= $_GET['tid'] ?>'>
		<input name='transfer' type='hidden' value="<?= $transfers ? 'true' : 'false' ?>">
		<input id='transfer_id_i' name='transfer_id' type="hidden" value='<?= $transfers != false ? $transfers[0]['id'] : ''; ?>'>
		<input id='reg' class="uk-input uk-form-danger uk-form-width-medium" type="hidden" name='reg_doc' placeholder="____ ______" pattern="\d{4}\s\d{6}" maxlength="11" value="">
		<br><br>
		<button id='reg_button' class="uk-button uk-button-primary" type="submit">Registration</button>
	</form>
</div>
</div>
<div id='main' style="min-width: 1200px;">
<?php if($transfers): ?>
<ul uk-tab>
	<?php 
		for($i=0;$i < count($transfers); $i++): 
			$transfer_ticket = $data['flight_obj']->db_query('SELECT * FROM transfer_ticket where transfer_id = ' . $transfers[$i]['id'] . ' AND ticket_id = ' . $_GET['tid']);
	?>
    <li id='<?= $i ?>' sit='<?= $transfer_ticket[0]['sit'] ?>' doc='<?= $doc ? $doc : "no data"; ?>' onclick="setTransferId(<?= $transfers[$i]['id'] ?>, this)" registration='<?= $transfer_ticket[0]['sit'] !== NULL ? 'true' : 'false' ?>'><a href="#"><?= $data['transfer_data'][$i]['IATA1'] .' => '. $data['transfer_data'][$i]['IATA2'] ?></a></li>
	<?php endfor; ?>
</ul>

<ul class="uk-switcher uk-margin">
<?php
	for($i=0;$i < count($transfers); $i++):
	$transfer_ticket = $data['flight_obj']->db_query('SELECT sit FROM transfer_ticket where transfer_id = ' . $transfers[$i]['id'] . ' AND sit is not null');
	$bookTicket = [];
	if($transfer_ticket)
	{
		for($j=0; $j < count($transfer_ticket); $j++)
		{
			$bookTicket[] = $transfer_ticket[$j]['sit'];
		}
	}
?>
    <li style='text-align: center;'>	
    	<div style="position: absolute;">
		<p>Departure place: <?= $data['transfer_data'][$i]['IATA1'] ?></p>
        <p>Arrival place: <?= $data['transfer_data'][$i]['IATA2'] ?></p>
        <p>Date dep: <?= $data['transfer_data'][$i]['date_dep'] ?></p>
        <p>Date arrival: <?= $data['transfer_data'][$i]['date_arrival'] ?></p>
        <p>Airplane: <?= $data['transfer_data'][$i]['plane'] ?> </p>
        <p>Carrier: <?= $data['transfer_data'][$i]['carrier'] ?> </p>
    	</div>	
    	<img src='source/sits.png' usemap="#seatmap_transfer<?= $transfers[$i]['id'] ?>">
    	<map name="seatmap_transfer<?= $transfers[$i]['id'] ?>">
			<?php if($ticket_type == 16) view::printBui($bookTicket); elseif($ticket_type == 15) view::printEco($bookTicket);?>
		</map>
	</li>
	<?php endfor; ?>
</ul>
<?php else: ?>
	<?php
	$flight_ticket = $data['flight_obj']->db_query('SELECT * FROM ticket where flight = ' . $_GET['fid']);
	$bookTicket = [];
	if($flight_ticket)
	{
		for($i=0; $i<count($flight_ticket); $i++)
		{
			$bookTicket[] = $flight_ticket[$i]['sit'];
		}
	}
	$reg = $data['flight_obj']->db_query('SELECT sit, document FROM ticket WHERE owner = ' . $owner . ' AND id = ' . $_GET['tid']);
	?>
	<div id='one_flight' style='text-align: center;' registration='<?= $reg[0]['sit'] != NULL && $reg[0]['document'] != NULL ? 'true' : 'false' ?>' sit='<?= $reg[0]['sit'] ?>' doc='<?= $reg[0]['document'] ?>'>
	<img src='source/sits.png' usemap="#seatmap" >
	<map name="seatmap">
		<?php if($ticket_type == 16) view::printBui($bookTicket); elseif($ticket_type == 15) view::printEco($bookTicket); ?>
	</map>
	</div>
<?php endif; ?>
</div>
<script>
function getSit(e) 
{
	var place_info = document.getElementById('place_info');
	var sit_number = document.getElementById('sit_number');
	place_info.innerHTML = e.getAttribute('sit');
	sit_number.value = e.getAttribute('sit');
}

function checkPlace()
{
	var	sit_number = document.getElementById('sit_number');
	if(sit_number.value == '')
	{
		UIkit.notification({message: 'select a place to book!', status: 'warning'});
		return false;
	}
}

function setTransferId(id, e)
{
	var input = document.getElementById('transfer_id_i');
	input.value = id;
	if(e.getAttribute('registration') == 'true')
	{
		setTicketInfo(e.getAttribute('sit'), e.getAttribute('doc'))
	}
	else if(e.getAttribute('registration') == 'false')
	{
		unabledForm();
	}
}

function setTicketInfo(sit, doc)
{
	var place = document.getElementById('place_info');
	var button = document.getElementById('reg_button');
	var	doc_input = document.getElementById('doc_reg');
	place.innerHTML = sit;
	button.setAttribute('disabled', 'true');
	button.innerHTML = 'Registration succsess';
	doc_input.innerHTML = 'Document ' + doc;
}

function unabledForm()
{
	var place = document.getElementById('place_info');
	var button = document.getElementById('reg_button');
	//var	doc_input = document.getElementById('doc_reg');
	place.innerHTML = 'Choose a place';
	button.removeAttribute('disabled');
	button.innerHTML = 'Registration';
	//doc_input.innerHTML = 'You document serial/number';
}

document.addEventListener("DOMContentLoaded", function(event) {
	if(one_flight = document.getElementById('one_flight'))
	{
		if(one_flight.getAttribute('registration') === 'true')
		{
			setTicketInfo(one_flight.getAttribute('sit'), one_flight.getAttribute('doc'));
		}
		var doc_info = document.getElementById('doc_reg');
		doc_info.innerHTML = 'Document ' + one_flight.getAttribute('doc');
		var input_hidden = document.getElementById('reg');
		input_hidden.setAttribute('value', one_flight.getAttribute('doc'));
	}
	else
	{
			var li = document.getElementById('0');
	if(li && li.getAttribute('registration') == 'true')
	{
		setTicketInfo(li.getAttribute('sit'), li.getAttribute('doc'));
	}
	else if(one_flight_div = document.getElementById('one_flight'))
	{
		if(one_flight_div.getAttribute('registration') == 'true')
		{
			setTicketInfo(one_flight_div.getAttribute('sit'), one_flight_div.getAttribute('doc'))
		}
	}
	var	doc_input = document.getElementById('doc_reg');
	doc_input.innerHTML = 'Document ' + li.getAttribute('doc');
	var input_hidden = document.getElementById('reg');
	input_hidden.setAttribute('value', li.getAttribute('doc'));
}
});
</script>
