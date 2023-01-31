#!/usr/bin/env php

<?php
include_once('./vendor/autoload.php');

use Barbowza\ParseArgs;

use App\Entity\User;


[$router, $helpers, ] = include_once('./bootstrap.php');

$getServices = $helpers->offsetGet('getServices');

$cliArgs = ParseArgs::parseArgs($_SERVER['argv']);
(match ($command = array_get($cliArgs, 0)) {

    'auth-token' => function () use ($cliArgs, $getServices) {

        $authService = $getServices()->get('auth');

        $type = array_get($cliArgs, 'type');
        if (!User::isValidType($type)) {

                return null;
        }

        echo $authService->getUserToken($type);
    },

    default => fn() => null,
})();
