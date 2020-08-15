<h2>Recently converted</h2>
<ul class="exchanges_list">
    <?php
    foreach ( $result as $print )   { ?>
        <li class="exchanges_list-item">
            <div class="exchanges_list-item--block"><?php echo $print->convector_amount; ?> </div>
            <div class="exchanges_list-item--block"> <?php echo $print->convector_currency_from ; ?> </div>
            <div class="exchanges_list-item--block">to</div>
            <div class="exchanges_list-item--block"> <?php echo $print->convector_currency_to; ?> </div>
            <div class="exchanges_list-item--date"><?php echo $print->created_at; ?> </div>
        </li>
    <?php }
    ?>
</ul>
