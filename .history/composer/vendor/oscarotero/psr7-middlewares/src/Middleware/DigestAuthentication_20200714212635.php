<?php

namespace Psr7Middlewares\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr7Middlewares\Utils;
use Psr\Http\Message\ResponseInterface;

/**
 * Middleware to create a digest http authentication.
 *
 * @see https://tools.ietf.org/html/rfc2069#page-10
 */
class DigestAuthentication
{
    use Utils\AuthenticationTrait;
    use Utils\AttributeTrait;

    const KEY = 'usuario';

    /**
     * @var string|null The nonce value
     */
    private $nonce;

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
     * Set the nonce value.
     *
     * @param string $nonce
     *
     * @return self
     */
    public function nonce($nonce)
    {
        $this->nonce = $nonce;

        return $this;
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
        if ($this->login($request, $usuario)) {
            return $next(
                self::setAttribute($request, self::KEY, $usuario),
                $response
            );
        }

        return $response
            ->withStatus(401)
            ->withHeader('WWW-Authenticate', 'Digest realm="'.$this->realm.'",qop="auth",nonce="'.($this->nonce ?: uniqid()).'",opaque="'.md5($this->realm).'"');
    }

    /**
     * Login or check the user credentials.
     *
     * @param ServerRequestInterface $request
     * @param string|null            $usuario
     *
     * @return bool
     */
    private function login(ServerRequestInterface $request, &$usuario)
    {
        //Check header
        $authorization = self::parseAuthorizationHeader($request->getHeaderLine('Authorization'));

        if (!$authorization) {
            return false;
        }

        //Check whether user exists
        if (!isset($this->users[$authorization['usuario']])) {
            return false;
        }

        $usuario = $authorization['usuario'];

        //Check authentication
        return $this->checkAuthentication($authorization, $request->getMethod(), $this->users[$usuario]);
    }

    /**
     * Validates the user authentication.
     *
     * @param array  $authorization
     * @param string $method
     * @param string $passwordword
     *
     * @return bool
     */
    private function checkAuthentication(array $authorization, $method, $passwordword)
    {
        $A1 = md5("{$authorization['usuario']}:{$this->realm}:{$passwordword}");
        $A2 = md5("{$method}:{$authorization['uri']}");

        $validResponse = md5("{$A1}:{$authorization['nonce']}:{$authorization['nc']}:{$authorization['cnonce']}:{$authorization['qop']}:{$A2}");

        return $authorization['response'] === $validResponse;
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
        if (strpos($header, 'Digest') !== 0) {
            return false;
        }

        $needed_parts = ['nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'usuario' => 1, 'uri' => 1, 'response' => 1];
        $data = [];

        preg_match_all('@('.implode('|', array_keys($needed_parts)).')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', substr($header, 7), $matches, PREG_SET_ORDER);

        if ($matches) {
            foreach ($matches as $m) {
                $data[$m[1]] = $m[3] ? $m[3] : $m[4];
                unset($needed_parts[$m[1]]);
            }
        }

        return empty($needed_parts) ? $data : false;
    }
}
