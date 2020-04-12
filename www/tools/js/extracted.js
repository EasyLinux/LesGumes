// Code extrait 
// Obsolète ou pas utilisé
function insertSQL() {
  var description = $("#description").val();
  var version = $("#version").val();
  var sql = $("#sql").val();
  data = {
    Action: "SQL",
    Desc: description,
    Version: version,
    SQL: sql
  };
  $.post("/tools/index.php", data);

}

function getSQL() {
  var version = $("#version").val();
  data = {
    Action: "getSQL",
    Version: version,
  };
  $.post("/tools/index.php", data,
    function (data, status) {
      $("#sql").text(data);
    });

}

