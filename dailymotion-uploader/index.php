<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Dailymotion Remote Uploader - mr.tranquocdai@gmail.com</title>
<meta name="description" content="This app allows you to upload video files to Dailymotion directly. You can upload your video files to Dailymotion and very easily without using Dailymotion web interface.">
<meta name="keywords" content="dailymotion video upload, dailymotion uploader, online dailymotion uploader, cloud dailymotion uploader">
<meta property="og:title" content="Dailymotion Remote Uploader"> 
<meta property="og:description" content="This app allows you to upload video files to Dailymotion directly. You can upload your video files to Dailymotion and very easily without using Dailymotion web interface.">
<meta property="og:type" content="website">
<meta property="og:url" content="//tool.tranquocdai.com/daily">
<meta property="og:image" content="//dailymotion.tranquocdai.com/img/logo128.png">
<link rel="shortcut icon" href="favicon.ico">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link rel='stylesheet' href='//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' />
<link rel='stylesheet' href='//cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css' />
<script type='text/javascript' src='//code.jquery.com/jquery-3.2.1.min.js'></script>
<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js'></script>
<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.min.js'></script>
<script type='text/javascript' src='//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script type='text/javascript' src='//cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js'></script>
</head>
<body>
    <div id="main" class="container"> 
        <h1>Dailymotion Upload video!</h1>
		<div class="row clearfix">
		<div class="col-md-12 column">
        <form method="post" enctype="multipart/form-data"  action="dmupload.php" id="upload_form">
			<label>API:</label>
            <input type="text" class='form-control' placeholder="Your apikey" name="apikey" id="apikey" /> 
			<br/><br/>
			<label>API Secret:</label>
            <input type="text" class='form-control' placeholder="Your apiSecret"  name="apiSecret" id="apiSecret" /> 
			<br/><br/>
			<label>Email:</label>
            <input type="text" class='form-control' placeholder="Your Email Channel" name="user" id="user" /> 
			<br/><br/>
			<label>Pass:</label>
            <input type="password" class='form-control' placeholder="Your Channel Password" name="passw" id="passw" /> 
			<br/><br/>
			<label>Title:</label>
            <input type="text" class='form-control' placeholder="Title Video" name="title_file" id="title_file" /> 
			<br/><br/>
			<label>Description:</label>
			<textarea class='form-control' name='videoDescription' id='videoDescription'>Dailymotion PHP SDK upload by https://tool.tranquocdai.com/daily/</textarea>
			<br/><br/>
			<label>Tags:</label>
			<input type='text' name='tags' id='tags' class='form-control' data-role='tagsinput' />
			<br/><br/>
			<label>Upload File:</label>
			<input type="file" name="upload_file" id="upload_file" />
			<br/><br/>
            <button type="submit" id="btn">Upload</button> 
        </form>
		<div id="progress-wrp"><div class="progress-bar"></div ><div class="status">0%</div></div>
		<div id="output"><!-- error or success results --></div>
		<br/><br/>
		<br/><br/>
		</div>
		</div>
    </div>
<script type="text/javascript">    
//configuration
var max_file_size 			= 314572800; //300MB allowed file size. (1 MB = 1048576)
var allowed_file_types 		= ['video/mp4','image/png', 'image/gif', 'image/jpeg', 'image/pjpeg']; //allowed file types
var result_output 			= '#output'; //ID of an element for response output
var my_form_id 				= '#upload_form'; //ID of an element for response output
var progress_bar_id 		= '#progress-wrp'; //ID of an element for response output
var total_files_allowed 	= 1; //Number files allowed to upload

//on form submit
$(my_form_id).on( "submit", function(event) { 
	event.preventDefault();
	var proceed = true; //set proceed flag
	var error = [];	//errors
	var total_files_size = 0;
	//reset progressbar
	$(progress_bar_id +" .progress-bar").css("width", "0%");
	$(progress_bar_id + " .status").text("0%");
	if(!window.File && window.FileReader && window.FileList && window.Blob){ //if browser doesn't supports File API
		error.push("Your browser does not support new File API! Please upgrade."); //push error text
	}else{
		var total_selected_files = this.elements['upload_file'].files.length; //number of files
		
		//limit number of files allowed
		if(total_selected_files > total_files_allowed){
			error.push( "You have selected "+total_selected_files+" file(s), " + total_files_allowed +" is maximum!"); //push error text
			proceed = false; //set proceed flag to false
		}
		 //iterate files in file input field
		$(this.elements['upload_file'].files).each(function(i, ifile){
			if(ifile.value !== ""){ //continue only if file(s) are selected
				if(allowed_file_types.indexOf(ifile.type) === -1){ //check unsupported file
					error.push( "<b>"+ ifile.name + "</b> is unsupported file type!"); //push error text
					proceed = false; //set proceed flag to false
				}

				total_files_size = total_files_size + ifile.size; //add file size to total size
			}
		});
		
		//if total file size is greater than max file size
		if(total_files_size > max_file_size){ 
			error.push( "You have "+total_selected_files+" file(s) with total size "+total_files_size+", Allowed size is " + max_file_size +", Try smaller file!"); //push error text
			proceed = false; //set proceed flag to false
		}
		
		var submit_btn  = $(this).find("input[type=submit]"); //form submit button	
		
		//if everything looks good, proceed with jQuery Ajax
		if(proceed){
			submit_btn.val("Please Wait...").prop( "disabled", true); //disable submit button
			var form_data = new FormData(this); //Creates new FormData object
			var post_url = $(this).attr("action"); //get action URL of form
			
			//jQuery Ajax to Post form data
$.ajax({
	url : post_url,
	type: "POST",
	data : form_data,
	contentType: false,
	cache: false,
	processData:false,
	xhr: function(){
		//upload Progress
		var xhr = $.ajaxSettings.xhr();
		if (xhr.upload) {
			xhr.upload.addEventListener('progress', function(event) {
				var percent = 0;
				var position = event.loaded || event.position;
				var total = event.total;
				if (event.lengthComputable) {
					percent = Math.ceil(position / total * 100);
				}
				//update progressbar
				$(progress_bar_id +" .progress-bar").css("width", + percent +"%");
				$(progress_bar_id + " .status").text(percent +"%");
			}, true);
		}
		return xhr;
	},
	mimeType:"multipart/form-data"
}).done(function(res){ //
	$(my_form_id)[0].reset(); //reset form
	$(result_output).html(res); //output response from server
	submit_btn.val("Upload").prop( "disabled", false); //enable submit button once ajax is done
});
		}
	}
	$(result_output).html(""); //reset output 
	$(error).each(function(i){ //output any error to output element
		$(result_output).append('<div class="error">'+error[i]+"</div>");
	});
		
});
</script>
 </body>
</html>
