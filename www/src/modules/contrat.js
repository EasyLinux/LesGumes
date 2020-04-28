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
  modal.createModal("contrat-edit", "edit-doc", "Contrat papier","modal-sm");
  var Btns=[{
    Label:    "Quitter",
    Func:     "",
    Action:   "",
    Glyph:    "log-out",
    type:     "secondary"
  }];
  modal.addButtons("edit-doc",Btns);
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