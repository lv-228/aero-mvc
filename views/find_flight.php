<form style="text-align: center;" method="get" action="index.php?page_controller=find_flight">
    <input type="hidden" name="page_controller" value="users_registration_tables">
    <fieldset class="uk-fieldset">
        <legend class="uk-legend">Find flight or transfer</legend>

        <div class="uk-margin">
            <input class="uk-input uk-form-width-large" type="text" placeholder="ID: <?= isset($_GET['fid']) ? $_GET['fid'] : 0; ?>" name='fid' required>
        </div>

        <div class="uk-margin">
            <select class="uk-select uk-form-width-large" name='type'>
                <option <?= isset($_GET['type']) && $_GET['type'] == 'Flight' ? 'selected' : '' ; ?> name='flight'>Flight</option>
                <option <?= isset($_GET['type']) && $_GET['type'] == 'Transfer' ? 'selected' : '' ; ?> name='transfer'>Transfer</option>
            </select>
        </div>

        <button class="uk-button uk-button-primary" type="submit">
        	Find
        </button>
    </fieldset>
</form>
<br>

<?php
if(isset($data['user_data'])):
if(isset($data['transfers']) && $data['transfers'] !== false): ?>
<p>Transfers: </p>
<?php for($q=0; $q<count($data['transfers']); $q++): ?>
<a href='index.php?page_controller=users_registration_tables&type=Transfer&fid=<?= $data['transfers'][$q]['id'] ?>'> <?= $data['transfers'][$q]['id'] ?> </a>
<?php endfor; ?>
<?php endif; ?>
<div class="uk-card uk-card-default uk-card-large uk-card-body">
	<div class="uk-card-badge uk-label"><?= isset($_GET['type']) && $_GET['type'] == 'Flight' ? 'flight' : 'transfer'; ?></div>
	<p>About <?= isset($_GET['type']) && $_GET['type'] == 'Flight' ? 'flight' : 'transfer'; ?></p>
	<ul class="uk-nav-sub">
        <li>Departure place: <?= $dep_place = $data['flight_data'][0]['IATA1']; ?></li>
        <li>Arrival place: <?= $arrival_place = $data['flight_data'][0]['IATA2']; ?></li>
        <li>Date dep: <?= $data['flight_data'][0]['date_d']; ?></li>
        <li>Date arrival: <?= $data['flight_data'][0]['date_a']; ?></li>
        <li>Airplane: <?= $data['flight_data'][0]['plane']; ?> </li>
        <li>Carrier: <?= $data['flight_data'][0]['carrier'] ?> </li>
    </ul>
</div>
<div class="uk-child-width-1-2@s" style="text-align: center;">
<table class="uk-table uk-table-middle uk-table-divider" align="center">
    <thead>
        <tr>
            <th style="text-align: center;">Sit</th>
            <th style="text-align: center;">Passanger Name</th>
            <th style="text-align: center;">Document</th>
            <th style="text-align: center;">Registration</th>
            <!-- <th style="text-align: center;">Profile</th> -->
        </tr>
    </thead>
    <tbody>
    	<?php foreach ($data['user_data'] as $key => $value): ?>
        <tr>
            <td><?= $value['sit'] == 'Not reg' ? 'See transfer flight' : $value['sit'] ?></td>
            <td><?= $value['full_name'] ?></td>
            <td><?= $value['doc'] ?></td>
            <td align="center"><a href="index.php?page_controller=flight_reg&fid=<?= $value['flight'] ?>&tid=<?= $value['ticket_id'] ?>&uid=<?= $value['id'] ?>">Registration ticket</a></td>
            <!-- <td class="uk-table-shrink"><a class="uk-button uk-button-default" type="button" href='index.php?userpage&uid=<?= $value['id'] ?>'>User profile</a></td> -->
        </tr>
    	<?php endforeach; ?>
<!--         <?php if(isset($user_not_reg) && $user_not_reg): ?>
        <?php foreach ($user_not_reg as $key => $value): ?>
        <tr>
            <td><?= isset($transfers) && $transfers ? 'See transfer flight' : $key ?></td>
            <td><?= $value['full_name'] ?></td>
            <td><?= $value['doc'] ?></td>
            <td align="center"><a href="index.php?flight_id=<?= $value['flight'] ?>&ticket_id=<?= $value['ticket_id'] ?>">Registration ticket</a></td>
            <td class="uk-table-shrink"><a class="uk-button uk-button-default" type="button" href='index.php?userpage&uid=<?= $value['id'] ?>'>User profile</a></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?> -->
    </tbody>
</table>
</div>
<?php endif; ?>
