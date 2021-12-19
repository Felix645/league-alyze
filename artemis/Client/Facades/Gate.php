<?php

namespace Artemis\Client\Facades;

use Closure;

/**
 * Class Hash
 * @package Artemis\Client\Facades
 *
 * @method static void define(string $gate_key, Closure|array $action) Defines a new gate.
 * @method static bool allows(string $gate_key, null|mixed|array $arguments = null) Checks if the given gate is allowed.
 * @method static bool denies(string $gate_key, null|mixed|array $arguments = null) Checks if the given gate is denied.
 * @method static bool any(array $gates, null|mixed|array $arguments = null) Checks if any of the given gates is allowed.
 * @method static bool none(array $gates, null|mixed|array $arguments = null) Checks if ALL the given gates are denied.
 * @method static void authorize(string $gate_key, null|mixed|array $arguments = null) Checks if the given gate is allowed. If not a ForbiddenException will be thrown.
 * @method static void before(Closure $action) Defines a closure to be executed BEFORE all gates.
 * @method static void after(Closure $action) Defines a closure to be executed AFTER all gates.
 *
 * @uses \Artemis\Core\Auth\Access\GateManager::define()
 * @uses \Artemis\Core\Auth\Access\GateManager::allows()
 * @uses \Artemis\Core\Auth\Access\GateManager::denies()
 * @uses \Artemis\Core\Auth\Access\GateManager::any()
 * @uses \Artemis\Core\Auth\Access\GateManager::none()
 * @uses \Artemis\Core\Auth\Access\GateManager::authorize()
 * @uses \Artemis\Core\Auth\Access\GateManager::before()
 * @uses \Artemis\Core\Auth\Access\GateManager::after()
 */
class Gate extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'gate_manager';
    }
}