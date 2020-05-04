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
/* harmony import */ var _modules_helper_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./modules/helper.js */ "./www/src/modules/helper.js");
//import * as user from './modules/user.js';



window.contrat = function(action, params){
   _modules_contrat_js__WEBPACK_IMPORTED_MODULE_0__["doAction"](action, params);
}

window.formatNumber = function(){
  _modules_helper_js__WEBPACK_IMPORTED_MODULE_1__["formatNumber"]();
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
      // Enregistrer le contrat (données)
      saveContrat(params);
      break;

    case 'doc':
      // Modifier le document contrat
      editDoc();
      break;
  
    case 'uploadDoc':
      // Ouvrir le navigateur pour choisir un fichier
      uploadDoc();
      break;

    case 'loadDoc':
      // Charger le .pdf
      loadDoc();
      break;

    case 'okDoc':
      // On a choisi le contrat
      okDoc();
      break;

    case 'livraison':
      // gère les livraisons
      livraison();
      break; 
    
    case 'addLivraison':
      editLivraison(0);
      break;

    case 'validLivraison':
      validLivraison(params);
      break;

    case 'editLivraison':
      editLivraison(params);
      break;
  
    case 'delLivraison':
      delLivraison(params);
      break;
    
    case 'addWait':
      chooseUser('Wait', "Ajouter à la liste d'attente");
      break;

    case 'okWait':
      okWait();
      break;

    case 'delWait':
      delWait(params);
      break;

    case 'acceptWait':
      // TODO pas implémenté
      alert("Pas implémenté");
      break;

    case 'addUser':
      chooseUser('User', "Ajouter au contrat");
      break;

    case 'okUser':
      okUser();
      break;
    
    case 'editRules':
      // Gestion des règles
      editRules();
      break; 

    case 'editProduct':
      editProduct(params);
      break;

    case 'okProduct':
      saveProduct();
      break;

    case 'delProduct':
      delProduct(params);
      break;

    default:
      console.log("Dans contrat - doAction: "+action);
      break;  
  }
}

/**
 * Création du formulaire d'édition du contrat
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
      document.getElementById('replace').dataset.id=resp.Data[0].IdSuppleant;
      $('#currentSeason').val(resp.Data[0].EnCours);
      $("#h3-season").text(resp.Data[0].EnCours);
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
      var contratDoc = resp.Data[0].Document;
      $('#contrat-doc').text(contratDoc.replace(/.*\//, ''));
      $('#contrat-doc').attr("href",resp.Data[0].Document);
      refreshWaitingList();
      refreshUserList(id);
      refreshProduct(id);
    });
  }
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("contrat-edit",Btns);
  $('#contrat-edit').modal('show');
  // Action à la fermeture du contrat
  $('#contrat-edit').on('hidden.bs.modal', function () {
    refreshListeContrat();
  });
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

/**
 * enregistre le contrat en cours d'édition
 * @param {bool} quit   si vrai, alors ferme le modal (valeur par défaut)
 */
function saveContrat(quit)
{

  // TODO Toujours verrouillé - Bug
  if( quit == undefined){
    quit=true;
  }
  var formValid = true;
  // Valider si on a bien les infos
  if( $('#contractName').val() == ""){
    formValid = false;
    $('#contractName').addClass('required');
  } else {
    $('#contractName').removeClass('required');
  }
  var sPost='';
  sPost += '{"id":"'+document.getElementById("contractName").dataset.id+'",';
  sPost += '"Name":"'+document.getElementById("contractName").value+'",';
  sPost += '"Type":"'+$("#contratType :selected").val()+'",';
  var idProducer = document.getElementById("producer").dataset.id;
  if( idProducer == '0'){
    formValid = false;
    $('#producer').addClass('required');
  } else {
    $('#producer').removeClass('required');
  }
  sPost += '"idProducteur":"'+idProducer+'",';
  var idReferent = document.getElementById("referent").dataset.id;
  if( idReferent == '0'){
    formValid = false;
    $('#referent').addClass('required');
  } else {
    $('#referent').removeClass('required');
  }
  sPost += '"idReferent":"'+idReferent+'",';
  var idSuppleant = document.getElementById("replace").dataset.id;
  
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
  sPost += '"Document":"'+$('#contrat-doc').attr('href')+'",'; 
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
      if( quit ){
        $('#contrat-edit').modal('hide');
      }
    });
    // TODO refreshContrat()
  } else {
    alert('Le contrat est invalide, veuillez le compléter');
  }
}


