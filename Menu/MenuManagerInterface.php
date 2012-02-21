<?php
/**
 * @author Dyllon Veitch <dyllon.v@pro1solutions.net>
 */

namespace Pro1\MenuBundle\Menu;


interface MenuManagerInterface
{
    /**
     * Builds the requested menu
     *
     * @abstract
     * @param string $name
     * @return ItemInterface
     * @throws \InvalidArgumentException if the menu does not exists
     */
    public function buildMenu($name);

    /**
     * Checks if the specified menu exists
     *
     * @abstract
     * @param string $name
     * @return bool
     */
    public function has($name);
}
