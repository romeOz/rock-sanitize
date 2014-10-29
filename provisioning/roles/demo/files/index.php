<?php

use rock\sanitize\Sanitize;

include_once(__DIR__ . '/vendor/autoload.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Demo Rock sanitize</title>
    <link href="/assets/css/main.min.css" rel="stylesheet">
    <script src="/assets/js/main.min.js"></script>
</head>
<body>
<div class="container main" role="main">
    <div class="demo-header">
        <h1 class="demo-title">Demo Rock sanitize</h1>
        <p class="lead demo-description">The example.</p>
    </div>
    <div class="demo-main">
        <div class="demo-post-title">
            Base
        </div>
        <pre><code class="php"><!--
-->use rock\sanitize\Sanitize;

Sanitize::removeTags()
    ->lowercase()
    ->sanitize('&lt;b&gt;Hello World!&lt;/b&gt;');<!--
--></code></pre>
        Result:
        <pre><code class="html"><?=var_export(Sanitize::removeTags()->lowercase()->sanitize('<b>Hello World!</b>'))?></code></pre>

        <div class="demo-post-title">
            For array or object
        </div>
        <pre><code class="php"><!--
-->use rock\sanitize\Sanitize;

$input = [
    'name' => '&lt;b&gt;Tom&lt;/b&gt;',
    'email' => '&lt;i&gt;(tom@site.com)&lt;/i&gt;'
];

$attributes = [
    'name' => Sanitize::removeTags(),
    'email' => Sanitize::removeTags()->email()
];

Sanitize::attributes($attributes)->sanitize($input);<!--
--></code></pre>
<?php
$input = [
    'name' => '<b>Tom</b>',
    'email' => '<i>(tom@site.com)</i>'
];
$attributes = [
    'name' => Sanitize::removeTags(),
    'email' => Sanitize::removeTags()->email()
];
?>
        Result:
        <pre><code class="html"><?=var_export(Sanitize::attributes($attributes)->sanitize($input))?></code></pre>
    </div>
</div>
<div class="demo-footer">
    <p>Demo template built on <a href="http://getbootstrap.com">Bootstrap</a> by <a href="https://github.com/romeOz">@romeo</a>.</p>
    <p>
        <a href="#">Back to top</a>
    </p>
</div>
</body>
</html>