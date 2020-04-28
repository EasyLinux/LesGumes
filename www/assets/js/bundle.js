/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./www/src/app.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./www/src/app.js":
/*!************************!*\
  !*** ./www/src/app.js ***!
  \************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _modules_contrat_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modules/contrat.js */ "./www/src/modules/contrat.js");
//import * as user from './modules/user.js';


window.contrat = function(action, params){
   _modules_contrat_js__WEBPACK_IMPORTED_MODULE_0__["doAction"](action, params);
}



/***/ }),

/***/ "./www/src/modules/contrat.js":
/*!************************************!*\
  !*** ./www/src/modules/contrat.js ***!
  \************************************/
/*! exports provided: doAction */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "doAction", function() { return doAction; });
/* harmony import */ var _modal_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./modal.js */ "./www/src/modules/modal.js");

/**
 * Gestion des contrats
 */

/**
 * doAction 
 * 
 * Aiguillage des demandes liées au contrat
 * @param {string} action   Action demandée
 * @param {objet} params    Paramètres requis
 */
function doAction(action, params)
{
  switch(action)
  {
    case 'init':
      initFormContact(0);
      break;

    case 'edit':
      // Retrouver id
      var id=params;
      initFormContact(id);
      break;
    
    case 'chooseProducer':
      chooseUser('Producteur',"Choisir un producteur");
      break;

    case 'chooseReferent':
      chooseUser('Referent',"Choisir un référent");
      break;
      
    case 'chooseSuppleant':
      chooseUser('Suppleant',"Choisir un suppléant");
      break;
      
    case 'okProducteur':
      $('#producer').val($('#user-choose :selected').text());
      var id = $('#user-choose :selected').val();
      document.getElementById('producer').dataset.id=id;
      $('#choose-user').modal('hide');
      break;

    case 'okReferent':
      $('#referent').val($('#user-choose :selected').text());
      var id = $('#user-choose :selected').val();
      document.getElementById('referent').dataset.id=id;
      $('#choose-user').modal('hide');
      break;

    case 'okSuppleant':
      $('#replace').val($('#user-choose :selected').text());
      var id = $('#user-choose :selected').val();
      document.getElementById('replace').dataset.id=id;
      $('#choose-user').modal('hide');
      break;
  
    case 'save':
      saveContrat();
      break;

    case 'doc':
      editDoc();
      break;
  
    default:
      console.log("Dans contrat - doAction: "+action);
      break;  
  }
}

/**
 * 
 * @param {int} id    contrat à modifier, 0 si nouveau
 */
