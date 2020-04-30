<?php
/**
 * Out of Stock plugin for Craft CMS 3.x
 *
 * Get notified when products are (almost) out of stock.
 *
 * @link      https://swishdigital.co
 * @copyright Copyright (c) 2020 Swish Digital
 */

namespace swishdigital\outofstock\events;

use yii\base\Event;

/**
 * @author    swishdigital
 * @package   OutOfStock
 * @since     1.0.0
 */
class LowStockEvent extends Event
{
    // Properties
    // =========================================================================

    /**
     * @var Variant The variant that's low on stock
     */
    public $variant;
}
