Pro1MenuBundle
===================

This bundle makes it easy to create menus in your config files by adding a Configuration Provider to the [KnpMenuBundle](https://github.com/knplabs/KnpMenuBundle)

## Installation

### Add this bundle to your project

#### Using the vendors script

Add the following lines to your  `deps` file and then run `php bin/vendors
install`:

```
[Pro1MenuBundle]
    git=https://github.com/pro1/Pro1MenuBundle.git
    target=bundles/Pro1/MenuBundle
```

#### Using Git submodules

```bash
git submodule add https://github.com/pro1/Pro1MenuBundle.git vendor/bundles/Pro1/MenuBundle
```

### Register the namespaces

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Pro1' => __DIR__.'/../vendor/bundles',
    // ...
));
```

### Register the bundle

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Pro1\MenuBundle\Pro1MenuBundle(),
    );
    // ...
)
```

## Creating menus

You can create a menu using your choice of configuration format.

This bundle provides the configured menus to the KnpMenuBundle, see [KnpMenuBundle](https://github.com/knplabs/KnpMenuBundle) documentation.


### YAML

```yaml
pro1_menu:
  menu:
    main:
      homepage:
        label: 'Home'

      nested_items:
        children:
            item_1:
              label: 'Item 1'
              children:
                item_1-1:
                    uri: '#'

                item_1-2:
                    uri: '#'
                    
                item_1-3:
                    uri: '#'

            item_2:
              label: 'Item 2'
              uri: '#'
              
            item_3:
              label: 'Item 3'
              uri: '#'
              
            item_4:
              label: 'Item 4'
              uri: '#'

      about:
        label: 'About Us'
        route: 'page_show'
        routeParameters:
            id: 42

```

## Rendering menus
Rendering is handled by [KnpMenuBundle](https://github.com/knplabs/KnpMenuBundle), see the [KnpMenuBundle](https://github.com/knplabs/KnpMenuBundle) documentation for more rendering options.

```jinja
{{ knp_menu_render('main') }}
```
