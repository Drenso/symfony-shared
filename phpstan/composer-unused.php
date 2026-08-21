<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;
use Webmozart\Glob\Glob;

return static function (Configuration $config): Configuration {
  return $config
      ->addNamedFilter(NamedFilter::fromString('phpstan/phpstan')) // tool doesn't understand that it replaces eluceo/ical
      ->addNamedFilter(NamedFilter::fromString('ext-apcu'))
      ->addNamedFilter(NamedFilter::fromString('league/commonmark'))
      ->addNamedFilter(NamedFilter::fromString('league/html-to-markdown'))
      ->addNamedFilter(NamedFilter::fromString('symfony/dotenv'))
      ->addNamedFilter(NamedFilter::fromString('symfony/flex'))
      ->addNamedFilter(NamedFilter::fromString('symfony/runtime'))
      ->addNamedFilter(NamedFilter::fromString('twig/inky-extra'))
      ->addNamedFilter(NamedFilter::fromString('twig/intl-extra'))
      ->addNamedFilter(NamedFilter::fromString('twig/markdown-extra'))
      ->setAdditionalFilesFor('drenso/023-nanolab-nl', [
          __FILE__,
          ...Glob::glob(__DIR__ . '/config/**/*.php'),
          ...Glob::glob(__DIR__ . '/public/*.php'),
      ]);
};
