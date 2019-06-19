<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Hasil Analisa</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</head>
	
	<body>
				
			<main class="container">
				<div class="starter-template">
					<br>
					<h1>Hasil Analisa Gambar</h1>
				</div>
				
				<script type="text/javascript">
					$(document).ready(function () {
						var subscriptionKey = "5a2a74802cdc47138a9ae7e52e5bcd75";
						var uriBase = "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
						
						// Request parameters.
						var params = {
							"visualFeatures": "Description",
							"details": "",
							"language": "en",
						};
						
						// Display the image.
						var sourceImageUrl = "<?= $url ?>";
						document.querySelector("#sourceImage").src = sourceImageUrl;
						
						// Make the REST API call.
						$.ajax({
							url: uriBase + "?" + $.param(params),
							
							// Request headers.
							beforeSend: function(xhrObj){
								xhrObj.setRequestHeader("Content-Type","application/json");
								xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
							},
							type: "POST",
							
							// Request body.
							data: '{"url": ' + '"' + sourceImageUrl + '"}',
						})
							.done(function(data) {
							
							// Show formatted JSON on webpage.
							$("#description").text(data.description.captions[0].text);
						})
							.fail(function(jqXHR, textStatus, errorThrown) {
							
							// Display error message.
							var errorString = (errorThrown === "") ? "Error. " :
							errorThrown + " (" + jqXHR.status + "): ";
							errorString += (jqXHR.responseText === "") ? "" :
							jQuery.parseJSON(jqXHR.responseText).message;
							alert(errorString);
						});
					});
				</script>
				
				<div id="wrapper" style="width:1020px; display:table;">
					<div id="imageDiv" style="width:420px; display:table-cell;">
						<b>Source Image:</b><br><br>
						<img id="sourceImage" width="300" /><br><br>
						<blockquote class="blockquote text-left">
                            <p class="mb-0" id="description"></p>
                        </blockquote>
					</div>
				</div>
	</body>
</html>