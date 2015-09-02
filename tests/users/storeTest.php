<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Ext\ExtTestMethodTrait;
use App\Models\User;

class storeTest extends TestCase
{
    use ExtTestMethodTrait;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        \Session::start();
    }

    public function testUpdate()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'id' => 1,
            'name' => 'test_update',
            'email' => 'test_update@test.te',
            'password' => 'updatePassword',
            'password_confirmation' => 'updatePassword',
        ];
        $this->post('/user/store', $params, $server)
            ->seeJsonEqualsExt([
                'status' => '200',
                'message' => 'OK'
            ]);

        $this->seeInDatabaseExt('users', [
            'id' => 1,
            'name' => 'test_update',
            'email' => 'test_update@test.te',
        ], [
            'created_at' => false,
            'updated_at' => true,
        ], false);

        $user = User::find(1);
        $this->assertTrue(\Hash::check('updatePassword', $user->password));
    }
}
