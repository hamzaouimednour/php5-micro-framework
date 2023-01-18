<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */
class DateTimestamp extends DateTime{

    public $date_format, $time_format, $timezone;

    public function __construct($dateTime = NULL){
        global $dbSchema;
        $dbManager = new ManagerDB();
        $dbManager->fetch(__CLASS__, $dbSchema['dateTime']);
        parent::__construct($dateTime, new DateTimeZone($this->timezone));
        //$this::setTimezone($this->$timezone);
    }
    /**
     * @see Main Setters and Getters of this __CLASS__.
     */
    public function setTimezone($timeZone){
        $this->timezone = $timeZone;
        parent::setTimezone(new DateTimeZone($this->timezone));
    }
    public function setTimeFormat($timeFormat){
        $this->time_format = $timeFormat;
    }
    public function setDateFormat($dateFormat){
        $this->date_format = $dateFormat;
    }
    public function getDateFormat(){
        return $this->date_format;
    }
    public function getTimeFormat(){
        return $this->time_format;
    }
    public function getTimezone(){
        return $this->timezone;
    }
    /**
     * @see Useful functions to avoid errors and make it easy in use.
     */
    public function getDate(){
        return parent::format($this->date_format);
    }
    public function getTime(){
        return parent::format($this->time_format);
    }
    public function getDateTime(){
        return parent::format($this->date_format.' '.$this->time_format);
    }
    public function dateTimeFormat( $dateFormat, $dateString, $newDateFormat){
        // generally it's saved in this format : 'Y-m-d H:i:s'
        return parent::createFromFormat($dateFormat, $dateString)->format($newDateFormat);
    }
    /**
     * @static
     * @method getTimezoneList()
     * @return array
     */
    public static function getTimezoneList(){
        return DateTimeZone::listIdentifiers();
    }
    public function monthFormat($oldDateFormat, $dateString, $lang){
        if($lang == 'fr'){
            $numMonth = $this->dateTimeFormat($oldDateFormat, $dateString, 'n');
            $month = array('Janvier','F&eacute;vrier','Mars','Avril','Mai','Juin','Juillet','Ao&ucirc;t','Septembre','Octobre','Novembre','D&eacute;cembre');
            $date = parent::createFromFormat( $oldDateFormat, $dateString);
            if(strpos($date, ':') === false){
                return $date->format('d').' '.utf8_encode($month[$numMonth]).' '.$date->format('Y');
            }else{
                return $date->format('d').' '.utf8_encode($month[$numMonth]).' '.$date->format('Y H:i');
            }
        }
        if($lang == 'en'){
            if(strpos($date, ':') === false)
                return $this->dateTimeFormat($oldDateFormat, $dateString, 'F d\,  Y');
            else{
                return $this->dateTimeFormat($oldDateFormat, $dateString, 'F d\,  Y H:i');
            }
        }
    }
    public function dayFormat($oldDateFormat, $dateString, $lang){
        if($lang == 'fr'){
            $numDay = $this->dateTimeFormat($oldDateFormat, $dateString, 'N');
            $day = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
            $date = parent::createFromFormat( $oldDateFormat, $dateString);
            if(strpos($date, ':') === false){
                return $day[$numDay].' '.$dateString;
            }else{
                $datef = explode(' ', $date);
                $time = $this->dateTimeFormat('H:i:s', $datef[1], 'H:i');
                return $day[$numDay].' '.$datef[0].' '.$time;
            }
        }
        if($lang == 'en'){
            $dayName = $this->dateTimeFormat($oldDateFormat, $dateString, 'l');
            if(strpos($date, ':') === false)
                return "$dayName, $dateString";
            else{
                $datef = explode(' ', $date);
                $time = $this->dateTimeFormat('H:i:s', $datef[1], 'H:i');
                return $dayName.', '.$datef[0].' '.$time;
            }
        }
    }
}

?>
