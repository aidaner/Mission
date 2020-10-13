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

namespace pjz9n\mission\language;

use aieuo\mineflow\utils\Language;
use InvalidStateException;
use pjz9n\mission\event\MissionCompleteEvent;
use pjz9n\mission\event\RewardReceiveEvent;
use pocketmine\lang\BaseLang;
use pocketmine\Server;
use pocketmine\utils\Config;

final class LanguageHolder
{
    /** @var BaseLang|null */
    private static $lang = null;

    /** @var string */
    private static $localePath;

    /** @var string */
    private static $fallbackLanguage;

    /** @var Config */
    private static $config;

    public static function init(string $localePath, string $fallbackLanguage, Config $config): void
    {
        self::$localePath = $localePath;
        self::$fallbackLanguage = $fallbackLanguage;
        self::$config = $config;
        self::update();
    }

    public static function update(): void
    {
        $language = ($lang = self::getLanguage()) === "default"
            ? Server::getInstance()->getLanguage()->getLang() : $lang;
        self::$lang = new BaseLang($language, self::getLocalePath(), self::getFallbackLanguage());
        self::updateMineflowLanguage();
    }

    public static function updateMineflowLanguage(): void
    {
        Language::add([
            "trigger.type.missionreward" => LanguageHolder::get()->translateString("mineflow.trigger.type.missionreward"),
            "trigger.type.missionreward.unknown" => LanguageHolder::get()->translateString("mineflow.trigger.type.missionreward.unknown"),
            "trigger.missionreward.select.title" => LanguageHolder::get()->translateString("mineflow.trigger.missionreward.select.title"),
            "trigger.missionreward.select.dropdown" => LanguageHolder::get()->translateString("mineflow.trigger.missionreward.select.dropdown"),
            "trigger.event." . MissionCompleteEvent::class => LanguageHolder::get()->translateString("mineflow.trigger.event." . MissionCompleteEvent::class),
            "trigger.event." . RewardReceiveEvent::class => LanguageHolder::get()->translateString("mineflow.trigger.event." . RewardReceiveEvent::class),
            "category.mission" => LanguageHolder::get()->translateString("mineflow.category.mission"),
            "action.addMissionStep.name" => LanguageHolder::get()->translateString("mineflow.action.addMissionStep.name"),
            "action.addMissionStep.detail" => LanguageHolder::get()->translateString("mineflow.action.addMissionStep.detail"),
            "action.addMissionStep.mission.notFound" => LanguageHolder::get()->translateString("mineflow.action.addMissionStep.mission.notFound"),
            "action.addMissionStep.form.mission" => LanguageHolder::get()->translateString("mineflow.action.addMissionStep.form.mission"),
            "action.addMissionStep.form.step" => LanguageHolder::get()->translateString("mineflow.action.addMissionStep.form.step"),
        ]);
    }

    public static function get(): BaseLang
    {
        if (self::$lang === null) throw new InvalidStateException("Not initialized");
        return self::$lang;
    }

    public static function getLocalePath(): string
    {
        if (self::$localePath === null) throw new InvalidStateException("Not initialized");
        return self::$localePath;
    }

    public static function getFallbackLanguage(): string
    {
        if (self::$fallbackLanguage === null) throw new InvalidStateException("Not initialized");
        return self::$fallbackLanguage;
    }

    public static function getLanguage(): string
    {
        return (string)self::getConfig()->get("language");
    }

    public static function setLanguage(string $language): void
    {
        self::getConfig()->set("language", $language);
        self::update();
    }

    private static function getConfig(): Config
    {
        if (self::$config === null) throw new InvalidStateException("Not initialized");
        return self::$config;
    }

    private function __construct()
    {
        //
    }
}
