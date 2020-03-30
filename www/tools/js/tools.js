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
          console.log("Etat: "+ status);
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
          console.log("Etat: "+ status);
        });
      
}

function ActiveEditor()
{
  ClassicEditor
			.create( document.querySelector( '.editor' ), {
        ckfinder: {
          uploadUrl: '/tools/ckfinder.php?command=QuickUpload&type=Files&responseType=json',
        },
   
				// extraPlugins: [ myCustomUploadAdapterPlugin ],
				toolbar: {
					items: [
            'ckfinder',
						'heading',
						'|',
						'bold',
						'italic',
						'link',
						'bulletedList',
						'numberedList',
						'|',
						'indent',
						'outdent',
						'|',
						'imageUpload',
						'blockQuote',
						'insertTable',
						'mediaEmbed',
						'undo',
						'redo'
					]
				},
				language: 'fr',
				image: {
					toolbar: [
						'imageTextAlternative',
						'imageStyle:full',
						'imageStyle:side'
					]
				},
				table: {
					contentToolbar: [
						'tableColumn',
						'tableRow',
						'mergeTableCells',
						'tableCellProperties',
						'tableProperties'
					]
				},
				licenseKey: '',
				
			} )
			.then( editor => {
				window.editor = editor;
		
				
				
				
			} )
			.catch( error => {
				console.error( error );
      } );
      
}
 