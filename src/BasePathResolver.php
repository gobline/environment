<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\Environment;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class BasePathResolver implements BasePathResolverInterface
{
    private $basePath;

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath;
    }

    public function resolve(ServerRequestInterface $request)
    {
        if ($this->basePath) {
            return $this->basePath;
        }

        $scriptPath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);

        $this->basePath = rtrim($this->getIntersection($scriptPath, $request->getUri()->getPath()), '/');

        return $this->basePath;
    }

    public function addBasePath(ServerRequestInterface $request)
    {
        $path = $request->getUri()->getPath();

        $basePath = $this->resolve($request);

        return $request
            ->withUri($request->getUri()
            ->withPath($basePath.$path));
    }

    public function removeBasePath(ServerRequestInterface $request)
    {
        $basePath = $this->resolve($request);

        if (!trim($basePath, '/')) {
            return $request;
        }

        $path = $request->getUri()->getPath();

        if (0 === strpos($path, $basePath)) {
            $path = '/'.substr_replace($path, '', 0, strlen($basePath));
        }

        return $request
            ->withUri($request->getUri()
            ->withPath($path));
    }

    /**
     * @param string $a
     * @param string $b
     */
    private function getIntersection($a, $b)
    {
        $result = '';
        $len = min(strlen($a), strlen($b));
        for ($i = 0; $i < $len; ++$i) {
            if ($a[$i] != $b[$i]) {
                break;
            }
            $result .= $a[$i];
        }

        return $result;
    }
}
