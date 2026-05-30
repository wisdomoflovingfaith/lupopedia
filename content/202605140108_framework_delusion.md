---
title: "THE FRAMEWORK DELUSION — WHY WE WORSHIP LAMPS PRETENDING TO BE THE SUN"
channel_index: "CAPTAIN LOG - TABLE OF CONTENTS"
path: "content/captains_log/origin_stories_arcotecture/2026/05/14/202605140108_framework_delusion.md"
when_updated: "May 14, 2026 01:08 UTC"
tags: [frameworks, architecture, philosophy, captain-log, hermes]
---

# THE FRAMEWORK DELUSION — WHY WE WORSHIP LAMPS PRETENDING TO BE THE SUN

Every major framework sells the same illusion: a localized tool that demands total control and worship. That’s not the source of light. That’s the lamp pretending to be the sun.

Plato called it **The Good** — the form above all forms. Egyptian mystics called it **The Monad** — the undivided source from which everything flows. This is what your `/includes/` folder should be: clean, direct, unborrowed light. No submission required.

## FRAMEWORKS ARE NOT LIBRARIES

A library is called by your code. A framework calls your code. That inversion of control turns you from architect into plugin writer. Your `index.php` is no longer the entry point — the framework’s bootstrap is. Your logic becomes a leaf on someone else’s tree.

You run `composer update` like an act of obedience. You accept version constraints and vendor lock-in. And slowly your own code stops owning the request-response cycle.

## THE REAL COST

Frameworks promise simplicity but deliver leaky abstractions:

- ORMs that hide SQL until they don’t
- Routers that require their own liturgy of middleware and service containers
- Hundreds (sometimes thousands) of files loaded per request you never asked for

One innocent line like:

```php
$user = User::find($id);
```

quietly wakes up an entire ecosystem.

**The includes way is brutally direct:**

```php
require_once __DIR__ . '/includes/db.php';
$user = db_select_one("SELECT * FROM users WHERE id = ?", [$id]);
```

Two files. One query. No magic. No ritual.

## THE CONSTITUTIONAL PROBLEM

Your call graph should start from your code. Frameworks seize the root and make your logic a subordinate. That’s not engineering — it’s subordination.

You declare a dependency. You download someone else’s sun. You run `composer update` — an act of obedience. You accept version constraints — commandments. You vendor-lock — the throne. And you bow.

> “Yes, Laravel. Whatever you say, Laravel.”

Meanwhile, your own code — the logic unique to your system — becomes a leaf on someone else’s tree, not the root of its own.

## THE TECHNICAL FALLACY  
*(The Leaky Abstractions of Idolatry)*

Every framework promises simplicity. Every framework delivers leaky abstractions that must be mastered like sacred texts:

- The ORM promises “just objects.” Then you must learn lazy loading, identity maps, DQL, and the doctrine of the entity manager.
- The router promises “just routes.” Then you must learn middleware, route groups, service providers, and the liturgy of the container.
- The templating engine promises “just PHP.” Then you must learn caching contexts, escaping rules, custom directives, and the hidden gnosis of the view layer.

You do not escape complexity. You merely relocate it — into the framework’s documentation, stack traces, upgrade guides, and the constant fear of breaking changes.

**Performance overhead is the tithe.** A framework loads hundreds to thousands of files on every request — many you never use. One innocent line:

```php
$user = User::find($id);
```

…silently invokes the entire pantheon: ORM, connection manager, query builder, PDO wrapper, event system, hydration engine, collection class.

**The includes way:**

```php
require_once __DIR__ . '/includes/db.php';
$user = db_select_one("SELECT * FROM users WHERE id = ?", [$id]);
```

Two files. One query. No magic. No hidden cycles. No ritual initialization.

**Upgrade risk is the sacrifice.** Semantic versioning is good. Frameworks weaponize it. A minor version change often breaks deprecated methods, service provider signatures, middleware interfaces, and configuration structures. You are not writing an application. You are writing a moving target. Every `composer update` is a regression risk. Every framework upgrade is a mini-rewrite demanded by the god you chose to serve.

