/**
 * 
 * @param {string}   parent 
 * @param {string}   id 
 * @param {string}   titre 
 * @param {string}   size     classe pour la taille de la boite modale <modal-xl||>
 */
export function createModal(parent, id, titre, size) {
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
export function addButtons(id, Btns)
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

/**
 * alertBox
 * 
 * Display an alert div 
 * @param {string} sType        type de message <danger|info|success> 
 * @param {string} sMessage     Message à afficher
 * @param {string} sTitre       Titre de la Box
 * @param {int} iDuration       Durée de l'affichage (optionnel 3000)
 */
export function alertBox(sType, sMessage, sTitre, iDuration) {
  if (typeof (iDuration) == 'undefined') {
    iDuration = 3000;
  }
  $("#ErrTitle").text(sTitre);
  $("#ErrMsg").html(sMessage);
  $("#ErrBox").removeClass('alert-success');
  $("#ErrBox").removeClass('alert-danger');
  $("#ErrBox").removeClass('alert-info');
  $("#ErrBox").addClass('alert-' + sType);
  $("#ErrBox").show();
  setTimeout(function () {
    $("#ErrBox").fadeOut(1000);
  }, iDuration);
}