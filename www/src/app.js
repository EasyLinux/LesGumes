import * as mContrat from './modules/contrat.js';
import * as helpers from  './modules/helper.js';
import * as user from     './modules/user.js';
import * as backup from   './modules/backup.js';

window.contrat = function(action, params){
   mContrat.doAction(action, params);
}

window.formatNumber = function(){
  helpers.formatNumber();
}

window.user = function(action, params){
  user.user(action, params);
}

window.getLink= function()
{
  return prompt("Le lien");
}

// window.alertBox = function()
// {
//   user.alertBox();
// }

window.backup = function(action, params)
{
  backup.switcher(action, params);
}