function editDoc()
{
  // TODO save si id == 0
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["createModal"]("contrat-edit", "edit-doc", "Contrat papier","modal-sm");
  var Btns=[{
    Label:    "Choisir",
    Func:     "contrat",
    Action:   "okDoc",
    Glyph:    "ok",
    type:     "primary"
  },{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("edit-doc",Btns);
  var doc = document.getElementById('edit-doc-content');
  var p = document.createElement('p');
  p.innerHTML = "Document actuel: "+$('#contrat-doc').text();
  doc.appendChild(p);
  var p = document.createElement('p');
  p.innerHTML = "Anciens contrats ";
  doc.appendChild(p);
  var select = document.createElement('select');
  select.id = "liste-doc";
  select.className = "form-control";
  select.setAttribute("multiple", true);
  select.style.height="100px";
  doc.appendChild(select);
  var p = document.createElement('p');
  p.innerHTML="<br /><span class='glyphicon glyphicon-download-alt'></span> Télécharger nouveau contrat";
  p.onclick = function(){
    contrat('uploadDoc',"");
  };
  doc.appendChild(p);
  var input = document.createElement('input');
  input.type="file";
  input.id='docFile';
  input.style.display="none";
  input.onchange=function(){
    contrat('loadDoc','');
  }
  doc.appendChild(input);
  data={
    Action:  "contrat",
    Want:    "getDocs",
    Vars:    $('#contractName').val()
  };
  $.post("/ajax/index.php", data, function (resp) {
    var contrat = document.getElementById("contractName").dataset;
    contrat.folder=resp.Folder;
    resp.Files.forEach(function(File){
      var option = document.createElement('option');
      option.text = File;
      select.add(option);
    });
  });
  $('#edit-doc').modal('show');
}

function uploadDoc()
{
  // Déclencher le bouton input
  document.getElementById('docFile').click(); 
}

function loadDoc()
{
  // Charge le fichier 
  var file_data = $('#docFile').prop('files')[0];
  var form_data = new FormData();
  form_data.append('file', file_data);
  form_data.append('Action', 'contrat');
  form_data.append('Want',   'uploadDoc');
  form_data.append('Vars',   $('#contractName').val());
  // Demande à .php le chargement du fichier
  $.ajax({
    url: "/ajax/index.php",
    data: form_data,
    cache: false,
    contentType: false,
    processData: false,
    type: 'post',
    success: function (resp) {
      // Rafraichir la liste
      data={
        Action:  "contrat",
        Want:    "getDocs",
        Vars:    $('#contractName').val()
      };
      $('#liste-doc').empty();
      var select = document.getElementById('liste-doc');
      $.post("/ajax/index.php", data, function (resp) {
        resp.Files.forEach(function(File){
          var option = document.createElement('option');
          option.text = File;
          select.add(option);
        });
      });
    },
    failed: function(resp){
      console.log(resp);
    }
  });    
}


function okDoc()
{
  var contrat = document.getElementById("contractName").dataset;
  $('#contrat-doc').text($('#liste-doc :selected').text());
  $('#contrat-doc').attr('href',contrat.folder+"/"+$('#liste-doc :selected').text());
  $('#edit-doc').modal('hide');
}


function livraison()
{
  // TODO save si id=0
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["createModal"]("contrat-edit", "edit-livraison", "Gestion des livraisons","");
  var Btns=[{
    Label:    "Ajouter",
    Func:     "contrat",
    Action:   "addLivraison",
    Glyph:    "plus",
    type:     "primary"
  },{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("edit-livraison",Btns);
  $('#edit-livraison-content').css("minHeight","300px");
  $('#edit-livraison-content').css("Height","300px");
  var table = document.createElement('table');
  table.className="table-condensed table-hover";
  var thead = document.createElement('thead');
  table.appendChild(thead);
  var row = thead.insertRow();
  var cell = row.insertCell();
  cell.innerHTML = "Date";
  var cell = row.insertCell();
  cell.innerHTML = "Numéro";
  var cell = row.insertCell();
  cell.innerHTML = "Montant";
  var cell = row.insertCell();
  cell.innerHTML = "&nbsp;";
  $('#edit-livraison-content').append(table);
  var tbody = document.createElement('tbody');
  tbody.id="liste-livraison-body";
  table.appendChild(tbody);
  refreshLivraison();
  $('#edit-livraison').modal('show');
}

function editLivraison(id)
{
  var el = document.getElementById('liste-livraison-body');
  if( id == 0 ){
    var row = el.insertRow();
    row.id="liste-livraison-row-0";
    var rowDate='';
    var rowNum=0;
    var rowMontant="0";
  } else {
    var row = document.getElementById("liste-livraison-row-"+id);
    var rowDate=row.cells[0].innerText;
    var rowNum=row.cells[1].innerText;
    var Montant = row.cells[2].innerText;
    var rowMontant=Montant.substr(0,Montant.length-2);
    row.innerHTML="";
  }
  var cell = row.insertCell();
  var inp = document.createElement('input');
  inp.type="text";
  inp.id="liste-livraison-date";
  inp.style.width='100px';
  inp.value=rowDate;
  cell.appendChild(inp);
  var cell = row.insertCell();
  var inp = document.createElement('input');
  inp.type="text";
  inp.id="liste-livraison-index";
  inp.style.width='40px';
  inp.value=rowNum;
  cell.appendChild(inp);
  var cell = row.insertCell();
  cell.style.textAlign="right";
  var inp = document.createElement('input');
  inp.type="text";
  inp.id="liste-livraison-montant";
  inp.style.width='80px';
  inp.value=rowMontant;
  cell.appendChild(inp);
  var cell = row.insertCell();
  cell.innerHTML="<span class='glyphicon glyphicon-ok'></span>";
  cell.style.textAlign="right";
  cell.onclick= function(){
    contrat('validLivraison',id);
  };
  $( "#liste-livraison-date" ).datepicker({
    altField: "#contract-end",
    closeText: 'Fermer',
    prevText: 'Précédent',
    nextText: 'Suivant',
    currentText: 'Aujourd\'hui',
    monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
    dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
    dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
    weekHeader: 'Sem.',
    dateFormat: 'dd/mm/yy'
    });
}

function validLivraison(id)
{
  // TODO automatisation sys_livraison_produit / sys_permanence
  var elContrat = document.getElementById('contractName');
  var idContrat = elContrat.dataset.id;
  // Lire les valeurs
  var rowDate = $('#liste-livraison-date').val();
  var rowIndex = $('#liste-livraison-index').val();
  var sRowMontant = $('#liste-livraison-montant').val();
  var rowMontant = parseFloat(sRowMontant).toFixed(2);
  var sVars='{"id":"'+id+'","idContrat":"'+idContrat+'","frDate":"';
  sVars += rowDate+'","numLivraison":"'+rowIndex;
  sVars += '","Montant":"'+rowMontant+'"}';
  data = {
    Action: "contrat",
    Want:   "saveLivraison",
    Vars:   sVars
  };
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
    }
    refreshLivraison();
  });
}

function delLivraison(id)
{
  if( !confirm("Supprimer la livraison ?") ){
    return false;
  }
  data = {
    Action: "contrat",
    Want:   "delLivraison",
    Vars:   id
  };
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
    }
    refreshLivraison();
  });
}

