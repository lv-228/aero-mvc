<form style="text-align: center;" method="get" action="index.php">
    <input type="hidden" name="page_controller" value="users_registration_tables">
    <fieldset class="uk-fieldset">
        <legend class="uk-legend">Find user tickets</legend>

        <div class="uk-margin">
            <input class="uk-input uk-form-width-large" type="text" placeholder="SPECIAL_ID: <?= isset($_GET['special_id']) ? $_GET['special_id'] : 0; ?>" name='special_id' required>
        </div>

        <button class="uk-button uk-button-primary" type="submit">
        	Find
        </button>
    </fieldset>
</form>
<?php if(isset($data['tickets']) && $data['tickets']): ?>
<table class="uk-table uk-table-middle uk-table-divider" align="center">
    <thead>
        <tr>
            <th style="text-align: center;">Type</th>
            <th style="text-align: center;">Flight</th>
            <!-- <th style="text-align: center;">Sit</th> -->
            <th style="text-align: center;">Document</th>
            <th style="text-align: center;">Full name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['tickets'] as $key => $value): ?>
        <tr>
            <td align="center"><?= $value['type'] === 15 ? 'Econom' : 'Business' ?></td>
            <td align="center"><?= $value['flight'] ?></td>
            <!-- <td align="center"><?= $value[5] === NULL ? 'Not regist.' : $value[5] ?></td> -->
            <td align="center"><?= $value['document'] ?></td>
            <td align="center"><?= $value['pas_full_name'] ?></td>
            <td align="center"><a href='index.php?page_controller=flight_reg&fid=<?= $value['flight'] ?>&tid=<?= $value['id'] ?>&uid=<?= $value['owner'] ?>'>Registration ticket</a></td>
            <!-- <td class="uk-table-shrink"><a class="uk-button uk-button-default" type="button" href='index.php?userpage&uid=<?= $value[1] ?>'>User profile</a></td> -->
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>