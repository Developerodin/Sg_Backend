<?php

use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PhpParser\Node\Expr\New_;

function getJWTFromRequest($authenticationHeader): string
{
    if (is_null($authenticationHeader)) { //JWT is absent
        throw new Exception('Missing or invalid JWT in request');
    }
    $data = explode(' ', $authenticationHeader);
    // print_r($data);
    
    //JWT is sent from client in the format Bearer XXXXXXXXX
    return $data[1];
}

function validateJWTFromRequest(string $encodedToken)
{
    
    
    $key = Services::getSecretKey();
    // $decodedToken = JWT::decode($encodedToken, $key, New array('HS256'));
    $decodedToken = JWT::decode($encodedToken, new Key($key, 'HS256'), new stdClass());

    $userModel = new UserModel();
    $userModel->findUserByUserName($decodedToken->user_name);
}

function getSignedJWTForUser(string $user_name)
{
    $issuedAtTime = time();
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;
    $payload = [
        'user_name' => $user_name,
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    $jwt = JWT::encode($payload, Services::getSecretKey(),'HS256');
    return $jwt;
}
