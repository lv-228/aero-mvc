<script type="text/javascript" src='js/matchpass.js'></script>
<div id="offcanvas-auth" uk-offcanvas="flip: true; overlay: true">
    <div class="uk-offcanvas-bar">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <h3>Authentication</h3>
        <form class="uk-grid-small" uk-grid action='?user_controller=auth' method="post">
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                    <input class="uk-input" type="text" placeholder="Login" required name='login'>
                </div>
            </div>
            <div class="uk-margin">
                <div class="uk-inline">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: lock"></span>
                    <input class="uk-input" type="password" placeholder="Password" required name='password'>
                </div>
            </div>
        <button class="uk-button uk-button-primary uk-width-1-1 uk-margin-small-bottom" type='submit'>Auth</button>
        </form>
    </div>
</div>

<div id="offcanvas-reg" uk-offcanvas="flip: true; overlay: true">
    <div class="uk-offcanvas-bar">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        <h3>Registration</h3>
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