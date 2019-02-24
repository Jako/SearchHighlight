SearchHighlight
===============

- Authors: Thomas Jakobi <thomas.jakobi@partout.info>
- License: GNU GPLv2

## Features

- Highlight search terms on page linked from search results.

## Installation

MODX Package Management

## Documentation

The plugin in this package highlights the searched terms in the `searched` request parameter on the fly on the current displayed page.
If the request parameter `searchtype` is filled with `exactphrase`, only the full request in `searched` is highlighted.

The highlight result could be templated with a chunk, that could be referenced in the MODX system settings. A lot of
SearchHighlight options with the systemsettings in the `searchhighlight` namespace.

## GitHub Repository

https://github.com/Jako/SearchHighlight
