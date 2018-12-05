# Yii2 Podcast Hosting & Streaming Module

## Features

* Host unlimited podcasts & episodes in `MP3` format
* Playback statistics (downloads, referrals, playback locations etc.)
* Tool to create episodes’ show notes: just write comment as you listen to the podcast, and a timestamp will be automatically added to the note
* Podcast notes
* Generate XML/RSS feed for iTunes etc
* Feedburner (or any external feed service) support

*Additionally*, `podcaster` playback will continue to work and write stats even if your database is temporary down (e.g. your VPS is rebooting, or there is maintenance; see documentation below).

Package includes `podcaster` module as well as a complete & ready to roll application. 

## Requirements

* PHP, MYSQL
* Composer
* FTP access to upload episodes

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

## Installation

This module is a standard Yii2 Application module - you can add it **to an existing Yii 2 app**. There is an example 
application provided with the code, based on Yii 2 Basic application template. 

Blow you will find instructions on how to setup both options.

#### Option 1: Setup and run included example application

1. Configure your database connection in file `example/config/db.php`. You can also just create `yii2basic` database 
accessible to `root` user without using password.

2. Install composer packages by running command

~~~
php composer.phar install
~~~

3. Run migrations:

> php example/yii migrate --migrationPath=./src/podcaster/migrations

4. Point your server (`example.com`) to `example/web` folder. Go to your website and enjoy! (see `Usage` section of this readme)


#### Option 2: add `podcaster` module to an existing project

1. First of, you have to add `webkadabra/podcaster` namespace to your composer config. Copy whole folder to the root of 
your project, then open up your `composer.json` and add these lines:

```
"autoload" : {
    "psr-4" : {
      "webkadabra\\podcaster\\" : "src/podcaster"
    }
},
```

2. Add `podcaster` module to your config (usually it's `config/web.php` file)

```
'modules' => [
    'podcaster' => [
        'class' => 'webkadabra\podcaster\Module',
        'layout' => '//podcaster',
        'uploadPath' => '@app/media/', // upload podcasts to www-unreachable directory
    ],
]
```

3. Add urlRules to your config:

```
// ...
'components' => [
    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'rules' => [
            [
                'pattern' => 'podcast/<podcast:\w+>/feed',
                'route' => 'podcaster/feed/feed', 'suffix' => '.xml'
            ],
            [
                'pattern' => 'podcast/<podcast:\w+>/feed-<dest>',
                'route' => 'podcaster/feed/feed', 'suffix' => '.xml'
            ],
            [
                'pattern' => '<channel:\w+>/stream/<episodeId>',
                'route' => 'podcaster/traffic/stream', 'suffix' => '.mp3'
            ],
            [
                'pattern' => '<channel:\w+>/<episodeId>',
                'route' => 'podcaster/traffic/download', 'suffix' => '.mp3'
            ],
        ],
    ],
],
```

Then, run migrations from **your application root folder**:

> php yii migrate --migrationPath=vendor/webkadabra/src/podcaster/migrations


## Usage

1. Create podcast via "Podcast Management" menu
2. Upload your podcast episodes to a configured folder (by default, it's `media` folder in the root path of your app) with your favorite FTP client (`FileZilla` is recommended)
3. Add podcast episodes via "Episodes" menu 

## Support project development

Hey guys, I'm disabled developer that can't hold a job in the office. If you can - support my opensource, I gotta keep the cats fed. Thanks!

Bitcoin address (I can not accept Paypal in my country):

> 1Ceg8xdYpFmyPgeqqWDBBXVztcuNsWTMpq

## TODO

* Upload via UI (not supported at the moment)
* Episodes Tags
* Data export (shownotes etc.)




