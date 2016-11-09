<?php

namespace Aimme\Ldap\Tests;

use Aimme\Ldap\Tests\AbstractTestCase;
use Ldap;

class LogoutTest extends AbstractTestCase
{
        /** @test */
    public function it_tests_whether_user_can_logout()
    {
        $user = factory(User::class)->create();

        Ldap::login($user);

        $loggedInUser = Ldap::user();

        $this->assertEquals($user->id, $loggedInUser->id);

        $this->assertNotEmpty(Ldap::user());

        Ldap::logout();

        $this->assertEmpty(Ldap::user());
    }
}