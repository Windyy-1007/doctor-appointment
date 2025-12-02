<?php

class Model
{
    protected PDO $db;

    // Store shared PDO instance for data access
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
