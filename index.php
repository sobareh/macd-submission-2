<?php
require_once 'vendor\autoload.php';
require_once "random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=orloviwebapp;AccountKey=bs+NMtJ/DHc3av/Ktur+yXZHVB00m6yfchqRtITmBcCyLhZJFuoqEW//EnTDxjfNDzAW3WhRNNEukV9JHMeS1A==;EndpointSuffix=core.windows.net";
$blobClient = BlobRestProxy::createBlobService($connectionString);
$containerName = "orlovicontainer";
	
if (isset($_POST['submit'])) {
	$fileToUpload = $_FILES["fileToUpload"]["name"];
	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
	echo fread($content, filesize($fileToUpload));
		
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	header("Location: index.php");
}	
	
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!DOCTYPE html>
    <html>
    <head>
        <title>Analisa Gambar</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    </head>
    <body>
    <div class="container">
    <h1>Analisa Gambar dengan Azure Computer Vision</h1>
    <hr>
    <div class="form-group mt-4 mb-2">
				<form class="d-flex justify-content-left" action="index.php" method="post" enctype="multipart/form-data">
                    <label for="exampleinput">Pilih Gambar yang akan di Analisa</label>    
                    <input type="file" name="fileToUpload" id="fileToUpload" required>
                </div>
                <button class="btn btn-primary" type="submit" name="submit">Upload</button>
                </form>
    <br><br>
    <table class="table table-bordered">
					<thead>
						<tr class="info">
                            <th class="text-center">Nama File</th>
                            <th class="text-center">Url Alamat File</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						do {
							foreach ($result->getBlobs() as $blob) {
						?>						
						<tr>
                            <td><?php echo $blob->getName() ?></td>
                            <td><?= $blob->getUrl()?></td>
							<td>
								<form action="hasilanalisa.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="Hasil Analisa" class="btn btn-primary">
								</form>
							</td>
						</tr>
						<?php
							} $listBlobsOptions->setContinuationToken($result->getContinuationToken());
						} while($result->getContinuationToken());
						?>
					</tbody>
    </table>
    </div>
    </body>
    </html>