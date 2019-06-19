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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
    <body>
    <div class="container">
    <h1>Analisa Gambar dengan Azure Computer Vision</h1>
    <hr>
    <div class="form-group">
				<form class="d-flex justify-content-left" action="index.php" method="post" enctype="multipart/form-data">
                    <label for="exampleinput">Pilih Gambar yang akan di Analisa</label>    
                    <input type="file" name="fileToUpload" id="fileToUpload" required>
                </div>
                <button class="btn btn-primary" type="submit" name="submit">Upload</button>
                </form>
    <br><br>
    <table class="table table-bordered table-sm">
					<thead>
						<tr class="thead-dark">
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