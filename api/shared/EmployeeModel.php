<?php

require_once( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'DB.php');

class EmployeeModel {

    public function getById($id) {
        
        $db = DB::connect();
        $result = $db->query('SELECT * FROM employees WHERE id=' . $id);
        
        if ( $result ) {
            $row = $result->fetchObject();
            return $row;
        }
    }

}