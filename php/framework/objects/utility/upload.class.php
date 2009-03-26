<?php
/*
 * Created on Jan 18, 2007
 * By Matt Travi
 */

class Uploader
{
	var $file_ref;
	var $file_name;
	var $file_type;
	var $related_id;
	var $directoryToPutFile;
	var $dbConnection;
	var $restrictedMimetypes = array();
	var $mimetype;
	var $filename_override;
	var $filename_prefix;
	var $insertIdQuery;
	var $finalQuery;

	function Uploader($file_ref,$directoryToPutFile)
	{
		$this->file_ref = $file_ref;
		$this->mimetype = $_FILES[$file_ref]['type'];

		$file_name = $_FILES[$file_ref]['name'];
		// strip file_name of slashes
		$file_name = stripslashes($file_name);
		$file_name = str_replace("'","",$file_name);
		$file_name = preg_replace('/[^a-z0-9_\-\.]/i', '_',$file_name);

		$this->file_name = $file_name;

		// append the slash to the end of the directory for later
		if(strrpos($directoryToPutFile,"/") != (strlen($directoryToPutFile)-1))
			$directoryToPutFile .= "/";
		$this->directoryToPutFile = $directoryToPutFile;

	}

	function upload($isImage=false)
	{
		if($this->acceptedMimetype($this->mimetype))
		{
			$this->checkDirectory();

			if ($_FILES[$this->file_ref]['error'] == 0)
			{
				if (is_uploaded_file($_FILES[$this->file_ref]['tmp_name']))
				{
				 	$this->buildQuery();

					if (copy($_FILES[$this->file_ref]['tmp_name'],$this->directoryToPutFile . $this->file_name))
					{
						//only read priviledges are needed for files
						chmod($this->directoryToPutFile . $this->file_name, 0644);

					 	$addlReqResults = $this->checkAdditionalRequirements();

						if($addlReqResults[0] == "good")
						{
							if(isset($this->dbConnection)  && isset($this->finalQuery))
							{
								mysql_query($this->finalQuery,$this->dbConnection) or die (mysql_error());

								if (mysql_affected_rows($this->dbConnection) == 1)
								{
									$status = "good";
									$msg = "The file was uploaded successfully";
								}
								else
								{
									//TODO: Should undo upload and db entries
									$status = "undo";
									$msg = "There was a problem with your upload";
								}
							}
							else
							{
								$status = "good";
								$msg = "The file was successfully uploaded";
							}
						}
						else
						{
							list($status,$msg) = $addlReqResults;
						}
					}
					else
					{
						//TODO: Should undo entry in db
					 	$status = "undo";
						$msg = "There was a problem with your upload";
					}
			 	}
			 	else
			 	{
				 	$status = "bad";
				 	$msg = uploadError($_FILES[$this->file_ref]['error']);
			 	}
			}
			else
			{
			 	if ($_FILES[$this->file_ref]['error'] > 0)
			 	{
					$status = "bad";
					$msg = uploadError($_FILES[$this->file_ref]['error']);
			 	}
			}
		}
	 	else
	 	{
			$status = "bad";
			$msg = "Unsupported Filetype.";

	  		return array($status,$msg);
	 	}

		if($status == "undo")
		{
			if(file_exists($this->directoryToPutFile.$this->file_name))
				unlink($this->directoryToPutFile.$this->file_name);
		}

  		return array($status,$msg);
	}

	function overrideFilename($alternate)
	{
		$this->filename_override = $alternate;
	}

	function filenamePrefix($prefix)
	{
		$this->filename_prefix = $prefix;
	}

	function setDbConnection($connection)
	{
		$this->dbConnection = $connection;
	}

	function setFinalQuery($query)
	{
		$this->finalQuery = $query;
	}

	function setRelatedId($relatedId)
	{
		$this->related_id = $relatedId;
	}

	function setInsertIdQuery($query)
	{
		$this->insertIdQuery = $query;
	}

	function checkDirectory()
	{
		if (!is_dir($this->directoryToPutFile))
		{
			mkdir($this->directoryToPutFile,0755,true);		//use the recursive attribute when php version has been upgraded to 5.0.0 or higher
			//system("mkdir -p ".escapeshellcmd($this->directoryToPutFile));	//needed before php version 5.0.0
			chmod($this->directoryToPutFile,0755);			//execution priveleges are needed for directories
			touch($this->directoryToPutFile."index.html");	//create empty index file so that directory structure is not displayed
		}
	}

	function checkAdditionalRequirements()
	{
		$status = "good";
		$msg = "Upload passed image specific requirements";

		return array($status,$msg);
	}

	function getInsertId()
	{
		$getId = mysql_query($this->insertIdQuery,$this->dbConnection) or die (mysql_error());
		return mysql_insert_id($this->dbConnection);
	}

