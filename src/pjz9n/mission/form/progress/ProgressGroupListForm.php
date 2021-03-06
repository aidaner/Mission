<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of Mission.
 *
 * Mission is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Mission is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Mission. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace pjz9n\mission\form\progress;

use dktapps\pmforms\MenuOption;
use pjz9n\mission\language\LanguageHolder;
use pjz9n\mission\mission\MissionList;
use pjz9n\mission\pmformsaddon\AbstractMenuForm;
use pocketmine\Player;

class ProgressGroupListForm extends AbstractMenuForm
{
    /** @var string[] */
    private $groups;

    public function __construct()
    {
        $this->groups = array_values(MissionList::getAllGroups());
        $options = [];
        foreach ($this->groups as $group) {
            $options[] = new MenuOption($group);
        }
        $options[] = new MenuOption(LanguageHolder::get()->translateString("unspecified"));
        parent::__construct(
            LanguageHolder::get()->translateString("mission.group.list"),
            LanguageHolder::get()->translateString("mission.group.pleaseselect"),
            $options
        );
    }

    public function onSubmit(Player $player, int $selectedOption): void
    {
        if (!isset($this->groups[$selectedOption])) {
            $player->sendForm(new ProgressListForm($player));
            return;
        }
        $player->sendForm(new ProgressListForm($player, $this->groups[$selectedOption]));
    }
}
