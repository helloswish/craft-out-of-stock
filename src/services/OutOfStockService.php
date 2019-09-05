<?php
/**
 * Out of Stock plugin for Craft CMS 3.x
 *
 * Get notified when products are (almost) out of stock.
 *
 * @link      https://stenvdb.be
 * @copyright Copyright (c) 2019 Sten Van den Bergh
 */

namespace stenvdb\outofstock\services;

use Craft;

use craft\web\View;
use craft\mail\Message;
use craft\base\Component;
use stenvdb\outofstock\OutOfStock;
use craft\commerce\elements\Variant;
use stenvdb\outofstock\events\LowStockEvent;

/**
 * OutOfStockService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Sten Van den Bergh
 * @package   OutOfStock
 * @since     1.0.0
 */
class OutOfStockService extends Component
{
    /**
     * @event SaveEvent The event that is triggered before a guest entry is saved.
     */
    const EVENT_VARIANT_LOW_ON_STOCK = 'variantLowOnStock';

    // Public Methods
    // =========================================================================

    public function checkVariantStock($variant, $original = null)
    {
        $settings = OutOfStock::$plugin->getSettings();

        // Do nothing if it has unlimited stock
        if ($variant->hasUnlimitedStock) {
            return;
        }

        // Check stock and or compare to old stock
        if (
            ($variant->stock <= $settings->stockThreshold && !$original)
            || ($original && $variant->stock <= $settings->stockThreshold && $variant->stock !== $original->stock)
        ) {
            // Fire event that stock has hit a low point
            $event = new LowStockEvent(['variant' => $variant]);
            $this->trigger(self::EVENT_VARIANT_LOW_ON_STOCK, $event);
        }
    }

    public function checkStockAfterOrder($order) {
        foreach($order->lineItems as $lineItem) {
            $this->checkVariantStock($lineItem->purchasable);
        }
    }

    public function sendMail($variantId, $recipient)
    {
        $settings = OutOfStock::$plugin->getSettings();
        $view = Craft::$app->getView();
        $oldMode = $view->getTemplateMode();
        $originalLanguage = Craft::$app->language;
        $variant = Variant::findOne($variantId);
        $renderVariables = ['variant' => $variant];
        $emailTemplatePath = ($settings->emailTemplatePath != '') ? $settings->emailTemplatePath : 'out-of-stock/email';
        
        if (strpos($emailTemplatePath, 'out-of-stock/email') !== false) { 
            $view->setTemplateMode($view::TEMPLATE_MODE_CP);
        } else {
            $view->setTemplateMode($view::TEMPLATE_MODE_SITE);
        }

        if (!$variant) {
            $error = Craft::t('out-of-stock', 'Could not find Variant for Out of Stock Notification email.');
            Craft::error($error, __METHOD__);
            Craft::$app->language = $originalLanguage;
            $view->setTemplateMode($oldMode);
            return false;
        }

        $templatePath = $view->renderString($emailTemplatePath, $renderVariables);

        if (!$view->doesTemplateExist($templatePath)) {
            $error = Craft::t('out-of-stock', 'Email template does not exist at “{templatePath}”.', [
                'templatePath' => $templatePath,
            ]);
            Craft::error($error, __METHOD__);
            Craft::$app->language = $originalLanguage;
            $view->setTemplateMode($oldMode);
            return false;
        }

        $mail = new Message();
        $mail->setTo($recipient);
        $mail->setSubject($settings->emailSubject);
        $mail->setHtmlBody($view->renderTemplate($templatePath, $renderVariables));

         // Try and send it
         try {
            if (!Craft::$app->getMailer()->send($mail)) {
                $error = Craft::t('out-of-stock', 'Out of Stock email “email” could not be sent');
                Craft::error($error, __METHOD__);
                $view->setTemplateMode($oldMode);
                Craft::$app->language = $originalLanguage;
                return false;
            }
        } catch (\Exception $e) {
            $error = Craft::t('out-of-stock', 'Out of Stock email could not be sent', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'order' => $order->id
            ]);
            Craft::error($error, __METHOD__);
            $view->setTemplateMode($oldMode);
            Craft::$app->language = $originalLanguage;
            return false;
        }

        return true;
    }
}
