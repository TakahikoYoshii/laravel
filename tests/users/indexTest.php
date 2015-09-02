<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Ext\ExtTestMethodTrait;

class indexTest extends TestCase
{
    use ExtTestMethodTrait;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        \Session::start();
    }

    public function testIndex()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token()
        ];
        $this->post('/user/index', $params, $server)
            ->seeJsonEqualsExt([
                'status' => '200',
                'message' => 'OK',
                'result' => [
                    '0' => [
                        'name' => 'test0',
                        'email' => 'test@test.te0'
                    ],
                    '1' => [
                        'name' => 'test1',
                        'email' => 'test@test.te1'
                    ],
                    '2' => [
                        'name' => 'test2',
                        'email' => 'test@test.te2'
                    ]
                ]
            ]);
    }
}
