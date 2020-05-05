//import * as user from './modules/user.js';
import * as mContrat from './modules/contrat.js';
import * as helpers from './modules/helper.js';
import * as user from './modules/user.js';

window.contrat = function(action, params){
   mContrat.doAction(action, params);
}

window.formatNumber = function(){
  helpers.formatNumber();
}

window.user = function(action, params){
  user.user(action, params);
}