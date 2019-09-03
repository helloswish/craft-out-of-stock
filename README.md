# Out of Stock plugin for Craft CMS 3.x

Get notified when products are (almost) out of stock.

## Requirements

This plugin requires Craft CMS 3.0.0 or later and Craft Commerce 2.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require stenvdb/craft-out-of-stock

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Out of Stock.

## Out of Stock Overview

Out of Stock will fire an event when a variant has surpassed a stock threshold point. Out of Stock can send an email for you if enabled in config.

It will fire a stock warning event on 2 occasions:
* Every time an paid order has been submitted
* When a variant is saved in the control panel and the stock value was manually updated

## Configuring Out of Stock

Either by copy pasting `config.php` file to 'config/outofstock.php' or through the control panel settings.

## Using Out of Stock

If enabled in config, Out of Stock will send an email to one or more recipients. You can also hook in to the event and write your own logic:

```
use stenvdb\outofstock\events\LowStockEvent;
use stenvdb\outofstock\services\OutOfStockService;

Event::on(OutOfStockService::class, OutOfStockService::EVENT_VARIANT_LOW_ON_STOCK, function(LowStockEvent $event) {
    // Do something when stock is sold out or critically low
});
```

Brought to you by [Sten Van den Bergh](https://stenvdb.be)
