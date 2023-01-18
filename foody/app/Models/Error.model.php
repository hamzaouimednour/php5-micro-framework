<?php 
/**
 * 
 */
class AlertError {

    static function success($msg, $autoDismiss = "auto-dismiss"){

        return '
        <div id="'.$autoDismiss.'" class="alert alert-success fade show">
            <span class="close" data-dismiss="alert">×</span>
            <strong>Succès!</strong>
            '.$msg.'.
        </div>';
    }
    static function successFront($msg, $autoDismiss = "auto-dismiss"){

        return '
        <div id="'.$autoDismiss.'" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><i class="mdi mdi-checkbox-marked-circle"></i> Succès!</strong> '.$msg.'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><small><i class="mdi mdi-close"></i></small></span>
            </button>
        </div>';
    }
    static function failed($msg, $autoDismiss = "auto-dismiss"){

        return '
        <div id="'.$autoDismiss.'" class="alert alert-danger fade show m-b-10">
            <span class="close" data-dismiss="alert">×</span>
            <strong>Echoué!</strong>
            '.$msg.'.
		</div>';
    }
    static function failedFront($msg, $autoDismiss = "auto-dismiss"){

        return '
        <div id="'.$autoDismiss.'" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="mdi mdi-close-circle"></i> Echoué!</strong> '.$msg.'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><small><i class="mdi mdi-close"></i></small></span>
            </button>
        </div>';
    }
    static function warningFront($msg, $autoDismiss = "auto-dismiss"){

        return '
        <div id="'.$autoDismiss.'" class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong><i class="mdi mdi-account-alert"></i> Attention!</strong> '.$msg.'
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><small><i class="mdi mdi-close"></i></small></span>
            </button>
        </div>';
    }
    static function rightNote($bsColor, $icon, $title, $msg, $closeIcon = null){
        $note = is_null($closeIcon) ? '' :  '<span class="close" id="close-note"><i class="ion-ios-close-circle"></i></span>';
        return '
        <!-- Notes -->
        <div id="auto-dismiss" class="note note-'.$bsColor.' note-with-right-icon m-b-15">
            '.$note.
            '<div class="note-icon"><i class="'.$icon.'"></i></div>
            <div class="note-content text-right">
                <h4><b>'.$title.'!</b></h4>
                <p> '.$msg.' </p>
            </div>
        </div>';
    }
    static function defaultNote($bsColor, $icon, $title, $msg, $closeIcon = null){
        $note = is_null($closeIcon) ? '' :  '<span class="close" id="close-note"><i class="ion-ios-close-circle"></i></span>';
        return '
        <!-- default -->
        <div id="auto-dismiss" class="note note-'.$bsColor.' m-b-15">
            '.$note.
            '
            <div class="note-icon"><i class="'.$icon.'"></i></div>
            <div class="note-content">
                <h4><b>'.$title.'!</b></h4>
                <p> '.$msg.' </p>
            </div>
        </div>';
    }

    /**
     * Undocumented function
     *
     * @param [type] $sizePrices
     * @return string
     */
    static function itemsModalPrice($sizePrices){
        // array('id' => , 'size' => , 'price" => );

        $data = '
        <label class="control-label">TAILLE <span class="required text-danger">*</span> </label> <small class="float-right text-truncate">(Obligatoire)</small>
        <ul class="list-group mb-4" id="item-price-size-content">
        ';
        $i = 0;
        foreach ($sizePrices as $item) {
            $i++;
            $defaultPriceStatus = ($i==1) ? "checked" : null;
            $data .= '
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div class="custom-control custom-radio">
                    <input type="radio" id="price-'.Handler::encryptInt($item->getId()).'" name="item-size-price" value="'.Handler::encryptInt($item->getId()).'" class="custom-control-input" '.$defaultPriceStatus.' data-price="'.$item->getPrice().'">
                    <label class="custom-control-label item-extra" for="price-'.Handler::encryptInt($item->getId()).'">'.ucfirst($item->getSizeName()).'</label>
                </div>
                <span class="text-muted">'.$item->getPrice().' <small class="">DT</small></span>
            </li>
            ';
        }
        $data .= '            
        </ul>';
        return $data;
    }
    static function itemsModalExtras($items){
        // array('id' => , 'extra_name' => , 'price" => );

        $data = '<label class="control-label">Les Suppléments </label> <small class="float-right text-truncate">(Optionnel)</small>
        <ul class="list-group mb-4" id="item-extras-content">
        ';

        foreach ($items as $object) {
            $data .= '
            <li class="list-group-item d-flex justify-content-between lh-condensed">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="extra-'.Handler::encryptInt($object->getId()).'" name="item-extras[]" value="'.Handler::encryptInt($object->getId()).'" data-price="'.$object->getPrice().'">
                    <label class="custom-control-label item-extra" for="extra-'.Handler::encryptInt($object->getId()).'">'.ucfirst($object->getExtraName()).'</label>
                </div>
                <span class="text-muted">'.$object->getPrice().' <small class="">DT</small></span>
            </li>
            ';
        }
        $data .= '
        </ul>
        ';
        return $data;
    }
}
