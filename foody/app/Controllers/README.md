# ManagerDB #
--- Variables/Methods Definition ---
Note : class() variables should be same 'names' in DataBase.
Note : Setters And Getters 'names' should Under this format handler::getMethod('the specified variable name')
        i.e : variable: user_id >>> method : getUserId, setUserId

--- Requirments ---
class/ManagerDB.class.php
includes/dbConnection.inc.php

--- Usage ---
$dbManager = new ManagerDB();
$db->getAttributes("db.table"); //Fields of the given table.

$obj = new Class();
$obj->setField('data');
$db->resetai('db.table');
if($db->insert($obj, 'db.table'))
$db->delete('db.table',array(
    'Field' => array('operator' => '=', 'value' => 'val')
));