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
