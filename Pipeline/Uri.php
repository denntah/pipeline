<?php
namespace Pipeline;

class Uri {
    public static function startsWith($path) {
        $pattern = '?^'.Uri::getPattern($path).'?';
        return preg_match($pattern, $_SERVER['REQUEST_URI']) > 0;
    }

    public static function matches($path, $method) {
        if ($_SERVER['REQUEST_METHOD'] !== $method)
            return false;
        
        $pattern = '?^'.Uri::getPattern($path).'$?';
        return preg_match($pattern, $_SERVER['REQUEST_URI']) > 0;
    }

    public static function getKeys($path) {
        preg_match_all('/{(\w+)}/i', $path, $keys);
        return $keys[1];
    }

    public static function getValues($pattern, $subject) {
        preg_match('?^'.$pattern.'$?', $subject, $values);
        return $values;
    }

    public static function getPattern($path) {
        return preg_replace('/({\w+})/i','(\w+)', $path);
    }

    public static function getArguments($path) {
        $keys = self::getKeys($path);
        $values = self::getValues(self::getPattern($path), $_SERVER['REQUEST_URI']);
        
        $arguments = array();

        if (count($keys) === count($values) - 1){
            $fullPath = array_shift($values);
            for ($i = 0; $i < count($keys); $i++)
                $arguments[$keys[$i]] = $values[$i];
        }

        return $arguments;
    }
}