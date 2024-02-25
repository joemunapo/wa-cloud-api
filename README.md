## What It Does

This package makes it easy for developers to access [WhatsApp Cloud API](https://developers.facebook.com/docs/whatsapp/cloud-api "WhatsApp Cloud API") service in their PHP code.

The first **1,000 conversations** each month are free from WhatsApp Cloud API. A conversation.

## Getting Started

Please create and configure your Facebook WhatsApp application following the ["Get Stared"](https://developers.facebook.com/docs/whatsapp/cloud-api/get-started) section of the official guide.

Minimum requirements – To run the SDK, your system will require **PHP >= 7.4** with a recent version of **CURL >=7.19.4** compiled with OpenSSL and zlib.

## Installation ⚠️⚠️  (Use github, it has not been added to Packagist)
<!-- ~~ -->
```json
    "autoload": {
        "psr-4": {
            ...
        }
    },
    "autoload-dev": {
        "psr-4": {
            ...
        }
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/joemags-apps/wa-cloud-api.git"
        }
    ],

```

Then run ```composer require joemags-apps/wa-cloud-api```
## Quick Examples

### Send a text message

```php
<?php

// Require the Composer autoloader.
require 'vendor/autoload.php';

use JoemagsApps\WACloudApi\WACloudApi;

// Instantiate the WACloudApi super class.
$wa_cloud_api = new WACloudApi([
    'from_phone_number_id' => 'your-configured-from-phone-number-id',
    'access_token' => 'your-facebook-whatsapp-application-token',
]);

$wa_cloud_api->sendTextMessage('34676104574', 'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.joemunapo.zw3.co');
```

### Send a document

You can send documents in two ways: by uploading a file to the WhatsApp Cloud servers (where you will receive an identifier) or from a link to a document published on internet.

```php
<?php

use JoemagsApps\WACloudApi\Message\Media\LinkID;
use JoemagsApps\WACloudApi\Message\Media\MediaObjectID;

$document_id = '341476474779872';
$document_name = 'whatsapp-cloud-api-from-id.pdf';
$document_caption = 'WhastApp API Cloud Guide';

// With the Media Object ID of some document upload on the WhatsApp Cloud servers
$media_id = new MediaObjectID($document_id);
$wa_cloud_api->sendDocument('34676104574', $media_id, $document_name, $document_caption);

// Or
$document_link = 'https://joemunapo.zw3.co/wp-content/uploads/2022/05/image.png';
$link_id = new LinkID($document_link);
$wa_cloud_api->sendDocument('34676104574', $link_id, $document_name, $document_caption);
```

### Send a template message

```php
<?php

$wa_cloud_api->sendTemplate('34676104574', 'hello_world', 'en_US'); // Language is optional
```

You also can build templates with parameters:

```php
<?php

use JoemagsApps\WACloudApi\Message\Template\Component;

$component_header = [];

$component_body = [
    [
        'type' => 'text',
        'text' => '*Mr Jones*',
    ],
];

$component_buttons = [
    [
        'type' => 'button',
        'sub_type' => 'quick_reply',
        'index' => 0,
        'parameters' => [
            [
                'type' => 'text',
                'text' => 'Yes',
            ]
        ]
    ],
    [
        'type' => 'button',
        'sub_type' => 'quick_reply',
        'index' => 1,
        'parameters' => [
            [
                'type' => 'text',
                'text' => 'No',
            ]
        ]
    ]
];

$components = new Component($component_header, $component_body, $component_buttons);
$wa_cloud_api->sendTemplate('34676104574', 'sample_issue_resolution', 'en_US', $components); // Language is optional
```

### Send an audio message

```php
<?php

use JoemagsApps\WACloudApi\Message\Media\LinkID;

$audio_link = 'https://joemunapo.zw3.co/wp-content/uploads/2022/05/file_example_OOG_1MG.ogg';
$link_id = new LinkID($audio_link);
$wa_cloud_api->sendAudio('34676104574', $link_id);
```

### Send an image message

```php
<?php

use JoemagsApps\WACloudApi\Message\Media\LinkID;
use JoemagsApps\WACloudApi\Message\Media\MediaObjectID;

$link_id = new LinkID('http(s)://image-url');
$wa_cloud_api->sendImage('<destination-phone-number>', $link_id);

//or

$media_id = new MediaObjectID('<image-object-id>');
$wa_cloud_api->sendImage('<destination-phone-number>', $media_id);
```

### Send a video message

```php
<?php

use JoemagsApps\WACloudApi\Message\Media\LinkID;
use JoemagsApps\WACloudApi\Message\Media\MediaObjectID;

$link_id = new LinkID('http(s)://video-url');
$wa_cloud_api->sendVideo('<destination-phone-number>', $link_id, '<video-caption>');

//or

$media_id = new MediaObjectID('<image-object-id>');
$wa_cloud_api->sendVideo('<destination-phone-number>', $media_id, '<video-caption>');
```

### Send a sticker message

Stickers sample: <https://github.com/WhatsApp/stickers>

```php
<?php

use JoemagsApps\WACloudApi\Message\Media\LinkID;
use JoemagsApps\WACloudApi\Message\Media\MediaObjectID;

$link_id = new LinkID('http(s)://sticker-url');
$wa_cloud_api->sendSticker('<destination-phone-number>', $link_id);

//or

$media_id = new MediaObjectID('<sticker-object-id>');
$wa_cloud_api->sendSticker('<destination-phone-number>', $media_id);
```

### Send a location message

```php
<?php

$wa_cloud_api->sendLocation('<destination-phone-number>', $longitude, $latitude, $name, $address);
```

### Send a contact message

```php
<?php

use JoemagsApps\WACloudApi\Message\Contact\ContactName;
use JoemagsApps\WACloudApi\Message\Contact\Phone;
use JoemagsApps\WACloudApi\Message\Contact\PhoneType;

$name = new ContactName('Adams', 'Smith');
$phone = new Phone('34676204577', PhoneType::CELL());

$wa_cloud_api->sendContact('<destination-phone-number>', $name, $phone);
```

### Send a list message

```php
<?php

use JoemagsApps\WACloudApi\Message\OptionsList\Row;
use JoemagsApps\WACloudApi\Message\OptionsList\Section;
use JoemagsApps\WACloudApi\Message\OptionsList\Action;

$rows = [
    new Row('1', '⭐️', "Experience wasn't good enough"),
    new Row('2', '⭐⭐️', "Experience could be better"),
    new Row('3', '⭐⭐⭐️', "Experience was ok"),
    new Row('4', '⭐⭐️⭐⭐', "Experience was good"),
    new Row('5', '⭐⭐️⭐⭐⭐️', "Experience was excellent"),
];
$sections = [new Section('Stars', $rows)];
$action = new Action('Submit', $sections);

$wa_cloud_api->sendList(
    '<destination-phone-number>',
    'Rate your experience',
    'Please consider rating your shopping experience in our website',
    'Thanks for your time',
    $action
);
```

### Send a button reply message

```php
<?php

use JoemagsApps\WACloudApi\WACloudApi;
use JoemagsApps\WACloudApi\Message\ButtonReply\Button;
use JoemagsApps\WACloudApi\Message\ButtonReply\ButtonAction;

$wa_cloud_api = new WACloudApi([
  'from_phone_number_id' => 'your-configured-from-phone-number-id',
  'access_token' => 'your-facebook-whatsapp-application-token' 
]);

$rows = [
    new Button('button-1', 'Yes'),
    new Button('button-2', 'No'),
    new Button('button-3', 'Not Now'),
];
$action = new ButtonAction($rows);

$wa_cloud_api->sendButton(
    '<destination-phone-number>',
    'Would you like to rate us on Trustpilot?',
    $action,
    'RATE US', // Optional: Specify a header (type "text")
    'Please choose an option' // Optional: Specify a footer 
);
```

### Replying messages

You can reply a previous sent message:

```php
<?php

$wa_cloud_api
    ->replyTo('<whatsapp-message-id-to-reply>')
    ->sendTextMessage(
        '34676104574',
        'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.joemunapo.zw3.co'
    );
```

## Media messages

### Upload media resources

Media messages accept as identifiers an Internet URL pointing to a public resource (image, video, audio, etc.). When you try to send a media message from a URL you must instantiate the `LinkID` object.

You can also upload your media resources to WhatsApp servers and you will receive a resource identifier:

```php
$response = $wa_cloud_api->uploadMedia('my-image.png');

$media_id = new MediaObjectID($response->decodedBody()['id']);
$wa_cloud_api->sendImage('<destination-phone-number>', $media_id);

```

### Download media resources

To download a media resource:

```php
$response = $wa_cloud_api->downloadMedia('<media-id>');
```

## Message Response

WACloudApi instance returns a Response class or a ResponseException if WhatsApp servers return an error.

```php
try {
    $response = $this->whatsapp_app_cloud_api->sendTextMessage(
        '<destination-phone-number>,
        'Hey there! I\'m using WhatsApp Cloud API. Visit https://www.joemunapo.zw3.co',
        true
    );
} catch (\JoemagsApps\WACloudApi\Response\ResponseException $e) {
    print_r($e->response()); // You can still check the Response returned from Meta servers
}
```

## Webhooks

### Webhook verification

Add your webhook in your Meta App dashboard. You need to verify your webhook:

```php
<?php
require 'vendor/autoload.php';

use JoemagsApps\WACloudApi\WebHook;

// Instantiate the WACloudApi super class.
$webhook = new WebHook();

echo $webhook->verify($_GET, "<the-verify-token-defined-in-your-app-dashboard>");
```

### Webhook notifications

Webhook is now verified, you will start receiving notifications every time your customers send messages.

```php
<?php
require 'vendor/autoload.php';
define('STDOUT', fopen('php://stdout', 'w'));

use JoemagsApps\WACloudApi\WebHook;


$payload = file_get_contents('php://input');
fwrite(STDOUT, print_r($payload, true) . "\n");

// Instantiate the Webhook super class.
$webhook = new WebHook();

// Read the first message
fwrite(STDOUT, print_r($webhook->read(json_decode($payload, true)), true) . "\n");

//Read all messages in case Meta decided to batch them
fwrite(STDOUT, print_r($webhook->readAll(json_decode($payload, true)), true) . "\n");
```

The `Webhook::read` function will return a `Notification` instance. Please, [explore](https://github.com/joemags-apps/wa-cloud-api/tree/main/src/WebHook/Notification "explore") the different notifications availables.

### Mark a message as read

When you receive an incoming message from Webhooks, you can mark the message as read by changing its status to read. Messages marked as read display two blue check marks alongside their timestamp.

Marking a message as read will also mark earlier messages in the conversation as read.

```php
<?php

$wa_cloud_api->markMessageAsRead('<message-id>');
```

### Get Business Profile

```php
<?php

$wa_cloud_api->businessProfile('<fields>');
```

### Update Business Profile

```php
<?php

$wa_cloud_api->updateBusinessProfile([
    'about' => '<about_text>',
    'email' => '<email>'
]);
```

Fields list: <https://developers.facebook.com/docs/whatsapp/cloud-api/reference/business-profiles>

## Features

* [ ] Send Text Messages
* [ ] Send Documents
* [ ] Send Templates with parameters
* [ ] Send Audios
* [ ] Send Images
* [ ] Send Videos
* [ ] Send Stickers
* [ ] Send Locations
* [ ] Send Contacts
* [ ] Send Lists
* [ ] Send Buttons
* [ ] Upload media resources to WhatsApp servers
* [ ] Download media resources from WhatsApp servers
* [ ] Mark messages as read
* [ ] Get/Update Business Profile
* [ ] Webhook verification
* [ ] Webhook notifications

## TODO

* [ ] Static methods to generate header i.e `WACloundApi::header("Header Text")`
* [ ] Static method to generate simple buttons `WACloundApi::header(array $buttons = ['Btn 1', 'Btn 2'])`
* [ ] A `reply`, `replyWith<TYPE>` methods that automatically takes he sender's phone
* [ ] Cache processing number to avoid duplicate requests
* [ ]
* [ ]

## Getting Help

## Changelog

## Testing

```php
composer unit-test
```

You also can run tests making real calls to the WhastApp Clou API. Please put your testing credentials on **WACloudApiTestConfiguration** file.

```php
composer integration-test
```

## Contributing

## License

The MIT License (MIT). Please see License File for more information. Please see [License file](/LICENSE "License file") for more information.

## Disclaimer

This package is not officially maintained by Facebook. WhatsApp and Facebook trademarks and logos are the property of Meta Platforms, Inc.

Inspired by [Netfile's Whatsapp Cloud API](https://github.com/netflie/whatsapp-cloud-api)