	function buildQuery()
	{
		$thisFileId = "";
		if(isset($this->dbConnection) && isset($this->insertIdQuery))
		{
			$thisFileId = $this->getInsertId();

			//update next query with the file id
			$this->finalQuery = str_replace('FILE_ID_HERE',$thisFileId,$this->finalQuery);
		}

		$extensions = array();
		$extensions = preg_split("/\./",$this->file_name);
		$extension = strtolower($extensions[count($extensions) - 1]);

		if(isset($this->filename_override))
		{
			$this->file_name = $this->filename_override . "." . $extension;
			$this->thumb_file_name = $this->filename_override . "_thumb." . $extension;
			$this->preview_file_name = $this->filename_override . "_preview." . $extension;
		}
		else
		{
			if(!isset($this->filename_prefix))
				$this->filename_prefix = $this->file_type;

			$this->file_name =	$this->filename_prefix."_".$this->related_id . "_" . $thisFileId . "." . $extension;
			$this->thumb_file_name = $this->filename_prefix . "_" .$this->related_id . "_" . $thisFileId . "_thumb." . $extension;
			$this->preview_file_name = $this->filename_prefix . "_" .$this->related_id . "_" . $thisFileId . "_preview." . $extension;
		}

		//update next query with file name(s)
		$this->finalQuery = str_replace('FILE_NAME_HERE',$this->file_name,$this->finalQuery);
	}

	function uploadError($errno)
	{
		switch($errno) {
			case 0: //no error; possible file attack!
				return ("There was a problem with your upload");
				break;
			case 1: //uploaded file exceeds the upload_max_filesize directive in phpini
				return ("The file you are trying to upload is too big");
				break;
			case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
				return ("The file you are trying to upload is too big");
				break;
			case 3: //uploaded file was only partially uploaded
				return ("The file you are trying upload was only partially uploaded");
				break;
			case 4: //no file was uploaded
				return ("You must select a file for upload");
				break;
			default: //a default error, just in case! :)
				return ("There was an unknown problem with your upload");
				break;
		}
	}

	// put two slashes (//) in front of the ones you never want to accept
	function acceptedMimetype($docType,$mimetype)
	{
		$mimes = array();

		if(!empty($this->restrictedMimetypes))
		{
			return in_array($mimetype,$this->restrictedMimetypes);
		}
		if($docType == 'image')
		{
			array_push($mimes,'image/jpeg');
			array_push($mimes,'image/gif');
			array_push($mimes,'image/pjpeg');
			//array_push($mimes,'image/png');
		}
		else if($docType == 'document')
		{
			array_push($mimes,'text/plain');
			array_push($mimes,'application/pdf');
			array_push($mimes,'application/doc');
			array_push($mimes,'application/msword');
			array_push($mimes,'application/rtf');
		}

		return in_array($mimetype,$mimes);
	}

	function restrictMimetypes($mimes = array())
	{
		$this->restrictedMimetypes = $mimes;
	}
}

class DocumentUploader extends Uploader
{
	function DocumentUploader($file_ref,$directoryToPutFile)
	{
		parent::Uploader($file_ref,$directoryToPutFile);
		$this->file_type = "file";
	}

	function acceptedMimetype($mimetype)
	{
		return parent::acceptedMimetype('document',$mimetype);
	}
}

class ImageUploader extends Uploader
{
	var $thumbNeeded;
	var $thumbSize;
	var $thumbDirectory;

	var $previewNeeded;
	var $prevWidth;
	var $prevHeight;
	var $previewDirectory;

	var $requiredWidth;

	function ImageUploader($file_ref,$directoryToPutFile)
	{
		require_once(DOC_ROOT.'/reusable/php/utilities/image.utilities.php');

		parent::Uploader($file_ref,$directoryToPutFile);
		$this->file_type = "image";
	}

	function setThumbnail($size="")
	{
		$this->thumbNeeded = true;
		if(!empty($size))
			$this->thumbSize = $size;
	}

	function setThumbDir($dir)
	{
		if(strrpos($dir,"/") != (strlen($dir)-1))
			$dir .= "/";
		$this->thumbDirectory = $dir;
	}

	function setPreview($widthRestriction="",$heightRestriction="")
	{
		$this->previewNeeded = true;
		if(!empty($widthRestriction))
			$this->prevWidth = $widthRestriction;
		if(!empty($heightRestriction))
			$this->prevHeight = $heightRestriction;
	}

	function setPreviewDir($dir)
	{
		if(strrpos($dir,"/") != (strlen($dir)-1))
			$dir .= "/";
		$this->previewDirectory = $dir;
	}

	function checkDirectory()
	{
		parent::checkDirectory();
		if (!is_dir($this->directoryToPutFile.$this->previewDirectory))
		{
			mkdir($this->directoryToPutFile.$this->previewDirectory,0755,true);		//use the recursive attribute when php version has been upgraded to 5.0.0 or higher
			//system("mkdir -p ".escapeshellcmd($this->directoryToPutFile.$this->previewDirectory));	//needed before php version 5.0.0
			chmod($this->directoryToPutFile.$this->previewDirectory,0755);			//execution priveleges are needed for directories
			touch($this->directoryToPutFile.$this->previewDirectory."index.html");	//create empty index file so that directory structure is not displayed
		}
		if (!is_dir($this->directoryToPutFile.$this->thumbDirectory))
		{
			mkdir($this->directoryToPutFile.$this->thumbDirectory,0755,true);		//use the recursive attribute when php version has been upgraded to 5.0.0 or higher
			//system("mkdir -p ".escapeshellcmd($this->directoryToPutFile$this->thumbDirectory));	//needed before php version 5.0.0
			chmod($this->directoryToPutFile.$this->thumbDirectory,0755);			//execution priveleges are needed for directories
			touch($this->directoryToPutFile.$this->thumbDirectory."index.html");	//create empty index file so that directory structure is not displayed
		}

	}

