<?php

namespace App\Controllers;

use Application\Core\Controller;
use Application\Core\Database;
use Application\Model\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class HomeController extends Controller
{
    private $userModel;
    private $jwt_secret;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $database = Database::getInstance($config['db']);
        $this->userModel = new User($database);
        $this->jwt_secret = $config['jwt_secret'];
    }

    public function register()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        // var_dump($input);
        // die;

        if (empty($input['username']) || empty($input['email']) || empty($input['password'])) {
            $this->jsonResponse(['error' => 'Username, email and password are required'], 400);
            return;
        }

        if ($this->userModel->findByUsername($input['username'])) {
            $this->jsonResponse(['error' => 'Username already exists'], 409);
            return;
        }

        if ($this->userModel->findByEmail($input['email'])) {
            $this->jsonResponse(['error' => 'Email already exists'], 409);
            return;
        }

        if ($this->userModel->create($input)) {
            $this->jsonResponse(['message' => 'User registered successfully']);
        } else {
            $this->jsonResponse(['error' => 'Registration failed'], 500);
        }
    }


    public function login()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['username']) || empty($input['password'])) {
            $this->jsonResponse(['error' => 'Username and password are required'], 400);
        }

        $user = $this->userModel->findByUsername($input['username']);

        if (!$user || !password_verify($input['password'], $user['password'])) {
            $this->jsonResponse(['error' => 'Invalid username or password'], 401);
        }

        $payload = [
            'iss' => 'your-domain.com',  // issuer
            'iat' => time(),             // issued at
            'exp' => time() + 3600,      // expire time (1 hour)
            'sub' => $user['id'],        // subject (user id)
            'username' => $user['username']
        ];

        $jwt = JWT::encode($payload, $this->jwt_secret, 'HS256');

        $this->jsonResponse(['token' => $jwt]);
    }



    public function profile()
    {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) {
            $this->jsonResponse(['error' => 'Authorization header required'], 401);
        }

        $authHeader = $headers['Authorization'];
        list($type, $token) = explode(" ", $authHeader);

        if ($type !== 'Bearer' || !$token) {
            $this->jsonResponse(['error' => 'Invalid Authorization format'], 401);
        }

        try {
            $decoded = JWT::decode($token, new Key($this->jwt_secret, 'HS256'));
            // $decoded->sub => user id
            // $decoded->username => username

            $user = $this->userModel->findByUsername($decoded->username);

            if (!$user) {
                $this->jsonResponse(['error' => 'User not found'], 404);
            }

            $this->jsonResponse([
                'id' => $user['id'],
                'username' => $user['username'],
                'created_at' => $user['created_at']
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => 'Invalid or expired token'], 401);
        }
    }
}
