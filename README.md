# Terbora OpenPGP

**A modern, PHP-native implementation of the OpenPGP standard (RFC 9580).**

Terbora OpenPGP is a PHP library for public-key and symmetric encryption, digital signatures, compression, and cryptographic key management.

It supports [RFC 9580](https://www.rfc-editor.org/rfc/rfc9580), including modern OpenPGP version 6 keys and signatures.

The project is maintained by **Terbora Ltd.** and is based on the original [PHP Privacy](https://github.com/web-of-trust/php-privacy) OpenPGP implementation by Nguyen Van Nguyen.

Terbora OpenPGP focuses on modern PHP compatibility, native cryptographic operations, standards compliance, interoperability, and reducing external dependencies.

## Requirements

- PHP 8.3 or later
- [phpseclib 3](https://github.com/phpseclib/phpseclib) for cryptographic algorithms
- PHP Sodium extension (`ext-sodium`) for native Argon2id key derivation

No external Argon2 executable or Symfony Process dependency is required.

## Features

- Data signing, verification, encryption, and decryption.
- OpenPGP key generation, import, export, and decryption.
- RFC 9580 support, including version 6 keys and signatures.
- Native Argon2id string-to-key derivation using PHP Sodium.
- Public-key algorithms: RSA, ECDSA, EdDSA, and ECDH.
- Symmetric ciphers: Blowfish, Twofish, AES, and Camellia.
- AEAD ciphers: EAX, OCB, and GCM.
- Hash algorithms: SHA-224, SHA-256, SHA-384, SHA-512, SHA3-256, and SHA3-512.
- Compression algorithms: ZIP, Zlib, and BZip2.
- ECC curves:
  - secp256r1, secp384r1, secp521r1
  - brainpoolP256r1, brainpoolP384r1, brainpoolP512r1
  - Curve25519, Curve448
  - Ed25519, Ed448
- Legacy algorithm support for verification and decryption, including DSA, ElGamal, TripleDES, IDEA, CAST5, MD5, SHA-1, and RIPEMD-160.

## Installation

Install using [Composer](https://getcomposer.org/):

```bash
composer require terbora/openpgp
```

Alternatively, add the package to your `composer.json`:

```json
{
    "require": {
        "terbora/openpgp": "^1.0"
    }
}
```

The version constraint above assumes a `1.x` release has been published. Adjust it to the available version.

## Basic Usage

```php
<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use OpenPGP\OpenPGP;

$armoredPublicKey = '-----BEGIN PGP PUBLIC KEY BLOCK-----...';
$armoredPrivateKey = '-----BEGIN PGP PRIVATE KEY BLOCK-----...';
$passphrase = 'Your passphrase';

$publicKey = OpenPGP::readPublicKey($armoredPublicKey);
$privateKey = OpenPGP::decryptPrivateKey(
    $armoredPrivateKey,
    $passphrase,
);

$message = OpenPGP::createCleartextMessage('Hello, Terbora OpenPGP!');
$signedMessage = $message->sign([$privateKey]);
$verifications = $signedMessage->verify([$publicKey]);
```

The original `OpenPGP\` PHP namespace is retained for source-level compatibility.

## Project Maintenance

**Terbora OpenPGP** is maintained by:

Terbora Ltd.  
Email: hello@terbora.com  
Website: https://terbora.com

Source code: https://github.com/terbora-core/openpgp

Issues and contributions are welcome through GitHub.

## Origins and Attribution

Terbora OpenPGP is derived from **PHP Privacy**, originally developed by **Nguyen Van Nguyen**.

Original project: https://github.com/web-of-trust/php-privacy

The original copyright notices and license terms are retained. Terbora Ltd. maintains the fork and its subsequent changes.

## License

Distributed under the [BSD 3-Clause License](LICENSE).

See the LICENSE file for copyright notices, permissions, and conditions.