function initFormContact(id)
{
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["createModal"]("id-contrat", "contrat-edit", "Gestion de contrat","modal-xl");
  // Ajout du contenu contrat-edit-content
  data ={
    Action: "Content",
    Content: "ContratEdit"
  };
  $.post("/ajax/index.php", data, function (data) {
    $('#contrat-edit-content').html(data);
    document.getElementById('contractName').dataset.id = id;
  });
  var Btns=[{
    Label:    "Enregistrer",
    Func:     "contrat",
    Action:   "save",
    Glyph:    "save",
    type:     "primary"
  },{
    Label:    "Nouvelle saison",
    Func:     "contrat",
    Action:   "new",
    Glyph:    "repeat",
    type:     "primary"
  },{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  if( id != 0){
    // get data
    data={
      Action: "contrat",
      Want:   "getInfo",
      Vars:   id
    }
    $.post("/ajax/index.php", data, function (resp) {
      console.log(resp);
      $('#contractName').val(resp.Data[0].Name);
      document.getElementById('contractName').dataset.id=resp.Data[0].id;
      $('#contratType option[value="'+resp.Data[0].idContratType+'"]').prop('selected', true);
      $('#producer').val(resp.Data[0].Producteur);
      document.getElementById('producer').dataset.id=resp.Data[0].IdProducteur;
      $('#referent').val(resp.Data[0].Referent);
      document.getElementById('referent').dataset.id=resp.Data[0].IdReferent;
      $('#replace').val(resp.Data[0].Suppleant);
      document.getElementById('replace').dataset.id=resp.Data[0].IdSupleant;
      $('#currentSeason').val(resp.Data[0].EnCours);
      if( resp.Data[0].Verouille == 1){
        $('#locked').prop("checked",true);
      } else {
        $('#locked').prop("checked",false);
      }
      var Debut = resp.Data[0].DebutContrat;
      var debContrat = Debut.substr(8,2)+"/"+Debut.substr(5,2);
      $('#contract-start').val(debContrat);
      var Fin = resp.Data[0].FinContrat;
      var finContrat = Fin.substr(8,2)+"/"+Fin.substr(5,2);
      $('#contract-end').val(finContrat);
      $('#nbPeople').val(resp.Data[0].nbPermanence);
      $('#amount').val(resp.Data[0].PrixContrat);
    });
  }
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("contrat-edit",Btns);
  $('#contrat-edit').modal('show');
}

function chooseUser(type, title)
{
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["createModal"]("contrat-edit", "choose-user", title,"modal-sm");
  var Btns=[{
    Label:    "Choisir",
    Func:     "contrat",
    Action:   "ok"+type,
    Glyph:    "ok",
    type:     "primary"
  },{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("choose-user",Btns);
  var sel = document.createElement('select');
  sel.id="user-choose";
  sel.className="form-control";
  sel.style.height="200px";
  sel.style.minHeight="200px";
  sel.multiple=true;
  var div = document.getElementById('choose-user-content');
  div.appendChild(sel);
  var i =0;
  data={
    Action:  "contrat",
    Want:    "get"+type
  };
  $.post("/ajax/index.php", data, function (resp) {
    resp.forEach(function(user){
      var option = document.createElement('option');
      option.text = user.Raisoc;
      option.value = user.id;
      sel.add(option);

    });
  });

  $('#choose-user').modal('show');
}

function saveContrat()
{
  var formValid = true;
  // Valider si on a bien les infos
  if( $('#contractName').val() == ""){
    formValid = false;
  }
  var sPost='';
  sPost += '{"id":"'+document.getElementById("contractName").dataset.id+'",';
  sPost += '"Name":"'+document.getElementById("contractName").value+'",';
  sPost += '"Type":"'+$("#contratType :selected").val()+'",';
  var idProducer = document.getElementById("producer").dataset.id;
  if( idProducer == '0'){
    formValid = false;
  }
  sPost += '"idProducteur":"'+idProducer+'",';
  var idReferent = document.getElementById("referent").dataset.id;
  if( idReferent == '0'){
    formValid = false;
  }
  var idSuppleant = document.getElementById("replace").dataset.id;
  sPost += '"idReferent":"'+idReferent+'",';
  sPost += '"idSuppleant":"'+idSuppleant+'",';
  sPost += '"curSeason":"'+$("#currentSeason").val()+'",';
  if($("#locked").is(":checked")){
    sPost += '"locked":"true",';
  } else {
    sPost += '"locked":"false",';
  }
  sPost += '"Start":"'+$("#contract-start").val() +'",';
  sPost += '"End":"'+$("#contract-end").val() +'",';
  sPost += '"nbPeople":"'+$("#nbPeople").val()+'",';
  sPost += '"price":"'+$("#amount").val()+'"}';
  if( formValid ){
    console.log(sPost);
    var data={
      Action: "contrat",
      Want:   "save",
      Vars:   sPost
    }
    $.post('/ajax/index.php',data,function(resp){
      if(resp.Errno != 0){
        alert(resp.ErrMsg);
        return false;
      }
      $('#contrat-edit').modal('hide');
    });
  }
}


function editDoc()
{
  console.log('Dans editDoc');
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["createModal"]("contrat-edit", "edit-doc", "Contrat papier","modal-sm");
  var Btns=[{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("edit-doc",Btns);
  var p = document.createElement('p');
  p.innerHTML="Document actuel: contrat.pdf<br/>Anciens contrats";
  document.getElementById('edit-doc-content').appendChild(p);
  var p = document.createElement('p');
  p.innerHTML="Nouveau document: ";
  document.getElementById('edit-doc-content').appendChild(p);
  var input = document.createElement('input');
  input.type="file";
  input.id="load-doc";
  p.appendChild(input);

  $('#edit-doc').modal('show');
}

/***/ }),

/***/ "./www/src/modules/modal.js":
/*!**********************************!*\
  !*** ./www/src/modules/modal.js ***!
  \**********************************/
/*! exports provided: createModal, addButtons */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "createModal", function() { return createModal; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addButtons", function() { return addButtons; });
/**
 * 
 * @param {string}   parent 
 * @param {string}   id 
 * @param {string}   titre 
 * @param {string}   size     classe pour la taille de la boite modale <modal-xl||>
 */
function createModal(parent, id, titre, size) {
  var Div = document.createElement('div');
  Div.className = "modal fade";
  Div.id = id;
  Div.setAttribute("role", "dialog");
  Div.setAttribute("aria-labelledby", id);
  Div.setAttribute("aria-hidden", "true");
  document.body.appendChild(Div);
  var Div2 = document.createElement('div');
  Div2.className = "modal-dialog "+size;
  Div2.setAttribute("role", "document");
  Div.appendChild(Div2);
  var Div3 = document.createElement('div');
  Div3.className = "modal-content";
  Div2.appendChild(Div3);
  var Div4 = document.createElement('div');
  Div4.className = "modal-header";
  Div3.appendChild(Div4);
  var Header = document.createElement('h5');
  Header.className = "modal-title";
  Header.innerText = titre;
  Div4.appendChild(Header);
  var Btn = document.createElement('button');
  Btn.setAttribute("type", "button");
  Btn.setAttribute("data-dismiss", "modal");
  Btn.className = "close";
  Div4.appendChild(Btn);
  var Span = document.createElement('span');
  Span.setAttribute("aria-hidden", "true");
  Span.innerText = "x";
  Btn.appendChild(Span);
  var DivContent = document.createElement('div');
  DivContent.className = "modal-body";
  DivContent.id = id + '-content';
  Div3.appendChild(DivContent);
  var DivFoot = document.createElement('div');
  DivFoot.className = "modal-footer";
  DivFoot.id = id + "-footer";
  Div3.appendChild(DivFoot);

  $('#' + id).on('show.bs.modal', function () {
    $('#' + parent).css('opacity', 0.5);
  });
  $('#' + id).on('hidden.bs.modal', function () {
    $('#' + parent).css('opacity', 1);
    $('#' + id).remove();
  });

  // $('#'+id).modal('show');
}

/**
 * 
 * @param {*} id 
 * @param {*} Btns 
 */
function addButtons(id, Btns)
{
  Btns.forEach(function(Button){
    var Btn = document.createElement('button');
    Btn.setAttribute("type", "button");
    if( Button.Func.length != 0){
      Btn.onclick = function () {
        var call = Button.Func+"('"+Button.Action+"','');";
        eval(call);         
      };  
    }
    Btn.className = "btn btn-"+Button.type;
    if( Button.Glyph == "log-out"){
      Btn.setAttribute("data-dismiss", "modal");
    }
    Btn.innerHTML = "<span class='glyphicon glyphicon-"+Button.Glyph+"'></span> "+Button.Label;
    document.getElementById(id+"-footer").appendChild(Btn);
  });
}

/***/ })

/******/ });
//# sourceMappingURL=bundle.js.map