<?php
    //var_dump($transfers);die;
?>
<?php if(empty($data['transfer_data'])): ?>
    <h1>Is that a direct flight!</h1>
<?php return; endif; ?>

<div class="uk-overflow-auto">
    <table class="uk-table uk-table-hover uk-table-middle uk-table-divider">
        <thead>
            <tr>
                <th>Way</th>
                <th>Date departure = > arrival</th>
                <th>Plane</th>
                <th>Carrier</th>
                <th>Time to flight (dd hh mm ss)</th>
            </tr>
        </thead>
        <tbody>
            <?php for($i = 0; $i < count($data['transfer_data']); $i++): ?>
            <tr>
                <td>Departure: <?= $data['transfer_data'][$i]['IATA1'] ?> => <?= $data['transfer_data'][$i]['IATA2'] ?></td>
                <td><?= $data['transfer_data'][$i]['date_dep'] . ' | ' . $data['transfer_data'][$i]['date_arrival'] ?></td>
                <td>
                    <?= $data['transfer_data'][$i]['plane'] ?>
                </td>
                <td><?= $data['transfer_data'][$i]['carrier'] ?></td>
                <td height="10px">
                    <div class="uk-grid-small uk-child-width-auto" uk-grid uk-countdown="date: <?= $data['transfer_data'][$i]['date_dep'] ?>+00:00">
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
            </tr>
            <?php endfor;?>
        </tbody>
    </table>
</div>