<?php

class Model
{
    protected PDO $db;

    // Store shared PDO instance for data access
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    protected function table(string $table): string
    {
        return (defined('DB_TABLE_PREFIX') ? DB_TABLE_PREFIX : '') . $table;
    }
}
