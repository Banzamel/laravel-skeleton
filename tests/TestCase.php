<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        // Override Docker env vars before the app boots
            //  docker exec db-edu mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS edu_school_test;"
            //  docker exec db-edu mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON edu_school_test.* TO 'edu_user'@'%';"
        $overrides = [
            'APP_URL' => 'http://localhost',
            'DB_DATABASE' => 'edu_school_test',
            'CACHE_STORE' => 'array',
            'QUEUE_CONNECTION' => 'sync',
            'SESSION_DRIVER' => 'array',
            'BCRYPT_ROUNDS' => '4',
            'BROADCAST_CONNECTION' => 'log',
        ];

        foreach ($overrides as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        return parent::createApplication();
    }
}
