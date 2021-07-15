<?php
    if(empty($data['flights']))
    {
        echo '<h3 class="uk-margin-left">You have no flights</h3>';
        return;
    }
?>

<div class="uk-overflow-auto">
    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th>Way</th>
                <th>Date departure = > arrival</th>
                <th>Plane</th>
                <th>Carrier</th>
                <th>Time to flight (dd hh mm ss)</th>
                <th>Tickets</th>
                <th>Transfers</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i = 0; $i < count($data['user_flights']); $i++): ?>
            <tr>
                <td>Departure: <?= $data['user_flights'][$i]['IATA1'] ?> => <?= $data['user_flights'][$i]['IATA2'] ?></td>
                <td><?= $data['user_flights'][$i]['date_d'] . ' | ' . $data['user_flights'][$i]['date_a'] ?></td>
                <td>
                    <?= $data['user_flights'][$i]['plane'] ?>
                </td>
                <td><?= $data['user_flights'][$i]['carrier'] ?></td>
                <td height="10px">
                    <div class="uk-grid-small uk-child-width-auto" uk-grid uk-countdown="date: <?= $data['user_flights'][$i]['date_d'] ?>+00:00">
                        <div>
                            <div class="uk-countdown-number uk-countdown-days"></div>
                            <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Days</div>
                        </div>
                        <div class="uk-countdown-separator">:</div>
                        <div>
                            <div class="uk-countdown-number uk-countdown-hours"></div>
                            <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Hours</div>
                        </div>
                        <div class="uk-countdown-separator">:</div>
                        <div>
                            <div class="uk-countdown-number uk-countdown-minutes"></div>
                            <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Minutes</div>
                        </div>
                        <div class="uk-countdown-separator">:</div>
                        <div>
                            <div class="uk-countdown-number uk-countdown-seconds"></div>
                            <div class="uk-countdown-label uk-margin-small uk-text-center uk-visible@s">Seconds</div>
                        </div>
                    </div>
                </td>
                <td>
                <ul uk-accordion="multiple: true">
    				<li class="uk-open">
        				<a class="uk-accordion-title" href="#">Tickets</a>
        				<div class="uk-accordion-content">
            				<?php 
                    			for($j=0; $j<count($data['user_flights'][$i]['tickets']); $j++)
                    			{
                    				echo '<a href=index.php?page_controller=flight_reg&fid=' . $data['user_flights'][$i]['id'] . '&tid='. $data['user_flights'][$i]['tickets'][$j] .'>' . $data['user_flights'][$i]['tickets'][$j] . '</a> ';
                    			}
                     		?>
        				</div>
    				</li>
				</ul>
                </td>
                <td>
                	<a class="uk-button uk-button-primary" href='index.php?page_controller=flight_transfers&fid=<?= $data['user_flights'][$i]['id'] ?>'>See transfers</a>
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
</div>