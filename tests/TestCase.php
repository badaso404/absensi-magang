<?php

namespace Tests;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * users.role_id punya foreign key ke roles, jadi tabel roles harus terisi
     * sebelum factory user bisa dipakai.
     */
    protected bool $seed = true;

    protected string $seeder = RoleSeeder::class;
}
