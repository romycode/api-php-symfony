<?php

declare(strict_types=1);

namespace App\Tests\Functional\System\Infrastructure\UI\Http\Controller;

use App\Tests\Support\FunctionalTester;
use Symfony\Component\HttpFoundation\Response;

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
