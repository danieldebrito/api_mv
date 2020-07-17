<?php

namespace Psr7Middlewares\Middleware;

use Psr7Middlewares\Utils;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware to create basic http authentication.
 */
class BasicAuthentication
{
    use Utils\AuthenticationTrait;
    use Utils\AttributeTrait;

    const KEY = 'usuario';

    /**
     * Returns the usuario.
     *
     * @param ServerRequestInterface $request
     *
     * @return string|null
     */
    public static function getusuario(ServerRequestInterface $request)
    {
        return self::getAttribute($request, self::KEY);
    }

    /**
     * Execute the middleware.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable               $next
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $authorization = self::parseAuthorizationHeader($request->getHeaderLine('Authorization'));

        if ($authorization && $this->checkUserpasswordword($authorization['usuario'], $authorization['passwordword'])) {
            return $next(
                self::setAttribute($request, self::KEY, $authorization['usuario']),
                $response
            );
        }

        return $response
            ->withStatus(401)
            ->withHeader('WWW-Authenticate', 'Basic realm="'.$this->realm.'"');
    }

    /**
     * Validate the user and passwordword.
     *
     * @param string $usuario
     * @param string $passwordword
     *
     * @return bool
     */
    protected function checkUserpasswordword($usuario, $passwordword)
    {
        if (!isset($this->users[$usuario]) || $this->users[$usuario] !== $passwordword) {
            return false;
        }

        return true;
    }

    /**
     * Parses the authorization header for a basic authentication.
     *
     * @param string $header
     *
     * @return false|array
     */
    private static function parseAuthorizationHeader($header)
    {
        if (strpos($header, 'Basic') !== 0) {
            return false;
        }

        $header = explode(':', base64_decode(substr($header, 6)), 2);

        return [
            'usuario' => $header[0],
            'passwordword' => isset($header[1]) ? $header[1] : null,
        ];
    }
}
