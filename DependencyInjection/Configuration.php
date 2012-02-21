<?php
/**
 * @author Dyllon Veitch <dyllon.v@pro1solutions.net>
 */

namespace Pro1\MenuBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


class Configuration implements ConfigurationInterface
{

    /**
     * Builds the config tree
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pro1_menu');


        /**
         * To handle an unknown number of nested menus we flatten the children nodes and assign parent ids to
         * allow proper config validation without creating a arbitrary number of prototype nodes with the same child nodes.
         *
         * Unfortunately this requires rebuilding the array while building the menu items.
         * I'm sure there's a better way to handle this, but here we are.
         **/
        $rootNode
                ->beforeNormalization()
                    ->always(function ($config) {
                        // Create our closure function to flatten the children nodes
                        $flatten = function ($menu, $parent = "", $divider_char = ".") use (&$flatten) {
                            $result = array();

                            if(is_array($menu)) {
                                foreach($menu as $itemKey => $item) {
                                    if (is_array($item)) {
                                        // Set the parent if child menu
                                        if ($parent) $item['parent'] = $parent;

                                        // If the route and uri are not set use the id as route
                                        if (!array_key_exists('route', $item) && !array_key_exists('uri', $item)) {
                                            $item['route'] = $itemKey;
                                        }

                                        // Add the item to the results array
                                        $result[$itemKey] = $item;

                                        // Handle children
                                        if (array_key_exists('children', $item)) {
                                            // Strip the children array from the results array
                                            unset($result[$itemKey]['children']);

                                            // Flatten the children array and add to the results array
                                            $result = array_merge($result, $flatten($item['children'], $parent.$itemKey.$divider_char, $divider_char));
                                        }
                                    }
                                }
                            }

                            return $result;
                        };

                        $menuItems = array();

                        // Flatten all the menus!
                        foreach ($config['menu'] as $key => $menu) {
                            $menuItems[$key] = $flatten($menu);
                        }

                        // Replace the multidimensional menu array with our newly flattened array.
                        unset($config['menu']);
                        $config['menu'] = $menuItems;

                        return $config;
                    })
                ->end()
                ->children()
                    ->arrayNode('menu')->useAttributeAsKey('id')
                        ->prototype('array')->useAttributeAsKey('id') // Menus
                            ->prototype('array') // Menu Items
                                ->children()
                                    ->scalarNode('parent')->end()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('label')->end()
                                    ->arrayNode('linkAttributes')->useAttributeAsKey('id')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->arrayNode('labelAttributes')->useAttributeAsKey('id')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->scalarNode('uri')->end()
                                    ->scalarNode('route')->end()
                                    ->arrayNode('routeParameters')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->booleanNode('routeAbsolute')->end()
                                    ->arrayNode('attributes')->useAttributeAsKey('id')
                                        ->prototype('scalar')->end()
                                    ->end()
                                    ->booleanNode('display')->end()
                                    ->booleanNode('displayChildren')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
