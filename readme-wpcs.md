# WordPress Coding Standards

[WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)

## Excluded:

- Missing doc comment for function ...() (Squiz.Commenting.FunctionComment.Missing)
- You must use "/**" style comments for a function comment (Squiz.Commenting.FunctionComment.WrongStyle)

- Inline comments must end in full-stops, exclamation marks, or question marks (Squiz.Commenting.InlineComment.InvalidEndChar)

- This comment is xx% valid code; is this commented out code? (Squiz.PHP.CommentedOutCode.Found)

- Loose comparisons are not allowed. Expected: "..."; Found: ".." (Universal.Operators.StrictComparisons.Loose...)

- Use Yoda Condition checks, you must. (WordPress.PHP.YodaConditions.NotYoda)

- All output should be run through an escaping function (see the Security sections in the WordPress Developer Handbooks), found .... (WordPress.Security.EscapeOutput.OutputNotEscaped)

- Processing form data without nonce verification. (WordPress.Security.NonceVerification.Recommended)

- Resource version not set in call to wp_enqueue_...(). This means new versions of the script may not always be loaded due to browser caching. (WordPress.WP.EnqueuedResourceParameters.MissingVersion)

- A function call to __() with texts containing placeholders was found, but was not accompanied by a "translators:" comment on the line above to clarify the meaning of the placeholders. (WordPress.WP.I18n.MissingTranslatorsComment)

## Ignored:

- see phpcs comments in some files
