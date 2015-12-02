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
interface EnvironmentInterface
{
    public function getRequest();

    public function getResponse();

    public function getDefaultLanguage();

    public function getSupportedLanguages();

    public function getLanguage();

    public function getBasePath();

    public function isDebugMode();

    public function getLanguageResolver();

    public function getBasePathResolver();

    public function getMatchedRouteName();

    public function getMatchedRouteParams();

    public function buildUri($path, $language = null, $absolute = false);

    public function buildUrl($path, $language = null);
}
