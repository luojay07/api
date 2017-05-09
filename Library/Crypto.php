<?php

// 加密类
class Crypto {

    const ENCRYPT_MODE = 1;
    const DECRYPT_MODE = 0;

    public static function desDecrypt($key, $input) {
        if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
            // 新方法解密
            return Crypto::decryptDesEcbPKCS5($input, $key);
        }
        // 判断是否为8的倍数
        $len = strlen($input);
        if ($len < 16 || $len % 8 != 0) {
            return null;
        }
        // 旧方法解密
        $decrypted = mcrypt_ecb(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_DECRYPT);
        // pkcs5反解
        $pad = ord($decrypted{$len - 1});
        if ($pad > $len) {
            return $decrypted;
        }
        if (strspn($decrypted, chr($pad), $len - $pad) != $pad) {
            return $decrypted;
        }
        // 返加
        return substr($decrypted, 0, -1 * $pad);
    }

    public static function desEncrypt($key, $input) {
        if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
            // 新方法
            return Crypto::encryptDesEcbPKCS5($input, $key);
        }
        // pkcs5填充
        $pad = 8 - (strlen($input) % 8);
        if (0 == $pad) {
            $pad = 8;
        }
        $input = $input . str_repeat(chr($pad), $pad);
        // 加密
        $decrypted = mcrypt_ecb(MCRYPT_TRIPLEDES, $key, $input, MCRYPT_ENCRYPT);
        return $decrypted;
    }

    public static function rsaDecrypt($key, $input) {
        $res = openssl_get_privatekey($key);
        if (openssl_private_decrypt($input, $decord, $res, OPENSSL_PKCS1_PADDING)) {
            return $decord;
        }

        return null;
    }

    /**
     *  
     * 加密函数 
     * 算法：des 
     * 加密模式：ecb 
     * 补齐方法：PKCS5 
     *  
     * @param unknown_type $input 
     */
    public static function encryptDesEcbPKCS5($input, $key) {
        $block = mcrypt_get_block_size('des', 'ecb');
        $input = Crypto::pkcs5_pad($input, $block);
        return mcrypt_encrypt(MCRYPT_DES, $key, $input, MCRYPT_MODE_ECB);
    }

    /**
     * 解密函数 
     * 算法：des 
     * 加密模式：ecb 
     * 补齐方法：PKCS5 
     * @param unknown_type $input 
     */
    public static function decryptDesEcbPKCS5($input, $key) {
        $input = mcrypt_decrypt(MCRYPT_DES, $key, $input, MCRYPT_MODE_ECB);
        return Crypto::pkcs5_unpad($input);
    }

    public static function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    /*
     * Function:		TripleDesEncrypt
     * Explanation:      3DES加密
     * Input:            $string
     * Return: 		true or false	
     * Others:		私有方法		
     */

    public static function TripleDesEncrypt($string, $key, $iv) {
        // 获取填充模块大小
        $block_size = mcrypt_get_block_size(MCRYPT_TRIPLEDES, MCRYPT_MODE_CBC);
        // 字符串填充
        $string = Crypto::pkcs5_pad($string, $block_size);
        // 密钥填充
        $key = str_pad($key, 24, '0');
        // 开始加密
        $encrypted_string = mcrypt_encrypt(MCRYPT_TRIPLEDES, $key, $string, MCRYPT_MODE_CBC, $iv);

        return $encrypted_string;
    }

    /*
     * Function:		TripleDesEncrypt
     * Explanation:      3DES解密
     * Input:            $string
     * Return: 		true or false	
     * Others:		私有方法		
     */

    public static function TripleDesDecrypt($string, $key, $iv) {
        // 密钥填充
        $key = str_pad($key, 24, '0');
        // 开始解密
        $decrypted_string = mcrypt_decrypt(MCRYPT_TRIPLEDES, $key, $string, MCRYPT_MODE_CBC, $iv);
        // 移除填充字符
        $data = Crypto::pkcs5_unpad($decrypted_string);

        return $data;
    }

    /*
     * AES/ECB/PKCS5Padding加密
     */

    public static function AESECBEncrypt($input, $key) {
        $block_size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = Crypto::pkcs5_pad($input, $block_size);

        return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $input, MCRYPT_MODE_ECB);
    }

    /*
     * AES/ECB/PKCS5Padding解密
     */

    public static function AESECBDecrypt($input, $key) {
        $input = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $input, MCRYPT_MODE_ECB);

        return Crypto::pkcs5_unpad($input);
    }

    /*
     * 获取字符串的hashCode，与java兼容
     */

    public static function hashCode($str) {
        $hash = 0;
        $len = strlen($str);
        if ($len == 0) {
            return $hash;
        }
        for ($i = 0; $i < $len; $i++) {
            $h = $hash << 5;
            $h -= $hash;
            $h += ord($str[$i]);
            $hash = $h;
            $hash &= 0xFFFFFFFF;
        }
        return $hash;
    }

    /*
     * 获取AES加/解密key
     */

    public static function getAESKey() {
        $date = date('mdy');
        $key = Crypto::hashCode($date);

        return $key;
    }

    /*
     * 将结果转为16进制
     */

    public static function strToHex($input) {
        $output = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $v = dechex(ord($input[$i]));
            $output .= str_pad($v, 2, '0', STR_PAD_LEFT);
        }

        return $output;
    }

    /*
     *  将16进制数据还原
     */

    public static function hexToStr($input) {
        $output = '';
        for ($i = 0; $i < strlen($input) - 1; $i += 2) {
            $v = hexdec($input[$i] . $input[$i + 1]);
            $output .= chr($v);
        }

        return $output;
    }

    /*
     * AES加密解密统一函数入口
     */

    public static function AESCrypto($input, $crypto_mode) {
        // 先获取加密密钥
        $key = Crypto::getAESKey();
        $output = '';
        if (Crypto::ENCRYPT_MODE == $crypto_mode) { // 加密
            $encrypt = Crypto::AESECBEncrypt($input, $key);
            // 加密数据转16进制
            $output = strtoupper(Crypto::strToHex($encrypt));
        } else if (Crypto::DECRYPT_MODE == $crypto_mode) { // 解密
            $encrypt = Crypto::hexToStr($input);
            $output = Crypto::AESECBDecrypt($encrypt, $key);
        }

        return $output;
    }

}
