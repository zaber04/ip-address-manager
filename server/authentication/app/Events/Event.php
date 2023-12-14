<?php

declare(strict_types=1);

namespace Authentication\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;
}
