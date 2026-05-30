---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: content/federation_node/0/captains_log/AGENT_ROLES_STRUCTURE/2026/04/30_the_em_dash_conspiracy.md
  web_path: https://www.lupopedia.com/lupopedia/content/federation_node/0/captains_log/AGENT_ROLES_STRUCTURE/2026/04/30_the_em_dash_conspiracy.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/captains_log/canonical/1026/04/30-em-dash-conspiracy.toon
  atoms_toon: null
  transcript_jsonl: 0/captains_log/30-em-dash-conspiracy
  artifact_type: documentation
  artifact_kind: guide
  channel_key: captains_log
  federation_node_id: 0
  thread_key: 30-em-dash-conspiracy
  lupopedia.schema: documentation
  prd_cluster: null
  title: 'The Em Dash Conspiracy: Why Non-ASCII Characters Are Breaking Your Build'
  summary: 'Captain''s log on ASCII-only discipline: smart quotes, em dashes, and Unicode as grep, diff, and tooling hazards; prefer -- straight quotes -> and [x].'
---
# The Em Dash Conspiracy: Why Non-ASCII Characters Are Breaking Your Build

## Or: How I Learned to Stop Worrying and Love the Hyphen

Let me tell you a story.

In 2009, I used computers every day. Everything was fine. Hyphens were hyphens. Quotes were straight. Life was simple.

Then I took a break.

When I came back, something had changed. My logs were full of weird characters. My diffs were failing. My grep commands returned nothing. I thought I was losing my mind.

"Smart quotes," they said. "Em dashes," they said. "They're better," they said.

**They lied.**

---

## The Conspiracy Theory (With Evidence)

I am almost certain that em dashes did not exist 15 years ago.

I have no proof. But I also have no proof that this wasn't introduced specifically to break grep.

Here's what actually happened:

Someone, somewhere, decided that plain ASCII wasn't "pretty enough." They wanted curly quotes. They wanted long dashes. They wanted little pictures in their code.

And they convinced everyone that this was "progress."

Meanwhile, the rest of us were trying to:

* Run grep without getting garbage output
* Open files in Windows Notepad without seeing corrupted characters
* Parse JSON without something breaking downstream
* Read logs without needing an exorcist

---

## The Technical Reality (Less Funny, More True)

Non-ASCII characters cause real, measurable problems:

| Problem             | Example                                                           |
| ------------------- | ----------------------------------------------------------------- |
| Diffs fail          | Git shows binary diff for a text file                             |
| Encoding drift      | Garbage characters instead of expected symbols                    |
| Search breaks       | grep "title" finds nothing because the file contains smart quotes |
| Parser issues       | Downstream tools choke on unexpected Unicode                      |
| Terminal corruption | A simple arrow becomes escaped byte sequences                     |

---

## The Solution (No Humor Allowed)

This is not about preference. This is about survival.

ASCII only. No exceptions.

* Use -- for long dashes
* Use " and ' for quotes
* Use -> for arrows
* Use [x] for checkmarks

Your code will work everywhere. Your logs will be readable. Your grep will find things. Your system will behave predictably.

---

## The Conspiracy Continues

I still don't trust em dashes. Every time I see one, I know something, somewhere, is going to break later.

So I ban them.

No emoji. No Unicode. No smart quotes. No em dashes.

Just ASCII. Predictable. Boring. Reliable.

-- End Transmission --
