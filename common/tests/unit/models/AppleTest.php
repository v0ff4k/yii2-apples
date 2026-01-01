<?php

namespace common\tests\unit\models;

//use common\components\HttpBasicUser;// todo
use backend\models\Apple;
use Codeception\Test\Unit;

/**
 * Class AppleTest
 *
 * RUN: vendor/bin/codecept run unit backend/tests/unit/models/AppleTest.php
 *
 * @package backend\tests\unit\models
 */
class AppleTest extends Unit
{
    public function testCreateApple()
    {
        $apple = new Apple();
        $apple->color = 'red';
        $apple->created_at = time();
        $this->assertTrue($apple->save());
        $this->assertNotNull($apple->id);
    }

    public function testCannotEatWhileOnTree()
    {
        $apple = new Apple();
        $apple->color = 'green';
        $apple->created_at = time();
        $apple->save();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Съесть нельзя, яблоко на дереве');
        $apple->eat(10);
    }

    public function testFallAndEat()
    {
        $apple = new Apple();
        $apple->color = 'yellow';
        $apple->created_at = time();
        $apple->save();

        $apple->fallToGround();
        $this->assertEquals(Apple::STATUS_ON_GROUND, $apple->getStatus());

        $apple->eat(30);
        $this->assertEquals(30.0, $apple->eaten_percent);
    }

    public function testFullyEatenDeletes()
    {
        $apple = new Apple();
        $apple->color = 'red';
        $apple->created_at = time();
        $apple->save();
        $apple->fallToGround();
        $apple->eat(100);

        $this->assertNull(Apple::findOne($apple->id));
    }
}