<?php
/**
 * Database Utility Class
 * Helper untuk database operations
 */

require_once __DIR__ . '/../../config/database.php';

class Database {
    private static $pdo = null;
    
    /**
     * Get PDO connection
     * 
     * @return PDO
     */
    public static function getConnection() {
        if (self::$pdo === null) {
            self::$pdo = getDBConnection();
        }
        return self::$pdo;
    }
    
    /**
     * Execute query and return results
     * 
     * @param string $sql
     * @param array $params
     * @return array
     */
    public static function query($sql, $params = []) {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database Query Error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Execute query and return single row
     * 
     * @param string $sql
     * @param array $params
     * @return array|false
     */
    public static function queryOne($sql, $params = []) {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Database Query Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Execute INSERT/UPDATE/DELETE
     * 
     * @param string $sql
     * @param array $params
     * @return int|false Number of affected rows or false on failure
     */
    public static function execute($sql, $params = []) {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Database Execute Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get last insert ID
     * 
     * @return string
     */
    public static function lastInsertId() {
        return self::getConnection()->lastInsertId();
    }
    
    /**
     * Begin transaction
     */
    public static function beginTransaction() {
        self::getConnection()->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public static function commit() {
        self::getConnection()->commit();
    }
    
    /**
     * Rollback transaction
     */
    public static function rollback() {
        self::getConnection()->rollBack();
    }
}



