<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This installs Moodle into empty database, config.php must already exist.
 *
 * This script is intended for advanced usage such as in Debian packages.
 * - sudo to www-data (apache account) before
 * - not compatible with Windows platform
 *
 * @package    core
 * @subpackage cli
 * @copyright  2010 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);

// extra execution prevention - we can not just require config.php here
if (isset($_SERVER['REMOTE_ADDR'])) {
    exit(1);
}

$help =
"Advanced command line Moodle database installer.
Please note you must execute this script with the same uid as apache.

Site defaults may be changed via local/defaults.php.

Options:
--lang=CODE           Installation and default site language. Default is en.
--adminuser=USERNAME  Username for the moodle admin account. Default is admin.
--adminpass=PASSWORD  Password for the moodle admin account.
--agree-license       Indicates agreement with software license.
-h, --help            Print out this help

Example:
\$sudo -u www-data /usr/bin/php admin/cli/install_database.php --lang=cs --adminpass=soMePass123 --agree-license
";

// Check that PHP is of a sufficient version
if (version_compare(phpversion(), "5.2.8") < 0) {
    $phpversion = phpversion();
    // do NOT localise - lang strings would not work here and we CAN NOT move it after installib
    fwrite(STDERR, "Sorry, Moodle 2.0 requires PHP 5.2.8 or later (currently using version $phpversion).\n");
    fwrite(STDERR, "Please upgrade your server software or install latest Moodle 1.9.x instead.\n");
    die(1);
}

// Nothing to do if config.php does not exist
$configfile = dirname(dirname(dirname(__FILE__))).'/config.php';
if (!file_exists($configfile)) {
    fwrite(STDERR, 'config.php does not exist, can not continue'); // do not localize
    fwrite(STDERR, "\n");
    die(1);
}

// Include necessary libs
require($configfile);

require_once($CFG->libdir.'/clilib.php');
require_once($CFG->libdir.'/installlib.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/componentlib.class.php');

// make sure no tables are installed yet
if ($DB->get_tables() ) {
    cli_error(get_string('clitablesexist', 'install'));
}

$CFG->early_install_lang = true;
get_string_manager(true);

raise_memory_limit(MEMORY_EXTRA);

// now get cli options
list($options, $unrecognized) = cli_get_params(
    array(
        'lang'              => 'en',
        'adminuser'         => 'admin',
        'adminpass'         => '',
        'agree-license'     => false,
        'help'              => false
    ),
    array(
        'h' => 'help'
    )
);


if ($options['help']) {
    echo $help;
    die;
}

if (!$options['agree-license']) {
    cli_error('You have to agree to the license. --help prints out the help'); // TODO: localize
}

if ($options['adminpass'] === true or $options['adminpass'] === '') {
    cli_error('You have to specify admin password. --help prints out the help'); // TODO: localize
}

$options['lang'] = clean_param($options['lang'], PARAM_SAFEDIR);
if (!file_exists($CFG->dirroot.'/install/lang/'.$options['lang'])) {
    $options['lang'] = 'en';
}
$CFG->lang = $options['lang'];

//download lang pack with optional notification
if ($CFG->lang != 'en') {
    make_upload_directory('lang');
    if ($cd = new component_installer('http://download.moodle.org', 'langpack/2.0', $CFG->lang.'.zip', 'languages.md5', 'lang')) {
        if ($cd->install() == COMPONENT_ERROR) {
            if ($cd->get_error() == 'remotedownloaderror') {
                $a = new stdClass();
                $a->url  = 'http://download.moodle.org/langpack/2.0/'.$CFG->lang.'.zip';
                $a->dest = $CFG->dataroot.'/lang';
                cli_problem(get_string($cd->get_error(), 'error', $a));
            } else {
                cli_problem(get_string($cd->get_error(), 'error'));
            }
        } else {
            // install parent lang if defined
            if ($parentlang = get_parent_language()) {
                if ($cd = new component_installer('http://download.moodle.org', 'langpack/2.0', $parentlang.'.zip', 'languages.md5', 'lang')) {
                    $cd->install();
                }
            }
        }
    }
}

// switch the string_manager instance to stop using install/lang/
$CFG->early_install_lang = false;
get_string_manager(true);


install_cli_database($options, true);

echo get_string('cliinstallfinished', 'install')."\n";
exit(0); // 0 means success