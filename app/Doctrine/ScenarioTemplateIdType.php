<?php

namespace BrasseursApplis\Arrows\App\Doctrine;

use BrasseursApplis\Arrows\Id\ScenarioTemplateId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class ScenarioTemplateIdType extends GuidType
{
    const SCENARIO_TEMPLATE_ID = 'scenario_template_id';

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return self::SCENARIO_TEMPLATE_ID;
    }

    /**
     * @param  string           $value
     * @param  AbstractPlatform $platform
     * @return ScenarioTemplateId
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return new ScenarioTemplateId($value);
    }

    /**
     * @param  ScenarioTemplateId $value
     * @param  AbstractPlatform   $platform
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }
}