function refreshLivraison()
{
  var elContrat = document.getElementById('contractName');
  var idContrat = elContrat.dataset.id;
  var tbody = document.getElementById('liste-livraison-body');
  tbody.innerHTML="";
  data={
    Action: "contrat",
    Want:   "listLivraison",
    Vars:   idContrat
  };
  $.post("/ajax/index.php",data,function(resp){
    if( resp.Errno != 0){
      alert(resp.ErrMsg);
    }
    console.log(resp);
    resp.Datas.forEach(function (sRow){
      var row = tbody.insertRow();
      row.id = "liste-livraison-row-"+sRow.id;
      var cell = row.insertCell();
      cell.innerHTML= sRow.frDate;
      var cell = row.insertCell();
      cell.innerHTML= sRow.numLivraison;
      var cell = row.insertCell();
      cell.style.textAlign="right";
      cell.innerHTML= sRow.Montant+"<span> €</span>";    
      var cell = row.insertCell();
      var Html  = "<span class='glyphicon glyphicon-trash' data-toggle='tooltip' title='Supprimer' ";
      Html += "onClick=\"contrat('delLivraison',"+sRow.id+");\"></span>";    
      Html += " <span class='glyphicon glyphicon-pencil' data-toggle='tooltip' title='Modifier' ";
      Html += "onClick=\"contrat('editLivraison',"+sRow.id+");\"></span>"; 
      cell.style.textAlign = "right";   
      cell.innerHTML = Html;
    });
  });
}

function okWait()
{
  var elContrat = document.getElementById('contractName');
  var idContrat = elContrat.dataset.id;
  var id = $('#user-choose :selected').val();
  var sVars='{"id":"'+id+'","idContrat":"'+idContrat+'"}';
  data={
    Action: 'contrat',
    Want:   'addWaitUser',
    Vars:   sVars
  };
  $.post("/ajax/index.php",data, function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
      return false;
    }
    refreshWaitingList();
    $('#choose-user').modal('hide');
  });
}

