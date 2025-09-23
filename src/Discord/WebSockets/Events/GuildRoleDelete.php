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

use MlSolutions\Discord\Parts\Guild\Guild;
use MlSolutions\Discord\Parts\Guild\Role;
use MlSolutions\Discord\WebSockets\Event;

/**
 * @link https://discord.com/developers/docs/topics/gateway-events#guild-role-delete
 *
 * @since 2.1.3
 */
class GuildRoleDelete extends Event
{
    /**
     * @inheritDoc
     */
    public function handle($data)
    {
        /** @var ?Guild */
        if ($guild = yield $this->discord->guilds->cacheGet($data->guild_id)) {
            /** @var ?Role */
            $rolePart = yield $guild->roles->cachePull($data->role_id);
        }

        return $rolePart ?? $data;
    }
}
