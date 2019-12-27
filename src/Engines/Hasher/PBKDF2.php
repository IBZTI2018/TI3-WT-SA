<?php

namespace WTSA1\Engines\Hasher;

class PBKDF2 {
    const algorithm = "pbkdf2_sha256";
    const iterations = 100000;
    public static function generate($input, $algorithm = self::algorithm, $iterations = self::iterations, $salt = false) {
        /**
        <algorithm>$<iterations>$<salt>$<hash>
        <algorithm>             We will use Django’s default hashing algorithm: PBKDF2 (SHA256 hash)
        $                       The dollar sign is used as a separator.
        <iterations>            The number of algorithm iterations (work factor)
        <salt>                  The random salt [Best-practice: /dev/urandom)
        <hash>                  The resulting password hash
         */
        if ($salt === false) {
            $salt = random_int(PHP_INT_MIN, PHP_INT_MAX);
        }
        $hash = hash_pbkdf2("sha256", $input, $salt, $iterations);
        return implode("$", array($algorithm, $iterations, $salt, $hash));
    }

    public static function verify($hashA, $input, $algorithm = self::algorithm, $iterations = self::iterations, $salt = false) {
        $hashB = self::generate($input, $algorithm, $iterations, $salt);
        return strcmp($hashA, $hashB) === 0;
    }
}

?>