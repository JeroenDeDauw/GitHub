## GitHub extension for MediaWiki

Simple MediaWiki extension for embedding content of files hosted in GitHub git repositories.
It supports markdown rendering, syntax highlighting and caching.

Build status:
[![Build Status](https://secure.travis-ci.org/JeroenDeDauw/GitHub.png?branch=master)](http://travis-ci.org/JeroenDeDauw/GitHub)
[![Code Coverage](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/?branch=master)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/badges/quality-score.png?s=b928c81a24ec2d8fcf6dd2b291b41c76ef528dbe)](https://scrutinizer-ci.com/g/JeroenDeDauw/GitHub/)

On Packagist:
[![Latest Stable Version](https://poser.pugx.org/jeroen/mediawiki-github/version.png)](https://packagist.org/packages/jeroen/mediawiki-github)
[![Latest Stable Version](https://poser.pugx.org/jeroen/mediawiki-github/d/total.png)](https://packagist.org/packages/jeroen/mediawiki-github)

Professional support and custom development is available via [Professional.Wiki](https://professional.wiki/).

## Requirements

* [PHP](http://www.php.net) 7.1 or later
* [MediaWiki](https://www.mediawiki.org) 1.27 or later
* Installation via [Composer](http://getcomposer.org/)

## Installation

The recommended way to install the GitHub extension is with [Composer](http://getcomposer.org) using
[MediaWiki 1.22 built-in support for Composer](https://www.mediawiki.org/wiki/Composer).

In your MediaWiki root directory, you can execute these two commands:

    COMPOSER=composer.local.json composer require --no-update jeroen/mediawiki-github "~1.4"
    composer update jeroen/mediawiki-github --no-dev -o
    
For more details on extension installation via Composer, see the documentation on MediaWiki.org.

### Verify installation success

Go to Special:Version and see if GitHub is listed there. If it is, you successfully installed it!

## Configuration

The default GitHub repo can be set using the `$egGitHubDefaultRepo` setting. Assign to this setting
the name of the default repo in your LocalSettings file, after the inclusion of this extension e.g.
for Semantic MediaWiki as follows:

```php
$egGitHubDefaultRepo = 'SemanticMediaWiki/SemanticMediaWiki';
```

To restrict from which repositories files can be fetched, use the `egGitHubRepositoryWhitelist`
setting. If this list is empty, which it is by default, users can fetch files from whatever
wiki they specify. This means they can include potentially harmful content. The extension should
escape harmful content; this setting adds an extra layer of security.

To allow only files from a single repo:

```php
$egGitHubRepositoryWhitelist = [
    'SemanticMediaWiki/SemanticMediaWiki',
];
```

To allow files from multiple repos:

```php
$egGitHubRepositoryWhitelist = [
    'SemanticMediaWiki/SemanticMediaWiki',
    'JeroenDeDauw/GitHub',
    'JeroenDeDauw/Maps',
];
```

### Syntax highlighting

If you want code syntax highlighting you need to have the
[SyntaxHighlight](https://www.mediawiki.org/wiki/Extension:SyntaxHighlight) extension enabled and configured. 
    
### Caching

The file contents gets cached in memory during the PHP request. The main MediaWiki cache
is used as secondary cache, with a default TTL of 600 seconds. You can use the
`$egGitHubCacheTime` setting to change the TTL:

```php
$egGitHubCacheTime = 900;
```

You can modify which caching method is used. The supported methods are

* `'full'` - use in memory and MediaWiki caches
* `'none'` - do not do any caching

The default is `'full'`. You can change this setting as follows:

```php
$egGitHubCache = 'none';
```

### Network

You can modify the GitHub raw content URL used to fetch the files. The default is
`https://cdn.rawgit.com`, due to `https://raw.githubusercontent.com` not working on all systems.
You can change this setting as follows:

```php
$egGitHubUrl = 'https://raw.githubusercontent.com';
```

You can modify which method is used to fetch the file. The supported methods are

* `'mediawiki'` - use MediaWikis HTTP class
* `'simple'` - use PHPs file_get_contents

The default is `'mediawiki'`. You can change this setting as follows:

```php
$egGitHubFetcher = 'simple';
```

## Usage

Add `{{#github:FileName}}` to your wiki page, where FileName is the name of the file you want to embed.
This can include a path, for instance `{{#github:docs/INSTALL.md}}`.

You can also specify the repo name and the branch name: `{{#github:FileName|user/repo|branchName}}`.

For syntax highlighting, this extension uses the same attributes as the
[SyntaxHighlight](https://www.mediawiki.org/wiki/Extension:SyntaxHighlight)
extension: lang, line, start, highlight, inline. Theses can be specified in any order using the
attribute names.

``{{#github:FileName|user/repo|branchName|lang=bash|line=1|start=1|highlight=1,5,4|inline=0}}``

The `lang` parameter can be specified as the fourth positional argument.

``{{#github:FileName|user/repo|branchName|bash}}``

The defaults are line=0, start=1, and inline=0 when this functionality is activated.

## Release notes

### 1.5.0 (2019-04-09)

* Changed minimum PHP version to 7.1
* Updated dependencies to increase compatibility

### 1.4.2 (2017-09-28)

* Fixed bug in the MediaWiki file fetching code and made it the default method again

### 1.4.1 (2017-07-01)

* `<pre>` HTML tags no longer get stripped out of the content

### 1.4.0 (2017-06-30)

* Changed extension installation location from vendor to the MediaWiki extensions folder
* Added `$egGitHubCache` setting, allowing for complete disabling of the cache
* Added error logging of failed HTTP requests to GitHub 

### 1.3.0 (2017-06-09)

* Dropped support for PHP < 7.0
* Added `egGitHubRepositoryWhitelist` setting
* Made code more robust in failure cases

### 1.2.0 (2017-04-17)

* Added support for syntax highlighting using MediaWiki's included SyntaxHighlight extension (by Shay Harding)

### 1.1.1 (2016-11-06)

* Fixed critical issue caused by failing i18n registration on recent versions of MediaWiki

### 1.1.0 (2016-07-11)

* Dropped support for PHP < 5.5
* Fixed error on file not found. The parser function will now return an empty string in this case.

### 1.0.3 (2016-07-10)

* Fixed version number on Special:Version
* Switched from FileFetcher ~2.0 to ~3.1

### 1.0.2 (2015-01-20)

* Added `$egGitHubFetcher` setting
* The files are now by default fetched using `file_get_contents` rather than MediaWikis `HTTP` class

### 1.0.1 (2015-01-19)

* Dropped support for MediaWiki < 1.24
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

## Running the tests

To use [MediaWiki vagrant](https://www.mediawiki.org/wiki/MediaWiki-Vagrant),
see [this gist](https://gist.github.com/JeroenDeDauw/bf61ebcc1ecfd338183cd61de55c7910)
which includes the steps needed to install PHP7 and run the MediaWiki test runner with PHPUnit 6+.

To run code style checks and the tests that do not rely on MediaWiki, execute this in the base directory:

    composer ci

## Author

The GitHub extension was created and is maintained by [Jeroen De Dauw](https://entropywins.wtf/).

## Links

* [GitHub on Packagist](https://packagist.org/packages/jeroen/mediawiki-github)
* [GitHub on Ohloh](https://www.ohloh.net/p/mediawiki-github)
* [GitHub on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:GitHub)
* [TravisCI build status](https://travis-ci.org/JeroenDeDauw/GitHub)
* [Latest version of the readme file](https://github.com/JeroenDeDauw/GitHub/blob/master/README.md)
