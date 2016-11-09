<?php

namespace Aimme\Ldap\Tests;

use Aimme\Ldap\Tests\AbstractTestCase;
use Ldap;
use Auth;

class LdapTest extends AbstractTestCase
{

    /** @test */
    public function it_asserts_that_ldap_user_is_not_auth_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->assertEmpty(Ldap::user());
        $this->assertEmpty(Auth::user());

        Ldap::login($user1);
        Auth::login($user2);

        $this->assertNotEmpty(Ldap::user());
        $this->assertNotEmpty(Auth::user());

        $ldapUser = Ldap::user();
        $authUser = Auth::user();

        $this->assertEquals($user1->id, $ldapUser->id);
        $this->assertEquals($user2->id, $authUser->id);

        $this->assertNotEquals($ldapUser->id, $authUser->id);
    }

    /** @test */
    public function it_asserts_that_ldap_user_instance_is_not_auth_user_instance()
    {
        $user1 = factory(User::class)->create();

        $this->assertEmpty(Ldap::user());
        $this->assertEmpty(Auth::user());

        Ldap::login($user1);

        $this->assertNotEmpty(Ldap::user());
        $this->assertEmpty(Auth::user());
    }

    /** @test */
    public function it_asserts_that_auth_user_instance_is_not_ldap_user_instance()
    {
        $user1 = factory(User::class)->create();

        $this->assertEmpty(Ldap::user());
        $this->assertEmpty(Auth::user());

        Auth::login($user1);

        $this->assertEmpty(Ldap::user());
        $this->assertNotEmpty(Auth::user());
    }

    /** @test */
    public function it_asserts_that_auth_user_can_log_in_as_ldap_user_simultaniuosly()
    {
        $user = factory(User::class)->create();

        $this->assertEmpty(Ldap::user());
        $this->assertEmpty(Auth::user());

        Auth::login($user);
        Ldap::login($user);

        $this->assertEquals(Ldap::user(), Auth::user());
    }

    /** @test */
    public function it_asserts_that_auth_logged_in_user_can_sign_out_independently()
    {
        $user = factory(User::class)->create();

        $this->assertEmpty(Ldap::user());
        $this->assertEmpty(Auth::user());

        Auth::login($user);
        Ldap::login($user);

        $this->assertEquals(Ldap::user(), Auth::user());

        Auth::logout();

        $this->assertNotEquals(Ldap::user(), Auth::user());

        $this->assertEmpty(Auth::user());
        $this->assertNotEmpty(Ldap::user());
    }


}