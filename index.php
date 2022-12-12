<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home page</title>
</head>
<body>
  <h1>Home page</h1>
  <? include('counter.php') ?>
  <p>
    Now what?
  </p>
  <ul>
    <li>
      <a href="/">Reload this page</a>
    </li>
    <li>
      <a href="/red.php">Switch to the Red Page</a>
    </li>
    <li>
      <a href="/blue.php">Switch to the Blue Page</a>
    </li>
    <li>
      <a href="/form.php">See a working &lt;form&gt;</a>
    </li>
    <li>
      <a href="/phpinfo.php">Show PHPInfo</a>
    </li>
  </ul>
  <p>
    <small>
      🍪 Look ma! No cookies!
    </small>
  </p>
</body>
</html>
