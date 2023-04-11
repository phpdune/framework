<?php

declare(strict_types=1);

namespace Dune\Http;

interface ValidaterInterface 
{
  public function validation(): ?array;
}