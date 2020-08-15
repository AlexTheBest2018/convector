jQuery(document).ready(function () {
  let form = document.getElementById('currency_form');
  form.addEventListener('submit', function(e){
    e.preventDefault();
    let reqBody = {};
    Object.keys(form.elements).forEach(key => {
      let element = form.elements[key];
      if (element.type !== "submit") {
        reqBody[element.name] = element.value;
      }
    });
    jQuery.get(myPlugin.ajaxurl, {action: 'convector', amount: reqBody.amount, select_currency_from: reqBody.select_currency_from, select_currency_to: reqBody.select_currency_to}, function (res) {
        document.querySelector('.result').value = res;
      jQuery.get(myPlugin.ajaxurl, {action: 'get_exchanges'}, function (res) {
        jQuery('.table_exchange').html(res);
      });
    });
  });
});

