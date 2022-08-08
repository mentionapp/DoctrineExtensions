<?php

/*
 * This file is part of the Doctrine Behavioral Extensions package.
 * (c) Gediminas Morkevicius <gediminas.morkevicius@gmail.com> http://www.gediminasm.org
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gedmo\Timestampable\Mapping\Event\Adapter;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Gedmo\Mapping\Event\Adapter\ODM as BaseAdapterODM;
use Gedmo\Timestampable\Mapping\Event\TimestampableAdapter;
use Mention\Kebab\Date\DateUtils;

/**
 * Doctrine event adapter for ODM adapted
 * for Timestampable behavior
 *
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 */
final class ODM extends BaseAdapterODM implements TimestampableAdapter
{
    /**
     * @param ClassMetadata $meta
     */
    public function getDateValue($meta, $field)
    {
        $mapping = $meta->getFieldMapping($field);
        if (isset($mapping['type']) && 'timestamp' === $mapping['type']) {
            return DateUtils::now()->getTimestamp();
        }
        if (isset($mapping['type']) && in_array($mapping['type'], ['date_immutable', 'time_immutable', 'datetime_immutable', 'datetimetz_immutable'], true)) {
            return DateUtils::now();
        }

        return DateUtils::toMutable(
            DateUtils::fromStringTz(
                number_format(microtime(true), 6, '.', ''),
                new \DateTimeZone(date_default_timezone_get()),
                'U.u'
            )
        );
    }
}
