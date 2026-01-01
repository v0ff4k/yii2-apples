<?php

namespace common\tests\functional;

use backend\tests\FunctionalTester;

/**
 * Class AppleCest
 * @package backend\tests\functional
 */
class AppleCest
{
    public function _before(FunctionalTester $I)
    {
        // Codeception использует тестовую БД автоматически
    }

    public function tryToGenerateApples(FunctionalTester $I)
    {
        // Защита Basic Auth — Codeception не поддерживает напрямую,
        // поэтому для functional-тестов временно отключите её в тестовом окружении,
        // или протестируйте через unit + acceptance (ниже).
    }
}
