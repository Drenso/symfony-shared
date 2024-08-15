<?php declare(strict_types = 1);

$ignoreErrors = [];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$objectOrClass of class ReflectionClass constructor expects class\\-string\\<T of object\\>\\|T of object, object\\|string given\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/Command/CheckActionSecurityCommand.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Database\\\\Types\\\\AbstractJmsType\\:\\:getSerializationGroups\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Database/Types/AbstractJmsType.php',
];
$ignoreErrors[] = [
	// identifier: method.notFound
	'message' => '#^Call to an undefined method Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition\\:\\:children\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: method.notFound
	'message' => '#^Call to an undefined method Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeParentInterface\\:\\:arrayNode\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: method.notFound
	'message' => '#^Call to an undefined method Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeParentInterface\\:\\:beforeNormalization\\(\\)\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: method.notFound
	'message' => '#^Call to an undefined method Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeParentInterface\\:\\:end\\(\\)\\.$#',
	'count' => 4,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: method.notFound
	'message' => '#^Call to an undefined method Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeParentInterface\\:\\:scalarNode\\(\\)\\.$#',
	'count' => 2,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureApiServices\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureCommands\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureDatabase\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureEmailService\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureEnv\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureFormExtensions\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureSentryTunnel\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureSerializer\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$node of method Drenso\\\\Shared\\\\DependencyInjection\\\\Configuration\\:\\:configureServices\\(\\) expects Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\ArrayNodeDefinition, Symfony\\\\Component\\\\Config\\\\Definition\\\\Builder\\\\NodeDefinition given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/Configuration.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureApiServices\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureCommands\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureDatabase\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureEmailService\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureEnv\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureFormExtensions\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureSentryTunnel\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureSerializer\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:configureServices\\(\\) has parameter \\$config with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\DependencyInjection\\\\DrensoSharedExtension\\:\\:loadInternal\\(\\) has parameter \\$mergedConfig with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/DependencyInjection/DrensoSharedExtension.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#2 \\$array of function array_map expects array, iterable given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Form/Type/Select2EntitySearchType.php',
];
$ignoreErrors[] = [
	// identifier: argument.templateType
	'message' => '#^Unable to resolve the template type T in call to method Doctrine\\\\Persistence\\\\ManagerRegistry\\:\\:getRepository\\(\\)$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Form/Type/Select2EntitySearchType.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertArray\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertBool\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertClass\\(\\) has parameter \\$objects with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertFloat\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertInt\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertString\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Helper\\\\ArrayHelper\\:\\:assertType\\(\\) has parameter \\$variables with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Helper/ArrayHelper.php',
];
$ignoreErrors[] = [
	// identifier: empty.variable
	'message' => '#^Variable \\$pieces in empty\\(\\) always exists and is not falsy\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Sentry/SentryTunnelController.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\AbstractObjectSerializer\\:\\:defaultSubscriber\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/AbstractObjectSerializer.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\AbstractObjectSerializer\\:\\:doSerialize\\(\\) has parameter \\$groups with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/AbstractObjectSerializer.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\DecimalHandler\\:\\:deserializeJson\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/DecimalHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\DecimalHandler\\:\\:getSubscribingMethods\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/DecimalHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\DecimalHandler\\:\\:serializeJson\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/DecimalHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\EnumHandler\\:\\:deserialize\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/EnumHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\EnumHandler\\:\\:serialize\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/EnumHandler.php',
];
$ignoreErrors[] = [
	// identifier: argument.type
	'message' => '#^Parameter \\#1 \\$callback of function call_user_func expects callable\\(\\)\\: mixed, non\\-falsy\\-string given\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/EnumHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\IdMapHandler\\:\\:deserializeJson\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/IdMapHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.generics
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\IdMapHandler\\:\\:deserializeJson\\(\\) return type with generic class Drenso\\\\Shared\\\\IdMap\\\\IdMap does not specify its types\\: T$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/IdMapHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\IdMapHandler\\:\\:getSubscribingMethods\\(\\) return type has no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/IdMapHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.generics
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\IdMapHandler\\:\\:serializeJson\\(\\) has parameter \\$data with generic class Drenso\\\\Shared\\\\IdMap\\\\IdMap but does not specify its types\\: T$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/IdMapHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\Handlers\\\\IdMapHandler\\:\\:serializeJson\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/Handlers/IdMapHandler.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Serializer\\\\ObjectConstructor\\:\\:construct\\(\\) has parameter \\$type with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Serializer/ObjectConstructor.php',
];
$ignoreErrors[] = [
	// identifier: missingType.iterableValue
	'message' => '#^Method Drenso\\\\Shared\\\\Twig\\\\JmsSerializerExtension\\:\\:encode\\(\\) has parameter \\$serializationGroups with no value type specified in iterable type array\\.$#',
	'count' => 1,
	'path' => __DIR__ . '/src/Twig/JmsSerializerExtension.php',
];

return ['parameters' => ['ignoreErrors' => $ignoreErrors]];
