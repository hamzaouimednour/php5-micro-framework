<?php

// require_once PATH_CONFIG . 'Variables.php';
require_once  PATH_CONFIG . "Variables.php";

class FileUpload {

    private $file, $upload_directory;

    private $file_name      = NULL;

    private $input_name     = NULL;

    private $file_array     = array();

    private $upload_url     = NULL;

    private $upload_max_filesize = 32*MB;

    protected $allowed_mime_types = array(

        'image' =>  array(

            'ico'   =>  'image/x-icon',

            'jpg'   =>  'image/jpeg',

            'jpeg'  =>  'image/jpeg',

            'png'   =>  'image/png',

            'gif'   =>  'image/gif',
        ),

        'file'  =>  array(

            'pdf'   =>  'application/pdf'
        )
    );

    private $allowed_mime_type = array();

    private $errors = array();

    protected $upload_error = array(
        'UPLOAD_ERR'            =>  'Can\'t complete request, please try again.',
        'UPLOAD_ERR_SIZE'       =>  'The uploaded file exceeds the maximum size allowed.',
        'UPLOAD_ERR_NO_FILE'    =>  'No file was uploaded.',
        'UPLOAD_ERR_NO_TMP'     =>  'Missing temporary file name.',
        'UPLOAD_ERR_CANT_WRITE' =>  'Failed to write file to disk, permission denied.',
        'UPLOAD_ERR_EXTENSION'  =>  'Uploaded file is not a valid file, Only ',
        'UPLOAD_ERR_MIME'       =>  'Invalid File MIME type.',
        'IMAGE_ERR_HEIGHT'      =>  'Incorrect Image height.',
        'IMAGE_ERR_WIDTH'       =>  'Incorrect Image width.',
        'UPLOAD_ERR_DIR'        =>  'Destination directory doesn\'t exist or permission denied. Can\'t complete request.'
    );

    public function __construct() {
        $this->file = array(
            "status"                =>    false,
            "mime"                  =>    "",
            "type"                  =>    "",
            "filename"              =>    "",
            "extension"             =>    "",
            "size"                  =>    0,
            "size_formated"         =>    "0B",
            "destination"           =>    "./",
            "error"                 =>    0,
        );

        if (isset($_FILES) and is_array($_FILES)) 
        {
            $this->file_array = $_FILES;
        }
    }

    public function getStatus(){
        return $this->file["status"];
    }
    public function getErrors(){
        return $this->errors;
    }
    public function setUploadDirectory( $destination_path ){
        if(is_dir($destination_path) && is_writable($destination_path)){ 
            $this->upload_directory = $destination_path;
        }
        return $this;
    }

    public function setFileName( $file_name ){
        if(!empty($file_name)){
            $this->file_name= $file_name;
        }
        return $this;
    }

    public function setInputName( $input_name ){
        if (!empty($input_name)) {
            $this->input_name = $input_name;
        }
        return $this;
    }

    public function setUploadURL( $input_url ){
        if (!empty($input_url) && filter_var($input_url, FILTER_VALIDATE_URL)) {
            $this->upload_url = $input_url;
        }
        return $this;
    }

    public function getUploadedFileName(){
        return $this->file["filename"];
    }

    public function setMaxFileSize( $file_size ){
        if (!empty($file_size)) {
            $this->upload_max_filesize = $file_size * MB;
        }
        return $this;
    }

    public function setAllowedMimeType( $file_type ){

        if(in_array(strtolower($file_type), array_keys($this->allowed_mime_types))){
            $this->allowed_mime_type = $this->allowed_mime_types[strtolower($file_type)];
        }
        return $this;
    }
    
    public function getAllowedExtensions(){
        return array_keys($this->allowed_mime_type);
    }
    public function getAllowedMimeTypes(){
       return $this->allowed_mime_type;
    }

    public function isValidExtension( $file_ext ){
        return in_array($file_ext, $this->getAllowedExtensions());
    }
    public function isValidMimeType( $file_mime ){
        return in_array($file_mime, $this->getAllowedMimeTypes());
    }

