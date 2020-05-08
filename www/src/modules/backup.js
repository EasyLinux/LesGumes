import * as modal from './modal.js';

export function switcher(action, params)
{
  switch(action)
  {
    case 'backupAll':
      Backup();
      break;

    case 'restore':
      Restore();
      break;

    case 'restoreNow':
      restoreNow();
      break;

    default:
      alert(action+" pas implémenté dans backup.js");
  }
}

/*==========================================================================================
 =                               Sauvegarde / Restauration                                 =
 ===========================================================================================*/
// TODO Mettre un progress
function Backup() {
  $("#backup-buttons").fadeOut('slow');
  data = {
    Action: 'doBackup',
    Want: 'makeIt',
    Type: '',
    id: ''
  }
  modal.alertBox('info', "Sauvegarde en cours", "INFO", 50000);

  $.post("/ajax/index.php", data, function (data) {
    modal.alertBox('success', "Sauvegarde terminée", "SUCCESS", 50000);
    $("#sub-msg-2").html("Fichier de sauvegarde : <a href='" + data.File + "'>" + data.File + "</a>");
  });
}

/**
 * Restore
 * 
 * Affiche les options de restauration
 * 
 * @param   void
 * @return  void
 */
function Restore() {
  loadBackupList();
  $("#backup-buttons").fadeOut('slow');
  $("#restore-options").fadeIn();
  $('#loadBackup').change(function () {
    var file_data = $('#loadBackup').prop('files')[0];
    var form_data = new FormData();
    if (file_data.type != "application/zip") {
      alert("Ce n'est pas un fichier.zip");
      return false;
    }
    form_data.append('file', file_data);
    form_data.append('Action', 'loadFile');
    form_data.append('Where', '/_Backup/');
    $.ajax({
      url: "/ajax/index.php",
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      type: 'post',
      success: function () {
        loadBackupList();
      }
    });
  });

}

/**
 * loadBackupList
 * 
 * Charge la liste selectBackup avec la liste des fichiers de sauvegarde
 * présents dans ./_Backup 
 * @param   void
 * @return  void
 */
function loadBackupList() {
  data = {
    Action: 'doBackup',
    Want: 'loadBackupList',
    Type: '',
    id: 0
  };
  $.post('/ajax/index.php', data, function (data) {
    var listitems;
    data.forEach((item) => {
      listitems += '<option value=' + item.id + '>' + item.label + '</option>';
    });
    $("#selectBackup").append(listitems);

  });
}

/**
 * restoreNow
 * 
 * Lance la restauration du fichier choisi
 */
function restoreNow() {
  if ($('#selectType').val() == 0) {
    alert("Veuillez choisir un type de restauration !");
    return false;
  }
  if ($('#selectBackup option:selected').val() == undefined) {
    alert('Choisissez une sauvegarde à restaurer');
    return false;
  }
  $("#sub-msg-1").text("Restauration en cours ...");
  data = {
    Action: "doBackup",
    Want: "doRestore",
    Type: $('#selectType').val(),
    id: $('#selectBackup option:selected').val()
  }
  $.post('/ajax/index.php', data, function (data) {
    $("#sub-msg-1").text("Restauration terminée");
    $("#sub-msg-2").html("Statut: " + data.ErrMsg);
  });
}

