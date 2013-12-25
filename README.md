## GitHub extension for MediaWiki

Simple MediaWiki extension that allows embedding the content of files hosted in GitHub git repositories.

TravisCI status:
[![Build Status](https://secure.travis-ci.org/JeroenDeDauw/GitHub.png?branch=master)](http://travis-ci.org/JeroenDeDauw/GitHub)

Packagist status:
[![Latest Stable Version](https://poser.pugx.org/jeroen-de-dauw/mediawiki-github/version.png)](https://packagist.org/packages/jeroen-de-dauw/mediawiki-github)
[![Latest Stable Version](https://poser.pugx.org/jeroen-de-dauw/mediawiki-github/d/total.png)](https://packagist.org/packages/jeroen-de-dauw/mediawiki-github)

## Requirements

* [PHP](http://www.php.net) 5.3 or later
* [MediaWiki](https://www.mediawiki.org) 1.16 or later
* Installation via [Composer](http://getcomposer.org/)

## Installation

You can use [Composer](http://getcomposer.org/) to download and install
this package as well as its dependencies. Alternatively you can simply clone
the git repository and take care of loading yourself.

### Composer

To add this package as a local, per-project dependency to your project, simply add a
dependency on `jeroen-de-dauw/mediawiki-github` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
GitHub 1.0:

    {
        "require": {
            "jeroen-de-dauw/mediawiki-github": "1.0.*"
        }
    }

### Manual

Get the GitHub code, either via git, or some other means. Also get all dependencies.
You can find a list of the dependencies in the "require" section of the composer.json file.
Load all dependencies and the load the GitHub library by including its entry point:
GitHub.php.

## Configuration

The default GitHub repo can be set using the $egGitHubDefaultRepo setting. Assign to this setting
the name of the default repo in your LocalSettings file, after the inclusion of this extension as
follows:

    $egGitHubDefaultRepo = 'wikimedia/mediawiki-extensions-SemanticMediaWiki';

## Usage

Add {{#github:FileName}} to your wiki page, where FileName is the name of the file you want to embed.
This can include a path, for instance {{#github:docs/INSTALL.md}}.

You can also specify the repo name and the branch name: {{#github:FileName|user/repo|branchName}}

## Release notes

### 1.0 (2013-07-15)

* Initial release

## Links

* [GitHub on Packagist](https://packagist.org/packages/jeroen-de-dauw/mediawiki-github)
* [GitHub on Ohloh](https://www.ohloh.net/p/mediawiki-github)
* [GitHub on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:GitHub)
* [TravisCI build status](https://travis-ci.org/JeroenDeDauw/GitHub)
* [Latest version of the readme file](https://github.com/JeroenDeDauw/GitHub/blob/master/README.md)
