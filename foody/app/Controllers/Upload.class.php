<?php
class Upload {

	public  $name, $type, $dir, $tmp, $size, $error, $typesValides = array(), $extValides = array() ;
	private $image_resize, $image_crop;
	public function __construct($file){
		$this->tmp  = $file['tmp_name'];
		$this->name = $file['name'];
		$this->type = $file['type'];
		$this->size = $file['size'];
	 }
	public function typesValides($t){ //set ur filtre
 		$this->typesValides = $t;
 	}
	public function extValides($ext){ //set ur filtre
		$this->extValides = $ext;
	}
	public function changeName($name){
		$ext = pathinfo($this->name);
		$this->name = $name.'.'.$ext['extension'];
	}
	//p1 :  your target dir
	//p2 :  Max Size
	public function resizeImage($resourceType,$image_width,$image_height,$resizeWidth,$resizeHeight) {
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
	}
	public function thumb($dirPath,$new_width,$new_height){
		$imageProcess = false;
		$fileName = $this->tmp;
		$sourceProperties = getimagesize($fileName);
		$sourceImageWidth = $sourceProperties[0];
		$sourceImageHeight = $sourceProperties[1];
		$uploadImageType = $sourceProperties[2];
		$fileExt = pathinfo($this->name, PATHINFO_EXTENSION);
		switch ($uploadImageType) {
            case IMAGETYPE_JPEG:
                $resourceType = imagecreatefromjpeg($fileName);
                $imageLayer = $this->resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagejpeg($imageLayer,$dirPath.$this->name);
								$imageProcess = true;
                break;

            case IMAGETYPE_GIF:
                $resourceType = imagecreatefromgif($fileName);
                $imageLayer = $this->resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagegif($imageLayer,$dirPath.$this->name);
								$imageProcess = true;
                break;

            case IMAGETYPE_PNG:
                $resourceType = imagecreatefrompng($fileName);
                $imageLayer = $this->resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight,$new_width,$new_height);
                imagepng($imageLayer,$dirPath.$this->name);
								$imageProcess = true;
                break;

            default:
                $imageProcess = false;
                break;
        }
				return $imageProcess;
	}
	public function uploadFile($dirPath, $size, $resize=null, $new_width=null, $new_height=null){
		$this->dir = $dirPath; // change the current directory
		$ext = pathinfo($this->name); //get the file infos
		if(is_null($resize)){
			if (empty($this->name)){
				$this->error = "err0";
				return false;
			}else if (!in_array($ext['extension'],$this->extValides)){ //check Size
				$this->error = "err3";
				return false;
			}else if (!in_array($this->type,$this->typesValides)){ //check extension
				$this->error = "err1";
				return false;
			}else if ($this->size > $size){ //check Size
				$this->error = "err2";
				return false;
			}else if (!move_uploaded_file($this->tmp , $this->dir.$this->name)){ //upload the file
				$this->error = "err4";
				return false;
			}else{
				return true;
			}
	}else {
		if (empty($this->name)){
			$this->error = "err0";
			return false;
		}else if (!in_array($ext['extension'],$this->extValides)){ //check Size
			$this->error = "err3";
			return false;
		}else if (!in_array($this->type,$this->typesValides)){ //check extension
			$this->error = "err1";
			return false;
		}else if ($this->size > $size){ //check Size
			$this->error = "err2";
			return false;
		}else if (!$this->thumb($this->dir,$new_width,$new_height)){ //upload the file
			$this->error = "err4";
			return false;
		}else{
			return true;
		}
	}
	}
	public function uploadError(){
		return $this->error ;
	}
}
?>
