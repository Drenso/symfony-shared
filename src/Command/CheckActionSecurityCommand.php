<?php

namespace Drenso\Shared\Command;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CheckActionSecurityCommand extends Command
{
  /** @param string[] $excludedControllers */
  public function __construct(
    private readonly ContainerInterface $container,
    private readonly RouterInterface $router,
    private readonly array $excludedControllers)
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this
      ->setDescription('Check if all actions in the app namespace either have a Security or an IsGranted attribute.')
      ->addOption('allow-class-attribute', null, InputOption::VALUE_NONE,
        'When given, a global class attribute is also allowed');
  }

  /** @throws ReflectionException */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    // Initialize variables
    $io                 = new SymfonyStyle($input, $output);
    $noSecurity         = [];
    $checkedControllers = [];
    $allowClass         = $input->getOption('allow-class-attribute') ?? false;
    // Find all routes
    $routes = $this->router->getRouteCollection()->all();

    foreach ($routes as $param) {
      // Get controller string
      $controller = $param->getDefault('_controller');

      // Skip controller if excluded
      if (in_array($controller, $this->excludedControllers)) {
        continue;
      }

      if ($controller !== null) {
        // Only check own controllers
        if (!str_contains(strtolower((string)$controller), 'app')) {
          continue;
        }

        // Find actions. Possible formats: <invokable service>, <service>:<action> and <namespace>:<bundle>:<action>. These need to be checked separately.
        $controllerArray = explode(':', (string)$controller);

        try {
          // Resolve service
          $controllerObject = $this->container->get($controllerArray[0]);
          if (count($controllerArray) === 1) {
            // Invokable controller
            $action = '__invoke';
          } else {
            $action = $controllerArray[2] ?? $controllerArray[1];
          }
        } catch (ServiceNotFoundException) {
          $controllerObject = $controllerArray[0];
          // Merge bundle with namespace, but only if this is defined
          if ($controllerArray[1]) {
            $controllerObject .= '/' . $controllerArray[1];
          }
          $action = $controllerArray[2];
        }

        $attrFlags = ReflectionAttribute::IS_INSTANCEOF;

        // Specific handling for invokable controllers
        if ($action === '__invoke') {
          $reflectedClass = new ReflectionClass($controllerObject);
          if ($reflectedClass->getAttributes(Route::class, $attrFlags)) {
            if (!$reflectedClass->getAttributes(IsGranted::class, $attrFlags)) {
              $noSecurity[] = '- ' . $controller;
            }

            // Save as checked for verbose output
            $checkedControllers[] = '- ' . $controller;
          }

          continue;
        }

        // Check if Route attribute exists
        $reflectedMethod = new ReflectionMethod($controllerObject, $action);
        if ($reflectedMethod->getAttributes(Route::class, $attrFlags)) {
          // Check if Security or IsGranted attribute exists, if not raise error
          if (!$reflectedMethod->getAttributes(IsGranted::class, $attrFlags)
            && (!$allowClass || !(new ReflectionClass($controllerObject))->getAttributes(IsGranted::class, $attrFlags))) {
            $noSecurity[] = '- ' . $controller;
          }

          // Save as checked for verbose output
          $checkedControllers[] = '- ' . $controller;
        }
      }
    }

    if ($output->isVerbose()) {
      $checked = [
        'Checked controllers:',
        ...$checkedControllers,
      ];

      $io->info(implode("\n", $checkedControllers));
      $output->writeln('');
    }

    // Build error string
    if (!empty($noSecurity)) {
      // Concatenate non-pre-authorized methods
      $error = [
        'The following methods do not contain a Security or IsGranted attribute:',
        ...$noSecurity,
      ];

      // Feedback error
      $io->error(implode("\n", $error));

      return Command::FAILURE;
    }

    // No errors occurred!
    $io->success('All methods contain a Security or IsGranted attribute!');

    return Command::SUCCESS;
  }
}