    public function checkFileSize( $file_size ){
        return $this->upload_max_filesize > $file_size;
    }
    public function checkUploadDirectory( $destination_path ){
        return is_dir($destination_path) && is_writable($destination_path);
    }
    public function resizeImage($resourceType, $image_width, $image_height, $resizeWidth, $resizeHeight){
        $imageLayer = imagecreatetruecolor($resizeWidth, $resizeHeight);
        imagecopyresampled($imageLayer, $resourceType, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $image_width, $image_height);
        return $imageLayer;
    }
    public function thumb($new_width,$new_height){
		$imageProcess = false;
        $fileName = $this->file["tmp"];
        $dirPath = $this->file["destination"];
		$sourceProperties = getimagesize($fileName);
		$sourceImageWidth = $sourceProperties[0];
		$sourceImageHeight = $sourceProperties[1];
		$uploadImageType = $sourceProperties[2];
		$fileExt = $this->file["extension"];
		switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($fileName);
                $imageLayer = $this->resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagejpeg($imageLayer,$dirPath.$this->file["filename"]);
								$imageProcess = true;
                break;

            case IMAGETYPE_GIF:
                $resourceType = imagecreatefromgif($fileName);
                $imageLayer = $this->resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagegif($imageLayer,$dirPath.$this->file["filename"]);
								$imageProcess = true;
                break;

            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($fileName);
                $imageLayer = $this->resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagepng($imageLayer,$dirPath.$this->file["filename"]);
								$imageProcess = true;
                break;

            default:
                $imageProcess = false;
                break;
        }
				return $imageProcess;
    }
    public function compress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
    public function upload( $resize = null, $new_width = null, $new_height = null){
        
        if (count($this->file_array) > 0) {

            $this->file["mime"]         =    $this->file_array[$this->input_name]["type"];
            if(!$this->isValidMimeType($this->file["mime"]))
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_MIME'];
            }

            $this->file["tmp"]        =    $this->file_array[$this->input_name]["tmp_name"];
            if (empty($this->file["tmp"]))
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_NO_TMP'];
            }

            $this->file["size"]        =    $this->file_array[$this->input_name]["size"];
            if(!$this->checkFileSize($this->file["size"]))
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_SIZE'];
            }

            $this->file["size_formated"]    =    Handler::size($this->file["size"]);

            $this->file["filename"] = $this->file_array[$this->input_name]['name'];
            $this->file["extension"] = mb_strtolower(pathinfo($this->file["filename"], PATHINFO_EXTENSION));
            if (!$this->isValidExtension($this->file["extension"])) {
                    $this->errors[] = $this->upload_error['UPLOAD_ERR_EXTENSION'] . implode(', ',$this->getAllowedExtensions()).' are allowed';
            }
            
            $this->file["filename"] = is_null($this->file_name) ?:  $this->file_name . '.' . $this->file['extension'];
            if(empty($this->file["filename"]))
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_NO_FILE'];
            }

            $this->file["destination"]    =    $this->upload_directory;
            if(!$this->checkUploadDirectory($this->file["destination"]))
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_DIR'];
            }

            $this->file["error"]        =     $this->file_array[$this->input_name]["error"];
            if( $this->file["error"] == UPLOAD_ERR_CANT_WRITE)
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_CANT_WRITE'];
            }

            if(!empty($this->file["error"]) or !empty($this->errors)){
                $this->file["status"] = false;
                // return false;
            }

            if (is_null($resize)){
                if (!move_uploaded_file($this->file["tmp"], $this->file["destination"] . $this->file["filename"])) { //upload the file
                    $this->errors[] = $this->upload_error['UPLOAD_ERR'];
                    $this->file["status"] = false;
                }
                $this->file["status"] = true;
            }else{
                if (!$this->thumb($new_width, $new_height)) { //upload the file
                    $this->errors[] = $this->upload_error['UPLOAD_ERR'];
                    $this->file["status"] = false;
                }
                $this->file["status"] = true;
            }
            return $this;
        }
    }
    public function uploadURL(){
        
        if (count($this->file_array) > 0) {

            $this->file["filename"] = mb_strtolower(pathinfo($this->upload_url, PATHINFO_BASENAME));
            $this->file["extension"] = mb_strtolower(pathinfo($this->file["filename"], PATHINFO_EXTENSION));
            if (!$this->isValidExtension($this->file["extension"])) {
                    $this->errors[] = $this->upload_error['UPLOAD_ERR_EXTENSION'] . implode(', ',$this->getAllowedExtensions()).' are allowed';
            }
            
            $this->file["filename"] = is_null($this->file_name) ?:  $this->file_name . '.' . $this->file['extension'];
            if(empty($this->file["filename"]))
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_NO_FILE'];
            }

            $this->file["destination"]    =    $this->upload_directory;
            if(!$this->checkUploadDirectory($this->file["destination"]))
            {
                $this->errors[] = $this->upload_error['UPLOAD_ERR_DIR'];
            }

            if(!empty($this->file["error"]) or !empty($this->errors)){
                $this->file["status"] = false;
                return false;
            }
            $image_data = file_get_contents($this->upload_url);
            if(!file_put_contents($this->file["destination"] . $this->file["filename"], $image_data)){
                $this->errors[] = $this->upload_error['UPLOAD_ERR'];
                $this->file["status"] = false;
            }
            $this->file["status"] = true;

        }
    }
}

?>