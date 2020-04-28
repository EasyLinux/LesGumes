//import * as user from './modules/user.js';
import * as mContrat from './modules/contrat.js';

window.contrat = function(action, params){
   mContrat.doAction(action, params);
}

