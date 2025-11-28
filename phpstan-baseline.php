<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	'message' => '#^Class Symfony\\\\Component\\\\Routing\\\\Annotation\\\\Route not found\\.$#',
	'identifier' => 'class.notFound',
	'count' => 2,
	'path' => __DIR__ . '/src/Command/CheckActionSecurityCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$objectOrClass of class ReflectionClass constructor expects class\\-string\\<T of object\\>\\|T of object, object\\|string given\\.$#',
	'identifier' => 'argument.type',
	'count' => 2,
	'path' => __DIR__ . '/src/Command/CheckActionSecurityCommand.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Database\\\\Types\\\\AbstractJmsType\\:\\:getSerializationGroups\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Database/Types/AbstractJmsType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureApiServices\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureCommands\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureDatabase\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureEmailService\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureEnv\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureFormExtensions\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureLogging\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureMessenger\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureSentryTunnel\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureSerializer\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureServices\\(\\) has parameter \\$node with generic class Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition but does not specify its types\\: TParent$#',
	'identifier' => 'missingType.generics',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureApiServices\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureCommands\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureDatabase\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureEmailService\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureEnv\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureFormExtensions\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureLogging\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureMessenger\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureSentryTunnel\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureSerializer\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureServices\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:loadInternal\\(\\) has parameter \\$mergedConfig with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#2 \\$array of function array_map expects array, iterable given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Form/Type/Select2EntitySearchType.php',
];
$ignoreErrors[] = [
	'message' => '#^Unable to resolve the template type T in call to method Doctrine\\\\Persistence\\\\ManagerRegistry\\:\\:getRepository\\(\\)$#',
	'identifier' => 'argument.templateType',
	'count' => 1,
	'path' => __DIR__ . '/src/Form/Type/Select2EntitySearchType.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertBool\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertClass\\(\\) has parameter \\$objects with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertFloat\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertInt\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertString\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertType\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^PHPDoc tag @phpstan\\-assert for \\$variables has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	'message' => '#^Variable \\$pieces in empty\\(\\) always exists and is not falsy\\.$#',
	'identifier' => 'empty.variable',
	'count' => 1,
	'path' => __DIR__ . '/src/Sentry/SentryTunnelController.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\AbstractObjectSerializer\\:\\:defaultSubscriber\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/AbstractObjectSerializer.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\AbstractObjectSerializer\\:\\:doSerialize\\(\\) has parameter \\$groups with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/AbstractObjectSerializer.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\DecimalHandler\\:\\:getSubscribingMethods\\(\\) return type has no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/DecimalHandler.php',
];
$ignoreErrors[] = [
	'message' => '#^Parameter \\#1 \\$callback of function call_user_func expects callable\\(\\)\\: mixed, non\\-falsy\\-string given\\.$#',
	'identifier' => 'argument.type',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/EnumHandler.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\ObjectConstructor\\:\\:construct\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/ObjectConstructor.php',
];
$ignoreErrors[] = [
	'message' => '#^Method Drenso\\\\Shared\\\\Twig\\\\JmsSerializerExtension\\:\\:encode\\(\\) has parameter \\$serializationGroups with no value type specified in iterable type array\\.$#',
	'identifier' => 'missingType.iterableValue',
	'count' => 1,
	'path' => __DIR__ . '/src/Twig/JmsSerializerExtension.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
