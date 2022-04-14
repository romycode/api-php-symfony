<?php

declare(strict_types=1);

namespace App\System\Domain;

interface SystemRepository
{
    public function getDatabaseStatus(): System;
}
