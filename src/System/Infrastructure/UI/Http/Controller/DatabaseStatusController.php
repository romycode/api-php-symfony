<?php

declare(strict_types=1);

namespace App\System\Infrastructure\UI\Http\Controller;

use App\Shared\Domain\Messaging\Query\QueryBus;
use App\Shared\Infrastructure\UI\Http\Controller\Controller;
use App\System\Application\GetDatabaseStatusQuery;
use App\System\Application\GetDatabaseStatusResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class DatabaseStatusController implements Controller
{
    public function __construct(private QueryBus $bus)
    {
    }

    #[Route('/v1/system', name: 'system_status', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        /** @var GetDatabaseStatusResponse $response */
        $response = $this->bus->ask(new GetDatabaseStatusQuery());

        return new JsonResponse(
            [
                'data' => [
                    'status' => match ($response->isSuccess()) {
                        true => 'OK',
                        false => 'KO',
                    },
                ],
            ],
            match ($response->isSuccess()) {
                true => JsonResponse::HTTP_OK,
                default => JsonResponse::HTTP_SERVICE_UNAVAILABLE,
            }
        );
    }
}
