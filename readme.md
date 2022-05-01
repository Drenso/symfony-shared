# Drenso Symfony Shared bundle

This bundle contains some common extension we use in our Symfony projects. When installed with Symfony Flex, all
extensions should be available automatically.

This bundle has currently no configuration options.

In order to use the form layout, make sure to enable it in your Twig configuration:

```yaml
twig:
  form_themes:
    - "@DrensoShared/bs4/form/form_layout.html.twig"
```

# PHPStorm integration

Configure the following:

```yaml
parameters:
  env(PHPSTORM_PROJECT): ''
framework:
  ide: '%env(resolve:phpstorm:PHPSTORM_PROJECT)%'
```

When you set the env var `PHPSTORM_PROJECT` to the name of your PHP storm project, it will be opened automatically.
Otherwise, the Symfony default browser implementation will still be opened as before.

Requires the Jetbrains toolbox to be installed.
