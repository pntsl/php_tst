<?php
namespace App\Entity;


class User {
    public const TYPE_CUSTOMER = 'CUSTOMER';
    public const TYPE_OWNER = 'OWNER';
    public const TYPE_COURIER = 'COURIER';

    public static function isValidType(string $type): bool
    {
        return in_array($type, [
            self::TYPE_CUSTOMER,
            self::TYPE_OWNER,
            self::TYPE_COURIER,
        ]);
    }
}
