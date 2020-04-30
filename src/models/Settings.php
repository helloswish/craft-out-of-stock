<?php
/**
 * Out of Stock plugin for Craft CMS 3.x
 *
 * Get notified when products are (almost) out of stock.
 *
 * @link      https://swishdigital.co
 * @copyright Copyright (c) 2020 Swish Digital
 */

namespace swishdigital\outofstock\models;

use swishdigital\outofstock\OutOfStock;

use Craft;
use craft\base\Model;

/**
 * OutOfStock Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Swish Digital
 * @package   OutOfStock
 * @since     3.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Recipients model attribute
     *
     * @var string
     */
    public $recipients = '';

    /**
     * Stock Threshold model attribute
     *
     * @var integer
     */
    public $stockThreshold = 0;

    /**
     * Recipients model attribute
     *
     * @var string
     */
    public $emailTemplatePath = '';

    /**
     * Stock Threshold model attribute
     *
     * @var boolean
     */
    public $sendEmail = false;

    /**
     * Stock Threshold model attribute
     *
     * @var string
     */
    public $emailSubject = 'Product is low on stock';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['recipients', 'string'],
            ['stockThreshold', 'integer'],
            ['stockThreshold', 'default', 'value' => 0],
            ['emailTemplatePath', 'string'],
            ['sendEmail', 'boolean'],
            ['sendEmail', 'default', 'value' => false],
            ['emailSubject', 'string'],
        ];
    }
}
