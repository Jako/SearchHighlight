The plugin in this package highlights the searched terms in the `searched` request parameter on the fly on the current displayed page.
If the request parameter `searchtype` is filled with `exactphrase`, only the full request in `searched` is highlighted.

The highlight result could be templated with a chunk, that could be referenced in the MODX system settings.

## System Settings

SearchHighlight uses the following system settings in the namespace `searchhighlight`.

Key | Description | Default
----|-------------|--------
Debug | Log debug informations in MODX error log. | No
Disabled Tags |(Comma separated list) SearchHighlight does not replace text inside of this HTML tags. | `head,form,select`
Search Highlight CSS Classnames | CSS classnames that are assigned to the found text on the current page. If multiple classnames are set, the first classname is added to every here  | `highlight`
Highlight Template | Template Chunk for the search highlight replacement. | `tplTermHighlight`
Searched Parameter Key | Request parameter key that contains the searched terms. | `searched`
Search Terms Marker | Marker that is replaced with the search terms. | `<!--search_terms-->`
Search Terms Template | Template Chunk for the search terms. | `tplSearchTerms`
Searchtype Request Key | Request parameter key that contains the searchtype. | `searchtype`
Restrict to Sections | Replace SearchHighlight links only in sections marked with \'&lt;!— SearchHighlightStart --&gt;\' and \'&lt;!— SearchHighlightEnd --&gt;\'. The section markers could be changed with the settings \'searchhighlight.sectionsStart\' and \'searchhighlight.sectionsEnd\'. | No
Section End Marker | Marker at the end of a section processed by SearchHighlight. The restriction to marked sections can be activated in the setting \'searchhighlight.sections\'. | `<!-- SearchHighlightEnd -->`
Section Start Marker | Marker at the start of a section processed by SearchHighlight. The restriction to marked sections can be activated in the setting \'searchhighlight.sections\'. | `<!-- SearchHighlightStart -->`