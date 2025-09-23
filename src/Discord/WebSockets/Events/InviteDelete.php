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

use MlSolutions\Discord\WebSockets\Event;
use MlSolutions\Discord\Parts\Channel\Channel;
use MlSolutions\Discord\Parts\Channel\Invite;
use MlSolutions\Discord\Parts\Guild\Guild;

/**
 * @link https://discord.com/developers/docs/topics/gateway-events#invite-delete
 *
 * @since 5.0.0
 */
class InviteDelete extends Event
{
    /**
     * @inheritDoc
     */
    public function handle($data)
    {
        $invitePart = null;

        /** @var ?Guild */
        if ($guild = yield $this->discord->guilds->cacheGet($data->guild_id)) {
            /** @var ?Channel */
            if ($channel = yield $guild->channels->cacheGet($data->channel_id)) {
                /** @var ?Invite */
                $invitePart = yield $channel->invites->cachePull($data->code);
            }

            if ($invitePart === null) {
                /** @var ?Invite */
                $invitePart = yield $guild->invites->cachePull($data->code);
            }
        }

        return $invitePart ?? $data;
    }
}
