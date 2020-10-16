<?php

namespace Drupal\customurl\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines UrlCounterController class.
 */
class UrlCounterController extends ControllerBase
{
    /**
     * @var \Drupal\Core\Database\Connection
     */
    private $injected_database;
    
    public function __construct(Connection $injected_database)
    {
        $this->injected_database = $injected_database;
    }
    
    public static function create(ContainerInterface $container)
    {
        return new static($container->get('database'));
    }
    
    public function content(Request $request)
    {
        $id = $request->request->get('id');
        
        $our_service = \Drupal::service('customurl.counter_service');
        $data = $our_service-> updateounter($id);

        
        $build = array(
            '#type' => 'markup',
            '#markup' => t($data)
        );
        // This is the important part, because will render only the TWIG template.
        return new Response(render($build));
    }
    
}