function delWait(id)
{
  var elContrat = document.getElementById('contractName');
  var idContrat = elContrat.dataset.id;
  var sVars='{"id":"'+id+'","idContrat":"'+idContrat+'"}';
  data={
    Action: 'contrat',
    Want:   'delWaitUser',
    Vars:   sVars
  };
  $.post("/ajax/index.php",data, function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
      return false;
    }
    $('#choose-user').modal('hide');
    refreshWaitingList();
  });
}

function refreshWaitingList()
{
  console.log("Rafraichir la liste");
  var elContrat = document.getElementById('contractName');
  var idContrat = elContrat.dataset.id;
  data={
    Action: "contrat",
    Want:   "listWait",
    Vars:   idContrat
  };
  var tbody = document.getElementById("waiting-list");
  tbody.innerHTML="";
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno == -1 ){
      alert(resp.ErrMsg);
      return false;
    }
    resp.Wait.forEach(function(line){
      var row = tbody.insertRow();
      var cell = row.insertCell();
      cell.innerHTML = line.frDate;
      var cell = row.insertCell();
      cell.innerHTML = line.Raisoc;
      var cell = row.insertCell();
      cell.innerHTML = line.sTelMobile;
      var cell = row.insertCell();
      cell.style.textAlign="right";
      var Html  = "<span class='glyphicon glyphicon-thumbs-up pointer' ";  
      Html += " data-toggle='tooltip' title='Accepter' ";
      Html += "onclick='contrat(\"acceptWait\","+line.id+");'></span> ";
      Html += "<span class='glyphicon glyphicon-trash pointer' ";  
      Html += " data-toggle='tooltip' title='Retirer de la liste' ";
      Html += "onclick='contrat(\"delWait\","+line.id+");'></span>";
      cell.innerHTML = Html;
    });
  });
}


function okUser()
{
  var elContrat = document.getElementById('contractName');
  var idContrat = elContrat.dataset.id;
  var id = $('#user-choose :selected').val();
  var sVars='{"id":"'+id+'","idContrat":"'+idContrat+'"}';
  data={
    Action: 'contrat',
    Want:   'addUser',
    Vars:   sVars
  };
  $.post("/ajax/index.php",data, function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
      return false;
    }
    console.log(resp.ErrMsg);
    refreshUserList(idContrat);
    $('#choose-user').modal('hide');
  });
}

function refreshUserList(id)
{
  if( id != undefined ){
    var idContrat = id;
  } else {
    var elContrat = document.getElementById('contractName');
    var idContrat = elContrat.dataset.id;
  }
  data={
    Action:  "contrat",
    Want:    "refreshUserList",
    Vars:    idContrat
  };
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno == -1){
      alert(resp.ErrMsg);
      return false;
    }
    var tbody = document.getElementById('user-list');
    resp.Data.forEach(function(line){
      var row = tbody.insertRow();
      var cell = row.insertCell();
      cell.innerHTML = line.dateInscription;
      var cell = row.insertCell();
      cell.innerHTML = line.Nom;
      var cell = row.insertCell();
      cell.innerHTML = line.Prenom;
      var cell = row.insertCell();
      cell.innerHTML = line.Telephone;
      var cell = row.insertCell();
      cell.style.textAlign="right";
      var Html  = "<input type='checkbox' data-toggle='tooltip'";
      Html += " title='Contrat en cours'>&nbsp;&nbsp;&nbsp;";
      Html += "<span class='glyphicon glyphicon-search' data-toggle='tooltip' title='Voir le détail'></span>";
      cell.innerHTML = Html;

    });
  });
}

function editProduct(id)
{
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["createModal"]("contrat-edit", "edit-product", "Edition produit","");
  var Btns=[{
    Label:    "Valider",
    Func:     "contrat",
    Action:   "okProduct",
    Glyph:    "ok",
    type:     "primary"
  },{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("edit-product",Btns);
  $('#edit-product-content').load('/tools/templates/product.smarty');
  if( id != 0 ){
    data = {
      Action: "contrat",
      Want:   "loadProduct",
      Vars:   id
    };
    $.post("/ajax/index.php",data,function(resp){
      if( resp.Errno != 0 ){
        alert(resp.ErrMsg);
        return false;
      }
      $('#label').val(resp.Data[0].Label);
      var label = document.getElementById('label');
      label.dataset.id = id;
      $('#unite').val(resp.Data[0].Unite);
      $('#max').val(resp.Data[0].MaxLivraison);
      $('#prix').val(resp.Data[0].prix);
      $('#description').val(resp.Data[0].Description);
      return;
    });
  }
  $('#edit-product').modal('show');
}

function saveProduct()
{
  var el = document.getElementById('label');
  var id = el.dataset.id;
  var label = el.value;
  if (label == ""){
    alert("Le label est obligatoire");
    return false;
  }
  var unite = document.getElementById('unite').value;
  var max = document.getElementById('max').value;
  var prix = document.getElementById('prix').value;
  var elContrat = document.getElementById('contractName');
  var idContrat = elContrat.dataset.id;
  var description = document.getElementById('description').value;

  var sVars= '{"id":"'+id+'","idContrat":"';
  sVars += idContrat +'","label":"'+label+'","unite":"';
  sVars += unite +'","max":"'+max+'","prix":"';
  sVars += prix+'","description":"'+description+'"}';

  data = {
    Action:  "contrat",
    Want:    "saveProduct",
    Vars:    sVars
  }
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
      return false;
    }
    $('#edit-product').modal('hide');
    refreshProduct(idContrat);
  });
}

