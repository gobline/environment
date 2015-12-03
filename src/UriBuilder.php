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

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class UriBuilder
{
    protected $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function buildUri($path, $language = null, $absolute = false)
    {
        $request = $this->environment->getRequest();
        $request = $request
            ->withUri($request->getUri()
            ->withPath($path)
            ->withFragment('')
            ->withQuery(''));

        if ($language) {
            $request = $this->environment->getLanguageResolver()->addLanguage($request, $language);
        }

        $request = $this->environment->getBasePathResolver()->addBasePath($request);

        if ($absolute) {
            return $request->getUri()->__toString();
        }

        return $request->getUri()->getPath();
    }
}
