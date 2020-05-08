var toolbarType = 'complete';
var CkEditor;
var CkMail;
//var editor;
var aRecords;

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
function editRights() {
  // Charger le Popup formulaire
  $('#holder').load('/tools/templates/popup.smarty', function () {
    // Changer le titre
    $('#pop-title').text('Edition des droits');
    // Charger le contenu
    $('#pop-content').load('/tools/templates/edit-rights.smarty');
    $("#pop-foot").html("");
    // charger les boutons
    createRightButtonBar();
    // Charger les enregistrements
    loadRightTable('show');
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
function createRightButtonBar() {
  // Création de la barre de boutons
  //   Nouveau  
  var Btn = document.createElement('button');
  Btn.id = "Add";
  Btn.innerHTML = "<span class='glyphicon glyphicon-plus'></span>  Nouveau";
  Btn.onclick = function () {
    if (aRecords.Status == "Saved") {
      // Sur appui 'Nouveau' Ajoute un élément à la liste, le déclare
      //     non sauvegardé
      aRecords.Status = "Not saved";
      aRecords.Idx = aRecords.length;
      Idx = aRecords.push({
        iId: 0,
        sLabel: "",
        sDescription: ""
      });
      // Pointe sur cet enregistrement 
      //    (id mis à zéro signifie nouveau pour la partie ajax)
      aRecords[Idx - 1].id = 0;
      $('#sRecLabel').val("");
      $('#sRecDesc').val("");
    }
  };
  Btn.className = "btn btn-primary";
  $("#pop-foot").append(Btn);
  //  Enregistrer 
  var Btn = document.createElement('button');
  Btn.id = "Save";
  Btn.innerHTML = "<span class='glyphicon glyphicon-save'></span> Enregistrer";
  Btn.onclick = function () {
    // Paramètres à envoyer à ajax
    data = {
      Action: 'doRights',
      Sub: 'saveRights',
      id: aRecords[aRecords.Idx].id,
      label: $('#sRecLabel').val(),
      desc: $('#sRecDesc').val()
    };
    // Appel ajax
    $.post("/ajax/index.php", data, function (data) {
      // Mettre à jour aRecords
      aRecords[aRecords.Idx].id = data.id.toString();
      aRecords[aRecords.Idx].sLabel = $('#sRecLabel').val();
      aRecords[aRecords.Idx].sDescription = $('#sRecDesc').val();
      // Mise à jour de l'affichage
      updateRightRecord();
      aRecords.Status = "Saved";
      if (data.Errno == 0) {
        $('#right-info').removeClass('alert-info');
        $('#right-info').addClass('alert-success');
        $('#right-info').children('h4').text('Exécuté');
        $('#right-info').children('p').html("Modification sauvagardée");
      }
    });
  };
  Btn.className = "btn btn-primary";
  $("#pop-foot").append(Btn);
  //   Supprimer
  var Btn = document.createElement('button');
  Btn.id = "Del";
  Btn.innerHTML = "<span class='glyphicon glyphicon-trash'></span> Supprimer";
  Btn.onclick = function () {
    // Supprimer un enregistrement
    data = {
      Action: "doRights",
      Sub: "delRight",
      id: aRecords[aRecords.Idx].id
    };
    $.post("/ajax/index.php", data, function (data) {
      if (data.Errno == -1) {
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
  Btn.id = "<span class='glyphicon glyphicon-log-out'></span> Quit";
  Btn.innerHTML = "<span class='glyphicon glyphicon-log-out'></span> Quitter";
  Btn.className = "btn btn-secondary";
  // Ce bouton ferme le popup
  Btn.setAttribute('data-dismiss', 'modal');
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
function updateRightRecord() {
  $('#maxRecord').text(aRecords.length);
  $("#curRecord").text(aRecords.Idx + 1);
  $('#sRecLabel').val(aRecords[aRecords.Idx].sLabel);
  $('#sRecDesc').val(aRecords[aRecords.Idx].sDescription);
}

/**
 * goFirstRight
 * 
 * Va sur le premier enregistrement 
 * 
 * @param {void}
 * @return {void}
 */
function goFirstRight() {
  aRecords.Idx = 0;
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
function goPreviousRight() {
  if (aRecords.Idx > 0) {
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
function goNextRight() {
  if (aRecords.Idx < aRecords.length - 1) {
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
function goLastRight() {
  aRecords.Idx = aRecords.length - 1;
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
function loadRightTable(whendone) {
  // Récupérer le contenu de la table sys_right dans aRecords
  data = {
    Action: 'doRights',
    Sub: 'listRights',
    id: 0,
    label: '',
    desc: ''
  };
  $.post("/ajax/index.php", data, function (data, status) {
    aRecords = data;
    // Pointe sur le premier enregistrement
    aRecords.Idx = 0;
    aRecords.Status = "Saved";
    // Afficher la première fiche
    if (whendone == 'show') {
      // Le popup est prêt, on l'affiche
      $('#pop-all').on("show.bs.modal", updateRightRecord());
      $('#pop-all').modal('show');
    } else {
      updateRightRecord();
    }

  });
}


/*==========================================================================================
 =                               Edition de contenu                                        =
 ===========================================================================================*/
/**
 * getImage
 * 
 * Cette fonction est appelée par l'éditeur CkEditor lors d'une demande d'insertion
 * d'image. L'image sera renvoyée par la fonction setImage
 * @param  {void}
 * @return {void}
 */
function getImage() {
  getFinder('art');
}

/**
 * getFinder
 * 
 * Cette fonction est appelée par l'éditeur CkEditor lors d'une demande d'insertion
 * d'image. L'image sera renvoyée par la fonction setImage
 * @param  {void}
 * @return {void}
 */
function getFinder(article) {

  // afficher popup
  $('#holder').load('/tools/templates/image.smarty', function () {
    $('#image').on('show.bs.modal', function () {
      if (article == undefined) { // Gestion de fichier
        $('.imgShow-left-art').hide();
        $('#btn-ok-finder').hide();
      } else { // sélection editeur
        // Appelé depuis l'éditeur, liste les articles de la base
        $('.imgShow-left-art').show();
        $('#btn-ok-finder').show();
      }
      // Pas d'image sélectionnée
      document.getElementById('img-files').dataset.obj='';
      document.getElementById('img-files').dataset.id='';
      // Pas de répertoire sélectionné
      document.getElementById('img-files').dataset.cur='';
      $('#toolbar-folder').hide();
      $('#Popup').css('opacity', 0.5);
    });
    $('#image').on('hidden.bs.modal', function () {
      $('#Popup').css('opacity', 1);
    });

    // on télécharge un fichier
    $('#fileInput').change(function () {
      doFinder("doUpload", '');
    });

    $('#image').modal('show');
  });
}

/**
 * setImage
 * 
 * Renvoi l'URL de l'image sélectionnée par l'utilisateur
 * 
 * @param {string} imageURL    URL relative de l'image
 */
function setImage(imageURL) {
  console.log("imageURL: "+imageURL);
  CkEditor.model.change(writer => {
    const imageElement = writer.createElement('image', {
      src: imageURL
    });
    // Insert the image in the current selection location.
    CkEditor.model.insertContent(imageElement, CkEditor.model.document.selection);
  });
}


/**
 * doFinder
 * 
 * Traitement du popup de sélection d'image, cette fonction gère  
 * la boîte modale /tools/templates/image.smarty
 * Lors de l'affichage de cette boîte de dialogue, nous avons besoin
 * de savoir quel répertoire ou quel objet est actuellement désigné.
 * Ces deux informations sont stockées dans data-cur (pour le répertoire)
 * et data-obj (pour l'objet) placé dans le div img-files 
 * 
 * @param {string} action   action requise
 * @param {void} id    id sélectionné 
 */
function doFinder(action, id) {
  switch (action) {
    case 'getIt':  
      // doc/image/article choisi -> envoi à ckEditor
      sObject = document.getElementById('img-files').dataset.obj;
      sCurFolder = document.getElementById('img-files').dataset.cur;
      if( sObject != "" ){
        console.log("sObject: "+sObject+" folder "+sCurFolder); 
        if( sObject.substring(0,1) == 'a'){
          sLinkLabel = prompt("Titre du lien");
          sLink = "<p><a href='#' onclick=\"loadContent('article_"+sObject.substring(2)+"');\">"+sLinkLabel+"</a></p>";
          // sLink = 'index.php?art='+sObject.substring(2);
          // CkEditor.execute( 'link', sLink );
          const viewFragment = CkEditor.data.processor.toView( sLink );
          const modelFragment = CkEditor.data.toModel( viewFragment );
          CkEditor.model.insertContent(modelFragment);
      }
        if( sCurFolder.substring(0,1) == 'i'){
          setImage(sObject);
        }
        if( sCurFolder.substring(0,1) == 'f'){
          CkEditor.execute( 'link', sObject, { linkIsExternal: true } );
        }
        $('#image').modal('hide');
      }
      break;

    case 'upload':
      // Actionne le bouton de sélection de fichier
      sWhere = document.getElementById('img-files').dataset.cur;
      if( sWhere == "" ){
        alert('Veuillez choisir un répertoire cible');
        return false;
      }  
      document.getElementById('fileInput').click();
      break;

    case 'doUpload':
      // Charge le fichier 
      var file_data = $('#fileInput').prop('files')[0];
      var form_data = new FormData();
      sWhere = document.getElementById('img-files').dataset.cur;
      sWhere = sWhere.replace("i-",'-');
      sWhere = sWhere.replace("f-",'-');
      targetFolder  = sWhere.replace(/-/g,"/");
      targetFolder += "/";
      form_data.append('file', file_data);
      form_data.append('Action', 'loadFile');
      form_data.append('Where', targetFolder);
      // Demande à .php le chargement du fichier
      $.ajax({
        url: "/ajax/index.php",
        data: form_data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        success: function () {
          sTarget = document.getElementById('img-files').dataset.cur;
          // Rafraichi le div de droite
          console.log('Refresh');
          if(sTarget.substring(0,1) == 'i'){
            doFinder('refresh-img',sTarget);
          } else {
            doFinder('refresh-file', sTarget);
          }
          
        }
      });
      break;

    case 'select-art':
      // On a cliqué sur un objet (article)
      $('#toolbar-file').show();
      $('#toolbar-folder').hide();
      document.getElementById('img-files').dataset.obj = id;
      document.getElementById('img-files').dataset.id = id;
      span = document.getElementById('cur-Object');
      span.innerHTML = " Article - "+document.getElementById(id).innerHTML;
      break;
    
    case 'select-img':
    case 'select-file':
      // On a cliqué sur un objet (image ou doc)
      $('#toolbar-file').show();
      $('#toolbar-folder').hide();
      document.getElementById('img-files').dataset.obj = id;
      span = document.getElementById('cur-Object');
      span.innerHTML = id;
      break;

    case 'trash-img':
      // Suppression d'une image
      sObject = document.getElementById('img-files').dataset.obj;
      data = { Action: 'Finder', Want: 'delFile', Vars: sObject };
      $.post('/ajax/index.php', data, function (resp) {
        if (resp.Errno != 0) {
          alert(resp.ErrMsg);
          return false;
        }
        // Rafraichir le div de droite
        sCurFolder = document.getElementById('img-files').dataset.cur;
        var dirname = ''+sObject.match(/.*\//);
        var dir2 = dirname.substring(0, dirname.length -1);
        newId = "i"+dir2.replace(/\//g,"-");
        doFinder('refresh-img',newId);
        });
      break;

    case 'refresh-img':
      // Basculer sur la toolbar Folder
      $('#toolbar-file').hide();
      $('#toolbar-folder').show();
      document.getElementById('img-files').dataset.cur=id;
      sCurFolder=id.replace(/-/g,"/");
      sCurFolder=sCurFolder.replace("i/media/images","/");
      sCurFolder=sCurFolder.replace("//","/");
      sObject='{"Type":"Img","Path":"'+sCurFolder+'"}';
      data = { Action: 'Finder', Want: 'getFiles', Vars: sObject };
      // Demande à ajax le contenu du répertoire
      $.post('/ajax/index.php', data, function (resp) {
        if (resp.Errno != 0) {
          alert(resp.ErrMsg);
          return false;
        }
        // Vide le div
        $('#img-files').html('');
        resp.Files.forEach(function(file){
          // crée la vignette
          createImage(file);
        });
        var curElement = document.getElementById(id+"-ul");
        if( curElement != null ){
          curElement.innerHTML="";
        } else {
          var el = document.getElementById(id);
          var ul = document.createElement('ul');
          ul.id=id+"-ul"; 
          // insérer après l'élément
          el.parentNode.insertBefore(ul, el.nextSibling);
        }
        resp.Folders.forEach(function(folder){
          createFolder('i',id+"-ul",resp.Folder,folder);
        });
      });
      break;

      case 'refresh-file':
        // Basculer sur la toolbar File
        $('#toolbar-file').hide();
        $('#toolbar-folder').show();
        // Met le répertoire courant en mémoire
        document.getElementById('img-files').dataset.cur=id;
        // Remplacer l'id f-media-documents-test en /documents
        sCurFolder=id.replace(/-/g,"/");
        sCurFolder=sCurFolder.replace("f/media/documents","/");
        sCurFolder=sCurFolder.replace("//","/");
        sObject='{"Type":"File","Path":"'+sCurFolder+'"}';
        data = { Action: 'Finder', Want: 'getFiles', Vars: sObject };
        // Demande à ajax le contenu du répertoire
        $.post('/ajax/index.php', data, function (resp) {
          if (resp.Errno != 0) {
            alert(resp.ErrMsg);
            return false;
          }
          // Vide le div
          $('#img-files').html('');
          resp.Files.forEach(function(file){
            // crée la vignette
            createFile(file);
          });
          var curElement = document.getElementById(id+"-ul");
          if( curElement != null ){
            curElement.innerHTML="";
          } else {
            console.log(id);
            var el = document.getElementById(id);
            var ul = document.createElement('ul');
            ul.id=id+"-ul"; 
            // insérer après l'élément
            el.parentNode.insertBefore(ul, el.nextSibling);
          }
          resp.Folders.forEach(function(folder){
            createFolder('f',id+"-ul",resp.Folder,folder);
          });
        });
        break;
  
      case 'refresh-art':
        // Basculer sur la toolbar File
        $('#toolbar-file').hide();
        $('#toolbar-folder').show();
        data = { Action: 'Finder', Want: 'getArticles', Vars: '' };
        // Demande à ajax la liste des articles
        $.post('/ajax/index.php', data, function (resp) {
          if (resp.Errno != 0) {
            alert(resp.ErrMsg);
            return false;
          }
          // Vide le div
          var imgFiles = document.getElementById('img-files');
          imgFiles.innerHTML = "";
          var ul = document.createElement('ul');
          imgFiles.appendChild(ul);
          resp.Articles.forEach(function(article){
            // Ajoute l'entrée
            var li = document.createElement('li');
            li.id = 'a-'+article.id;
            li.innerHTML = article.sTitre;
            $(li).on("click",function(){
              newId = "a-"+article.id;
              doFinder('select-art',newId);
            });
            ul.appendChild(li);
          });
        });
        break;
    
    case 'afficher':
      sObject = document.getElementById('img-files').dataset.obj;
      sCurFolder = document.getElementById('img-files').dataset.cur;
      console.log(sObject+ " dans "+ sCurFolder);
      if( sCurFolder.substring(0,1) == 'f'){
        window.open(sObject);
      } else {
        createModalImage("img-display",sObject);
        document.getElementById('img-display').style.display='block';  
      }
      break;   
    
    case 'newFolder':
      sFolder = document.getElementById('img-files').dataset.cur;
      sCurId = sFolder;
      sFolder=sFolder.substring(1);
      sCurFolder=sFolder.replace(/-/g,"/");
      sCurFolder=sCurFolder.replace("//","/");
      if( sCurId.substring(0,1) == 'i' ){
        sType = "img";
        sCurFolder=sCurFolder.replace("/media/images","/");
      } else {
        sType = "file";
        sCurFolder=sCurFolder.replace("/media/documents","/");
      }
      newFolder = prompt("Nom du répertoire");
      console.log('Créer '+newFolder+" dans "+sCurFolder);
      sObject='{"Type":"'+sType+'","Path":"'+sCurFolder+'","Folder":"'+newFolder+'"}';
      data = { Action: 'Finder', Want: 'addFolder', Vars: sObject };
      $.post('/ajax/index.php',data,function(resp){
        if(resp.Errno != 0){
          alert(resp.ErrMsg);
        }
        if( sCurId.substring(0,1) == 'i' ){
          doFinder('refresh-img',sCurId);
        } else {
          console.log("Refresh-file "+sCurId);
          doFinder('refresh-file',sCurId);
        }
      });
      break;

  case 'trash-folder':
      sFolder = document.getElementById('img-files').dataset.cur;
      sFolder=sFolder.substring(1);
      sCurFolder=sFolder.replace(/-/g,"/");
      sCurFolder=sCurFolder.replace("//","/");
      if( sFolder.substring(0,1) == "i"){
        sType="img";
        sCurFolder=sCurFolder.replace("/media/images","/");
      } else {
        sType="folder";
        sCurFolder=sCurFolder.replace("/media/documents","/");
      }
      if( sCurFolder == "/" ){
        alert("ERREUR: il n'est pas possible de supprimer la racine !");
        return false;
      }
      if( confirm("Supprimer "+sCurFolder+ " ainsi que ses sous-répertoires ?") ){
        console.log('Suppression');        
        sObject='{"Path":"'+sCurFolder+'","Type":"+sType+"}';
        data = { Action: 'Finder', Want: 'delFolder', Vars: sObject };
        $.post('/ajax/index.php',data,function(resp){
          if(resp.Errno != 0){
            alert(resp.ErrMsg);
          }
          id = document.getElementById('img-files').dataset.cur;
          id = ''+id.match(/.*-/);
          id = id.substring(0,id.length-1);
          if( sType == 'img' ){
            doFinder('refresh-img',id);
          } else {
            doFinder('refresh-file',id);
          }
        });
      }
      break;
    
    default:
      console.log("Dans doFinder(" + action + ") indéfini");
      break;
  }
  return false;
}

/* =====================================================================================
  =                                   Edition des pseudo tables                        =
  ======================================================================================*/
/**
 * editParameters
 * 
 * Ouvre un popup qui permet d'éditer les pseudo tables et les paramètres
 */
function editParameters() {
  // Charger le Popup formulaire
  $('#holder').load('/tools/templates/popup.smarty', function () {
    // Changer le titre
    $('#pop-title').text('Edition des paramètres');
    // Charger le contenu
    $('#pop-content').load('/tools/templates/parameters.smarty');
    $("#pop-foot").html("");
    // Bouton  Quitter
    var Btn = document.createElement('button');
    Btn.id = "Quit";
    Btn.innerHTML = "<span class='glyphicon  glyphicon-log-out'> Quitter";
    Btn.className = "btn btn-secondary";
    // Ce bouton ferme le popup
    Btn.setAttribute('data-dismiss', 'modal');
    $("#pop-foot").append(Btn);
    // charger les tables
    data = {
      Action: "paramTables"
    };
    $.post('/ajax/index.php', data, function (resp) {
      // TODO bug, parfois aRecords peut ne pas être chargé
      aRecords = resp;
      aRecords.Idx = 0;
      // Attendre que la fenêtre modale soit chargée, pour afficher les éléments
      $('#pop-all').on("show.bs.modal", function () {
        var first = true;  // pour sélectionner la première table
        aRecords.forEach(function (item) {
          // pour chacune des entrées de sys_parameter où name='pseudo'
          if (item.type == 'pseudoTable') {
            // type == 'pseudoTable' signifie qu'on désigne une table, ajout dans select
            html = "<option value='" + item.id + "'>" + item.value + "</option>"
            $('#selectTable').append(html);
            if (first) {
              // première table -> extraire la description
              first = false;
              $('#tableDesc').val(item.description);
              // ajouter les entrées de la table dans le selectItem
              sSearch = 'table' + item.value;
              aRecords.forEach(function (item2) {
                if (item2.link == sSearch) {
                  html2 = "<option value='" + item2.id + "'>" + item2.value + "</option>";
                  $('#selectItem').append(html2);
                }
              });
            }
          }
        });
      });
      // Le popup est prêt, on l'affiche
      $('#pop-all').modal('show');
    });
  });
}

/**
 * updateParameter
 * 
 * Mise à jour de la table affichée dans le modal
 * 
 * @parameter {void}
 * @return    {void}
 */
function updateParameter() {
  var id; // @var int id 
  var sSearch; // @var string  nom de la table à chercher
  // Récupérer l'Id
  id = $('#selectTable :selected').val();
  // Récupérer le nom de la pseudo table
  aRecords.forEach(function (item) {
    if (item.id == id) {
      sSearch = 'table' + item.value;
      $('#tableDesc').val(item.description);
    }
  });
  // vider la liste
  $('#selectItem').empty();

  aRecords.forEach(function (item2) {
    if (item2.link == sSearch) {
      html2 = "<option value='" + item2.id + "'>" + item2.value + "</option>";
      $('#selectItem').append(html2);
    }
  });

}

/**
 * editParameter
 * 
 * Modifie un élément d'une pseudo table 
 * 
 * @parameter {string}    type : <add|del|edit> action à réaliser
 * @return    {void}
 */
function editParameter(type) {
  if (type == 'del') {
    id = $('#selectItem :selected').val();
    aRecords.forEach(function (item) {
      if (item.id == id) {
        valeur = item.value;
      }
    });
    ret = confirm("Effacer " + valeur + " ?");
    updateParameters('del', id, '', '');
  }
  if (type == 'add') {
    idTable = $('#selectTable :selected').val();
    aRecords.forEach(function (item) {
      if (item.id == idTable) {
        table = item.value;
      }
    });
    ret = prompt("Nouvelle valeur");
    updateParameters('add', 0, ret, table);
  }
  if (type == 'edit') {
    id = $('#selectItem :selected').val();

    aRecords.forEach(function (item) {
      if (item.id == id) {
        valeur = item.value;
      }
    });
    ret = prompt("Nouvelle valeur", valeur);
    updateParameters('edit', id, ret, '');
  }
}

function updateParameters(type, id, value, table) {
  data = {
    Action: 'updateParameters',
    Type: type,
    Id: id,
    Value: value,
    Table: table
  }
  $.post('/ajax/index.php', data, function (data) {
    aRecords = data;
    refreshParameters($('#selectTable :selected').val());
  });
}

function refreshParameters(id) {
  $('#selectItem').empty();
  aRecords.forEach(function (item) {
    if (item.id == id) {
      sSearch = 'table' + item.value;
    }
  });
  aRecords.forEach(function (item) {
    if (item.link == sSearch) {
      html = "<option value='" + item.id + "'>" + item.value + "</option>";
      $('#selectItem').append(html);
    }
  });
}

/* =================================================================================================
 =                                     Gestion des news                                            =
 ===================================================================================================*/
function openEditor(params) {
  $('#myPopup').text(params.Title);
  data = {
    Action: params.Action,
    Want: 'loadHeader',
    Vars: JSON.stringify(params.Vars)
  };
  $.post("/ajax/index.php", data, function (resp) {
    if (resp.Errno != 0) {
      alert(resp.ErrMsg);
      return false;
    } else {
      $("#before-editor").html(resp.html);
    }
    data = {
      Action: params.Action,
      Want: params.Want,
      Vars: params.Vars
    };
    $.post('/ajax/index.php', data, function (resp) {
      if (resp.Errno != 0) {
        alert(resp.ErrMsg);
        return false;
      }
      $aRecords = resp;
      resp.news.forEach(function (option) {
        html = "<option value='" + option.id + "'>" + option.date + " - " + option.titre + "</option>";
        $('#selectArt').append(html);
      });
      $('#Popup').on('show.bs.modal', function () {
        $('[data-toggle="tooltip"]').tooltip();
      });
      $('#Popup').modal({
        focus: false,
        show: true
      });
    });
  });
}

function editNews() {
  data = {
    Title: "Edition des news",
    Action: "doNews",
    Want: "listNews",
    Vars: {
      selectLabel: "Choisir une nouvelle",
      dispatch: "doNews",
      buttons: [{
        Action: 'add',
        Title: 'Ajouter une nouvelle',
        Glyph: 'plus'
      }, {
        Action: 'del',
        Title: 'Supprimer la nouvelle',
        Glyph: 'trash'
      }, {
        Action: 'edit',
        Title: 'Editer le titre',
        Glyph: 'pencil'
      }, {
        Action: 'load',
        Title: "Ouvrir dans l'éditeur",
        Glyph: 'open'
      }, {
        Action: 'save',
        Title: "Enregistrer le contenu",
        Glyph: 'save' 
      }]
    }
  }
  openEditor(data);
}

/**
 * doNews
 * 
 * Agit sur une nouvelle en fonction de la demande
 * @param {string} action <add|del|edit|load|save>
 */
function doNews(action) {
  switch (action) {
    case 'add':
      titre = prompt('Nom de la nouvelle');
      data = {
        Action: 'doNews',
        Want: 'add',
        Vars: titre
      };
      $.post('/ajax/index.php', data, function (resp) {
        // Ajouter option
        html = "<option value='" + resp.id + "'>" + resp.Display + "</option>";
        $('#selectArt').prepend(html);
      });
      break;

    case 'del':
      data = {
        Action: 'doNews',
        Want: 'del',
        Vars: $('#selectArt :selected').val()
      };
      $.post('/ajax/index.php', data);
      $('#selectArt :selected').remove();
      break;

    case 'edit':
      text = $('#selectArt :selected').text();
      date = text.substring(0, 21);
      titre = text.substring(24);
      newTitre = prompt('Titre', titre);
      sVars = JSON.stringify({ Titre: newTitre, id: $('#selectArt :selected').val() });
      data = {
        Action: 'doNews',
        Want: 'edit',
        Vars: sVars
      };
      $.post('/ajax/index.php', data);
      $('#selectArt :selected').text(date + " - " + newTitre);
      break;

    case 'load':
      //console.log("Choisi"+$('#selectArt :selected').val());
      data = {
        Action: 'doNews',
        Want: 'load',
        Vars: $('#selectArt :selected').val()
      };
      $.post('/ajax/index.php', data, function (resp) {
        if (resp.Errno != 0) {
          alert(resp.ErrMsg);
        }
        CkEditor.setData(resp.html);
      });
      break;

    case 'save':
      Vars = {
        id: $('#selectArt :selected').val(),
        Contenu: CkEditor.getData()
      }
      sVars = JSON.stringify(Vars);
      data = {
        Action: 'doNews',
        Want: 'save',
        Vars: sVars
      };
      $.post('/ajax/index.php', data);
      break;

    default:
      alert(action);
      break;
  }
}

/* ==================================================================================================
   =                                  Gestion des utilisateurs                                      =
   ==================================================================================================*/
var uid = 0;          // @var int user id
var user;           // @var objet utilisateur sélectionné
var zipCodes = [];    // Tableau des codes postaux   

function createModalImage(idModal, imgSrc)
{
  var div = document.getElementById(idModal);
  if( div == undefined ){
    var div = document.createElement('div');
    div.className="modal-img";
    div.id = idModal;
    var span = document.createElement('span');
    span.className = "close-img";
    span.id='close-img';
    span.innerHTML = "&times;";
    span.onclick= function(){
      this.parentNode.style.display = 'none';
    };
    div.appendChild(span);
    img = document.createElement('img');
    img.className="modal-content-img";
    img.id=idModal+"img";
    img.src=imgSrc;
    div.appendChild(img);
    // <div id=caption>/div>
    document.body.appendChild(div);  
  }
  else {
    var img = document.getElementById(idModal+"img");
    img.src=imgSrc;
  }
}

function getZip(num) {
  value = '';
  // TODO add running
  switch (num) {
    case '44230':
      value = "SAINT SEBASTIEN/LOIRE";
      break;
    case '44115':
      value = "BASSE GOULAINE";
      break;
    case '44400':
      value = 'REZE';
      break;
  }
  if (value != "") {
    $('#city').val(value);
  }
}

function dateConvert(sDate) {
  ret = sDate.substring(8, 2) + "/" + sDate.substring(5, 2) + "/" + sDate.substring(0, 4);
  ret += " à " + sDate.substring(11);
  return ret;
}

/* ============================================================================================
   =                            Gestion du menu                                               =
  =============================================================================================*/
function editMenu() {
  Data = {
    dialogClass: '',
    popTitle: 'Gestion du menu (non fonctionnel)',
    popContentClass: 'myModal-body editMenu',
    Dispatch: 'menuAction',
    Buttons: [{
      Type: 'info',
      Glyph: 'plus',
      Label: 'Ajouter',
      Want: "menuAction('add');"
    }, {
      Type: 'info',
      Glyph: 'pencil',
      Label: 'Editer',
      Want: "menuAction('edit');"
    }, {
      Type: 'secondary',
      Glyph: 'log-out',
      Label: 'Quitter',
      Want: ''
    }],
    Content: {
      Action: 'doMenu',
      Want: 'listMenu'
    }
  };
  openPopup(Data);
}

/**
 * 
 * @param {object} oData 
 */
function openPopup(oData) {
  // Load Popup
  $("#content").load("/tools/templates/popup.smarty", function () {
    $(".modal-dialog").addClass(oData.dialogClass);
    $("#pop-title").text(oData.popTitle);
    $("#pop-content").addClass(oData.popContentClass);
    $('#pop-foot').html('');

    oData.Buttons.forEach(function (Btn) {
      var Button = document.createElement('button');
      Button.setAttribute("type", "button");
      Button.className = "btn btn-" + Btn.Type;
      Button.innerHTML = "<span class='glyphicon glyphicon-" + Btn.Glyph + "'> " + Btn.Label;
      Button.setAttribute("onClick", Btn.Want);
      if (Btn.Glyph == 'log-out') {
        Button.setAttribute("data-dismiss", "modal");
      }
      $("#pop-foot").append(Button);
    });

    data = {
      Action: oData.Content.Action,
      Want: oData.Content.Want,
    };
    console.log(data);
    $.post('/ajax/index.php', data, function (resp) {
      if (resp.Errno != 0) {
        alert(resp.ErrMsg);
      }
      $("#pop-content").html(resp.html);
      $('#pop-all').modal({
        focus: false,
        show: true
      });
    });

  });
}

/* ==========================================================================================================
   =                                 Gestion des articles                                                   =
   ==========================================================================================================*/

function gestArticle(Action, Params) {
  if (Action == undefined) {
    console.log('Depuis le menu');
    Action = 'init';
  }

  switch (Action) {
    case 'init':
      data = {
        Title: "Edition des articles",
        Action: "gestArticle",
        Want: "listArticles",
        Vars: {
          selectLabel: "Choisir un article",
          dispatch: "gestArticle",
          buttons: [{
            Action: 'add',
            Title: 'Ajouter un article',
            Glyph: 'plus'
          }, {
            Action: 'del',
            Title: 'Supprimer un article',
            Glyph: 'trash'
          }, {
            Action: 'edit',
            Title: 'Editer le titre',
            Glyph: 'pencil'
          }, {
            Action: 'load',
            Title: "Ouvrir dans l'éditeur",
            Glyph: 'open'
          }, {
            Action: 'save',
            Title: "Enregistrer le contenu",
            Glyph: 'save'
          }]
        }
      }
      openEditor(data);
      break;

    case 'add':
      titre = prompt("Nom de l'article'");
      data = {
        Action: 'gestArticle',
        Want: 'add',
        Vars: titre
      };
      $.post('/ajax/index.php', data, function (resp) {
        if (resp.Errno != 0) {
          alert(resp.ErrMsg);
          return false;
        }
        // Ajouter option
        html = "<option value='" + resp.id + "'>" + resp.Display + "</option>";
        $('#selectArt').prepend(html);
      });
      break;

    case 'del':
      data = {
        Action: 'gestArticle',
        Want: 'del',
        Vars: $('#selectArt :selected').val()
      };
      $.post('/ajax/index.php', data);
      $('#selectArt :selected').remove();
      break;

    case 'edit':
      text = $('#selectArt :selected').text();
      date = text.substring(0, 21);
      titre = text.substring(24);
      newTitre = prompt('Titre', titre);
      sVars = JSON.stringify({ Titre: newTitre, id: $('#selectArt :selected').val() });
      data = {
        Action: 'gestArticle',
        Want: 'edit',
        Vars: sVars
      };
      $.post('/ajax/index.php', data);
      $('#selectArt :selected').text(date + " - " + newTitre);
      break;

    case 'load':
      //console.log("Choisi"+$('#selectArt :selected').val());
      data = {
        Action: 'gestArticle',
        Want: 'load',
        Vars: $('#selectArt :selected').val()
      };
      $.post('/ajax/index.php', data, function (resp) {
        if (resp.Errno != 0) {
          alert(resp.ErrMsg);
        }
        CkEditor.setData(resp.html);
      });
      break;

    case 'save':
      Vars = {
        id: $('#selectArt :selected').val(),
        Contenu: CkEditor.getData()
      }
      sVars = JSON.stringify(Vars);
      data = {
        Action: 'gestArticle',
        Want: 'save',
        Vars: sVars
      };
      $.post('/ajax/index.php', data);
      break;

    default:
      console.log('Action ' + Action + " non définie dans gestArticles");
      break;
  }
}   

/**
 * createImage
 * 
 * Crée une entrée image dans le finder
 * @param {string} sFileName    chemin relatif de l'image
 */
function createImage(sFileName)
{
  var div = document.createElement('div');
  $('#img-files').append(div);
  var a = document.createElement('a');
  a.setAttribute('href','#');
  a.className='img-thumb';
  div.appendChild(a);
  var img = document.createElement('img');
  img.setAttribute('src',sFileName);
  a.appendChild(img);
  var div2 = document.createElement('div');
  div2.innerText=sFileName.replace(/.*\//, ''); 
  div2.className='img-txt';
  a.appendChild(div2);
  // TODO a.addEventListener("click",doFinder('refresh-img', data));
  
  $(a).on("click",function(){
    doFinder('select-img',sFileName);
  });
}

/**
 * createFolder, crée le div où se site le chemin
 * 
 * 
 * @param {string} path 
 * @param {string} folder 
 */
function createFolder(sType,idParent,path,folder)
{
  var li = document.createElement('li');
  li.innerText=folder;
  fullPath = path+folder;
  id = sType+fullPath.replace(/\//g, '-');
  li.id= id;
  if( sType == 'i'){
    li.setAttribute("onclick","doFinder('refresh-img', '"+id+"')");
  } else {
    li.setAttribute("onclick","doFinder('refresh-file', '"+id+"')");
  }
  document.getElementById(idParent).appendChild(li);
}

/**
 * createFile
 * 
 * Crée une entrée document dans le finder
 * @param {string} sFileName    chemin relatif du document
 */
function createFile(sFileName)
{
  var div = document.createElement('div');
  $('#img-files').append(div);
  var a = document.createElement('a');
  a.setAttribute('href','#');
  a.className='img-thumb';
  div.appendChild(a);
  var img = document.createElement('img');
  fileType = sFileName.substr(sFileName.length-3);
  img.setAttribute('src','/assets/images/filetype/'+fileType+'File.png');
  a.appendChild(img);
  var div2 = document.createElement('div');
  div2.innerText=sFileName.replace(/.*\//, '');
  div2.className='img-txt';
  a.appendChild(div2);
  // TODO a.addEventListener("click",doFinder('refresh-img', data));
  
  $(a).on("click",function(){
    doFinder('select-file',sFileName);
  });
}

/**
 * gestion des mail
 */
function sendMail()
{
  $('#send-mail').modal('show');
}

