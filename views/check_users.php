<?php
	$users = $data['users'];
?>
<br>
<div style="text-align: center;">
	<button class="uk-button uk-button-primary" type="button" uk-toggle="target: #reg-new-user">Create user</button>
</div>
<div class="uk-overflow-auto">
    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider" align="center">
        <thead>
            <tr>
                <th style="text-align: center;">Username</th>
                <th style="text-align: center;">Email</th>
                <th style="text-align: center;">Role</th>
                <th style="text-align: center;">Profile</th>
            </tr>
        </thead>
        <tbody>
        	<?php for($i=0;$i<count($users);$i++): ?>
            <tr>
                <td style="text-align: center;"><?= $users[$i]['first_name'] . ' ' . $users[$i]['second_name'] . ' ' . $users[$i]['last_name'] ?></td>
                <td style="text-align: center;"><?= $users[$i]['email'] ?></td>
                <td style="text-align: center;">
                	<form method="post" action="index.php?user_controller=change_role">    
        				<input class="uk-input uk-form-width-xsmall" type="text" placeholder="<?= $users[$i]['role'] ?>" name='role'>
        				<input type="hidden" name="uid" value='<?= $users[$i]['id'] ?>'>
    					<button class="uk-button uk-button-link" type="submit">Change</button>
					</form>
				</td>
                <td style="text-align: center;"><a href='index.php?page_controller=userpage&uid=<?= $users[$i]['id'] ?>'>See profile</a></td>
            </tr>
        	<?php endfor; ?>
        </tbody>
    </table>
</div>

<div id="reg-new-user" uk-offcanvas="flip: true; overlay: true">
    <div class="uk-offcanvas-bar">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <h3>Create User</h3>
                <form class="uk-grid-small" uk-grid action="index.php?user_controller=registration" method="post" name="reg" required>
            <div class="uk-width-1-1">
                <input class="uk-input" type="text" placeholder="Login" name='login' required>
            </div>
            <div class="uk-width-1-1">
                <input class="uk-input" type="email" placeholder="E-mail" name='email' required>
            </div>
            <div class="uk-width-1-1">
                <input class="uk-input" type="text" placeholder="First name" name='first_name' required>
            </div>
            <div class="uk-width-1-1">
                <input class="uk-input" type="text" placeholder="Second name" name='second_name' required>
            </div>
            <div class="uk-width-1-1">
                <input class="uk-input" type="text" placeholder="Last name" name='last_name' required>
            </div>
            <div class="uk-width-1-1">
                <input id='pas1' class="uk-input" type="password" placeholder="Password" name='pas' required>
            </div>
            <div class="uk-width-1-1">
                <input id='pas2' class="uk-input" type="password" placeholder="Repeat password" name='reppass' required>
            </div>
            <div class="uk-width-1-1">
                <input class="uk-input" type="number" placeholder="Age" name='age' required maxlength="3">
            </div>
            <div class="uk-width-1-1">
                <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                    <label><input id='male' class="uk-radio" type="radio" name="sex" checked required value='1'> Male</label>
                    <label><input id='female' class="uk-radio" type="radio" name="sex" required value="0"> Female</label>
                </div>
            </div>
<!--             <div class="uk-width-1-1">
                <input class="uk-input" type="text" placeholder="Document data" name='reg[doc]' required maxlength="20">
            </div> -->
            <button class="uk-button uk-button-primary uk-width-1-1" type='submit'>Registration</button>
        </form>
    </div>
</div>