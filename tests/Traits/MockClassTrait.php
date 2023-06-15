<?php

namespace Tests\Traits;

use App\Domain\Panel\Models\Permission;
use App\Domain\Panel\Models\Role;
use App\Domain\Panel\Models\User;
use App\Domain\Shopify\Models\ShopifySession;
use Firebase\JWT\JWT;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use ReflectionException;

trait MockClassTrait
{
    /**
     * Call protected/private method of a class.
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to all call.
     * @param array $parameters Array of parameters to be pass into method.
     * @throws ReflectionException
     * @return mixed Method return.
     */
    protected function invokeMethod(&$object, string $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @throws ReflectionException
     * @return mixed
     */
    protected function getPropertyValue(&$object, string $propertyName): mixed
    {
        $reflection = new ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param string $url
     * @param array $query
     * @return string
     */
    protected function getShopifyUrl(string $url = '/', array $query = []): string
    {
        $mainRoute = 'https://' . config('shopify.api.host') . $url;

        if (count($query)) {
            $mainRoute .= '?' . http_build_query($query);
        }

        return $mainRoute;
    }

    /**
     * @param string $url
     * @param array $query
     * @return string
     */
    protected function getInternalUrl(string $url = '/', array $query = []): string
    {
        $mainRoute = 'https://' . config('generic.api.host') . $url;

        if (count($query)) {
            $mainRoute .= '?' . http_build_query($query);
        }

        return $mainRoute;
    }

    /**
     * @param string $url
     * @param array $query
     * @return string
     */
    protected function getExternalUrl(string $url = '/', array $query = []): string
    {
        $mainRoute = 'https://' . config('amplitude.api.host') . $url;

        if (count($query)) {
            $mainRoute .= '?' . http_build_query($query);
        }

        return $mainRoute;
    }

    /**
     * @param string $shop
     * @param int $isOnline
     * @return string
     */
    protected function createShopifySession(string $shop, int $isOnline = 1)
    {
        $session = ShopifySession::factory()->create([
            'shop' => $shop,
            'is_online' => $isOnline,
            'expires_at' => $isOnline ? now()->addDay() : null,
        ]);

        return JWT::encode(['shop' => $shop, 'id' => $session->id], config('shopify.api.api_secret'));
    }

    /**
     * @param $class
     * @param array $ignoreMethods
     * @return array
     */
    public function getClassMethods($class, $ignoreMethods = []): array
    {
        return array_diff(get_class_methods($class), array_merge($ignoreMethods, ['__construct']));
    }

    /**
     * Create a trait mock including mocking all the methods except ignored once and disabling constructor
     * @param $class
     * @param array $ignoreMethods
     * @throws Exception
     * @return MockObject
     */
    public function getIsolatedTraitMock($class, array $ignoreMethods = []): MockObject
    {
        return $this->getMockForTrait(
            $class,
            [],
            '',
            false,
            true,
            true,
            $this->getClassMethods($class, $ignoreMethods)
        );
    }

    /**
     * @param $class
     * @param array $ignoreMethods
     * @return MockObject
     */
    public function getIsolatedMock($class, $ignoreMethods = [])
    {
        return $this->getDisabledConstructorMock($class, $this->getClassMethods($class, $ignoreMethods));
    }

    /**
     * For setting private or protected property of an object
     * @param mixed $object
     * @param mixed $property
     * @param $value
     * @throws ReflectionException
     */
    public function setPrivateProperty($object, $property, $value)
    {
        $reflection = new ReflectionClass($object);

        $reflectionProperty = $reflection->getProperty($property);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);
    }

    /**
     * @param $class
     * @param array $mockMethods
     * @return MockObject
     */
    public function getDisabledConstructorMock($class, $mockMethods = [])
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($mockMethods)
            ->getMock();
    }

    /**
     * @param array $params
     * @param array $permissions
     * @return User
     */
    public function user(array $params = [], array $permissions = []): User
    {
        $user = User::factory()->create($params);

        if (!count($permissions)) {
            return $user;
        }

        $role = Role::factory()->create();
        $user->role_id = $role->id;
        $user->save();

        $permissionData = [];

        foreach ($permissions as $permission) {
            $permissionModel = Permission::factory()->create([
                'name' => $permission,
            ]);

            $permissionData[$permissionModel->id] = ['is_active' => true];
        }

        $role->permissions()->sync($permissionData);

        return $user;
    }
}
