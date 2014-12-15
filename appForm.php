<p>heeey, you got herev :)</p>

<p>display table with the files from the drop box here</p>

<p>option to add more files here</p>



<div id="camera">
</div>

<div id="result"></div>

<script type="text/javascript">
 	Webcam.attach('#camera');
 	function take_snapshot(){
 		var data_uri = Webcam.snap();
 		/*Webcam.snap(function(data_uri){
 			document.getElementById('result').innerHTML = '<img src="'+data_uri+'" />';
 		});*/
 		
 		Webcam.upload( data_uri, 'index.php', function(code, text) {
        // Upload complete!
        // 'code' will be the HTTP response code from the server, e.g. 200
        // 'text' will be the raw response content
    	});

 	}
</script>

<a class="green ui button" href="javascript:void(take_snapshot())">Take Snapshot</a>