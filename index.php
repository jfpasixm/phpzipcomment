<?php
$f = "visit.php";
if(!file_exists($f)){
	touch($f);
	$handle =  fopen($f, "w" ) ;
	fwrite($handle,0) ;
	fclose ($handle);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Create Zip Archive with Comment</title>
		<link href="img/favicon.ico" rel="icon" type="image">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/navbarclock.js"></script>
    </head>
	<body onload="startTime()">
		<nav class="navbar-inverse" role="navigation">
			<a href="https://www.facebook.com/MrNiemand03" target="_blank">
				<img src="img/niemand.png" class="hederimg">
			</a>
			<div id="clockdate">
				<div class="clockdate-wrapper">
					<div id="clock"></div>
					<div id="date"><?php echo date('l, F j, Y'); ?></div>
				</div>
			</div>
			<div class="pagevisit">
				<div class="visitcount">
					<?php
					$handle = fopen($f, "r");
					$counter = ( int ) fread ($handle,20) ;
					fclose ($handle) ;
					
					if(!isset($_POST['submit'])){
						$counter++ ;
					}
					
					echo "This page has been visited ".$counter." time";
					echo ($counter > 1) ? "s":"";
					
					$handle =  fopen($f, "w" ) ;
					fwrite($handle,$counter) ;
					fclose ($handle) ;
					?>
				</div>
			</div>
		</nav>
		<div class="col-md-4"></div>
		<div class="col-md-4 well maincode" style="margin-top:110px;">
			<strong class="codetitle" style="text-align:center;">
				PHP - Create Zip Archive with Comment
			</strong>
			<hr style="border-top:1px dotted #ccc;"/>
			<div>
				<form method="POST" enctype="multipart/form-data" class="form-inline">
					<div class="form-group">
						<input class="form-control" type="file" name="upload[]" multiple />
					</div>
					<input type="submit" class="btn btn-primary" name="submit" value="Archive">
				</form>
			</div>
			<br/>
			<?php
				if(ISSET($_POST['submit'])){
					if(array_sum($_FILES['upload']['error']) > 0){
						echo "No Selected File";
					}else{
						$zipname = "FILE-".date('njyHis').".zip";
						$archive = new ZipArchive();
						$archive->open($zipname, ZipArchive::CREATE);
						$files = $_FILES['upload'];
						
						for($i = 0; $i < count($files['name']); $i++){
							$tmp_name = $files['tmp_name'][$i];
							$filename = $files['name'][$i];
							
							move_uploaded_file($tmp_name, $filename);
							
							$archive->addFile("$filename");
						}
						$archive->setArchiveComment(file_get_contents("comment.txt"));
						$archive->close();
						
						header('Content-type: application/zip');
						header('Content-Disposition: attachment; filename="'.$zipname.'"');
						readfile($zipname);
						
						foreach($files['name'] as $filedel){
							unlink($filedel);
						}
						
						unlink($zipname);
						echo "Successfully compressed the file";
					}
				}
			?>
		</div>
	</body>	
</html>