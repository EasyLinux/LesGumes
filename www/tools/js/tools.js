var toolbarType='complete';
var CkEditor;
var aRecords;

function insertSQL()
{
    var description = $("#description").val();
    var version = $("#version").val();
    var sql = $("#sql").val();
    data = {
        Action: "SQL",
        Desc: description,
        Version: version,
        SQL: sql
    };
    $.post("/tools/index.php",data,
        function(data, status){
          //console.log("Etat: "+ status);
        });
      
}

function getSQL()
{
    var version = $("#version").val();
    data = {
        Action: "getSQL",
        Version: version,
    };
    $.post("/tools/index.php",data,
        function(data, status){
          $("#sql").text(data);
          //console.log("Etat: "+ status);
        });
      
}

/*==========================================================================================
 =                               Gestion des droits                                        =
 ===========================================================================================*/
/**
 * editRights
 * 
 * ouvre le popup d'édition des droits, récupère le contenu de la table
 * sys_right dans l'array globale aRecords.
 * @param {void}
 * @return {void}
 */
function editRights()
{
  // Charger le Popup formulaire
  $('#holder').load('/tools/templates/popup.smarty',function (){
    // Changer le titre
    $('#pop-title').text('Edition des droits');
    // Charger le contenu
    $('#pop-content').load('/tools/templates/edit-rights.smarty');
    // charger les boutons
    createRightButtonBar();
    // Charger les enregistrements
    loadRightTable('show');
      // setTimeout(function() {
      //   $('#right-info').fadeOut('slow');
      // },5000);
      // hide.bs.modal
      // $("#pop-all").on('shown.bs.modal', function(){
      //   setTimeout(setRightsEvents(),250); 
      // });
    
  });
}

/**
 * createRightButtonBar
 * 
 * Crée la barre de bouton du popup de gestion des droits:
 * - Ajouter : crée un enregistrement aRecords vierge, aRecords[].id = 0 
 * - Enregistrer : envoi le contenu de l'enregistrement actuel à ajax
 * - Supprimer : demande à ajax si le droit est inutilisé, si oui, supprime et recharge 
 *               la table
 * - Quitter : ferme le popup (pas de vérification d'enregistrement modifié !)
 * 
 * @param {void}
 * @return {void}
 */
function createRightButtonBar()
{
  // Création de la barre de boutons
  //   Nouveau  
  var Btn = document.createElement('button');
  Btn.id = "Add";
  Btn.innerHTML = "Nouveau";
  Btn.onclick = function () {
    if( aRecords.Status ==  "Saved")
    {
      // Sur appui 'Nouveau' Ajoute un élément à la liste, le déclare
      //     non sauvegardé
      aRecords.Status = "Not saved";
      aRecords.Idx = aRecords.length;
      Idx = aRecords.push({
        Id: 0,
        Label: "",
        Description: ""
      });
      // Pointe sur cet enregistrement 
      //    (id mis à zéro signifie nouveau pour la partie ajax)
      aRecords[Idx-1].Id = 0;
      $('#sRecLabel').val("");
      $('#sRecDesc').val("");
    }
  };
  Btn.className = "btn btn-info";
  $("#pop-foot").append(Btn);
  //  Enregistrer 
  var Btn = document.createElement('button');
  Btn.id = "Save";
  Btn.innerHTML = "Enregistrer";
  Btn.onclick =function () {
    // Paramètres à envoyer à ajax
    data = {
      Action: 'saveRights',
      id: aRecords[aRecords.Idx].Id,
      label: $('#sRecLabel').val(),
      desc: $('#sRecDesc').val()
    };
    // Appel ajax
    $.post("/ajax/index.php",data, function(data){
      // Mettre à jour aRecords
      aRecords[aRecords.Idx].Id = data.id.toString();
      aRecords[aRecords.Idx].Label = $('#sRecLabel').val();
      aRecords[aRecords.Idx].Description = $('#sRecDesc').val();
      // Mise à jour de l'affichage
      updateRightRecord();
      aRecords.Status = "Saved";
      if( data.Errno == 0 ){
        $('#right-info').removeClass('alert-info');
        $('#right-info').addClass('alert-success');
        $('#right-info').children('h4').text('Exécuté');
        $('#right-info').children('p').html("Modification sauvagardée");
      }
    });   
  };
  Btn.className = "btn btn-info";
  $("#pop-foot").append(Btn);
  //   Supprimer
  var Btn = document.createElement('button');
  Btn.id = "Del";
  Btn.innerHTML = "Supprimer";
  Btn.onclick =function () {
    // Supprimer un enregistrement
    data = {
      Action: "delRight",
      id: aRecords[aRecords.Idx].Id
    };
    $.post("/ajax/index.php",data, function(data){
      if( data.Errno == -1 ){
        $('#right-info').removeClass('alert-info');
        $('#right-info').addClass('alert-danger');
        $('#right-info').children('h4').text('Erreur');
        $('#right-info').children('p').html(data.ErrMsg);
      } else {
        $('#right-info').removeClass('alert-info');
        $('#right-info').addClass('alert-success');
        $('#right-info').children('h4').text('Exécuté');
        $('#right-info').children('p').html(data.ErrMsg);
      }
      // recharger aRecords
      loadRightTable('none');
    });
  };
  Btn.className = "btn btn-danger";
  $("#pop-foot").append(Btn);
  //   Quitter
  var Btn = document.createElement('button');
  Btn.id = "Quit";
  Btn.innerHTML = "Quitter";
  Btn.className = "btn btn-secondary";
  // Ce bouton ferme le popup
  Btn.setAttribute('data-dismiss','modal');
  $("#pop-foot").append(Btn);
}

