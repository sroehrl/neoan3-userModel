<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/vendor/neoan3-model/index/Index.model.php';
require_once dirname(__DIR__) . '/vendor/neoan3-model/index/Index.transformer.php';
require_once dirname(__DIR__) . '/vendor/neoan3-apps/transformer/TransformValidator.php';
require_once dirname(__DIR__) . '/vendor/neoan3-apps/transformer/Transformer.php';
require_once dirname(__DIR__) . '/User.transformer.php';
require_once dirname(__DIR__) . '/User.model.php';

use Neoan3\Apps\Db;
use Neoan3\Model\UserModel;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{

    public function setUp(): void
    {
        Db::setEnvironment(['assumes_uuid' => true, 'name' => 'db_app']);
    }

    /**
     * @depends  testCreate
     *
     * @param $provided
     */
    public function testGet($provided)
    {
        $t = UserModel::get($provided['id']);
        $this->assertArrayHasKey('id',$t);
    }

    public function testLogin()
    {

    }

    public function testFindEmail()
    {

    }

    public function testCreate()
    {
        $user = UserModel::create(['userName'=>'Tester','password'=>['password'=>'TestHash'],'email'=>[['email'=>'someCoolEmail@mydomain.com']]]);
        $this->assertArrayHasKey('id',$user);
        return $user;
    }

    public function testFind()
    {

    }

    public function testUpdate()
    {

    }
}
