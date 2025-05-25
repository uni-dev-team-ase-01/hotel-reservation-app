<?php

use Livewire\Volt\Volt;

Volt::route('/', 'home')->name('home');
Volt::route('/hotels', 'hotels.index')->name('hotels');
Volt::route('/about', 'about')->name('about');
Volt::route('/contact', 'contact')->name('contact');

Auth::routes();
