export function formatNumber()
{
  var sPrix = $('#amount').val();

  if( sPrix.indexOf(',') > 0){
    sPrix = sPrix.replace(',','.');
  }
  var dPrix = parseFloat(sPrix).toFixed(2);
  sPrix = $('#amount').val(dPrix);
}
