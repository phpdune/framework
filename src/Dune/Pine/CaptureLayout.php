<?php

declare(strict_types=1);

namespace Dune\Pine;

use Dune\Pine\FileMapper;

class CaptureLayout
{
    /**
     * \Dune\Pine\FileMapper instance
     *
     * @var FileMapper
     */
    private FileMapper $mapper;
    /**
     * layout file name
     *
     * @var ?string
     */
    private ?string $layoutName = null;
    /**
