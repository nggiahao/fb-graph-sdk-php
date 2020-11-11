<?php
namespace Nggiahao\Facebook\Tests\Model;

use Nggiahao\Facebook\Models\User;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function test_create_user() {
        $user = new User([
            'id' => 1234,
            'name' => "Nguyễn Gia Hào",
            'birthday' => "08/09/1999",
            'email' => "giahao9899@gmail.com"
        ]);

        $this->assertInstanceOf(User::class, $user);
    }
}