<?php
/**
 * Validation Utility
 * Input validation helper
 */

class Validator {
    
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validateRequired($value) {
        return !empty(trim($value));
    }
    
    public static function validateLength($value, $min = 0, $max = null) {
        $length = strlen(trim($value));
        if ($length < $min) {
            return false;
        }
        if ($max !== null && $length > $max) {
            return false;
        }
        return true;
    }
    
    public static function validateDate($date) {
        if (empty($date)) {
            return true; // Optional field
        }
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
    
    public static function csrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateCsrf($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}


