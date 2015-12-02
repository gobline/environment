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
class DummyResolver implements BasePathResolverInterface, LanguageResolverInterface
{
    public function resolve(ServerRequestInterface $request)
    {
        return null;
    }

    public function addBasePath(ServerRequestInterface $request)
    {
        return $request;
    }

    public function removeBasePath(ServerRequestInterface $request)
    {
        return $request;
    }

    public function addLanguage(ServerRequestInterface $request, $language)
    {
        return $request;
    }

    public function removeLanguage(ServerRequestInterface $request)
    {
        return $request;
    }
}
