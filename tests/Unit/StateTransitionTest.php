<?php

declare(strict_types=1);

use Thettler\SimpleStates\Tests\Fixtures\BasicTransitionState;
use Thettler\SimpleStates\Tests\Fixtures\BuggyState;

it('can get possible Transition Options', function () {
    expect(BasicTransitionState::Draft->getTransitions())
        ->toBe([BasicTransitionState::Approved, BasicTransitionState::Archived])
        ->and(BasicTransitionState::Approved->getTransitions())
        ->toBe([BasicTransitionState::Published, BasicTransitionState::Archived])
        ->and(BasicTransitionState::Approved->getTransitions(flag2: true))
        ->toBe([BasicTransitionState::Draft])
        ->and(BasicTransitionState::Published->getTransitions())
        ->toBe([BasicTransitionState::Archived])
        ->and(BasicTransitionState::Published->getTransitions(flag: true))
        ->toBe([BasicTransitionState::Draft])
        ->and(BasicTransitionState::Archived->getTransitions())
        ->toBe([BasicTransitionState::Draft])
        ->and(BasicTransitionState::NoMethod->getTransitions(flag: true))
        ->toBeEmpty();
});

it('can get if Transition is possible', function () {
    expect(BasicTransitionState::Draft->canTransitionTo(BasicTransitionState::Approved))
        ->toBeTrue()
        ->and(BasicTransitionState::Draft->canTransitionTo(BasicTransitionState::Published))
        ->toBeFalse();
});

it('can transition to new State', function () {
    expect(BasicTransitionState::Published->transitionTo(BasicTransitionState::Archived))
        ->toBe(BasicTransitionState::Archived);
});

it('can not transition to new State which is not allowed', function () {
    expect(BasicTransitionState::Published->transitionTo(BasicTransitionState::Approved))
        ->toBe(BasicTransitionState::Approved);
})->throws(
    'State Thettler\SimpleStates\Tests\Fixtures\BasicTransitionState::Published is not allowed to transition to Thettler\SimpleStates\Tests\Fixtures\BasicTransitionState::Approved',
);

it('throws error on wrong Class', function () {
    expect(BuggyState::WrongClass->getTransitions());
})->throws('Invalid class name: I\DONT\EXIST');
