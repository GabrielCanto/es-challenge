<?php

class DB {

    static $DB_name='es_challenge';
    static $Connection;

    public static function connect() {

        if ( !self::$Connection) {
            self::$Connection = new PDO('mysql:host=localhost;dbname=' . self::$DB_name, 'sqluser', 'sqlpass');
        }

        return self::$Connection;
    }
    
}