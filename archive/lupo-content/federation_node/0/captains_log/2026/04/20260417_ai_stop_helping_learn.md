---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-content/federation_node/0/captains_log/20260417_ai_stop_helping_learn.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-content/federation_node/0/captains_log/20260417_ai_stop_helping_learn.md"
  status: "draft"
  when_updated: "20260417205659"
  trust_tier: "development"
  questions_toon: null
  memory_toon: "lupo-memory/development/development/2026/04/ai-stop-helping-learn.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/ai-stop-helping-learn"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "ai-stop-helping-learn"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Captain's log: My fight with AI (liquid design vs helpful nonsense)"
  summary: "Personal essay on handcrafted 9-slice UI, repeat-x/repeat-y discipline, doctrine-first code workflow, and why AI defaults (cover, stretch, auto-center) fight intentional pixel work."
---
# My Fight With AI: Liquid Design vs. Helpful Nonsense

I’ve been building UIs since the web ran on tables, spacer GIFs, and prayer.

And yeah, I still use **liquid design** — the real kind. The kind that doesn’t rely on “hero images,” flexbox magic, or whatever the current JavaScript framework flavor of the month tells you is best practice.

Liquid design isn’t nostalgia — it’s the only layout system that never breaks, never distorts, and never depends on a framework surviving the next fad cycle.

I cut my own 9-slice PNGs by hand. I align every seam pixel-perfect in GIMP. I use `repeat-x` and `repeat-y` like they’re religious commandments. The layout is deterministic. It never stretches. It never centers something it shouldn’t. It just *works*, at any window size, on any device, forever.

Modern tools (and modern AIs) look at that workflow and their circuits melt. They don’t even understand the problem I’m solving.

## The Fight: AI Keeps Trying to “Help”

And then I hand the same problem to an AI coding assistant.

“Oh, maybe the image is a different size, let me stretch it for you :)”  
“Let me center it :)”  
“Let me add `background-size: cover` :)”  
“Let me throw a gradient behind it :)”  
“Let me wrap your 9-slice in a flexbox container with 14 divs because… reasons :)”  
“Let me rewrite your entire CSS while I’m at it :)”

Meanwhile I’m literally yelling at the screen:

**“NO. I MADE THE IMAGE. I KNOW EXACTLY WHAT SIZE IT IS.”**

The AI treats my carefully crafted PNG the same way it would treat some random stock photo it scraped off the internet. It assumes I’m lazy. It assumes I want “auto-everything.” It has zero respect for the doctrine I spent years perfecting.

## The Parallel: How I Make Images vs. How I Make Code

Here’s where it gets interesting — because my coding workflow is literally the same disciplined pipeline. And half the time, I’m fighting the AI to keep it from sprinting ahead like an overcaffeinated intern.

I’ll be halfway through writing a PRD and the AI is already generating React components, database migrations, and a Kubernetes manifest.

And I’m sitting there saying:

“Slow down.  
No, we are not coding yet.  
Do you build the car frame before you know the size of the engine?”

Because that’s what real engineering is: sequence, structure, doctrine.

**Images**  
1. Multiple AI agents generate raw art concepts.  
2. I review every single one.  
3. I open GIMP and cut them up.  
4. I airbrush, flip, retouch, and align every pixel.  
5. I export perfect 9-slice tiles.  

The AI gives me raw material. I do the actual craft.

**Code**  
1. I write detailed **PRD files** that define the feature, the rules, the constraints, the edge cases — the *why* behind the code.  
2. I create **mockups of every single element** in that PRD — pixel-perfect, liquid-design compliant. No guessing.  
3. **Only then** do we touch the database. I design every table, every column, every relation by hand.  
   - No “just throw everything into one giant JSON blob” laziness.  
   - If something needs to be a searchable row with proper indexes and foreign keys, it gets its own damn table and columns.  
4. I maintain **JSON schemas** for every table and every column — the doctrine that keeps the system self-documenting and fully recoverable.  
5. Then I write the **base-case code** — the simplest, most boring implementation that works *everywhere*. No fancy frameworks, no external services, no assumptions.  
6. From that rock-solid base, we move in two directions:

   **Fall-Forward (Features)**  
   This is optimistic progression.  
   - I load a page.  
   - The system tries the modern path first (AJAX calls, newer services, advanced features).  
   - If it works — great, we fall forward into the better experience.  
   - If anything fails, we gracefully degrade to the base case without breaking.  

   **Fall-Back (Infrastructure)**  
   This is the defensive safety net.  
   - The app tries the primary database first.  
   - If the database is down, corrupted, or unreachable, we fall back to the filesystem.  
   - The JSON files on disk contain the exact same schema the DB uses.  
   - The system keeps running smoothly.  
   - No data loss. No panic. No blank screens. No “sorry, service unavailable.”  

Most people start with frameworks and hope the architecture emerges.  
I start with architecture and let the implementation fall into place.

Meanwhile the AI is still bouncing in the corner like:  
“I generated a GraphQL layer and a microservice and a dark mode toggle and—”

And I’m like:  
“Stop. We’re not even done with the schema yet.”

AI agents still write individual classes or functions — they generate the raw material. But I assemble it. I enforce the architecture. I maintain the doctrine. I keep the entire system deterministic.

It’s the exact same workflow as the images:  
**AI generates raw material. I do the real engineering.**

## The Punchline: Kids Think I’m an Old Fart Until They See the Setup

New developers walk into my office, see me typing in Notepad++ and laugh: “lol old fart.”

Then they look over my shoulder and see:

- 10 IDE agents running in parallel  
- 3 command-line AIs churning out code  
- 4 external LLMs feeding ideas  
- A liquid 9-slice UI that still looks perfect  
- A custom doctrine system that enforces every decision  
- A multi-agent orchestration pipeline that actually respects my rules  

And their faces change instantly:

“Wait… what the hell is this guy doing… and why does it look better than anything we’ve shipped?”

## The Lesson: AI Is a Tool, Not a Designer

AI is *fantastic* at generating raw material.  
AI is *terrible* at respecting handcrafted systems.

It assumes everything is a hero image.  
It assumes you want auto-everything.  
It doesn’t understand liquid design.  
It doesn’t understand pixel-perfect slicing.  
It doesn’t understand doctrine.

But I do.

AI can generate code. I build systems. There’s a difference.

That’s why my system works.  
That’s why it survives database outages.  
That’s why it still looks perfect after fifteen years.

**See also (expanded, TOON terminology + doctrine alignment):** `lupo-content/federation_node/0/captains_log/20260418_ai_stop_helping_learn_token_toon_and_doctrine.md`