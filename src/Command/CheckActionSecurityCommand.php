<?php

namespace Drenso\Shared\Command;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Routing\Annotation\Route;

class CheckActionSecurityCommand extends Command
{
  /**
   * Makes the command lazy loaded
   *
   * @var string
   */
  protected static $defaultName = 'drenso:check:action-security';

  /**
   * CheckActionSecurityCommand constructor.
   *
   * @param string[] $excludedControllers
   */
  public function __construct(
      private ContainerInterface $container,
      private array              $excludedControllers)
  {
    parent::__construct();
  }

  protected function configure()
  {
    $this
        ->setDescription('Check if all actions in the app namespace either have a Security or an IsGranted attribute.');

    $this->addOption('allow-class-attribute', NULL, InputOption::VALUE_NONE,
        'When given, a global class attribute is also allowed');
  }

  /**
   * @throws ReflectionException
   */
  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    // Initialize variables
    $io                 = new SymfonyStyle($input, $output);
    $noSecurity         = [];
    $checkedControllers = [];
    $allowClass         = $input->getOption('allow-class-attribute') ?? false;
    // Find all routes
    $routes = $this->container->get('router')->getRouteCollection()->all();

    foreach ($routes as $param) {
      // Get controller string
      $controller = $param->getDefault('_controller');

      // Skip controller if excluded
      if (in_array($controller, $this->excludedControllers)) {
        continue;
      }

      if ($controller !== NULL) {
        // Only check own controllers
        if (!str_contains(strtolower($controller), 'app')) continue;

        // Find actions. Possible formats: <service>:<action> and <namespace>:<bundle>:<action>. These need to be checked separately.
        $controllerArray = explode(':', $controller);
        try {
          // Resolve service
          $controllerObject = $this->container->get($controllerArray[0]);
          $action           = $controllerArray[2] ?? $controllerArray[1];
        } catch (ServiceNotFoundException) {
          $controllerObject = $controllerArray[0];
          // Merge bundle with namespace, but only if this is defined
          if ($controllerArray[1]) {
            $controllerObject .= '/' . $controllerArray[1];
          }
          $action = $controllerArray[2];
        }

        // Create ReflectionMethod
        $reflectedMethod = new ReflectionMethod($controllerObject, $action);
        $attrFlags       = ReflectionAttribute::IS_INSTANCEOF;
        // Check if Route annotation exists
        if ($reflectedMethod->getAttributes(Route::class, $attrFlags)) {
          // Check if Security or IsGranted annotation exists, if not raise error
          if (!$reflectedMethod->getAttributes(Security::class, $attrFlags) &&
              !$reflectedMethod->getAttributes(IsGranted::class, $attrFlags) &&
              (!$allowClass || !(new ReflectionClass($controllerObject))->getAttributes(IsGranted::class, $attrFlags))) {
            $noSecurity[] = '- ' . $controller;
          }

          // Save as checked for verbose output
          $checkedControllers[] = '- ' . $controller;
        }
      }
    }

    // Build error string
    if (!empty($noSecurity)) {
      $error = [];
      // Concatenate non-pre-authorized methods
      $error[] = 'The following methods do not contain a Security or IsGranted annotation:';
      $error   = array_merge($error, $noSecurity);

      // Feedback error
      $io->error(implode("\n", $error));

      return 1;
    }

    // No errors occurred!
    $io->success('All methods contain a Security or IsGranted annotation!');

    if ($output->isVerbose()) {
      $output->writeln("Checked controllers:");
      $output->writeln(implode("\n", $checkedControllers));
      $output->writeln('');
    }

    return 0;
  }
}
