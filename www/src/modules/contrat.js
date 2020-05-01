import * as modal from './modal.js';
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
export function doAction(action, params)
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
  
      case 'editRules':
      // Gestion des règles
      editRules();
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
  modal.createModal("id-contrat", "contrat-edit", "Gestion de contrat","modal-xl");
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
    });
  }
  modal.addButtons("contrat-edit",Btns);
  $('#contrat-edit').modal('show');
}

function chooseUser(type, title)
{
  modal.createModal("contrat-edit", "choose-user", title,"modal-sm");
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
  modal.addButtons("choose-user",Btns);
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
  modal.createModal("contrat-edit", "edit-doc", "Contrat papier","modal-sm");
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
  modal.addButtons("edit-doc",Btns);
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
  modal.createModal("contrat-edit", "edit-livraison", "Gestion des livraisons","");
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
  modal.addButtons("edit-livraison",Btns);
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
  });
  
}

function refreshWaitingList()
{
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
      var Html  = "<span class='glyphicon glyphicon-trash pointer' ";  
      Html += " data-toggle='tooltip' title='Retirer de la liste' ";
      Html += "onclick='contrat(\"delWait\","+line.id+");'></span>";
      cell.innerHTML = Html;
  
    });


  });
  // var tbody = document.getElementById("waiting-list");
  // var row = tbody.insertRow();
  //                     <td>01/05/2020</td>
  //                     <td>NOEL Serge</td>
  //                     <td>06.07.51.68.21</td>
  //                     <td style='text-align: right;'>
  //                       <span class="glyphicon glyphicon-trash pointer"  
  //                             data-toggle="tooltip" 
  //                             title="Retirer de la liste"
  //                             onclick="contrat('delWait','');"></span>

}



















function editRules()
{
  modal.createModal("contrat-edit", "edit-rules", "Règles de fonctionnement","");
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
  modal.addButtons("edit-rules",Btns);
  $('#edit-rules-content').css("Height","250px");
  $('#edit-rules-content').css("minHeight","250px");
  var aera = document.createElement('textarea');
  aera.style.height="100%";
  aera.style.width='100%';
  $('#edit-rules-content').append(aera);
  $('#edit-rules').modal('show');
}

