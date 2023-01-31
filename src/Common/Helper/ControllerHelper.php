<?php
namespace Common\Helper;

use Common\Entity\Response;

class ControllerHelper
{
    public static function getResponse(\Closure $callback, int $succesStatusCode = 200, int $errorStatusCode = 500): array
    {
        try {

            return Response::getSuccessResponse($callback(), $succesStatusCode);
        } catch (\Exception $e) {

            if (\ORM::get_db()->inTransaction()) {

                \ORM::get_db()->rollBack();
            }

            return Response::getErrorResponse(
                [
                    'messages' => [$e->getMessage()],
                    'trace' => $e->getTraceAsString(),
                ],
                $errorStatusCode,
            );
        }
    }

    public static function getErrorResponse(array $messages, int $errorStatusCode): array
    {
        return Response::getErrorResponse(
            [
                'messages' => $messages,
            ],
            $errorStatusCode,
        );
    }
}
