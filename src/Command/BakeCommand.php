<?php
declare(strict_types=1);
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Bake\Command;

use Bake\Utility\CommonOptionsTrait;
use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Core\ConventionsTrait;

/**
 * Base class for commands that bake can use.
 *
 * Classes that extend this class will be auto-discovered by bake
 * and attached as subcommands.
 */
abstract class BakeCommand extends Command
{
    use CommonOptionsTrait;
    use ConventionsTrait;

    /**
     * Handles splitting up the plugin prefix and classname.
     *
     * Sets the plugin parameter and plugin property.
     *
     * @param string $name The name to possibly split.
     * @return string The name without the plugin prefix.
     */
    protected function _getName($name)
    {
        if (strpos($name, '.')) {
            list($plugin, $name) = pluginSplit($name);
            $this->plugin = $plugin;
        }

        return $name;
    }

    /**
     * Get the prefix name.
     *
     * Handles camelcasing each namespace in the prefix path.
     *
     * @param \Cake\Console\Arguments $args Arguments instance to read the prefix option from.
     * @return string The inflected prefix path.
     */
    protected function getPrefix(Arguments $args)
    {
        $prefix = $args->getOption('prefix');
        if (!$prefix) {
            return '';
        }
        $parts = explode('/', $prefix);

        return implode('/', array_map([$this, '_camelize'], $parts));
    }

    /**
     * Delete empty file in a given path
     *
     * @param string $path Path to folder which contains 'empty' file.
     * @param \Cake\Console\ConsoleIo $io ConsoleIo to delete file with.
     * @return void
     */
    protected function deleteEmptyFile($path, $io)
    {
        if (file_exists($path)) {
            unlink($path);
            $io->out(sprintf('<success>Deleted</success> `%s`', $path), 1, Shell::QUIET);
        }
    }
}