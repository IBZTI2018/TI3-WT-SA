<?php

namespace WTSA1\Engines\Hasher;

/**
  * This is the Class responsible for the implementation of PBKDF2
  *
  * To check how the algorithm works, please visit: https://en.wikipedia.org/wiki/PBKDF2
  *
  * @author Sven Gehring <sven.gehring@student.ibz.ch> André Glatzl <andre.glatzl@student.ibz.ch>
  * @package WTSA1\Engines\Hasher
  * @since 1.0
  */

class PBKDF2 {
    const algorithm = "pbkdf2_sha256";
    const iterations = 100000;

    /**
     * This function generates a PBKDF2 hash for a given raw input.
     * The dollar sign ($) is used as a separator.
     * 
     * @param string $input Raw input to be hashed
     * @param string $algorithm Name of the algorithm to be used
     * @param int $iterations Number of algorithm iterations (Work factor)
     * @param string $salt The random salt [Best-practice: /dev/urandom)
     * 
     * @return string The resulting hash.
     */
    public static function generate($input, $algorithm = self::algorithm, $iterations = self::iterations, $salt = false) {
        if ($salt === false) {
            $salt = random_int(PHP_INT_MIN, PHP_INT_MAX);
        }
        $hash = hash_pbkdf2("sha256", $input, $salt, $iterations);
        return implode("$", array($algorithm, $iterations, $salt, $hash));
    }

    /**
     * This function compares a given hash with a generated hash from a given raw input.
     * It is important that the given hash has the same parameters like algorithm, iterations and salt as for the generated raw input one.
     * Only this way both can be equal.
     * 
     * @param string $hashA Given PBKDF2 hash
     * @param string $input Raw input to be hashed
     * @param string $algorithm Name of the algorithm to be used
     * @param int $iterations Number of algorithm iterations (Work factor)
     * @param string $salt The random salt [Best-practice: /dev/urandom)
     * 
     * @return bool Returns true if both hashes are equal.
     */
    public static function verify($hashA, $input, $algorithm = self::algorithm, $iterations = self::iterations, $salt = false) {
        $hashB = self::generate($input, $algorithm, $iterations, $salt);
        return strcmp($hashA, $hashB) === 0;
    }
}

?>