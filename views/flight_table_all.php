<?php //var_dump($data['flights']);die; ?>
<div class="uk-overflow-auto">
    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th>Way</th>
                <th>Date departure = > arrival</th>
                <th>Plane</th>
                <th>Carrier</th>
                <th>Time to flight (dd hh mm ss)</th>
                <th>Flight</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i = 0; $i < count($data['flights']); $i++): ?>
            <tr>
                <td>Departure: <?= $data['flights'][$i]['IATA1'] ?> => <?= $data['flights'][$i]['IATA2'] ?></td>
                <td><?= $data['flights'][$i]['date_d'] . ' | ' . $data['flights'][$i]['date_a'] ?></td>
                <td>
                    <?= $data['flights'][$i]['plane'] ?>
                </td>
                <td><?= $data['flights'][$i]['carrier'] ?></td>
                <td height="10px">
                    <div class="uk-grid-small uk-child-width-auto" uk-grid uk-countdown="date: <?= $data['flights'][$i]['date_d'] ?>+00:00">
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
                    
                </td>
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
</div>