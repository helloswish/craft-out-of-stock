<?php
/**
 * Out of Stock plugin for Craft CMS 3.x
 *
 * Get notified when products are (almost) out of stock.
 *
 * @link      https://stenvdb.be
 * @copyright Copyright (c) 2019 Sten Van den Bergh
 */

/**
 * Out of Stock config.php
 *
 * This file exists only as a template for the Out of Stock settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'out-of-stock.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [

    "recipients" => '',
    "stockThreshold" => 0,
    "emailTemplatePath" => '',
    "sendEmail" => true,
    "emailSubject" => "Product is low on stock"

];
