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
interface BasePathResolverInterface extends UriResolverInterface
{
    public function addBasePath(ServerRequestInterface $request);

    public function removeBasePath(ServerRequestInterface $request);
}
