<?php

class Mcrypted {

    static private $strspit_nr = 400;
    static private $rep_letter = 'b';
    static private $secret_key =  'default_key';

    public static function doEncrypt($plain_txt, $secretkey = null)
    {
       if ($secretkey == null) { $secretkey = self::$secret_key; }
       $salt      = substr( base64_encode(openssl_random_pseudo_bytes(16)), 0, 10);
       $plain_txt = $salt.$plain_txt;
       $arr = str_split($plain_txt, self::$strspit_nr);
       $encrypted_txt = '';
       foreach ($arr as $v)
        {
           $encrypted_txt .= substr(self::doEncryptDecrypt('encrypt', $secretkey, $v), 0, -2)."_";
        }
       $encrypted_txt = substr($encrypted_txt, 0, -1);
       $encrypted_txt = self::replace("go", $encrypted_txt);
       $hash  = substr( hash('sha512', $encrypted_txt) , 0, 10);
       $encrypted_txt = $hash.$encrypted_txt;
       return $encrypted_txt;
    }

    public static function doDecrypt($encrypted_txt, $secretkey = null)
    {
       if ($secretkey == null) { $secretkey = self::$secret_key; }
       $hash          = substr($encrypted_txt, 0, 10);
       $encrypted_txt = substr($encrypted_txt, 10);
       $hash_on_the_fly  = substr( hash('sha512', $encrypted_txt) , 0, 10);
       if ($hash !== $hash_on_the_fly) { return null; }
       $encrypted_txt   = self::replace("back", $encrypted_txt);
       $arr  = explode("_", $encrypted_txt);
       $decrypted_txt = '';
       foreach ($arr as $v)
        {
           $decrypted_txt .= self::doEncryptDecrypt('decrypt', $secretkey, $v);
        }
       $decrypted_txt = substr($decrypted_txt, 10);
       return utf8_encode($decrypted_txt);
    }

    private static function doEncryptDecrypt($action, $secretkey, $source)
    {
        $output     = false;
        $secretkey  = hash('sha512', $secretkey);
        $iv = substr( base64_encode(openssl_random_pseudo_bytes(16)), 0, 16);
        if ( $action == 'encrypt' )
        {
            $output = openssl_encrypt($source, "AES-256-CBC", $secretkey, 0, $iv);
            $output = $iv.base64_encode($output);
        }
        else if( $action == 'decrypt' )
        {
            $iv     = substr($source, 0, 16);
            $source = substr($source, 16);
            $output = openssl_decrypt(base64_decode($source), "AES-256-CBC", $secretkey, 0, $iv);
        }
        return $output;
    }

    private static function replace($action, $source)
    {
        if ($action == "go")
        {
            $source     = str_replace(self::$rep_letter, "|", $source);
            $source     = str_replace("_", self::$rep_letter, $source);
        }
        else if ($action == "back")
        {
            $source     = str_replace(self::$rep_letter, "_", $source);
            $source     = str_replace("|", self::$rep_letter, $source);
        }
        return $source;
    }

    public static function generateKeypair()
    {
        $config = array(
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privKey);
        $pubKey = openssl_pkey_get_details($res);
        $key['privatekey'] = $privKey;
        $key['publickey']  = $pubKey["key"];
        return $key;
    }

    public static function publicEncrypt($source, $publickey)
    {
        openssl_public_encrypt($source, $encrypted, $publickey);
        $encrypted = base64_encode($encrypted);
        return $encrypted;
    }

    public static function privateDecrypt($source, $pirvatekey)
    {
        $source = base64_decode($source);
        openssl_private_decrypt($source, $decrypted, $pirvatekey);
        return $decrypted;
    }
}
