---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: question
  when_updated: "20260405172914"
  file_path_from_root: "docs/versions/4.0.94/questions/20260405172914_QUESTION_what_is_correct_help_content_structure.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.94/questions/20260405172914_QUESTION_what_is_correct_help_content_structure.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: question
  artifact_kind: structural
  thread_id: "4.0.94-help-content-organization"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: "What is the correct folder structure for user-side help content?"
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# What is the correct folder structure for user-side help content?

**Question ID:** 20260405172914  
**Asked By:** USER (actor_id 10000)  
**Date:** 2026-04-05 17:29:14 UTC  

## Question

I thought the structure of the content directory was `content\{federation_node_id}\{channel/folder/thread}\{content_file_title}`. Do we have a PRD on how content is organized for that folder in the PRD files? I was a bit confused to see `content/0/0/`. Maybe we need to look at how we are organizing that content.

Also, 42 was the development channel. There should be a channel for help_documentation and it would be way easier if the folder was by channel_key not the channel_id, so we should update the PRD.

## Context

The user identified several issues with the current content organization:

1. **Unclear Structure:** The `content/0/0/` structure doesn't match the expected pattern
2. **Wrong Channel:** Using development channel (42) for help documentation is inappropriate  
3. **Numeric vs Semantic:** Preference for channel_key over channel_id for folder naming
4. **Missing Documentation:** Need PRD updates to reflect correct structure

## Specific Concerns

### Current Structure Issues
- `content/0/0/` uses numeric channel_id (0) which is non-semantic
- Development channel (42) shouldn't be used for user-facing help
- No clear dedicated channel for help documentation

### Desired Improvements
- Use `content/{federation_node_id}/{channel_key}/{content_file}` format
- Create dedicated `help_documentation` channel
- Update PRDs to document canonical channel_key usage
- Better organization for user-facing vs development content

## Related PRDs Mentioned
- PRD 29: Project structure
- PRD 02: Channels and discussions
- Need to check for additional PRDs covering content organization

## Expected Answer

The answer should:
1. Confirm the correct canonical folder structure
2. Explain the difference between channel_id and channel_key usage
3. Identify which PRDs need updating
4. Provide the proper structure for help documentation
5. Explain the benefits of channel_key-based organization

---
**Status:** ANSWERED  
**Related Answer:** See `answers/20260405172914_ANSWER_channel_key_based_organization.md`
