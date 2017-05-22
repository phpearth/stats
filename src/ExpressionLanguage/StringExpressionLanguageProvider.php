<?php

namespace PhpEarth\Stats\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class StringExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return [
            new ExpressionFunction('strlen', function ($str) {
                return sprintf('(is_string(%1$s) ? strlen(%1$s) : %1$s)', $str);
            }, function ($arguments, $str) {
                if (!is_string($str)) {
                    return $str;
                }

                return strlen($str);
            }),
            new ExpressionFunction('contains', function ($haystack, $needle) {
                return sprintf('(strpos(%1$s, %2$s) !== false) ? true : false', $haystack, $needle);
            }, function ($arguments, $haystack, $needle) {
                return strpos($haystack, $needle) !== false;
            }),
        ];
    }
}
