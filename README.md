# PHP 4 Transparent Session Experiment/Demonstration

## Background

[PHP 4 introduced](https://www.php.net/manual/en/history.php.php#history.php4) built-in support for session management. Back in the 1990s, you couldn't necessarily rely on cookie support, even for sessional cookies, in browsers, so PHP also provided [a feature called transparent session support](https://web.archive.org/web/20000815110812/http://www.php.net/manual/ref.session.php) ([transparent sessions](https://www.php.net/manual/en/session.configuration.php#ini.session.use-trans-sid) are still supported, but they're very unlikely to be enabled by default even support for them is compiled-in).

The way transparent sessions worked was that PHP would try to detect `<a href="...">`, `<area href="...">`, `<frame src="...">`, and `<input src="...">` attributes, as well as `<form>`s (which gained a hidden input) and manipulate the URL by adding the session ID as a query parameter. When the resulting request arrived (e.g. a link was clicked), this provided a continuation of browsing state even if a cookie wasn't sent back by the browser. It was ingenious, if hacky.

During a conversation with a coworker who came to PHP well after this behaviour became outdated, I discovered how little-known it might be. This project attempts to demonstrate how PHP 4+'s transparent sessions worked in practice, using a PHP 4 installation (via Docker).

## How to use

### Building and running

Build the docker image and run it, mounting the project directory, e.g.:

```bash
build -t php4-experiment .
run -v $(pwd):/var/www/html -p 8008:80 --rm php4-experiment
```

(if using a Windows Command Prompt, use `%cd%` instead of `$(pwd)`)

### Demonstration

1. Visit http://localhost:8008/
2. Click through to a variety of pages and observe how the "pages loaded:" counter keeps incrementing.
3. On [the form page](http://localhost:8008/form.php), enter your name and submit; notice how it remembers your name even after navigating to another page and back.
4. Observe how the session ID appears in the address bar (it's the same as the kind you'd get in a "normal" PHP session cookie); observe how no cookie is set.

## Understand

I used [@nouphet](https://github.com/nouphet)'s [PHP4.4 Docker image](https://github.com/nouphet/docker-php4) as the basis for this project, with the following modifications:

- `register_globals = "On"`; this defaulted to "On" until PHP [4.2](https://www.php.net/ChangeLog-4.php#PHP_4_2); it's "Off" by default nowadays for good reason, but turning it on improved the historical fidelity of the experiment.
- `session.use_trans_sid = "On"`; this was locked-on in PHP 4 versions prior to [4.0.3](https://www.php.net/ChangeLog-4.php#4.0.3), and defaulted to `"On"` (where support was compiled-in) thereafter until... I'm not sure when! It instructs PHP to inject the session ID into links unless a cookie was received: as a side-effect, this often meant that the first clicks on a web application after loading it would show a session ID in the address bar (because the cookie hadn't been returned yet) but subsequent ones didn't. This suboptimal behaviour - from a security perspective - is part of the reason that transparent sessions would soon become non-default: when sharing a link with a friend, you seldom want them to automatically take over your session!
- `session.use_cookies = "Off"`; this is the only "unusual" change I've made: I've disabled cookie-based sessions entirely so that you can see that this experiment _never_ sets a cookie, even if your browser accepts them, but session state is maintained. In a more-natural late-1990s environment you'd see transparent and cookie-based sessions work hand-in-hand, and PHP would select the right one in a relatively smart way.

PHP's detection of where to inject transparent session IDs into URLs wasn't perfect. In particular, it failed in a few areas that would require developer attention if it was to be expected to behave properly, including:

- `<form method="GET" action="...">` could go awry if query parameters were included in the `action` already
- Because it only looks at the tags listed above, other tags (e.g. `<img src="...">`) needed special attention if session support was required for them
- Similarly, PHP couldn't understand interpolation of URLs in Javascript or similar client-side scripting languages (do you remember, back then, when sometimes people would write JScript or VBScript instead of Javascript? bad times...)
- PHP didn't do anything with URLs presented in headers, so if you sent a `header('Location' . $redirect_url);` you'd need to force the session ID to be appended manually (there's [a function to help with that](https://www.php.net/manual/en/function.session-id.php))
- Obviously this technique failed if the user opened two browser windows, pointing to the same site (but one of them without the session ID): the two windows would be treated as separate users... although in some cases I suppose that could be considered a feature!

One fringe advantage of transparent sessions over cookies is they can be scoped to domains that don't share a root: so long as both sites share access to the same [save handler](https://www.php.net/manual/en/function.session-set-save-handler.php) (e.g. filesystem or database), a session can propogate from one site to the other and back again. Even where transparent sessions are disabled, as is now the case, this technique can be used even in modern PHP - just redirect the user to the "other" site, including the session ID, and the recipient site can access the session contents. Cross-site cookies made easy (with minor limitations)! Of course, branding and reasons usually make it inadvisable to spread a user's session across multiple distinct domains, and using subdomains is better-served by scoping your cookies to the top-level domain, but the option's there I suppose!

## License

In the unlikely event this is any use to you, consider it MIT-licensed.
