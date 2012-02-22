<?php
/**
 * @author Dyllon Veitch <dyllon.v@pro1solutions.net>
 */

namespace Pro1\MenuBundle\Provider;

use Pro1\MenuBundle\Menu\MenuManagerInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigurationAwareProvider implements MenuProviderInterface
{
    private $container;

    private $menuManager;

    /**
     * @param ContainerInterface $container
     * @param MenuManagerInterface $menuManager
     *
     */
    public function __construct(ContainerInterface $container, MenuManagerInterface $menuManager)
    {
        $this->container = $container;
        $this->menuManager = $menuManager;
    }

    /**
     * Gets a menu by name
     *
     * @param string $name The alias name of the menu
     * @return ItemInterface
     * @throws \InvalidArgumentException
     */
    public function get($name, array $options = array())
    {
        if (!$this->menuManager->has($name)) {
            throw new \InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        return $this->menuManager->buildMenu($name);
    }

    /**
     * Checks if the specified menu exists
     *
     * @param string $name
     * @return bool
     */
    public function has($name, array $options = array())
    {
        return $this->menuManager->has($name);
    }
}
