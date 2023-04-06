<?php


if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!function_exists('e_')) {
    function e_(array $array, $key, $def = '')
    {
        if ($key && is_array($key)) {
            if (count($key) == 1) {
                $key = $key[0];
            } else {
                $k = array_shift($key);
                $subArr = e_($array, $k, []);
                if (!is_array($subArr)) return $def;
                return e_($subArr, $key, $def);
                //$k = array_shift($key);
                //return e_(e_($array, $k, []), $key, $def);
            }
        }
        return isset($array[$key]) ? $array[$key] : $def;
    }
}

if (!function_exists('et')) {
    function et(array $array, $key, $def = '')
    {
        return trim(e_($array, $key, $def));
    }
}


if (!function_exists('ea')) {
    function ea(array $array, $key, $def = [])
    {
        return is_array(($a = e_($array, $key, $def))) ? $a : [];
    }
}

if (!function_exists('ei')) {
    function ei(array $array, $key, $def = 0)
    {
        return intval(et($array, $key, $def));
    }
}

if (!function_exists('ef')) {
    function ef(array $array, $key, $def = 0)
    {
        return floatval(et($array, $key, $def));
    }
}

if (!function_exists('p')) {
    function p()
    {
        if (count($a = func_get_args()) == 1) $a = $a[0];
        echo '<pre style="text-align:left">';
        print_r($a);
        echo '</pre>';
    }
}


if (!function_exists('isJson')) {
    function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('isInt')) {

    /**
     * Integer validity
     * @param string $nr the number that is beign checked
     * @param integer $from the minimal value
     * @param integer $to the minimal value
     * @return boolean is the integer that is at least the $from value
     */
    function isInt($nr, $from = 0, $to = PHP_INT_MAX)
    {
        return is_numeric($nr) && intval($nr) == $nr &&
            $from <= $nr && $nr <= $to;
    }
}

if (!function_exists('startsWith')) {

    /**
     * Search backwards starting from haystack length characters from the end
     * @param string $text Entire string
     * @param string $prefix The prefix
     * @return boolean if the string starts with given prefix
     */
    function startsWith($text, $prefix)
    {
        return $prefix === "" || strrpos($text, $prefix, -strlen($text)) !== false;
    }
}

if (!function_exists('endsWith')) {

    /**
     * Search forward starting from end minus needle length characters
     * @param type $text Text, withs have to be with given suffix
     * @param type $suffix the suffix
     * @return boolean if the string ends with given suffix
     */
    function endsWith($text, $suffix)
    {
        return $suffix === "" || (($temp = strlen($text) - strlen($suffix)) >= 0 && strpos($text, $suffix, $temp) !== false);
    }
}

if (!function_exists('jsonEncode')) {

    /**
     * JSON encode for php 5.3
     * @param array $array
     * @return string corresponding json
     */
    function jsonEncode(array $array, int $flags = 0)
    {
        return preg_replace_callback('/\\\\u(\w{4})/', fn ($m) =>  html_entity_decode("&#x{$m[1]};", ENT_COMPAT, 'UTF-8'), json_encode($array, $flags));
    }
}

if (!function_exists('a2s')) {

    function a2s($val, $tab = "")
    {
        if (!is_array($val)) {
            return "\"$val\"";
        } else {
            if ($val) {
                $tab2 = "$tab\t";
                $res = "[\n";
                $cnt = count($val);
                foreach ($val as $k => $v) {
                    $v = a2s($v, $tab2);
                    $cm = --$cnt ? ',' : '';
                    $res .= "{$tab2}\"$k\" => $v$cm\n";
                }
                $res .= "{$tab}]";
                return $res;
            } else {
                return "[]";
            }
        }
    }
}

if (!function_exists('d')) {
    /**
     * Directory check
     * @param string $path full path
     * @return boolean true if the folder exist
     */
    function d($path, $ds = true)
    {
        if ($path && in_array(substr($path, -1), [DS, "/"])) {
            $path = substr($path, 0, -1);
        }
        return file_exists($path) && is_dir($path) ? ($path . ($ds ? DS : '')) : '';
    }
}
if (!function_exists('f')) {
    /**
     * File check
     * @param string $path full path
     * @return boolean true if the file exist
     */
    function f($path)
    {
        return file_exists($path) && is_file($path) ? $path : '';
    }
}

if (!function_exists('rmdir_rec')) {
    function rmdir_rec($path)
    {
        $path = trim($path);
        if (substr($path, -1) == DS) $path = substr($path, 0, -1);
        if (f($path)) {
            unlink($path);
        } elseif (d($path)) {
            foreach (scandir($path) as $f) if (!in_array($f, ['..', '.'])) rmdir_rec($path . DS . $f);
            rmdir($path);
        }
    }
}

if (!function_exists('array_decombine')) {
    function array_decombine($assocArray, $twoArr = false)
    {
        $a = [array_keys($assocArray), array_values($assocArray)];
        return $twoArr ? $a : array_map(fn ($k, $v) => [$k, $v], $a[0], $a[1]);
    }
}

if (!function_exists('mb_ucfirst')) {
    function mb_ucfirst(string $str)
    {
        return $str ? (mb_strtoupper(mb_substr($str, 0, 1)) . mb_strtolower(mb_substr($str, 1))) : '';
    }
}


if (!function_exists('o2a')) {
    /**
     * Converting an object to array
     * @param object $a a value that might be an object
     * @return array the corresponding array
     */
    function o2a($o)
    {
        return is_scalar($o) ? $o : array_map(__FUNCTION__, is_array($o) ? $o : get_object_vars($o));
    }
}

if (!function_exists('a2c')) {
    /**
     * Compacting array, i.e [[longkeyname1=>1,longkeyname1=>2],[longkeyname1=>3,longkeyname2=>4]] => [[longkeyname1,longkeyname2],[1,2],[3,4]]. It's for reducign internet traffic
     * @param array $data array that will be compacted
     * @return array the compacted array
     */
    function a2c(array $data)
    {
        if ($data) {
            $i = -1;
            foreach ($data as $k => $v) if ($k != ++$i) {
                $i = -1;
                break;
            }
            if ($i > -1) return array_merge([array_keys($data[0])], array_map(function ($e) {
                return array_values($e);
            }, $data));
        }
        return $data;
    }
}

if (!function_exists('a2o')) {
    /**
     * Converting an array to object
     * @param array $a a value that might be an array
     * @return object the corresponding object
     */
    function a2o($a)
    {
        return is_array($a) ? (object) array_map(__FUNCTION__, $a) : $a;
    }
}


if (!function_exists('spl')) {

    /**
     * The preg_split without non-empty elements in array
     * @param type $subject The string
     * @param type $pattern pattern between /../
     * @return array the splitted string
     */
    function spl($str, $re = "\s+")
    {
        return preg_split("/$re/", $str, -1, PREG_SPLIT_NO_EMPTY);
    }
}
