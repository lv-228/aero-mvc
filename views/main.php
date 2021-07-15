<?php 
require_once './classes/view.php';
require_once 'modalAuth.php';

    if(isset($_SESSION['message']))
    {
        view::send_message(isset($_SESSION['message'][0]) ? $_SESSION['message'][0] : $_SESSION['message']['msg'], isset($_SESSION['message'][0]) ? $_SESSION['message'][1] : $_SESSION['message']['type']);
        unset($_SESSION['message']);
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Title</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/uikit.min.css" />
        <!-- UIkit CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.3.3/dist/css/uikit.min.css" />
        <link rel="shortcut icon" href="/source/airplaneicon.png" type="image/png">
        <script src="js/uikit.min.js"></script>
        <script src="js/uikit-icons.min.js"></script>
	<script type="text/javascript" src='js/my.js'></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit@3.3.3/dist/js/uikit.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit@3.3.3/dist/js/uikit-icons.min.js"></script>
	<?php
		if(isset($_SESSION['message']))
		{
			view::send_message(isset($_SESSION['message'][0]) ? $_SESSION['message'][0] : $_SESSION['message']['msg'], isset($_SESSION['message'][0]) ? $_SESSION['message'][1] : $_SESSION['message']['type']);
			unset($_SESSION['message']);
		}
	?>
    </head>
    <body>
        <nav class="uk-navbar-container" uk-navbar>
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <li class="uk-active"><a href="index.php" uk-icon="search" >Find ticket</a></li>
                </ul>
            </div>
            <?php require_once 'templates/authnav.php'; ?>
        </nav>
            <?php require_once $data['page']; ?>
<footer></footer>
    </body>
</html>
