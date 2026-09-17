<?php

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    public static function getInstance(): Database
    {
        if (Database::$instance == null) {
            Database::$instance = new Database();
        }

        return Database::$instance;
    }

    public function __construct() {
        $this->pdo  = new PDO(
            "mysql:host=localhost;dbname=divc_ecommerce_db",
            "root",
            ""
        );
    }

    public function getAll(string $query, array $params = []): array {
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);

        $result = $statement->fetchAll(PDO::FETCH_OBJ);
        return $result ?? [];
    }

    public function getOne(string $query, array $params = []) : ?object {
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);

        $result = $statement->fetch(PDO::FETCH_OBJ);
        return !$result ? null : $result;
    }

    public function execute(string $query, array $params = []) : bool {
        $statement = $this->pdo->prepare($query);
        return $statement->execute($params);
    }
}