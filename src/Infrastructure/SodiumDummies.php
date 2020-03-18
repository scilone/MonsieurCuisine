<?php


namespace App\Infrastructure;

use Exception;

class SodiumDummies
{
    /**
     * Generate a random key pair wich will allow create secret and public key
     *
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateKeyPair(string $type = '') :string
    {
        switch ($type) {
            case 'kx':
                return sodium_crypto_kx_keypair();
            case 'sign':
                return sodium_crypto_sign_keypair();
            case 'box':
            case '':
                return sodium_crypto_box_keypair();
            default:
                throw new Exception('Unknown type');
        }
    }

    /**
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateKeygen(string $type = '') :string
    {
        switch ($type) {
            case 'aes256gcm':
                return sodium_crypto_aead_aes256gcm_keygen();
            case 'chacha20poly1305_ietf':
                return sodium_crypto_aead_chacha20poly1305_ietf_keygen();
            case 'chacha20poly1305':
                return sodium_crypto_aead_chacha20poly1305_keygen();
            case 'auth':
                return sodium_crypto_auth_keygen();
            case 'kdf':
                return sodium_crypto_kdf_keygen();
            case 'secretbox':
                return sodium_crypto_secretbox_keygen();
            case 'shorthash':
                return sodium_crypto_shorthash_keygen();
            case 'stream':
                return sodium_crypto_stream_keygen();
            case '':
            case 'generichash':
                return sodium_crypto_generichash_keygen();
            default:
                throw new Exception('Unknown type');
        }
    }

    /**
     * Allow user to generate a key pair wich will allow create secret and public key
     *
     * @param string $text
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateCustomKeyPair(string $text, string $type = '') :string
    {
        switch ($type) {
            case 'kx':
                return sodium_crypto_kx_seed_keypair(
                    $this->generateCustomKey($text)
                );
            case 'sign':
                return sodium_crypto_sign_seed_keypair(
                    $this->generateCustomKey($text, 'sign')
                );
            case 'box':
            case '':
                return sodium_crypto_box_seed_keypair(
                    $this->generateCustomKey($text)
                );
            default:
                throw new Exception('Unknown type');
        }
    }

    /**
     * @param string $text
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateCustomKey(string $text, string $type = '') :string
    {
        switch ($type) {
            case 'auth':
                $lenght = SODIUM_CRYPTO_AUTH_KEYBYTES;
                break;
            case 'secretbox':
                $lenght = SODIUM_CRYPTO_SECRETBOX_KEYBYTES;
                break;
            case 'shorthash':
                $lenght = SODIUM_CRYPTO_SHORTHASH_KEYBYTES;
                break;
            case 'stream':
                $lenght = SODIUM_CRYPTO_STREAM_KEYBYTES;
                break;
            case 'max':
                $lenght = SODIUM_CRYPTO_GENERICHASH_KEYBYTES_MAX;
                break;
            case 'sign':
                $lenght = SODIUM_CRYPTO_SIGN_SEEDBYTES;
                break;
            case 'min':
                $lenght = SODIUM_CRYPTO_GENERICHASH_KEYBYTES_MIN;
                break;
            case 'aes256gcm':
                $lenght = SODIUM_CRYPTO_AEAD_AES256GCM_KEYBYTES;
                break;
            case 'generichash':
            case '':
                $lenght = SODIUM_CRYPTO_GENERICHASH_KEYBYTES;
                break;
            default:
                throw new Exception('Unknown type');
        }

        return sodium_crypto_generichash(
            $text,
            '',
            $lenght
        );
    }

    /**
     * Generate a secret key thanks to key pair
     *
     * @param string $keyPair
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function generateSecretKeyFromKeyPair(string $keyPair, string $type = '') :string
    {
        switch ($type) {
            case 'kx':
                return sodium_crypto_kx_secretkey($keyPair);
            case 'sign':
                return sodium_crypto_sign_secretkey($keyPair);
            case 'box':
            case '':
                return sodium_crypto_box_secretkey($keyPair);
            default:
                throw new Exception('Unknown type');
        }
    }

    /**
     * Generate a public key thanks to key pair
     *
     * @param string $keyPair
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function generatePublicKeyFromKeyPair(string $keyPair, string $type = '') :string
    {
        switch ($type) {
            case 'kx':
                return sodium_crypto_kx_publickey($keyPair);
            case 'sign':
                return sodium_crypto_sign_publickey($keyPair);
            case 'box':
            case '':
                return sodium_crypto_box_publickey($keyPair);
            default:
                throw new Exception('Unknown type');
        }
    }

    /**
     * Create a key from secret and public key
     * This key must be confidential cause it contain a secret key, and can be used to encrypt or decrypt a message
     *
     * @param string $secretKey
     * @param string $publicKey
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function getKeyFromSecretKeyAndPublicKey(string $secretKey, string $publicKey, $type = '') :string
    {
        switch ($type) {
            case 'sign':
                return sodium_crypto_sign_keypair_from_secretkey_and_publickey($secretKey, $publicKey);
            case 'box':
            case '':
                return sodium_crypto_box_keypair_from_secretkey_and_publickey($secretKey, $publicKey);
            default:
                throw new Exception('Unknown type');
        }
    }

    /**
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public function getNonce(string $type = '') :string
    {
        switch ($type) {
            case 'secretbox':
                return random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            case 'stream':
                return random_bytes(SODIUM_CRYPTO_STREAM_NONCEBYTES);
            case 'box':
            case '':
                return random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
            default:
                throw new Exception('Unknown type');
        }
    }

    /**
     * Create a Box
     * A box is a crypt message which can be decrypted by 2 people
     * These 2 people are the public key owner and the private key owner that allowed to create the key
     *
     * @param string $text
     * @param string $nonce
     * @param string $key
     *
     * @return string
     */
    public function createBox(string $text, string $nonce, string $key) :string
    {
        return sodium_crypto_box($text, $nonce, $key);
    }

