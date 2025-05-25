<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('hotels.index');

    $component->assertSee('');
});
