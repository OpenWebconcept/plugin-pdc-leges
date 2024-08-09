<?php

namespace OWC\PDC\Leges\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnit;
use ReflectionClass;

class TestCase extends PHPUnit
{
    public function setUp(): void
    {
        \WP_Mock::setUp();

        \WP_Mock::userFunction('wp_parse_args', [
            'return' => [
                '_owc_setting_portal_url' => '',
                '_owc_setting_portal_pdc_item_slug' => '',
                '_owc_setting_include_theme_in_portal_url' => 0,
                '_owc_setting_include_subtheme_in_portal_url' => 0,
                '_owc_setting_pdc-group' => 0,
                '_owc_setting_identifications' => 0,
            ],
        ]);

        \WP_Mock::userFunction('get_option', [
            'return' => [
                '_owc_setting_portal_url' => '',
                '_owc_setting_portal_pdc_item_slug' => '',
                '_owc_setting_include_theme_in_portal_url' => 0,
                '_owc_setting_include_subtheme_in_portal_url' => 0,
                '_owc_setting_pdc-group' => 0,
                '_owc_setting_identifications' => 0,
            ],
        ]);
    }
    
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Sets a protected property on a given object via reflection
     *
     * @param $object   - instance in which protected value is being modified
     * @param $property - property on instance being modified
     * @param $value    - new value of the property being modified
     *
     * @return void
     */
    public function setProtectedProperty($object, $property, $value)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}
