# Materialize CSS for Yii2

----

This package integrates the Materialize CSS framework into [Yii2](http://www.yiiframework.com/).
[Materialize](http://materializecss.com/) is a modern responsive front-end framework based on Material Design.

See [official documentation](http://macgyer.github.io/yii2-materializecss/) for detailed information.

----

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```SHELL
$ composer require uomocon/materialize "*"
```

or add

```JSON
"uomocon/materialize": "*"
```

to the `require` section of your `composer.json` file.


## Usage

To load the Materialize CSS files integrate the MaterializeAsset into your app.
Two ways to achieve this is to register the asset in the main layout:

```php
// @app/views/layouts/main.php

\uomocon\materialize\assets\MaterializeAsset::register($this);
// further code
```

or as a dependency in your app wide AppAsset.php

```php
// @app/assets/AppAsset.php

public $depends = [
    'uomocon\materialize\assets\MaterializeAsset',
    // more dependencies
];
```