    /**
     * Open a closed box
     * A box is a crypt message which can be decrypted by 2 people
     * These 2 people are the public key owner and the private key owner that allowed to create the key
     *
     * @param string $textCrypted
     * @param string $nonce
     * @param string $key
     *
     * @return string
     */
    public function openBox(string $textCrypted, string $nonce, string $key) :string
    {
        return sodium_crypto_box_open($textCrypted, $nonce, $key);
    }

    /**
     * Create a box seal
     * A box seal is a crypt message which can only be decrypted by 1 person, public key owner
     *
     * @param string $text
     * @param string $publicKey
     *
     * @return string
     */
    public function createBoxSeal(string $text, string $publicKey) :string
    {
        return sodium_crypto_box_seal($text, $publicKey);
    }

    /**
     * Open a closed box seal
     * The private key owner are the only one who can open the box seal
     *
     * @param string $textCrypted
     * @param string $privateKey
     *
     * @return string
     */
    public function openBoxSeal(string $textCrypted, string $privateKey) :string
    {
        return sodium_crypto_box_seal_open($textCrypted, $privateKey);
    }

    /**
     * Create a secret box
     * A secret box crypt a message with a key, only this key can decrypt this message
     * So all people who want open the secret box must know the "master" key
     *
     * @param string $text
     * @param string $nonce
     * @param string $key
     *
     * @return string
     */
    public function createSecretBox(string $text, string $nonce, string $key) :string
    {
        return sodium_crypto_secretbox($text, $nonce, $key);
    }

    /**
     * Open a secret box
     * Everyone who know the key can open it
     *
     * @param string $textCrypted
     * @param string $nonce
     * @param string $key
     *
     * @return string
     */
    public function openSecretBox(string $textCrypted, string $nonce, string $key) :string
    {
        return sodium_crypto_secretbox_open($textCrypted, $nonce, $key);
    }

    /**
     * Sign a message
     * A signed message can only be decrypted with the public key of the person who sign it
     *
     * @param string $message
     * @param string $secretKey
     *
     * @return string
     */
    public function signMessage(string $message, string $secretKey) :string
    {
        return sodium_crypto_sign($message, $secretKey);
    }

    /**
     * Decrypt a signed message
     * If the message is correctly decrypted, that prove that the message was write by the public key owner
     *
     * @param string $messageSigned
     * @param string $publicKey
     *
     * @return string
     */
    public function decryptSignedMessage(string $messageSigned, string $publicKey) :string
    {
        return sodium_crypto_sign_open($messageSigned, $publicKey);
    }
}
