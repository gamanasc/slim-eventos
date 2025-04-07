<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;
use App\Helper\JwtHelper;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginAction
{
    private $jwtHelper;
    private $users;
    public function __construct(JwtHelper $jwtHelper)
    {
        $this->jwtHelper = $jwtHelper;
        // Dummy users for demonstration purposes
        $this->users = [
            'user1' => 'password1',
            'user2' => 'password2'
        ];
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        $data = (array)$request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';


        // Validate credentials
        if (isset($this->users[$username]) && $this->users[$username] === $password) {
            // Generate JWT token
            $token = $this->jwtHelper->encode([
                'sub' => $username,
                'iat' => time(),
                'exp' => time() + 3600, // 1 hour expiration
            ]);

            $response->getBody()->write(json_encode(['token' => $token]));
            return $response->withHeader('Content-Type', 'application/json');
        }

        // Invalid credentials
        $response->getBody()->write(json_encode(['error' => 'Invalid credentials']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}