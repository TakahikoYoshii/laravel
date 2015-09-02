<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\Ext\ExtTestMethodTrait;
use App\Models\User;

class createTest extends TestCase
{
    use ExtTestMethodTrait;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        \Session::start();
    }

    public function testCreate()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => 'test_create@test.te',
            'password' => 'createPassword',
            'password_confirmation' => 'createPassword',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'status' => '200',
                'message' => 'OK'
            ]);

        $this->seeInDatabaseExt('users', [
            'name' => 'test_create',
            'email' => 'test_create@test.te',
        ], [
            'created_at' => true,
            'updated_at' => true,
        ], false);

        $user = User::where('email', 'test_create@test.te')->first();;
        $this->assertTrue(\Hash::check('createPassword', $user->password));
    }

    public function testRequiredName()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => '',
            'email' => 'test_create@test.te',
            'password' => 'createPassword',
            'password_confirmation' => 'createPassword',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'name' => [
                    '0' => 'The name field is required.'
                    ]
            ]);
    }

    public function testRequiredEmail()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => '',
            'password' => 'createPassword',
            'password_confirmation' => 'createPassword',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'email' => [
                    '0' => 'The email field is required.'
                ]
            ]);
    }

    public function testRequiredPassword()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => 'test_create@test.te',
            'password' => '',
            'password_confirmation' => '',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'password' => [
                    '0' => 'The password field is required.'
                ]
            ]);
    }

    public function testInvalidEmail()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => 'test_create',
            'password' => 'createPassword',
            'password_confirmation' => 'createPassword',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'email' => [
                    '0' => 'The email must be a valid email address.'
                ]
            ]);
    }

    public function testExsistEmail()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => 'test@test.te0',
            'password' => 'createPassword',
            'password_confirmation' => 'createPassword',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'email' => [
                    '0' => 'The email has already been taken.'
                ]
            ]);
    }

    public function testTooShortPassword()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => 'test_create@test.te',
            'password' => 'create',
            'password_confirmation' => 'create',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'password' => [
                    '0' => 'The password must be between 8 and 16 characters.'
                ]
            ]);
    }

    public function testTooLongPassword()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => 'test_create@test.te',
            'password' => 'createPasswordCreatePassword',
            'password_confirmation' => 'createPasswordCreatePassword',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'password' => [
                    '0' => 'The password must be between 8 and 16 characters.'
                ]
            ]);
    }

    public function testNotSameConfirmationPassword()
    {
        $server = array('HTTP_X-Requested-With' => 'XMLHttpRequest');
        $params = [
            '_token' => \Session::token(),
            'name' => 'test_create',
            'email' => 'test_create@test.te',
            'password' => 'createPassword',
            'password_confirmation' => 'create',
        ];
        $this->post('/user/create', $params, $server)
            ->seeJsonEqualsExt([
                'password' => [
                    '0' => 'The password confirmation does not match.'
                ]
            ]);
    }

}