function refreshProduct(id)
{
  data = {
    Action:  "contrat",
    Want:    "listProduct",
    Vars:    id
  }
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
      return false;
    }
    var tbody = document.getElementById('product-list');
    tbody.innerHTML="";
    resp.Data.forEach(function(line){
      var row = tbody.insertRow();
      var cell = row.insertCell();
      cell.innerHTML=line.Label;
      var cell = row.insertCell();
      cell.innerHTML=line.Unite;
      var cell = row.insertCell();
      cell.innerHTML=line.Prix;
      var cell = row.insertCell();
      cell.style.textAlign="right";
      var Html = "<span class='glyphicon glyphicon-pencil' ";
      Html += "data-toggle='tooltip' title='Editer' ";
      Html += "onclick=\"contrat('editProduct',"+line.id+");\"></span>&nbsp;&nbsp;";
      Html += "<span class='glyphicon glyphicon-trash' ";
      Html += "data-toggle='tooltip' title='Supprimer' ";
      Html += "onclick=\"contrat('delProduct',"+line.id+");\"></span>";
      cell.innerHTML =Html;
    });
    console.log(resp);
  });
}

function delProduct(id)
{
  console.log("Dans delProduct "+id);
  data = {
    Action:  "contrat",
    Want:    "delProduct",
    Vars:    id
  }
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
      return false;
    }
    var elContrat = document.getElementById('contractName');
    var idContrat = elContrat.dataset.id;
    console.log("refresh "+idContrat);
    refreshProduct(idContrat);
  });
}













function editRules()
{
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["createModal"]("contrat-edit", "edit-rules", "Règles de fonctionnement","");
  var Btns=[{
    Label:    "Accepter",
    Func:     "contrat",
    Action:   "okAttente",
    Glyph:    "ok",
    type:     "primary"
  },{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  _modal_js__WEBPACK_IMPORTED_MODULE_0__["addButtons"]("edit-rules",Btns);
  $('#edit-rules-content').css("Height","250px");
  $('#edit-rules-content').css("minHeight","250px");
  var aera = document.createElement('textarea');
  aera.style.height="100%";
  aera.style.width='100%';
  $('#edit-rules-content').append(aera);
  $('#edit-rules').modal('show');
}

function refreshListeContrat()
{
  console.log("dans refreshListeContrat");
  var tbody = document.getElementById('liste-contrat');
  tbody.innerHTML = "";
  data={
    Action: "contrat",
    Want:   "listeContrat",
    Vars:   ""
  }
  $.post('/ajax/index.php',data,function(resp){
    if( resp.Errno != 0 ){
      alert(resp.ErrMsg);
      return false;
    }
    resp.Data.forEach(function(line){
      var row = tbody.insertRow();
      row.onclick = function(){
        contrat('edit',line.id);
      };
      var cell = row.insertCell();
      cell.innerHTML = line.label;
      var cell = row.insertCell();
      cell.innerHTML = line.Type;
      var cell = row.insertCell();
      cell.innerHTML = line.Producteur;
      var cell = row.insertCell();
      cell.innerHTML = line.Referent;
      var cell = row.insertCell();
      cell.innerHTML = line.Verouille;
    });
  });
}

/***/ }),

/***/ "./www/src/modules/helper.js":
/*!***********************************!*\
  !*** ./www/src/modules/helper.js ***!
  \***********************************/
/*! exports provided: formatNumber */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "formatNumber", function() { return formatNumber; });
function formatNumber()
{
  var sPrix = $('#amount').val();

  if( sPrix.indexOf(',') > 0){
    sPrix = sPrix.replace(',','.');
  }
  var dPrix = parseFloat(sPrix).toFixed(2);
  sPrix = $('#amount').val(dPrix);
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