<?php
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    
    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        // Verificar que las constantes estén definidas
        if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER') || !defined('DB_PASS')) {
            error_log("ERROR: Constantes de base de datos no definidas");
            $this->error = "Configuración de base de datos no encontrada";
            return;
        }
        
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        // Create PDO instance
        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e) {
            $this->error = $e->getMessage();
            error_log("ERROR de conexión a BD: " . $this->error);
            error_log("Intentando conectar a: host=" . $this->host . ", db=" . $this->dbname . ", user=" . $this->user);
            // No lanzar excepción aquí, pero marcar el error
            $this->dbh = null;
        }
    }

    // Prepare statement with query
    public function query($sql) {
        if ($this->dbh === null) {
            $errorMsg = "Error: No hay conexión a la base de datos. ";
            if ($this->error) {
                $errorMsg .= "Detalles: " . $this->error;
            } else {
                $errorMsg .= "Verifica la configuración en src/config/config.php";
            }
            error_log($errorMsg);
            throw new Exception($errorMsg);
        }
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null) {
        
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute() {
        try {
            if ($this->stmt === null) {
                error_log("ERROR: No hay statement preparado para ejecutar");
                return false;
            }
            
            $result = $this->stmt->execute();
            
            // Execute completed
            
            return $result;
        } catch (PDOException $e) {
            error_log("ERROR PDO al ejecutar: " . $e->getMessage());
            error_log("SQL: " . ($this->stmt ? $this->stmt->queryString : 'N/A'));
            return false;
        } catch (Exception $e) {
            error_log("ERROR general al ejecutar: " . $e->getMessage());
            return false;
        }
    }

    // Get result set as array of objects
    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    // Get single record as object
    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    // Get row count
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    // Get PDO connection (for advanced operations like triggers)
    public function getConnection() {
        return $this->dbh;
    }
    
    // Get last insert ID
    public function lastInsertId() {
        if ($this->dbh === null) {
            return false;
        }
        return $this->dbh->lastInsertId();
    }
}
