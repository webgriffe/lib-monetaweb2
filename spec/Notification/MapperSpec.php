<?php

namespace spec\Webgriffe\LibMonetaWebDue\Notification;

use PhpSpec\ObjectBehavior;
use Webgriffe\LibMonetaWebDue\Notification\Mapper;

class MapperSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Mapper::class);
    }
}
