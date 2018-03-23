<?php

abstract class TypedEnum
{
    private static $_instancedValues;

    private $_value;
    private $_name;

    private function __construct($value, $name)
    {
        $this->_value = $value;
        $this->_name = $name;
    }

    private static function _fromGetter($getter, $value)
    {
        $reflectionClass = new ReflectionClass(get_called_class());
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_STATIC | ReflectionMethod::IS_PUBLIC);    
        $className = get_called_class();

        foreach($methods as $method)
        {
            if ($method->class === $className)
            {
                $enumItem = $method->invoke(null);

                if ($enumItem instanceof $className && $enumItem->$getter() === $value)
                {
                    return $enumItem;
                }
            }
        }

        throw new OutOfRangeException();
    }

    protected static function _create($value)
    {
        if (self::$_instancedValues === null)
        {
            self::$_instancedValues = array();
        }

        $className = get_called_class();

        if (!isset(self::$_instancedValues[$className]))
        {
            self::$_instancedValues[$className] = array();
        }

        if (!isset(self::$_instancedValues[$className][$value]))
        {
            $debugTrace = debug_backtrace();
            $lastCaller = array_shift($debugTrace);

            while ($lastCaller['class'] !== $className && count($debugTrace) > 0)
            {
                $lastCaller = array_shift($debugTrace);
            }

            self::$_instancedValues[$className][$value] = new static($value, $lastCaller['function']);
        }

        return self::$_instancedValues[$className][$value];
    }

    public static function fromValue($value)
    {
        return self::_fromGetter('getValue', $value);
    }

    public static function fromName($value)
    {
        return self::_fromGetter('getName', $value);
    }

    public function getValue()
    {
        return $this->_value;
    }

    public function getName()
    {
        return $this->_name;
    }
}

?>