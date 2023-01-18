<?php
/**
 * @author Hamzaoui MedNour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 */

class OrderController {
    /**
     * @var string Define a constant caontain the name of the primary key of dbTable.
     */
    const  primaryKey = "id";

    /**
     * @property mixed
     */
    public $id, $price, $restaurant_id, $date_time, $status;

    /**
     * @method Setters() and Getters().
     */
    public function getId(){
        return $this->id ;
    }
    public function setId($pId){
        $this->id = $pId ;
        return $this;
    }
    public function getPrice(){
        return $this->price ;
    }
    public function setPrice($pPrice){
        $this->price = $pPrice ;
        return $this;
    }
    public function getRestaurantId(){
        return $this->restaurant_id ;
    }
    public function setRestaurantId($pRestaurantId){
        $this->restaurant_id = $pRestaurantId ;
        return $this;
    }
    public function getDateTime(){
        return $this->date_time ;
    }
    public function setDateTime($pDateTime){
        $this->date_time = $pDateTime ;
        return $this;
    }
    public function getStatus(){
        return $this->status ;
    }
    public function setStatus($pStatus){
        $this->status = $pStatus ;
        return $this;
    }
    /**
    * @method Controllers().
    */
}
?>