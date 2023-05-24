<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Dune\Session;

class SessionEncrypter
{
    /**
     * The chipper for ssl encrypt/decrypt
     *
     * @var const
     */
    protected const CHIPPER = 'aes-256-ctr';
    /**
      * a random hex value for encryption and decryption
      *
      * @var const
      */
    protected const HEX_KEY = '1f4276388ad3214c873428dbef42243f';
    /**
      * Bit for encryption and decryption
      *
      * @var const
      */
    protected const BIT = '8bit';

    /**
     * Session encryption goes here
     *
     * @param  string  $str
     *
     * @return string|null
     */
    public function encrypt(string $str): ?string
    {
        $key = hex2bin(self::HEX_KEY);

        $nonceSize = openssl_cipher_iv_length(self::CHIPPER);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $str,
            self::CHIPPER,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );
        return base64_encode($nonce . $ciphertext);
    }

    /**
     * Session decryption goes here
     *
     * @param  string  $str
     *
     * @return string|null
     */
    public function decrypt(string $str): ?string
    {
        $key = hex2bin(self::HEX_KEY);
        $str = base64_decode($str);
        $nonceSize = openssl_cipher_iv_length(self::CHIPPER);
        $nonce = mb_substr($str, 0, $nonceSize, self::BIT);
        $ciphertext = mb_substr($str, $nonceSize, null, self::BIT);

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::CHIPPER,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );
        return $plaintext;
    }
}
