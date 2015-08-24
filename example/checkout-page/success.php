<?php

require __DIR__.'/../../vendor/autoload.php';

use Hochstrasser\Wirecard\Fingerprint;
use Hochstrasser\Wirecard\Context;

$context = new Context('D200001', 'B8AKTPWBRMNBV455FG6M2DANE99WU2', 'de');

$fingerprint = Fingerprint::fromResponseParameters($_POST, $context);
$fingerprintIsValid = hash_equals((string) $fingerprint, $_POST['responseFingerprint']);

?>
<?php if ($_POST): ?>
<h3>Response Parameters</h3>
<pre><code><?php var_dump($_POST) ?></code></pre>
<?php endif ?>

<h3>Fingerprint</h3>

<p>
    Fingerprint is <?= $fingerprintIsValid ? "valid": "invalid" ?>
</p>

<div>Expected:</div>
<div>
    <code><?= $fingerprint ?></code>
</div>
<div>Actual:</div>
<div>
    <code><?= $_POST['responseFingerprint'] ?></code>
</div>

<p>
    <a href="/index.php">New Request</a>
</p>