	function requireWidth($width)
	{
		$this->requiredWidth = $width;
	}

	function acceptedMimetype($mimetype)
	{
		return parent::acceptedMimetype('image',$mimetype);
	}

	function checkAdditionalRequirements()
	{
		$status = "good";
		$msg = "Image passed image specific requirements";

		if(isset($this->requiredWidth))
		{
			$sourceImage = getImageResource($this->directoryToPutFile,$this->file_name,$this->mimetype);

			$originalWidth = imagesx($sourceImage);

			if($originalWidth != $this->requiredWidth)
			{
				$status = "undo";
				$msg = "The image was not uploaded because it does not have the required width";
			}
		}

        if (isset($this->previewSettings) == true)
			$this->previewSettings->createPreview();
        if (isset($this->thumbSettings) == true)
			$this->thumbSettings->createThumbnail();

		if($status == "good")
		{
			if($this->previewNeeded == true)
				$this->createPreview($this->prevHeight,$this->prevWidth);
			else
				$this->finalQuery = str_replace('PREVIEW_NAME_HERE','',$this->finalQuery);

			if($this->thumbNeeded == true)
				$this->createSquareThumb();
			else
				$this->finalQuery = str_replace('THUMB_NAME_HERE','',$this->finalQuery);
		}

  		return array($status,$msg);
	}

	function upload()
	{
		return parent::upload(true);
	}

	function createPreview($newHeight,$newWidth)
	{
		if(empty($newHeight) && empty($newWidth))
		{
			$newHeight = 275;
			$newWidth = 250;
		}

		$sourceImage = getImageResource($this->directoryToPutFile,$this->file_name,$this->mimetype);

		$originalWidth = imagesx($sourceImage);
		$originalHeight = imagesy($sourceImage);

		$okToMakeNewFile = true;

		// portrait view
		if ($originalWidth < $originalHeight  || empty($newWidth))
		{
			$difference = $originalHeight / $newHeight;
			$newWidth = $originalWidth / $difference;

			if ($newHeight > $originalHeight)
				$okToMakeNewFile = false;
		}
		// landscape
		else
		{
			$difference = $originalWidth / $newWidth;
			$newHeight = $originalHeight / $difference;

			if ($newWidth > $originalWidth)
				$okToMakeNewFile = false;
		}

		if ($okToMakeNewFile)
		//use resized image as thumbnail since it is smaller than original
		{
			$destinationImage = imagecreatetruecolor($newWidth,$newHeight);

			imagecopyresized ($destinationImage,$sourceImage,0,0,0,0,$newWidth,$newHeight,$originalWidth,$originalHeight);
			imagejpeg ($destinationImage, $this->directoryToPutFile.$this->previewDirectory.$this->preview_file_name, 100);
			//only read priviledges are needed for files
			chmod ($this->directoryToPutFile.$this->previewDirectory.$this->preview_file_name, 0644);
		}
		else
		//copy the orginal file for use as the thumbnail since it is smaller than the resized image
		{
			copy($this->directoryToPutFile . $this->file_name,$this->directoryToPutFile.$this->previewDirectory.$this->preview_file_name);
			//only read priviledges are needed for files
			chmod($this->directoryToPutFile.$this->previewDirectory.$this->preview_file_name,0644);
		}
		$this->finalQuery = str_replace('PREVIEW_NAME_HERE',$this->preview_file_name,$this->finalQuery);
 	}

	function createSquareThumb($thumb_size = "")
	{
		if(empty($thumb_size))
			$thumb_size = 75;

		$size = getimagesize($this->directoryToPutFile.$this->file_name);
		$width = $size[0];
		$height = $size[1];

		if($width > $height) {
			$x = ceil(($width - $height) / 2 );
			$width = $height;
		} elseif($height > $width) {
			$y = ceil(($height - $width) / 2);
			$height = $width;
		}

		$new_im = ImageCreatetruecolor($thumb_size,$thumb_size);
		$im = imagecreatefromjpeg($this->directoryToPutFile.$this->file_name);
		imagecopyresampled($new_im,$im,0,0,$x,$y,$thumb_size,$thumb_size,$width,$height);
		imagejpeg($new_im,$this->directoryToPutFile.$this->thumbDirectory.$this->thumb_file_name,100);

		$this->finalQuery = str_replace('THUMB_NAME_HERE',$this->thumb_file_name,$this->finalQuery);
	}
}
?>