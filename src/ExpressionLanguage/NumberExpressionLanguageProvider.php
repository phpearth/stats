<?php

namespace PhpEarth\Stats\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class NumberExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return [
            new ExpressionFunction('ceil', function ($num) {
                return sprintf('(is_numeric(%1$s) ? ceil(%1$s) : %1$s)', $num);
            }, function ($arguments, $num) {
                if (!is_numeric($num)) {
                    return $num;
                }

                return ceil($num);
            }),
            new ExpressionFunction('floor', function ($num) {
                return sprintf('(is_numeric(%1$s) ? floor(%1$s) : %1$s)', $num);
            }, function ($arguments, $num) {
                if (!is_numeric($num)) {
                    return $num;
                }

                return floor($num);
            }),
        ];
    }
}
