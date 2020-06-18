<?php

namespace Drenso\Shared\Command;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionException;
use ReflectionMethod;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
   * @var ContainerInterface
   */
  private $container;
  /**
   * @var string[]
   */
  private $excludedControllers;

  /**
   * CheckActionSecurityCommand constructor.
   *
   * @param ContainerInterface $container
   * @param string[]           $excludedControllers
   */
  public function __construct(ContainerInterface $container, array $excludedControllers)
  {
    $this->container           = $container;
    $this->excludedControllers = $excludedControllers;

    parent::__construct(NULL);
  }

  /**
   * {@inheritdoc}
   */
  protected function configure()
  {
    $this
        ->setDescription('Check if all actions in the app namespace either have a Security or a IsGranted annotation.');
  }

  /**
   * {@inheritdoc}
   * @throws ReflectionException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    // Initialize variables
    $io                 = new SymfonyStyle($input, $output);
    $noSecurity         = [];
    $checkedControllers = [];
    $annotationReader   = new AnnotationReader();
    // Find all routes
    $routes = $this->container->get('router')->getRouteCollection()->all();

    foreach ($routes as $route => $param) {
      // Get controller string
      $controller = $param->getDefault('_controller');

      // Skip controller if excluded
      if (in_array($controller, $this->excludedControllers)) {
        continue;
      }

      if ($controller !== NULL) {
        // Only check own controllers
        if (strpos(strtolower($controller), 'app') === false) continue;

        // Find actions. Possible formats: <service>:<action> and <namespace>:<bundle>:<action>. These need to be checked separately.
        $controllerArray = explode(':', $controller);
        try {
          // Resolve service
          $controllerObject = $this->container->get($controllerArray[0]);
          $action           = $controllerArray[2] ?? $controllerArray[1];
        } catch (ServiceNotFoundException $e) {
          $controllerObject = $controllerArray[0];
          // Merge bundle with namespace, but only if this is defined
          $controllerArray[1] ? $controllerObject .= '/' . $controllerArray[1] : NULL;
          $action = $controllerArray[2];
        }

        // Create ReflectionMethod
        $reflectedMethod = new ReflectionMethod($controllerObject, $action);
        // Check if Route annotation exists
        if ($annotationReader->getMethodAnnotation($reflectedMethod, Route::class)) {
          // Check if Security or IsGranted annotation exists, if not raise error
          if (!$annotationReader->getMethodAnnotation($reflectedMethod, Security::class) &&
              !$annotationReader->getMethodAnnotation($reflectedMethod, IsGranted::class)) {
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
