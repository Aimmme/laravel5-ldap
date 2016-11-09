<?php

namespace Aimme\Ldap\Tests;

use Aimme\Ldap\Tests\AbstractTestCase;
use Ldap;

class LoginTest extends AbstractTestCase
{

    /** @test */
    public function it_tests_whether_user_can_login_using_id()
    {

        $user = factory(User::class)->create();

        $this->assertEmpty(Ldap::user());

        Ldap::loginUsingId($user->id);

        $this->assertNotEmpty(Ldap::user());

        $loggedInUser = Ldap::user();

        $this->assertEquals($user->id, $loggedInUser->id);
    }

    /** @test */
    public function it_tests_whether_user_can_login()
    {

        $user = factory(User::class)->create();

        Ldap::login($user);

        $loggedInUser = Ldap::user();

        $this->assertEquals($user->id, $loggedInUser->id);

    }
}