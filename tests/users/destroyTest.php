<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Ext\ExtTestMethodTrait;

class destroyTest extends TestCase
{
    use ExtTestMethodTrait;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        \Session::start();
    }

    public function testDestroy()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'id' => 1,
        ];
        $this->post('/user/destroy', $params, $server)
            ->seeJsonEqualsExt([
                'status' => '200',
                'message' => 'OK'
            ]);

        $this->seeInDatabaseExt('users', [
            'id' => 1
        ], [
            'created_at' => false,
            'updated_at' => true,
        ], true);
    }
}