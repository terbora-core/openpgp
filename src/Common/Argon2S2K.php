<?php declare(strict_types=1);
/**
 * This file is part of the PHP Privacy project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OpenPGP\Common;

use OpenPGP\Enum\S2kType;
use OpenPGP\Type\S2KInterface;

/**
 * Argon2 string-to-key class
 *
 * See RFC 9580, section 3.7.
 *
 * Implementation of the Argon2 string-to-key specifier.
 * This S2K method hashes the passphrase using Argon2, as specified in RFC9106.
 * This provides memory hardness, further protecting the passphrase against brute-force attacks.
 *
 * @package  OpenPGP
 * @category Common
 * @author   Nguyen Van Nguyen - nguyennv1981@gmail.com
 */
class Argon2S2K implements S2KInterface
{
    use S2KTrait;

    /**
     * Argon2 salt length
     */
    const SALT_LENGTH = 16;

    /**
     * String-to-key type
     */
    private readonly S2kType $type;

    /**
     * Constructor
     *
     * @param string $salt - Salt value
     * @param int $iteration - Number of iterations
     * @param int $parallelism - Number of parallel threads
     * @param int $memoryExponent - The exponent of the memory size
     * @return self
     */
    public function __construct(
        private readonly string $salt,
        private readonly int $iteration = 3,
        private readonly int $parallelism = 1,
        private readonly int $memoryExponent = 16,
    ) {
        if (!function_exists("sodium_crypto_pwhash")) {
            throw new \RuntimeException("The sodium extension is required.");
        }
        if ($parallelism !== 1) {
            throw new \InvalidArgumentException(
                "Native Argon2id requires parallelism 1.",
            );
        }
        if (strlen($salt) !== self::SALT_LENGTH) {
            throw new \InvalidArgumentException(
                "Salt size must be " . self::SALT_LENGTH . " bytes.",
            );
        }
        $this->type = S2kType::Argon2;
    }

    /**
     * {@inheritdoc}
     */
    public function toBytes(): string
    {
        return implode([
            chr($this->type->value),
            $this->salt,
            chr($this->iteration),
            chr($this->parallelism),
            chr($this->memoryExponent),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function produceKey(string $passphrase, int $length): string
    {
        return sodium_crypto_pwhash(
            $length,
            $passphrase,
            $this->salt,
            $this->iteration,
            1 << ($this->memoryExponent + 10),
            SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13,
        );
    }

    /**
     * Parsing function for argon2 string-to-key specifier.
     *
     * @param string $bytes - Payload of argon2 string-to-key specifier
     * @return self
     */
    public static function fromBytes(string $bytes): self
    {
        $offset = 1;
        $salt = substr($bytes, $offset, self::SALT_LENGTH);
        $offset += self::SALT_LENGTH;
        $iteration = ord($bytes[$offset++]);
        $parallelism = ord($bytes[$offset++]);
        $memoryExponent = ord($bytes[$offset]);
        return new self($salt, $iteration, $parallelism, $memoryExponent);
    }

    /**
     * Check argon2 supported.
     *
     * @return bool
     */
    public static function argon2Supported(): bool
    {
        return function_exists("sodium_crypto_pwhash");
    }
}