<?php

namespace App\Enums;

class Role
{
    public const SUPER_ADMIN = 'super_admin';
    public const ADMIN = 'admin';
    public const MEMBER = 'member';
    public const SALES = 'sales';
    public const MANAGER = 'manager';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::SUPER_ADMIN,
            self::ADMIN,
            self::MEMBER,
            self::SALES,
            self::MANAGER,
        ];
    }

    /**
     * @return list<string>
     */
    public static function canCreateShortUrls(): array
    {
        return [self::SALES, self::MANAGER];
    }
}
