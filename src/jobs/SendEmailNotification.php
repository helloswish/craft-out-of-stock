<?php
/**
 * Out of Stock plugin for Craft CMS 3.x
 *
 * Get notified when products are (almost) out of stock.
 *
 * @link      https://swishdigital.co
 * @copyright Copyright (c) 2020 Swish Digital
 */

namespace swishdigital\outofstock\jobs;

use swishdigital\outofstock\OutOfStock;

use Craft;
use craft\queue\BaseJob;

/**
 * SendEmailNotification job
 *
 * Jobs are run in separate process via a Queue of pending jobs. This allows
 * you to spin lengthy processing off into a separate PHP process that does not
 * block the main process.
 *
 * You can use it like this:
 *
 * use swishdigital\outofstock\jobs\SendEmailNotification as SendEmailNotificationJob;
 *
 * $queue = Craft::$app->getQueue();
 * $jobId = $queue->push(new SendEmailNotificationJob([
 *     'description' => Craft::t('out-of-stock', 'This overrides the default description'),
 *     'someAttribute' => 'someValue',
 * ]));
 *
 * The key/value pairs that you pass in to the job will set the public properties
 * for that object. Thus whatever you set 'someAttribute' to will cause the
 * public property $someAttribute to be set in the job.
 *
 * Passing in 'description' is optional, and only if you want to override the default
 * description.
 *
 * More info: https://github.com/yiisoft/yii2-queue
 *
 * @author    Swish Digital
 * @package   OutOfStock
 * @since     3.0.0
 */
class SendEmailNotification extends BaseJob
{
    // Public Properties
    // =========================================================================

    /**
     * Some attribute
     *
     * @var int
     */
    public $variantId;

    /**
     * Some attribute
     *
     * @var string|array
     */
    public $email;

    // Public Methods
    // =========================================================================

    /**
     * When the Queue is ready to run your job, it will call this method.
     * You don't need any steps or any other special logic handling, just do the
     * jobs that needs to be done here.
     *
     * More info: https://github.com/yiisoft/yii2-queue
     */
    public function execute($queue)
    {
        OutOfStock::$plugin->outOfStockService->sendMail($this->variantId, $this->email);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Returns a default description for [[getDescription()]], if [[description]] isn’t set.
     *
     * @return string The default task description
     */
    protected function defaultDescription(): string
    {
        return Craft::t('out-of-stock', 'Send Out of Stock Notification');
    }
}
