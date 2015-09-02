<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Ext\ExtTestMethodTrait;

class showTest extends TestCase
{
    use ExtTestMethodTrait;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        \Session::start();
    }

    public function testShow()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'id' => 1
        ];
        $this->post('/user/show', $params, $server)
            ->seeJsonEqualsExt([
                'status' => '200',
                'message' => 'OK',
                'result' => [
                    'name' => 'test0',
                    'email' => 'test@test.te0'
                ]
            ]);
    }

    public function testNotFound()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'id' => 10000
        ];
        $this->post('/user/show', $params, $server)
            ->seeJsonEqualsExt([
                'status' => '404',
                'message' => 'Not Found'
            ]);
    }
}