/**
 * updateRightRecord
 * 
 * Change les données affichées du popup pour suivre le contenu
 * de l'enregistrement aRecords
 * 
 * @param {void}
 * @return {void}
 */
function updateRightRecord()
{
  $('#maxRecord').text(aRecords.length);
  $("#curRecord").text(aRecords.Idx+1);
  $('#sRecLabel').val(aRecords[aRecords.Idx].Label);
  $('#sRecDesc').val(aRecords[aRecords.Idx].Description);
}

/**
 * goFirstRight
 * 
 * Va sur le premier enregistrement 
 * 
 * @param {void}
 * @return {void}
 */
function goFirstRight()
{
  aRecords.Idx=0;
  updateRightRecord();
}

/**
 * goPreviousRight
 * 
 * Va sur le précédent enregistrement 
 * 
 * @param {void}
 * @return {void}
 */
function goPreviousRight()
{
  if( aRecords.Idx > 0)
  {
    aRecords.Idx--;
    updateRightRecord();
  }
  
}

/**
 * goNextRight
 * 
 * Va sur l' enregistrement suivant
 * 
 * @param {void}
 * @return {void}
 */
function goNextRight()
{
  if( aRecords.Idx < aRecords.length-1)
  {
    aRecords.Idx++;
    updateRightRecord();
  }
}

/**
 * goLastRight
 * 
 * Va sur le dernier enregistrement
 * 
 * @param {void}
 * @return {void}
 */
function goLastRight()
{
  aRecords.Idx=aRecords.length-1;
  updateRightRecord();
}

/**
 * loadRightTable
 * 
 * charge les données depuis la base
 * 
 * @param {string} whendone si 'show' alors affiche le popup
 * @return {void}
 */
function loadRightTable(whendone)
{
  // Récupérer le contenu de la table sys_right dans aRecords
  data = {
    Action: 'listRights'
  };
  $.post("/ajax/index.php",data, function(data, status){
    aRecords=data;
      // Pointe sur le premier enregistrement
    aRecords.Idx = 0;
    aRecords.Status = "Saved";
    // Afficher la première fiche
    updateRightRecord();
    if( whendone == 'show'){
      // Le popup est prêt, on l'affiche
      $('#pop-all').modal('show');
    }
    
  });
}









/*==========================================================================================
 =                               Edition de contenu                                        =
 ===========================================================================================*/


function editContent()
{
  // TODO a changer
  $('#Popup').modal('show');
}

function getImage()
{
  return prompt("URL image : ");
}