<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Zend\Diactoros\ServerRequest;
use Gobline\Environment\LanguageSubdirectoryResolver;
use Gobline\Environment\UriBuilder;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class UriBuilderTest extends PHPUnit_Framework_TestCase
{
    public function testLanguageSubdirectoryResolver()
    {
        $url = 'http://example.com/foo/bar';
        $request = new ServerRequest([], [], $url);
        $environment = new Environment();
        $environment->setRequest($request);

        $languageResolver = new LanguageSubdirectoryResolver(['fr', 'nl', 'en'], 'en');
        $language = $languageResolver->resolve($request);
        $this->assertSame('en', $language);

        $environment->setLanguageResolver($languageResolver);
        $uriBuilder = new UriBuilder($environment);

        $this->assertSame('/foo/bar', $uriBuilder->buildUri($request->getUri()->getPath(), null, false));
        $this->assertSame($url, $uriBuilder->buildUri($request->getUri()->getPath(), null, true));

        $this->assertSame('http://example.com/fr/foo/bar', $uriBuilder->buildUri($request->getUri()->getPath(), 'fr', true));

        $url = 'http://example.com:8080/foo/bar';
        $request = new ServerRequest([], [], $url);
        $environment->setRequest($request);

        $languageResolver = new LanguageSubdirectoryResolver(['fr', 'nl', 'en'], 'en');
        $language = $languageResolver->resolve($request);
        $this->assertSame('en', $language);

        $environment->setLanguageResolver($languageResolver);
        $uriBuilder = new UriBuilder($environment);

        $this->assertSame('http://example.com:8080/fr/foo/bar', $uriBuilder->buildUri($request->getUri()->getPath(), 'fr', true));

        $url = 'http://example.com/fr/pomme/framboise';
        $request = new ServerRequest([], [], $url);
        $environment->setRequest($request);

        $languageResolver = new LanguageSubdirectoryResolver(['fr', 'nl', 'en'], 'en');
        $language = $languageResolver->resolve($request);
        $this->assertSame('fr', $language);

        $environment->setLanguageResolver($languageResolver);
        $uriBuilder = new UriBuilder($environment);

        $this->assertSame('/fr/pomme/framboise', $uriBuilder->buildUri($request->getUri()->getPath(), null, false));
        $this->assertSame($url, $uriBuilder->buildUri($request->getUri()->getPath(), null, true));
    }
}
