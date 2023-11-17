<?php

declare(strict_types=1);

namespace Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
use Tests\Support\FunctionalTester;

class DatabaseStatusControllerCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function shouldReturnStatusOk(FunctionalTester $I): void
    {
        $I->sendGET('/v1/system');
        $I->seeResponseCodeIs(Response::HTTP_OK);
        $I->seeResponseContainsJson([
            'data' => [
                'status' => 'OK',
            ],
        ]);
    }
}