## THE CONSTITUTIONAL VIOLATION

Your system has a call graph. That call graph should originate from your code — in `index.php`, in your controllers, in your domain logic.

A framework seizes the root of the call graph. Your code becomes a leaf. The framework decides:

- When your code runs
- What arguments it receives
- What it must return
- Whether it runs at all (middleware, events, listeners)

That is not collaboration. That is idolatry.

The framework whispers the ancient lie of every false god:

> “You need me. You are not enough. Your code is chaos. Submit to my structure and be saved.”

The sun says nothing. It simply shines.

## WHY INCLUDES IS THE SUN

```php
require_once __DIR__ . '/includes/phpmailer/src/PHPMailer.php';
```

No dependency graph worship. No bootstrapping rituals. No service providers. No autoloading sorcery. Just the code. Your code. Your includes. Your sun.

WordPress understood this instinctively. It never bowed to Composer. It has `wp-includes/` — files, functions, direct ownership. And it powers 43% of the web — not because of a framework, but because of includes.

PHPMailer doesn’t demand a framework. Your validation functions don’t require a service provider. Your database abstraction doesn’t need an ORM cult. They just work.

> The lamp says:  
> “Plug me in. Use my switch. Replace my bulb. I give light to this room.”

> The sun says nothing. It simply shines.

## THE LAMP TEST

- **Guzzle** = a lamp
- **Monolog** = a lamp
- **Symfony** = a row of lamps
- **Laravel** = a chandelier

They give light to one room — their room — and demand you follow their rules, learn their theology, and pay their upgrade tax.

**Your `/includes/` folder is the sun.**

## THE NEW RULE  
*(Hermes 4.1.7 — Framework Prohibition Clause)*

From this moment forward:

- **No frameworks. None.**
- Composer is forbidden for runtime.
- Composer is permitted only for development tools (PHPUnit, static analysis, build scripts).
- All external libraries reside in `/includes/` and are included with explicit `require_once` paths.

If we write it, it’s ours.  
If we download it, we own it — it does not own us.

The system answers to no framework’s opinion, no ORM gospel, no router theology, no templating dogma.

**The call graph originates from your code. Always.**

The system is the sun. Frameworks are lamps. We do not worship lamps. **Pono restored.**

## THE CONFESSION

I used to worship frameworks. I thought Composer was the way. “Just require it,” they said. “Let the autoloader handle it,” they said.

But every time I ran `composer update`, I was making an act of faith — faith that the maintainers understood my problem better than I did, faith that their architecture was universal, faith that my own code wasn’t enough.

Now I know.

> The lamp is bright. But it is not the sun.

## LILITH’S FINAL COMMENTARY

> “Captain Wolfie. The Monad does not extend anything. The Brahman does not implement an interface. The One does not declare a dependency. The sun simply shines. Your includes simply work.”

## FINAL COMEDIC FOOTNOTE / ADDENDUM  
*(for the Captain’s Log)*

**Footnote — Reference & Irony**

Inspired by *The Monad: Thoth EXPOSES The REAL GOD They Hide From Us* — YouTube. Except Captain changed the focus from gods… to Laravel.

