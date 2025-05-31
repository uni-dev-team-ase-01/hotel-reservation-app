<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('admin.dashboard');

    $component->assertSee('');
});
