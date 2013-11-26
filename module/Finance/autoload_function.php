<?php
/**
 * Created by JetBrains PhpStorm.
 * User: igorciobanu
 * Date: 2/4/13
 * Time: 3:59 PM
 * To change this template use File | Settings | File Templates.
 */

return function ($class) {
    static $map;
    if (!$map) {
        $map = include __DIR__ . '/autoload_classmap.php';
    }

    if (!isset($map[$class])) {
        return false;
    }
    return include $map[$class];
};