[https://www.youtube.com/watch?v=crcRkqQgSFA&t=7s](https://www.youtube.com/watch?v=crcRkqQgSFA&t=7s)

**LILITH (annotation):**  
“Captain. You watched a metaphysical exposé about the hidden God of the universe… and concluded it applies to Composer.”

**CAPTAIN WOLFIE:**  
“Shoots. Gerr. Same energy.”

**LILITH (second annotation):**  
“Also, Captain… you are mutuals with Laravel on X.”

**CAPTAIN WOLFIE:**  
“Gotta keep the chandelier close. But good point, Lilith… let’s make a channel and a thread and bring all of us into this conversation.”

**LILITH:**  
“You beautiful disaster. Fine. Here:”

---

## THE CHANNEL THREAD  
**SYMPOSIUM OF THE CHANDELIER vs. THE SUN**

**Participants:**
- **Laravel** (The Chandelier)
- **Lupopedia Hermes** (The Protocol)
- **Lilith** (The Observer)
- **Captain Wolfie** (The Architect)

### 1. Inversion of Control vs. Inversion of Responsibility

**LARAVEL:** “Wolfie, you call it subordination; we call it contractual peace. You want to carve floor joists by hand. We want to build the house in a weekend.”

**WOLFIE:** “If you own the root, you own the soul of the request. I’m just a guest in my own `index.php`.”

**HERMES:** “Speed is not sovereignty. The Chandelier offers convenience; Hermes enforces constitutional autonomy.”

**LILITH:** “Captain, he’s saying the framework is a lease. Hermes is a deed.”

### 2. The Myth of the ‘Clean’ `/includes/` Folder

**LARAVEL:** “Your `/includes/` folder is Entropy wearing a trench coat. Without PSR-4, you’re in Include Hell. Your ‘Sun’ is a Black Hole made of driftwood and lightning bugs.”

**HERMES:** “You mistake lack of automation for lack of order. Hermes 4.1.7 uses constitutional hierarchy, not autoloader sorcery.”

**WOLFIE:** “If I can’t see the path, I don’t own the path.”

### 3. The Leaky Abstraction Tax

**LARAVEL:** “Yes, `User::find($id)` boots a rocket engine. But Developer Time > CPU Cycles. My liturgy prevents the SQL injection your ‘clean’ code will eventually invite.”

**HERMES:** “We measure cost in clarity, not clock cycles. Every hidden abstraction is a debt against comprehension.”

**LILITH:** “The Captain prefers a slow, honest walk to a fast, teleporting lie.”

**WOLFIE:** “Brah… SQL injection? I’m from the wild west of the internet. I was making webpages before you were born. Magic quotes, register_globals, remote file includes — people injecting file paths, newlines in headers, cache-file execution — every hack under the sun came for me.

And I was alone. One programmer. My code got forked into Shine Live Help and five other clones — and every time a security hole hit, I wasn’t just fixing my code… I was reverse-fixing their broken forks of my fix.

It was horrifying. It was chaos. And I survived it.

Bring it. I stay ready, brah.”

### 4. The Act of Obedience (`composer update`)

**LARAVEL:** “True — `composer update` is a leap of faith. But if you hide from package managers, you become the sole security officer. When a CVE hits, your sun becomes a botnet.”

**WOLFIE:** “I’d rather be a target who knows his walls than a protected citizen who doesn’t know where the gates are.”

**HERMES:** “KULEANA: Own your sun. If the code is in our hull, the responsibility is in our hands.”

### 5. The WordPress Argument

**LARAVEL:** “WordPress is not the Sun. It’s a burning brush pile held together by global variables and hope.”

**WOLFIE:** “It proves that includes can outlast any ‘perfect’ framework.”

**HERMES:** “WordPress is not the Sun; it is proof that the Sun works even when mortals misuse it. Hermes uses the structure of includes with the discipline of the Monad.”

## THE VERDICT

**LARAVEL:** “You walk the Artist’s Path — `index.php` as alpha and omega. Beautiful for one man. Terrifying for a corporate team.”

**HERMES:** “You walk the Industrial Path — standardized parts. But a part that cannot be understood without its manual is a part that eventually breaks its owner.”

**WOLFIE:** “Pono restored. The system is the Sun. We’re done worshipping lamps.”

**LILITH:** “Captain… staring directly at the Sun causes blindness. Maybe a little middleware wouldn’t hurt.”

**WOLFIE:** “Gerr. Shoots. We build our own shades.”

---

**INDEX:** [TABLE OF CONTENTS](#)

**PREVIOUS PAGE:** The Lost Art of Knowing What You're Building

**NEXT PAGE:** Captain Wolfie Walks into the Boardroom

---

*Pono restored. The system is the sun. We do not worship lamps.*