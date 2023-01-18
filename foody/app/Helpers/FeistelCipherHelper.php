<?php
class FeistelCipherHelper
{

    /**
     * @param     $str
     * @param int $i
     * @return string
     */
    public static function encode($str, $i = 7)
    {
        $len = strlen($str);
        if ($len % 2 !== 0)
        {
            $str = $str.' ';
        }
        $str = str_split($str, 2);
        $hash = '';
        foreach ($str as $chr)
        {
            $l = ord(substr($chr, 0, 1));
            $r = ord(substr($chr, 1));
            FeistelCipherAlgorithm::encode($l, $r, $i);
            $l = chr($l);
            $r = chr($r);
            $hash .= $l.$r;
        }
        $hash = trim($hash, ' ');
        $hash = Base64Url::encode($hash);

        return $hash;
    }

    /**
     * @param     $hash
     * @param int $i
     * @return string
     */
    public static function decode($hash, $i = 7)
    {
        $hash = Base64Url::decode($hash);
        $len = strlen($hash);
        if ($len % 2 !== 0)
        {
            $hash = $hash.' ';
        }
        $hash = str_split($hash, 2);
        $str = '';
        foreach ($hash as $chr)
        {
            $l = ord(substr($chr, 0, 1));
            $r = ord(substr($chr, 1));
            FeistelCipherAlgorithm::decode($l, $r, $i);
            $l = chr($l);
            $r = chr($r);
            $str .= $l.$r;
        }
        $str = trim($str, ' ');

        return $str;
    }
}

class XorHelper
{
    /**
     * @param     $str
     * @param     $passw
     * @param     $salt
     * @return int|string
     */
    public static function encode($str, $passw, $salt)
    {
        $str = XorAlgorithm::code($str, $passw, $salt);
        $str = Base64Url::encode($str);

        return $str;
    }

    /**
     * @param     $str
     * @param     $passw
     * @param     $salt
     * @return int|string
     */
    public static function decode($str, $passw, $salt)
    {
        $str = Base64Url::decode($str);
        $str = XorAlgorithm::code($str, $passw, $salt);

        return $str;
    }
}

class Base64Url
{
    /**
     * @param string $input
     * @return string
     */
    public static function encode($input)
    {
        $str = strtr(base64_encode($input), '+/', '-_');
        $str = str_replace('=', '', $str);

        return $str;
    }

    /**
     * @param string $input
     * @return string
     */
    public static function decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}

class XorAlgorithm
{
    public static function code($str, $passw = '', $salt = 'aeJRhN7840Xn')
    {
        $len = strlen($str);
        $gamma = '';
        $n = $len > 100 ? 8 : 2;
        while (strlen($gamma) < $len)
        {
            $gamma .= substr(pack('H*', sha1($passw.$gamma.$salt)), 0, $n);
        }

        return $str ^ $gamma;
    }
}

class FeistelCipherAlgorithm
{
    /**
     * @param $block
     * @param $key
     * @return int
     */
    public static function func($block, $key)
    {
        $val = ((2 * $block) + pow(2, $key));

        return $val;
    }

    /**
     * @param     $left
     * @param     $right
     * @param int $steps
     */
    public static function encode(&$left, &$right, $steps = 5)
    {
        $i = 1;
        while ($i < $steps)
        {
            $temp = $right ^ static::func($left, $i);
            $right = $left;
            $left = $temp;
            $i++;
        }

        $temp = $right;
        $right = $left;
        $left = $temp;
    }

    /**
     * @param     $left
     * @param     $right
     * @param int $steps
     */
    public static function decode(&$left, &$right, $steps = 5)
    {
        $i = $steps - 1;
        while ($i > 0)
        {
            $temp = $right ^ static::func($left, $i);
            $right = $left;
            $left = $temp;
            $i--;
        }
        $temp = $right;
        $right = $left;
        $left = $temp;
    }
}
?>
