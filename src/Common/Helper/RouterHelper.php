<?php
namespace Common\Helper;


class RouterHelper
{
    public static function jsonDecode(string $input): array
    {
        return json_decode($input, true);
    }
}
