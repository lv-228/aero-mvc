<?php if(!isset($_SESSION['login'])): ?>
<div class="uk-navbar-right">
    <ul class="uk-navbar-nav">
        <li class="uk-active">
            <div class="uk-margin-small">
                <div class="uk-button-group">
                    <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #offcanvas-auth">Sig in</button>
                    <button class="uk-button uk-button-primary" type="button" uk-toggle="target: #offcanvas-reg">Registration</button>
                </div>
            </div>
        </li>
    </ul>
</div>
<?php else: ?>
<div class="uk-navbar-right">
    <ul class="uk-navbar-nav">
<!--         <li class="uk-active">
            <div class="uk-margin-small">
                <div class="uk-button-group">
                     <form action="serverscripts/service_logic.php" method="post">
                        <input type="hidden" name="out" value="true">
                        <button class="uk-button uk-button-primary" type="submit">Log out [ <?= $_SESSION['login'] ?> ]</button>
                    </form>
                    <form>
                        <a href='index.php?userpage' class="uk-button uk-button-primary" type="submit">My page</a>
                    </form>
                    <form>
                        <a href='index.php?flighttable' class="uk-button uk-button-primary" type="submit">Flight table</a>
                    </form>
                    <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 3): ?>
                        <form action='index.php' method='get'>
                            <input type="hidden" name="adminpanel" value="true">
                            <button class="uk-button uk-button-primary" type="submit">Admin panel</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </li> -->
        <li >
            <a href="#"><span class="uk-margin-small-right" uk-icon="triangle-down"></span><?= $_SESSION['login'] ?></a>
            <div class="uk-navbar-dropdown">
                <ul class="uk-nav uk-navbar-dropdown-nav">
                    <li>
                        <div>
                            <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                                <li class="uk-active"><a href="#">Active</a></li>
                                <li class="uk-parent">
                                    <a href="#"><span class="uk-margin-small-right" uk-icon="home"></span>User</a>
                                    <ul class="uk-nav-sub">
                                        <li><a href='index.php?user_controller=profile&uid=' <?= $_SESSION['uid'] ?> >Profile</a></li>
                                    </ul>
                                </li>
                                <li class="uk-parent">
                                    <a href="#"><span class="uk-margin-small-right" uk-icon="expand"></span>Flight</a>
                                    <ul class="uk-nav-sub">
                                        <li><a href='index.php?page_controller=flight_table'>Flight table</a></li>
                                    </ul>
                                </li>
                                <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 3): ?>
                                <li class="uk-parent">
                                    <a href="#"><span class="uk-margin-small-right" uk-icon="user"></span>Admin</a>
                                    <ul class="uk-nav-sub">
                                        <li><a href='index.php?page_controller=admin_panel'>Admin panel</a></li>
                                    </ul>
                                </li>
                                <?php endif; ?>
                                <?php if(isset($_SESSION['role']) && ($_SESSION['role'] == 2 || $_SESSION['role'] == 3)): ?>
                                <li class="uk-parent">
                                    <a href="#"><span class="uk-margin-small-right" uk-icon="users"></span>Moderator</a>
                                    <ul class="uk-nav-sub">
                                        <li><a href='index.php?page_controller=users_registration_tables'>Users flights</a></li>
                                    </ul>
                                </li>
                                <?php endif; ?>
<!--                                 <li class="uk-nav-header">Header</li>
                                <li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: table"></span> Item</a></li>
                                <li><a href="#"><span class="uk-margin-small-right" uk-icon="icon: thumbnails"></span> Item</a></li> -->
                                <li class="uk-nav-divider"></li>
                                <li>
                                <form action="?user_controller=un_auth" method="post">
                                    <input type="hidden" name="out" value="true">
                                    <button class="uk-button uk-button-primary" type="submit">Log out</button>
                                </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                            <!-- <li class="uk-nav-header">Profile</li>
                            <li>
                                    <a href='index.php?userpage' type="submit">My page</a>
                            </li>
                            <li class="uk-nav-header">Flights</li>
                            <li>
                                <a href='index.php?flighttable' type="submit">Flight table</a>
                            </li>
                            <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 3): ?>
                            <li class="uk-nav-header">User role: Admin</li>
                            <li>
                                    <a href='index.php?adminpanel' type="submit">Page</a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($_SESSION['user_type']) && ($_SESSION['user_type'] === 2 || $_SESSION['user_type'] === 3)): ?>
                            <li class="uk-nav-header">User role: Moderator</li>
                            <li>
                                <form>
                                    <a href='index.php?users_registration&id=0&type=Flight'>Users registrations</a>
                                </form>
                            </li>
                            <?php endif; ?>
                            <li class="uk-nav-divider"></li>
                            <li>
                                <form action="serverscripts/service_logic.php" method="post">
                                    <input type="hidden" name="out" value="true">
                                    <button class="uk-button uk-button-primary" type="submit">Log out</button>
                                </form>
                            </li> -->
                        </ul>
                    </div>
            </li>
    </ul>
</div>
<?php endif; ?>