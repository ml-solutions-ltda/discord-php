<?php

declare(strict_types=1);

/*
 * This file is a part of the DiscordPHP project.
 *
 * Copyright (c) 2015-present David Cole <david.cole1340@gmail.com>
 *
 * This file is subject to the MIT license that is bundled
 * with this source code in the LICENSE.md file.
 */

namespace MlSolutions\Discord\Repository\Thread;

use MlSolutions\Discord\Http\Endpoint;
use MlSolutions\Discord\Parts\Thread\Member;
use MlSolutions\Discord\Repository\AbstractRepository;

/**
 * Contains members of a thread.
 *
 * @see Member
 * @see \Discord\Parts\Thread\Thread
 *
 * @since 7.0.0
 *
 * @method Member|null get(string $discrim, $key)
 * @method Member|null pull(string|int $key, $default = null)
 * @method Member|null first()
 * @method Member|null last()
 * @method Member|null find(callable $callback)
 */
class MemberRepository extends AbstractRepository
{
    /**
     * @inheritDoc
     */
    protected $discrim = 'user_id';

    /**
     * @inheritDoc
     */
    protected $endpoints = [
        'all' => Endpoint::THREAD_MEMBERS,
        'get' => Endpoint::THREAD_MEMBER,
    ];

    /**
     * @inheritDoc
     */
    protected $class = Member::class;

    /**
     * @inheritDoc
     */
    public function __construct($discord, array $vars = [])
    {
        unset($vars['channel_id']); // For thread
        parent::__construct($discord, $vars);
    }
}
