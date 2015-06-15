Postcss filter
===================

This is a fork of [assetic-autoprefixer](https://github.com/netzmacht/assetic-autoprefixer).

All credits goes to [assetic-autoprefixer](https://github.com/netzmacht/assetic-autoprefixer).

The original [assetic-autoprefixer](https://github.com/netzmacht/assetic-autoprefixer) implements only the [autoprefixer](https://github.com/postcss/autoprefixer) postcss module.

This fork adds a [cssnext](https://github.com/cssnext/cssnext) filter for the [PHP assetic framework](https://github.com/kriswallsmith/assetic).
The project is called Postcss as it could implement each postcss module and let you choose which one to use.
As I am new with postcss I am starting with [cssnext](https://github.com/cssnext/cssnext) which is made of (many postcss modules)[http://cssnext.io/usage/] (including autoprefixed) and make it a good start.


Requirements
------------

[`kriswallsmith/assetic`](https://github.com/kriswallsmith/assetic) is required to be installed in your php project.
[`postcss/cssnext`](https://github.com/postcss/cssnext) is required to be installed on your system.

### Install kriswallsmith/assetic with composer

```bash
php composer.phar require kriswallsmith/assetic ~1.0
```

### Install cssnext globally on your system

```bash
sudo npm install -g cssnext
```

### Install cssnext locally on your system

```bash
npm install cssnext
```

Usage in PHP
------------

```php
use Bit3\Assetic\Filter\Postcss\CssnextFilter;

// if you have installed cssnext globally
$cssnextBinary = '/usr/bin/cssnext';

// if you have installed cssnext locally
$cssnextBinary = '/../node_modules/.bin/cssnext';

$cssnextFilter = new CssnextFilter($cssnextBinary);

// if node.js binary is not installed as /usr/bin/node
// (e.g. on debian/ubuntu the binary is named /usr/bin/nodejs)
$cssnextFilter->setNodeBin('/usr/bin/nodejs');
```

Usage in Symfony2
-----------------

This project comes with a assetic filter configuration file, located in the `config` directory.

Define the cssnext binary path in the `parameters.yml`:

```yaml
parameters:
  # if you have installed cssnext globally
  assetic.cssnext.bin: /usr/bin/cssnext

  # if you have installed cssnext locally
  assetic.cssnext.bin: %kernel.root_dir%/../node_modules/.bin/cssnext

  # if node.js binary is not installed as /usr/bin/node
  # (e.g. on debian/ubuntu the binary is named /usr/bin/nodejs)
  assetic.node.bin: /usr/bin/nodejs
```

Then enable the filter in the `assetic` configuration chapter:

```yaml
# Assetic Configuration
assetic:
    filters:
        cssnext:
          resource: "%kernel.root_dir%/../vendor/netzmacht/assetic-postcss/config/cssnext.xml"
          # if you like, you can use apply_to here :-)
          # e.g, apply_to: "\.css"
          # otherwise you use the filter in your template with filter="cssnext"
```
