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
use MlSolutions\Discord\Parts\Channel\StageInstance;
use MlSolutions\Discord\WebSockets\Event;
use MlSolutions\Discord\Parts\Guild\Guild;

/**
 * @link https://discord.com/developers/docs/topics/gateway-events#stage-instance-update
 *
 * @since 7.0.0
 */
class StageInstanceUpdate extends Event
{
    /**
     * @inheritDoc
     */
    public function handle($data)
    {
        $stageInstancePart = $oldStageInstance = null;

        /** @var ?Guild */
        if ($guild = yield $this->discord->guilds->cacheGet($data->guild_id)) {
            /** @var ?Channel */
            if ($channel = yield $guild->channels->cacheGet($data->channel_id)) {
                /** @var ?StageInstance */
                if ($oldStageInstance = yield $channel->stage_instances->cacheGet($data->id)) {
                    // Swap
                    $stageInstancePart = $oldStageInstance;
                    $oldStageInstance = clone $oldStageInstance;

                    $stageInstancePart->fill((array) $data);
                }
            }
        }

        if ($stageInstancePart === null) {
            /** @var StageInstance */
            $stageInstancePart = $this->factory->part(StageInstance::class, (array) $data, true);
        }

        if (isset($channel)) {
            $channel->stage_instances->set($data->id, $stageInstancePart);
        }

        return [$stageInstancePart, $oldStageInstance];
    }
}
