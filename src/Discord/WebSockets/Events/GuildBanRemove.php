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

use MlSolutions\Discord\Parts\Guild\Ban;
use MlSolutions\Discord\WebSockets\Event;

/**
 * @link https://discord.com/developers/docs/topics/gateway-events#guild-ban-remove
 *
 * @since 2.1.3
 */
class GuildBanRemove extends Event
{
    /**
     * @inheritDoc
     */
    public function handle($data)
    {
        $banPart = null;

        /** @var ?\Discord\Parts\Guild\Guild */
        if ($guild = yield $this->discord->guilds->cacheGet($data->guild_id)) {
            /** @var ?Ban */
            if ($banPart = yield $guild->bans->cachePull($data->user->id)) {
                $banPart->fill((array) $data);
                $banPart->created = false;
            }
        }

        $this->cacheUser($data->user);

        return $banPart ?? $this->factory->part(Ban::class, (array) $data);
    }
}
