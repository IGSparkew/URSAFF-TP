<?php
    namespace App\Service;

    class AuthService { 
        public function login($header, $param_user, $param_password): bool {
            $user =  $header->get("php-auth-user");
            $passwd =  $header->get("php-auth-pw");
            $userDecoded = base64_decode($user);
            $pwDecoded = base64_decode($passwd);
            return $userDecoded == $param_user && $pwDecoded == $param_password;
        }
    }
?>