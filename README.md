## GitHub extension for MediaWiki

Simple MediaWiki extension for embedding content of files hosted in GitHub git repositories.

Build status:
[![Build Status](https://secure.travis-ci.org/JeroenDeDauw/GitHub.png?branch=master)](http://travis-ci.org/JeroenDeDauw/GitHub)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/badges/quality-score.png?s=b928c81a24ec2d8fcf6dd2b291b41c76ef528dbe)](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/jeroen/mediawiki-github/version.png)](https://packagist.org/packages/jeroen/mediawiki-github)
[![Latest Stable Version](https://poser.pugx.org/jeroen/mediawiki-github/d/total.png)](https://packagist.org/packages/jeroen/mediawiki-github)

## Requirements

* [PHP](http://www.php.net) 5.5 or later (HHVM and PHP 7 are supported)
* [MediaWiki](https://www.mediawiki.org) 1.24 or later (earlier versions likely work when using [ExtensionInstaller](https://github.com/JeroenDeDauw/ExtensionInstaller))
* Installation via [Composer](http://getcomposer.org/)

## Installation

The recommended way to install the GitHub extension is with [Composer](http://getcomposer.org) using
[MediaWiki 1.22 built-in support for Composer](https://www.mediawiki.org/wiki/Composer).

##### Step 1

Go to the root directory of your MediaWiki installation.

##### Step 2

If you have previously installed Composer skip to step 3.

To install Composer:

    wget http://getcomposer.org/composer.phar

##### Step 3

Now using Composer, install the GitHub extension.

If you do not have a composer.json file yet, copy the composer-example.json file to composer.json. If you
are using the ExtensionInstaller, the file to copy will be named example.json, rather than composer-example.json. When this is done, run:

    php composer.phar require jeroen/mediawiki-github "@dev"

##### Verify installation success

Go to Special:Version and see if GitHub is listed there. If it is, you successfully installed it!

## Configuration

The default GitHub repo can be set using the `$egGitHubDefaultRepo` setting. Assign to this setting
the name of the default repo in your LocalSettings file, after the inclusion of this extension e.g.
for Semantic MediaWiki as follows:

    $egGitHubDefaultRepo = 'SemanticMediaWiki/SemanticMediaWiki';

The file contents gets cached in memory during the PHP request. The main MediaWiki cache
is used as secondary cache, with a default TTL of 600 seconds. You can use the
`$egGitHubCacheTime` setting to change the TTL:

    $egGitHubCacheTime = 900;

You can modify the GitHub raw content URL used to fetch the files. The default is
`https://cdn.rawgit.com`, due to `https://raw.githubusercontent.com` not working on all systems.
You can change this setting as follows:

    $egGitHubUrl = 'https://raw.githubusercontent.com';

You can modify which method is used to fetch the file. The supported methods are

* `'simple'` - use PHPs file_get_contents
* `'mediawiki'` - use MediaWikis HTTP class

The default is `'simple'`. You can change this setting as follows:

    $egGitHubFetcher = 'mediawiki';

## Usage

Add `{{#github:FileName}}` to your wiki page, where FileName is the name of the file you want to embed.
This can include a path, for instance `{{#github:docs/INSTALL.md}}`.

You can also specify the repo name and the branch name: `{{#github:FileName|user/repo|branchName}}`

## Release notes

### 1.1.0 (2016-07-16)

* Dropped support for PHP < 5.5
* Fixed error on file not found. The parser function will now return an empty string in this case.

### 1.0.3 (2016-07-16)

* Fixed version number on Special:Version
* Switched from FileFetcher ~2.0 to ~3.1

### 1.0.2 (2015-01-20)

* Added `$egGitHubFetcher` setting
* The files are now by default fetched using `file_get_contents` rather than MediaWikis `HTTP` class

### 1.0.1 (2015-01-19)

* Added `$egGitHubUrl` setting
* Changed default GitHub raw content url from `https://raw.githubusercontent.com` to `https://cdn.rawgit.com` (thanks to Mike Cariaso)

### 1.0.0 (2015-01-19)

#### New features

* Added support for markdown. Files ending on .md or .markdown are now rendered appropriately
* Added `$egGitHubCacheTime` setting

#### Compatibility changes

* The extension now needs to be installed via Composer
* The package name has changed from `jeroen-de-dauw/mediawiki-github` to `jeroen/mediawiki-github`

#### Enhancements

* Compatibility with the latest version of the GitHub API has been added
* PSR-4 based autoloading is now used
* The ParserHooks library is now used for the github parser hook
* Additional tests have been added

### 0.1.0 (2013-07-15)

* Initial release

## Links

* [GitHub on Packagist](https://packagist.org/packages/jeroen/mediawiki-github)
* [GitHub on Ohloh](https://www.ohloh.net/p/mediawiki-github)
* [GitHub on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:GitHub)
* [TravisCI build status](https://travis-ci.org/JeroenDeDauw/GitHub)
* [Latest version of the readme file](https://github.com/JeroenDeDauw/GitHub/blob/master/README.md)
