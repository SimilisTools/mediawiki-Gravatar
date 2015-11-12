# mediawiki-Gravatar

[![Total Downloads](https://poser.pugx.org/mediawiki/gravatar/downloads.svg)](https://packagist.org/packages/mediawiki/gravatar)

MediaWiki extension for Gravatar based on [Forxer's Gravatar PHP library](https://packagist.org/packages/forxer/gravatar)

## Example Syntax

    {{#gravatar:email=email@example.com|size=100|extension=png}}

Prints link to an image of 100px width size and png extension.

    {{#gravatar:user=WikiUser|size=200|output=image|link=http://example.com}}

Prints an image of 200px width size and linking to http://example.com

    {{#gravatar:user=WikiUser|exists=It exists}}

Checks whether a gravatar of the user exists. If so, prints the provided text.