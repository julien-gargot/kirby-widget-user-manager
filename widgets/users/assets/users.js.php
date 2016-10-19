<script type="text/javascript">

console.log("Widget JS works.");

var reader;
var request;

/*
 * displayMessage(var text = string)
 * Replace the widget text content. Usefull to pass informations to users.
 */
function displayMessage(message) {
  $('#message').html(message);
}

/*
 * csvToJson(var (string)strData, var (string)strDelimiter = ',', var (boolean)returnAsObject = false)
 * Convert a CSV string with a header to a JSON string (default) or object.
 */
function csvToJson( strData, strDelimiter, returnAsObject ){
  var strDelimiter = (strDelimiter || ",");
  var returnAsObject = (returnAsObject || false);

  var lines= strData.match(/[^\r\n]+/g);
  var result = [];
  var headers=lines[0].split(strDelimiter);

  for(var i=1;i<lines.length;i++){

	  var obj = {};
	  var currentline=lines[i].split(strDelimiter);

	  for(var j=0;j<headers.length;j++){
		  obj[headers[j]] = currentline[j];
	  }

	  result.push(obj);

  }

  if (returnAsObject) {
    return result; //JavaScript object
  }
  else {
    return JSON.stringify(result); //JSON
  }
}

/*
 * handleFileSelect()
 * Event process on file upload.
 */
function handleFileSelect(event) {
  console.log('handleFileSelect',event);

  // Get the file
  var f = event.target.files[0];

  // Only process CSV file.
  if (!f.type.match('text/csv')) {
    displayMessage('Please, use CSV file.');
    return;
  }

  // Reset progress indicator on new file selection.
  reader = new FileReader();

  // Handlers
  reader.onerror = function(e) {
    switch(e.target.error.code) {
      case e.target.error.NOT_FOUND_ERR:
        displayMessage('File not Found!');
        break;
      case e.target.error.NOT_READABLE_ERR:
        displayMessage('File is not readable');
        break;
      case e.target.error.ABORT_ERR:
        displayMessage('File read cancelled');
        break; // noop
      default:
        displayMessage('An error occurred reading this file.');
    };
  };
  reader.onabort = function(e) {
    displayMessage('File read cancelled');
  };
  reader.onloadstart = function(e) {
    displayMessage('File loading…');
  }
  reader.onload = function(e) {
    displayMessage('File processing…');
    var csv = csvToJson(e.target.result);
    sentDatas( csv );
  }

  // Read in the image file as a binary string.
  reader.readAsBinaryString(f);
}

/*
 * sentDatas(var (string)datas)
 * Sent datas to Kirby via AJAX.
 */
function sentDatas(datas) {
  // Abort any pending request
  if (request) {
    request.abort();
  }

  // Fire off the request
  request = $.ajax({
    url: '/' + '<?= c::get('management.import', 'import') ?>',
    type: "post",
    // data: {csrf: app.csrf(), datas: datas}
    data: {datas: datas}
  });

  // Callback handler that will be called on success
  request.done(function (response, textStatus, jqXHR){
    // Log a message to the console
    console.log('Ajax request done.', response, textStatus, jqXHR);
    // console.log('response', JSON.parse(response));
    displayMessage();
  });

  request.success(function (response, textStatus, jqXHR){
    // Log a message to the console
    console.log('Ajax request success.');
    // console.log('response', JSON.parse(response));
    displayMessage();
  });

  // Callback handler that will be called on failure
  request.fail(function (jqXHR, textStatus, errorThrown){
    // Log the error to the console
    console.log('Ajax request fail.');
    console.error(
      "The following error occurred: " +
      textStatus, errorThrown
    );
  });
}

$(document).on('click', '#users-widget a[title="Import"]', function(event) {
  event.preventDefault();
  /* Act on the event */
  console.log("Click on “import”.");
  $('input[type=file]').trigger('click');
});

$(document).on('change', 'input[type=file]', handleFileSelect);

/* TEST for a Kirby way to upload the message. */
// $('[href="#upload"]').attr('data-upload', true);
//
// $('#upload').uploader(function(data) {
//   console.log('uploader',data);
//   // app.content.reload();
// });
/* end TEST */
</script>
