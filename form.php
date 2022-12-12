<?
session_register('name');
if( ! $name ) $name = 'Anonymous';
if( $my_name_is ) $name = $my_name_is;
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blue page</title>
</head>
<body bgcolor="#efe">
  <h1>Form page</h1>
  <? include('counter.php') ?>
  <form>
    <p>
      <b>Hi, <?= htmlspecialchars($name) ?>!</b>
      👈
      <i>I'll remember the name you give me when you come back!</i>
    </p>
    <p>
      That's not my name! My name is:
      <input name="my_name_is" placeholder="your name here">
      <input type="submit" value="Hi!">
    </p>
  </form>
  <p>
    Now what?
  </p>
  <ul>
    <li>
      <a href="/">Go home</a>
    </li>
    <li>
      <a href="/red.php">Switch to the Red Page</a>
    </li>
  </ul>
</body>
</html>
