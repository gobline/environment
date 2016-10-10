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
class LanguageSubdirectoryResolver implements LanguageResolverInterface
{
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function resolve(ServerRequestInterface $request)
    {
        $path = $request->getUri()->getPath();

        $pathExploded = array_filter(explode('/', $path));
        if (!$pathExploded) {
            if ($this->environment->getDefaultLanguage()) {
                return $this->environment->getDefaultLanguage();
            }

            throw new \RuntimeException('Could not resolve language from uri "'.$path.'"');
        }

        $language = array_shift($pathExploded);

        if (in_array($language, $this->environment->getSupportedLanguages())) {
            return $language;
        } elseif ($this->environment->getDefaultLanguage()) {
            return $this->environment->getDefaultLanguage();
        }

        throw new \RuntimeException('Could not resolve language from uri "'.$path.'"');
    }

    public function addLanguage(ServerRequestInterface $request, $language)
    {
        $path = $request->getUri()->getPath();

        if ($language !== $this->environment->getDefaultLanguage()) {
            $path = '/'.$language.($path === '/' ? '' : $path);
        }

        return $request
            ->withUri($request->getUri()
            ->withPath($path));
    }

    public function removeLanguage(ServerRequestInterface $request)
    {
        $language = $this->resolve($request);

        if ($language === $this->environment->getDefaultLanguage()) {
            return $request;
        }

        $path = $request->getUri()->getPath();

        $language = '/'.$language;

        if (0 === strpos($path, $language)) {
            $path = '/'.substr_replace($path, '', 0, strlen($language));
        }

        return $request
            ->withUri($request->getUri()
            ->withPath($path));
    }
}
