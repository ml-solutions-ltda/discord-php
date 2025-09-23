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

namespace MlSolutions\Discord\WebSockets\Events;

use MlSolutions\Discord\Parts\Channel\Channel;
use MlSolutions\Discord\Parts\Channel\Message;
use MlSolutions\Discord\Parts\Guild\Guild;
use MlSolutions\Discord\Parts\Thread\Thread;
use MlSolutions\Discord\WebSockets\Event;

/**
 * @link https://discord.com/developers/docs/topics/gateway-events#thread-create
 *
 * @since 7.0.0
 */
class ThreadCreate extends Event
{
    /**
     * @inheritDoc
     */
    public function handle($data)
    {
        /** @var Thread */
        $threadPart = $this->factory->part(Thread::class, (array) $data, true);

        /** @var ?Guild */
        if ($guild = yield $this->discord->guilds->cacheGet($data->guild_id)) {
            /** @var ?Channel */
            if ($parent = yield $guild->channels->cacheGet($data->parent_id)) {
                $parent->last_message_id = $data->id;
                $parent->threads->set($data->id, $threadPart);
                /** @var ?Message */
                if ($messageSource = yield $parent->messages->cacheGet($data->id)) {
                    if ($messageSource->has_thread) {
                        $messageSource->thread = $data;
                        $parent->messages->set($messageSource->id, $messageSource);
                    }
                }
            }
        }

        return $threadPart;
    }
}
