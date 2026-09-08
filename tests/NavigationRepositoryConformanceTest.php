<?php

declare(strict_types=1);

namespace Kumwe\Navigation\Tests;

use Kumwe\Navigation\Application\NavigationRepository;
use Kumwe\Navigation\Tests\Conformance\NavigationRepositoryContract;
use Kumwe\Navigation\Tests\Fixture\MemoryNavigationRepository;

final class NavigationRepositoryConformanceTest extends NavigationRepositoryContract
{
    protected function repository(): NavigationRepository
    {
        return new MemoryNavigationRepository();
    }
}
