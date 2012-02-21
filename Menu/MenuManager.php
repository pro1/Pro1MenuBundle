<?php
/**
 * @author Dyllon Veitch <dyllon.v@pro1solutions.net>
 */
 
namespace Pro1\MenuBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuManager implements MenuManagerInterface
{
    private $container;
    private $factory;
    private $menuConfig;

    /**
     * @param ContainerInterface $container
     * @param FactoryInterface $factory
     * @param array $menu
     */
    public function __construct(ContainerInterface $container, FactoryInterface $factory, array $menu)
    {
        $this->container = $container;
        $this->factory = $factory;
        $this->menuConfig = $menu;
    }

    /**
     * Builds the requested menu
     *
     * @param string $name
     * @return ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function buildMenu($name)
    {
        if (!array_key_exists($name, $this->menuConfig)) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        $menuConfig = $this->menuConfig[$name];
        $menuItems = array();

        foreach ($menuConfig as $key => $item) {
            $item['name'] = $key;
            // If item has a parent move it to the parents children array
            if (array_key_exists('parent', $item)) {
                // Get the array key parts and unset the parent
                $parts = preg_split('/\./', $item['parent'], -1, PREG_SPLIT_NO_EMPTY);
                unset($item['parent']);

                // Set first level and shift the first part off the parts array
                $parent = &$menuItems[$parts[0]];
                array_shift($parts);

                // Loop through the parents
                foreach ($parts as $part) {
                    if (!array_key_exists($part, $parent['children'])) {
                        throw new \InvalidArgumentException(sprintf('The parent menu item "%s" is not defined.', $part));
                    }
                    $parent = &$parent['children'][$part];
                }

                $parent['children'][$key] = $item;
            }
            else {
                $menuItems[$key] = $item;
            }
        }

        // Build the menu
        $menu = $this->factory->createFromArray(array('children' => $menuItems));
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        return $menu;
    }

    /**
     * Checks if the specified menu exists
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, $this->menuConfig);
    }
}