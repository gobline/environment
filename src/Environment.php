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
use Psr\Http\Message\ResponseInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Environment implements EnvironmentInterface
{
    private $request;
    private $response;
    private $basePath;
    private $language;
    private $supportedLanguages;
    private $defaultLanguage;
    private $debugMode = false;
    private $languageResolver;
    private $basePathResolver;
    private $uriBuilder;
    private $matchedRouteName;
    private $matchedRouteParams;

    public function __construct()
    {
        $this->debugMode = $this->isLocalHost();

        $this->languageResolver = new DummyResolver();
        $this->basePathResolver = new DummyResolver();

        $this->uriBuilder = new UriBuilder($this);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(ServerRequestInterface $request)
    {
        $this->request = $request;

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;

        return $this;
    }

    public function getDefaultLanguage()
    {
        return $this->defaultLanguage;
    }

    public function setDefaultLanguage($language)
    {
        $this->defaultLanguage = $language;

        return $this;
    }

    public function getSupportedLanguages()
    {
        return $this->supportedLanguages;
    }

    public function setSupportedLanguages($languages = [])
    {
        $this->supportedLanguages = $languages;

        return $this;
    }

    public function getLanguage()
    {
        if ($this->language) {
            return $this->language;
        }

        if (!$this->request) {
            throw new \Exception('Request not found');
        }

        $request = $this->basePathResolver->removeBasePath($this->request);

        $this->language = $this->languageResolver->resolve($request);

        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    public function getBasePath()
    {
        if ($this->basePath) {
            return $this->basePath;
        }

        if (!$this->request) {
            throw new \Exception('Request not found');
        }

        $this->basePath = $this->basePathResolver->resolve($this->request);

        return $this->basePath;
    }

    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        $this->basePathResolver = new BasePathResolver($basePath);

        return $this;
    }

    public function isDebugMode()
    {
        return $this->debugMode;
    }

    public function setDebugMode($debugMode)
    {
        $this->debugMode = (bool) $debugMode;

        return $this;
    }

    public function getLanguageResolver()
    {
        return $this->languageResolver;
    }

    public function setLanguageResolver($languageResolver)
    {
        if ($languageResolver instanceof LanguageResolverInterface) {
            $this->languageResolver = $languageResolver;

            return $this;
        }

        if ($languageResolver === 'subdirectory') {
            $this->languageResolver = new LanguageSubdirectoryResolver($this);

            return $this;
        }

        if ($languageResolver === 'subdomain') {
            throw new \RuntimeException('Unimplemented language resolver "subdomain"');
        }

        throw new \InvalidArgumentException('Invalid $languageResolver "'.$languageResolver.'"');
    }

    public function getBasePathResolver()
    {
        return $this->basePathResolver;
    }

    public function setBasePathResolver($basePathResolver)
    {
        if ($basePathResolver instanceof BasePathResolverInterface) {
            $this->basePathResolver = $basePathResolver;

            return $this;
        }

        if ($basePathResolver === 'auto') {
            $this->basePathResolver = new BasePathResolver();

            return $this;
        }

        throw new \InvalidArgumentException('Invalid $basePathResolver "'.$basePathResolver.'"');
    }

    public function getMatchedRouteName()
    {
        return $this->matchedRouteName;
    }

    public function setMatchedRouteName($name)
    {
        $this->matchedRouteName = $name;

        return $this;
    }

    public function getMatchedRouteParams()
    {
        return $this->matchedRouteParams;
    }

    public function setMatchedRouteParams(array $params)
    {
        $this->matchedRouteParams = $params;

        return $this;
    }

    public function buildUri($path, $language = null, $absolute = false)
    {
        return $this->uriBuilder->buildUri($path, $language, $absolute);
    }

    public function buildUrl($path, $language = null)
    {
        return $this->uriBuilder->buildUri($path, $language, true);
    }

    private function isLocalHost()
    {
        if (!isset($_SERVER['REMOTE_ADDR'])) {
            return false;
        }

        return $_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1';
    }
}
