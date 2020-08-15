<form id="currency_form">
    <div class="currency_form">
        <input type="text" name="amount" class="amount mr-2" value="">
        <select name="select_currency_from mr-2" id=""><?= $select_currency ?></select>
        <div class="to mr-2">в</div>
        <select name="select_currency_to" class="mr-2" id=""><?= $select_currency ?></select>
        <button type="submit" class="submit">go</button>
    </div>
    <input type="text" readonly class="result" value="">

</form>
<div class="table_exchange"><?php convector_table_last_exchanges(); ?></div>
