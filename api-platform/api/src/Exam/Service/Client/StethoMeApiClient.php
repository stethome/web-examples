<?php

declare(strict_types=1);

namespace App\Exam\Service\Client;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class StethoMeApiClient
{
    private readonly HttpClientInterface $client;
    private readonly HttpClientInterface $clientMedia;

    private int $clientTokenValidUntil = 0;
    private string $clientToken = '';

    public function __construct(
        HttpClientInterface $client,
        #[Autowire('%stethome.api.url%')] string $url,
        #[Autowire('%stethome.api.media_url%')] string $urlMedia,
        #[Autowire('%stethome.api.token%')] string $token,
    ) {
        $this->client = $client->withOptions([
            'base_uri' => $url,
            'auth_bearer' => $token,
        ]);

        $this->clientMedia = $client->withOptions([
            'base_uri' => $urlMedia,
            'auth_bearer' => $token,
        ]);
    }

    private static function decodeJWT(string $jwt): array
    {
        $payload = base64_decode(explode('.', $jwt)[1]);
        $payload = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        return $payload;
    }

    /** Get short-lived client token. */
    public function getClientToken(): ResponseInterface
    {
        return $this->client->request('GET', '/v2/token');
    }

    /** Get short-lived client token for Media API IFrames. */
    public function getClientMediaToken(string $examId): ResponseInterface
    {
        return $this->clientMedia->request('GET', '/v2/security/token');
    }

    public function getExam(string $examId): ResponseInterface
    {
        return $this->client->request('GET', "/v2/pulmonary/visit/$examId/check", [
            'auth_bearer' => $this->getClientTokenHeader(),
        ]);
    }

    /**
     * Gets client token for internal client usage,
     * re-uses the token until it's near expiration to minimize amount of requests made.
     */
    private function getClientTokenHeader(): string
    {
        if ((time() - 5) > $this->clientTokenValidUntil) {
            $this->clientToken = $this->getClientToken()->toArray()['token'];

            $this->clientTokenValidUntil = self::decodeJWT($this->clientToken)['exp'];
        }

        return $this->clientToken;
    }
}
