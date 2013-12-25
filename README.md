## GitHub extension for MediaWiki

Simple MediaWiki extension that allows embedding the content of files hosted in GitHub git repositories.

TravisCI status:
[![Build Status](https://secure.travis-ci.org/JeroenDeDauw/GitHub.png?branch=master)](http://travis-ci.org/JeroenDeDauw/GitHub)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/badges/quality-score.png?s=b928c81a24ec2d8fcf6dd2b291b41c76ef528dbe)](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/)

Packagist status:
[![Latest Stable Version](https://poser.pugx.org/jeroen-de-dauw/mediawiki-github/version.png)](https://packagist.org/packages/jeroen-de-dauw/mediawiki-github)
[![Latest Stable Version](https://poser.pugx.org/jeroen-de-dauw/mediawiki-github/d/total.png)](https://packagist.org/packages/jeroen-de-dauw/mediawiki-github)

## Requirements

* [PHP](http://www.php.net) 5.3 or later
* [MediaWiki](https://www.mediawiki.org) 1.16 or later
* Installation via [Composer](http://getcomposer.org/)

## Installation

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

### 2.0 (under development)

#### New features

* Added support for markdown. Files ending on .md or .markdown are now rendered appropriately.

#### Compatibility changes

* The extension now needs to be installed via Composer.

#### Enhancements

* PSR-0 based autoloading is now used
* The ParserHooks library is now used for the github parser hook
* Additional tests have been added

### 1.0 (2013-07-15)

* Initial release

## Links

* [GitHub on Packagist](https://packagist.org/packages/jeroen-de-dauw/mediawiki-github)
* [GitHub on Ohloh](https://www.ohloh.net/p/mediawiki-github)
* [GitHub on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:GitHub)
* [TravisCI build status](https://travis-ci.org/JeroenDeDauw/GitHub)
* [Latest version of the readme file](https://github.com/JeroenDeDauw/GitHub/blob/master/README.md)
