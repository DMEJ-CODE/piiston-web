<?php

use App\Livewire\Auth\Onboarding;
use App\Livewire\Auth\RegisterFlow;
use Livewire\Livewire;

/*
 * A non-empty OTP renders as a JSON array inside the double-quoted x-data
 * attribute. Raw quotes there would close the attribute early and spill the
 * Alpine source into the page as visible text, so the digits must be escaped.
 *
 * A partial code is used deliberately: filling all six digits fires the
 * component's updatedOtp() hook, which starts phone verification and is
 * unrelated to how the field renders.
 */

test('onboarding otp field escapes the digits json inside the x-data attribute', function () {
    $html = Livewire::test(Onboarding::class)
        ->set('step', 2)
        ->set('otp', '123')
        ->html();

    expect($html)->toContain('digits: [&quot;1&quot;');

    expect($html)->not->toContain('digits: ["1"');
});

test('onboarding otp field renders an empty digit list when no code is entered', function () {
    $html = Livewire::test(Onboarding::class)
        ->set('step', 2)
        ->set('otp', '')
        ->html();

    expect($html)->toContain('digits: []');
});

test('registration otp field escapes the digits json inside the x-data attribute', function () {
    $html = Livewire::test(RegisterFlow::class)
        ->set('step', 3)
        ->set('otp', '123')
        ->html();

    expect($html)->toContain('digits: [&quot;1&quot;');

    expect($html)->not->toContain('digits: ["1"');
});

test('registration otp field renders an empty digit list when no code is entered', function () {
    $html = Livewire::test(RegisterFlow::class)
        ->set('step', 3)
        ->set('otp', '')
        ->html();

    expect($html)->toContain('digits: []');
});

test('otp field keeps the whole alpine definition inside the x-data attribute', function () {
    $html = Livewire::test(Onboarding::class)
        ->set('step', 2)
        ->set('otp', '123')
        ->html();

    $attribute = str($html)
        ->after('x-data="{')
        ->before('}"')
        ->toString();

    // The browser decodes entities before Alpine reads the attribute, so the
    // definition it receives must be complete and contain the real digits.
    $decoded = html_entity_decode($attribute, ENT_QUOTES);

    expect($decoded)
        ->toContain('digits: ["1","2","3"]')
        ->toContain('focusNext')
        ->toContain('focusPrev');
});
