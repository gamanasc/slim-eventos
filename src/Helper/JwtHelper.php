<?php
namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Psr\Log\LoggerInterface;

class JwtHelper
{
    private $secretKey;
    private $logger;

    public function __construct(string $secretKey, LoggerInterface $logger)
    {
        $this->secretKey = $secretKey;
        $this->logger = $logger;
    }

    public function encode(array $data): string
    {
        $this->logger->info('Encoding JWT', ['data' => $data]);
        $token = JWT::encode($data, $this->secretKey, 'HS256');
        $this->logger->info('JWT encoded', ['token' => $token]);
        return $token;
    }

    public function decode(string $jwt): object
    {
        $this->logger->info('Decoding JWT', ['jwt' => $jwt]);
        try {
            $decoded = JWT::decode($jwt, new Key($this->secretKey, 'HS256'));
            $this->logger->info('JWT decoded', ['decoded' => (array)$decoded]);
            return $decoded;
        } catch (\Exception $e) {
            $this->logger->error('JWT decoding failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}