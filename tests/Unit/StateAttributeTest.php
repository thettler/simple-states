<?php

declare(strict_types=1);

use Thettler\SimpleStates\Tests\Fixtures\BasicState;
use Thettler\SimpleStates\Tests\Fixtures\FaultyState;
use Thettler\SimpleStates\Tests\Fixtures\WithParamsState;

it('can get State Attribute', function () {
    expect(BasicState::Draft->getStateAttribute('color'))
        ->toBe('orange')
        ->and(BasicState::Published->getStateAttribute('color'))
        ->toBe('blue')
        ->and(BasicState::Approved->getStateAttribute('color'))
        ->toBe('green')
        ->and(BasicState::Archived->getStateAttribute('color'))
        ->toBe('gray')
        ->and(BasicState::Deleted->getStateAttribute('color'))
        ->toBe('red');
});

it('can get State pass params to attribute Attribute', function () {
    expect(WithParamsState::Approved->getStateAttribute('color', color: 'approved_color'))
        ->toBe('approved_color')
        ->and(WithParamsState::Archived->getStateAttribute('color', color: 'archived_color'))
        ->toBe('archived_color')
        ->and(WithParamsState::Deleted->getStateAttribute('color', color: 'deleted_color'))
        ->toBe('deleted_color');
});

it('throws exception if State Attribute is missing for case', function () {
    expect(FaultyState::Archived->getStateAttribute('color'));
})->throws(
    \Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException::class,
    'State Thettler\SimpleStates\Tests\Fixtures\FaultyState::Archived is missing attribute "color"',
);

it('throws exception if State Attribute is missing', function () {
    expect(FaultyState::Archived->getStateAttribute('not_existing_attribute'));
})->throws(
    \Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException::class,
    'State Thettler\SimpleStates\Tests\Fixtures\FaultyState::Archived is missing attribute "not_existing_attribute"',
);

it('throws exception if State Attribute is missing but Attribute has different Attribute', function () {
    expect(FaultyState::Approved->getStateAttribute('not_existing_attribute'));
})->throws(
    \Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException::class,
    'State Thettler\SimpleStates\Tests\Fixtures\FaultyState::Approved is missing attribute "not_existing_attribute"',
);

it('throws exception if State Attribute is missing but Attribute has different compute method', function () {
    expect(FaultyState::Deleted->getStateAttribute('not_existing_attribute'));
})->throws(
    \Thettler\SimpleStates\Exceptions\SimpleStateAttributeMissingException::class,
    'State Thettler\SimpleStates\Tests\Fixtures\FaultyState::Deleted is missing attribute "not_existing_attribute"',
);
