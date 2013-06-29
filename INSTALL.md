Installation of the GitHub MediaWiki extension
==============================================

GitHub has the following dependencies:

* [SimpleCache](https://github.com/JeroenDeDauw/SimpleCache) 0.1 or later
* [MediaWiki](https://www.mediawiki.org/) 1.16 or later

And nothing else.

It also requires PHP 5.3 or above to run.

Installation with Composer
--------------------------

The standard and recommended way to install GitHub is with [Composer](http://getcomposer.org).
If you do not have Composer yet, you first need to install it, or get the composer.phar file.

Depending on your situation, pick one of the following approaches:

1. If you already have a copy of the GitHub code, change into its root
directory and type "composer install". This will install all dependencies of GitHub.

2. If you want to get GitHub and all of its dependencies, use
"composer create-package jeroen-de-dauw/mediawiki-github".

For more information on using Composer, see [using composer](http://getcomposer.org/doc/01-basic-usage.md).

The entry point of GitHub is GitHub.php. Including this file
takes care of autoloading and defining the version constant of this component.

Installation without composer
-----------------------------

If you install without composer, simply include the entry point file. You are then
responsible for loading all dependencies of this component before including the
entry point, and can do this in whatever way you see fit.
