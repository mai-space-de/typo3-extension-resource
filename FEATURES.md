# mai_resource — Feature Reference

## Download Record

Each download record is stored in `tx_mairesource_download` and provides a FAL-managed file
that can optionally be restricted to specific frontend user groups.

| Field | Type | Required | Notes |
|---|---|---|---|
| `title` | `input` (max 255) | ✔ | Record label in backend list; trimmed on save |
| `description` | `text` (rows 5) | — | Short description for the download; trimmed on save |
| `file` | FAL file reference (min 1, max 1) | ✔ | Single downloadable file; all types accepted |
| `fe_groups` | `select` (multiple, FK → `fe_groups`) | — | Restricts access to the listed frontend user groups; empty means publicly accessible |
| `categories` | `category` (manyToMany) | — | Links to `sys_category` — no custom category table |

Records are sorted by the TCA-defined `sorting` field (manual drag-and-drop order in the
backend list).

## Infobox Record

Each infobox record is stored in `tx_mairesource_infobox` and provides a rich-text block
with optional file attachments and multilingual support.

| Field | Type | Required | Notes |
|---|---|---|---|
| `title` | `input` (max 255) | ✔ | Record label in backend list; trimmed on save |
| `content` | `text` RTE (rows 15) | — | Rich-text body; uses RichText configuration `default` |
| `attachment` | FAL file references (max 5) | — | Up to five attached files; drag-and-drop sortable; each can be individually hidden |
| `categories` | `category` (manyToMany) | — | Links to `sys_category` — no custom category table |

The backend form groups the language palette (`--palette--;;language`) and access/visibility
controls (`--palette--;;hidden`, `--palette--;;access`) on dedicated tabs.

Records are sorted by the TCA-defined `sorting` field.

## Template Record

Each template record is stored in `tx_mairesource_template` and holds a single downloadable
document template (forms, contracts, official documents) separate from generic downloads.

| Field | Type | Required | Notes |
|---|---|---|---|
| `title` | `input` (max 255) | ✔ | Record label in backend list; trimmed on save |
| `description` | `text` (rows 5) | — | Short description of the template's purpose; trimmed on save |
| `file` | FAL file reference (min 1, max 1) | ✔ | Single template file; all types accepted |
| `categories` | `category` (manyToMany) | — | Links to `sys_category` — no custom category table |

The backend form groups the language palette and access controls on dedicated tabs.
Records are sorted by the TCA-defined `sorting` field.

The key difference from a Download record: Template records are not gated by frontend user
groups (`fe_groups`) and have a distinct semantic purpose (fillable or reference documents).

## FE Group Access Control

The `fe_groups` field on Download records restricts which logged-in users can access a file.
The field is a multi-select relation to the `fe_groups` table, ordered alphabetically.

| State | Behaviour |
|---|---|
| No groups selected | The download is publicly accessible (no FE login required) |
| One or more groups selected | Only users who are members of at least one of the listed groups may access the record |

**Important:** The access control is enforced at the **content element / template level**, not
at the HTTP level. The `fe_groups` data is stored on the record and must be evaluated by
whichever template or data processor renders the download list or serves the file. TYPO3's
built-in page- and record-level visibility rules (using the `FE Group` system fields) apply
on top of the custom `fe_groups` relation.

No protected file-serving middleware is shipped with `mai_resource` in the current scaffolded
state — implementing the HTTP-level gate is tracked as **task resource-1**.

## sys_category Integration

`mai_resource` follows the project-wide architecture rule: **no custom category table**.

- All three record types (`Download`, `Infobox`, `Template`) use TYPO3's built-in
  `CategoryConfig` (TCA field type `category`), which maps to the shared `sys_category` table.
- Relations are stored in `sys_category_record_mm` (TYPO3 core table — no custom MM table).
- The same `sys_category` tree is shared with `mai_news`, `mai_gallery`, `mai_faq`,
  `mai_testimonials`, and `mai_timeline`, enabling a single category hierarchy across all
  record types.

## Design: TCA-Only Data Extension

`mai_resource` is deliberately a **data-definition extension** in its current scaffolded state.
The `Classes/` directory contains only a `.gitkeep` placeholder.

This design means:

- No Extbase plugin is registered — there is no `ext_localconf.php`.
- No TypoScript setup is shipped — there are no plugin view-path constants.
- No FlexForm is included — editors cannot yet place a resource content element.
- No PHP controller exists — there is no frontend rendering pipeline in this extension.
- Frontend rendering of `tx_mairesource_*` records is pending (see **Planned Rendering** below).

All three record types are immediately available in the TYPO3 backend and can be managed
by editors once the extension is active.

## Download Tracking Specification

Download tracking and protected file serving are designed as a two-part system:

1. **A download counter** that increments each time a file is successfully served.
2. **A protected file-serving endpoint** (PSR-15 middleware or eID script) that enforces the
   `fe_groups` gate before streaming the file.

Implementation is deferred until a concrete frontend use case is defined — see **task resource-1**.

### Database changes

When the feature is implemented, `tx_mairesource_download` will need an additional column:

| Column | SQL type | Notes |
|---|---|---|
| `downloads` | `int(11)` | Download counter; defaults to 0; unsigned. Incremented atomically on each successful file delivery. |

This column should be added via an `ext_tables.sql` override or a TCA `sql` configuration in
the column definition, using TYPO3's schema migration to alter the existing table.

The `downloads` column should NOT appear in the backend edit form by default (no `showitem`
entry); it is a read-only counter visible only in the backend list view via an additional
`columns` override in the TCA `interface` section, or via a custom `ctrl` `selicon_field`
if a visual badge is desired.

### Architecture decision: eID vs. Middleware

| Approach | Pros | Cons |
|---|---|---|
| **eID script** (`eID.php` entry) | Simple routing; no middleware stack side-effects; low overhead; works regardless of page-not-found handling | No access to full TYPO3 frontend context (no site config, no TSFE); manual route registration |
| **PSR-15 middleware** | Full TYPO3 context (site, language, FE user session); composable with other middleware; standard registration pattern | Must be positioned correctly in the stack; slight overhead on every request for route matching |

**Recommended approach: PSR-15 middleware**, for full access to the FE user session (needed
for `fe_groups` evaluation) and site configuration. The middleware should be registered
`after: [typo3/cms-frontend/site]` and `before: [typo3/cms-frontend/page-resolver]` in
`Configuration/RequestMiddlewares.php`, matching the pattern used by `mai_faq`'s API middleware.

### Route design

A dedicated route prefix — for example `/dl/<uid>` — is intercepted by the middleware:

```
GET /dl/42        → Serve download uid=42
GET /dl/42/file   → Serve the original file name from FAL
```

The route matching should use a simple URI path prefix check (e.g.,
`str_starts_with($path, '/dl/')` and parse the trailing segment as the record UID).

If the route does not match the expected pattern, the middleware returns `$handler->handle($request)`
(no-op pass-through).

### Protected file-serving flow

When a matching route is received:

1. **Resolve the record** — Query `tx_mairesource_download` for the UID. Check `hidden=0`,
   `deleted=0`, and `starttime`/`endtime` constraints. Return 404 if not found or disabled.
2. **Check FE groups** — If the `fe_groups` field is non-empty, verify the current FE user
   (`$GLOBALS['TSFE']->fe_user` or PSR-7 attribute) is a member of at least one group.
   Return 403 (JSON `{"error": {"message": "Access denied", "code": 403}}`) if unauthorised.
3. **Check FAL reference** — Resolve the `file` field to a `sys_file_reference`. Verify the
   referenced `sys_file` exists on disk. Return 404 if the file is missing.
4. **Increment download counter** — Execute `UPDATE tx_mairesource_download SET downloads = downloads + 1 WHERE uid = ?` atomically via `ConnectionPool`. Silently ignore failures
   (the counter is non-critical — the file should still be served).
5. **Stream the file** — Return a PSR-7 response with:
   - `Content-Type` from the file's MIME type
   - `Content-Length` from file size
   - `Content-Disposition: attachment; filename="<original-name>"`
   - `Cache-Control: no-cache, no-store, must-revalidate`
   - The file contents as the body stream (use `StreamFactoryInterface::createStreamFromFile()`)
   Composer dependency: `typo3/cms-core` already provides all needed PSR-7/PSR-17 factories.

### Download tracking data model

The counter alone tracks aggregate popularity. If per-user or per-session tracking is later
required, a separate `tx_mairesource_download_log` table can store individual events:

| Column | SQL type | Notes |
|---|---|---|
| `uid` | `int(11)` | Auto-increment primary key |
| `pid` | `int(11)` | Storage page |
| `download_uid` | `int(11)` | FK → `tx_mairesource_download.uid` |
| `fe_user_uid` | `int(11)` | Nullable; FK → `fe_users.uid` if logged in |
| `session_id` | `varchar(255)` | Nullable; PHP session ID for anonymous tracking |
| `remote_addr` | `varchar(45)` | Client IP (IPv4/IPv6) — stored anonymised or truncated |
| `user_agent` | `varchar(512)` | Client User-Agent string |
| `tstamp` | `int(11)` | Timestamp of the download event |

This log table is **explicitly NOT part of the planned first iteration**. It is documented
here as a forward-looking schema extension and should only be implemented when a concrete
requirement for per-user analytics emerges.

### Frontend integration

When a content element plugin renders download lists, each download link should point to the
protected route:

```
<a href="/dl/{download.uid}/{download.file.originalName}">
    {download.title}
</a>
```

This ensures every download goes through the middleware and is counted. Direct FAL URLs
(e.g., `fileadmin/...`) must NOT be used in download list renderings, because they bypass
the access gate and the counter.

For FE-group-restricted files, the plugin template can conditionally show a login prompt
instead of the download link when the user does not have the required groups — this is
a rendering-level UX decision and is not a substitute for the HTTP-level gate.

### Error responses

| Scenario | HTTP status | Response body |
|---|---|---|
| Record not found / disabled | 404 | `{"error": {"message": "Download not found", "code": 404}}` |
| FE group check failed | 403 | `{"error": {"message": "Access denied", "code": 403}}` |
| File reference is missing or file not on disk | 404 | `{"error": {"message": "File not found", "code": 404}}` |
| Counter update fails (DB error) | 200 (file served) | Counter error is silently logged; file is still delivered |

All error responses are `application/json` for consistency with the project's API pattern.
HTML error pages can be considered if a concrete use case demands it.

### Implementation order

1. **TCA change** — Add `downloads` column to `tx_mairesource_download` TCA
2. **Middleware class** — `Classes/Middleware/DownloadMiddleware.php` implementing the full
   flow (record lookup, FE group check, counter increment, file streaming)
3. **Route registration** — `Configuration/RequestMiddlewares.php`
4. **Service container** — Register the middleware in `Configuration/Services.yaml`
5. **Tests** — Unit tests for the middleware covering all response scenarios (404, 403, 200,
   missing file, FE group check pass/fail)
6. **Log table** (future) — Only when per-user analytics is required
7. **Extbase plugin** (future) — When the frontend listing plugin is implemented (resource-2)

## Render Path Specification

This section documents the data model and rendering paths for infobox and template records
as a design contract. Frontend rendering plugins will be built when a concrete use case
emerges.

### Infobox rendering path

The expected approach for **infoboxes** is an Extbase plugin that:

1. Queries `tx_mairesource_infobox` via an `InfoboxRepository` scoped to `settings.storagePid`.
2. Accepts FlexForm settings for `storagePid` and optional `categoryUid` filter.
3. Passes results (including the resolved FAL attachment references) to a Fluid template in
   `mai_theme`'s template structure:
   - Container variable: `infoboxes` (Extbase `QueryResultInterface`)
   - Per-record variables: `title`, `content` (processed RTE HTML), `attachments` (FAL collection)
4. The Fluid template renders a `<div class="mai-infobox">` with `{infobox.title}`,
   `{infobox.content -> f:format.html()}`, and attachment download links.

### Download list rendering path

The expected approach for **downloads** is an Extbase plugin that:

1. Queries `tx_mairesource_download` via a `DownloadRepository`.
2. Filters by `settings.categoryUid` (optional) and enforces `fe_groups` visibility using
   TYPO3's record-level access check (`AccessControl` or a custom `f:if` guard).
3. Passes `downloads` to a Fluid template that renders file metadata (title, description,
   file type icon, file size) and a download link pointing to the protected route
   (`/dl/{download.uid}`) — never a direct FAL URL.

### Template/form list rendering path

The expected approach for **document templates** is similar to the download list but omits
the `fe_groups` check and uses a separate Fluid template with a semantic label ("Template"
or "Form") to distinguish template records from generic downloads. Template links also use
the protected `/dl/` route (which checks only the `fe_groups` condition on the Download
table — Templates have no `fe_groups` field, so the gate always passes for them).

### Future Extbase plugin registration

When the rendering plugins are implemented (tracked as potential subtasks of **resource-2**):

- `ext_localconf.php` registers the Extbase plugins
- TypoScript constants/setup map each plugin to its Fluid template paths in `mai_theme`
- FlexForms define `storagePid` and optional `categoryUid` filters
- Plugin registration follows the same pattern as `mai_faq` and `mai_news` for consistency

## Backend Icon

All three record types (`tx_mairesource_download`, `tx_mairesource_infobox`,
`tx_mairesource_template`) use the shared icon from `mai_base`:

```
EXT:mai_base/Resources/Public/Icons/generic_table.svg
```

This maps to the `mai-table` TYPO3 icon identifier. See `mai_base` README for the shared
icon contract and the `mai-*` namespace reservation rules.

The extension's own `ext-maispace-mai_resource` icon (registered in `Configuration/Icons.php`)
is used for the extension's entry in the Extension Manager only.

## Database Tables

All three tables are auto-generated by TYPO3 from the TCA definition (no `ext_tables.sql`).
`setDefaultConfig()` (from `mai_base`'s `Table` builder) adds the standard TYPO3 enableFields:
`hidden`, `deleted`, `starttime`, `endtime`, `sys_language_uid`, `l18n_parent`, `l18n_diffsource`,
`crdate`, `tstamp`, and `sorting`.

### `tx_mairesource_download`

| Column | SQL type | Notes |
|---|---|---|
| `uid` | `int(11)` | Auto-increment primary key |
| `pid` | `int(11)` | Storage page |
| `title` | `varchar(255)` | Record label; required |
| `description` | `text` | Short description; optional |
| `file` | `int(11)` | FAL reference count; actual files in `sys_file_reference` |
| `fe_groups` | `text` | Comma-separated list of `fe_groups.uid` values; empty = public |
| `categories` | `int(11)` | MM counter; relations in `sys_category_record_mm` |
| Standard enableFields | — | `hidden`, `deleted`, `starttime`, `endtime`, `sorting`, timestamps, … |

### `tx_mairesource_infobox`

| Column | SQL type | Notes |
|---|---|---|
| `uid` | `int(11)` | Auto-increment primary key |
| `pid` | `int(11)` | Storage page |
| `title` | `varchar(255)` | Record label; required |
| `content` | `mediumtext` | RTE body content |
| `attachment` | `int(11)` | FAL reference count (max 5); actual files in `sys_file_reference` |
| `categories` | `int(11)` | MM counter; relations in `sys_category_record_mm` |
| Standard enableFields | — | `hidden`, `deleted`, `starttime`, `endtime`, `sorting`, timestamps, `sys_language_uid`, `l18n_*` |

### `tx_mairesource_template`

| Column | SQL type | Notes |
|---|---|---|
| `uid` | `int(11)` | Auto-increment primary key |
| `pid` | `int(11)` | Storage page |
| `title` | `varchar(255)` | Record label; required |
| `description` | `text` | Short description; optional |
| `file` | `int(11)` | FAL reference count; actual files in `sys_file_reference` |
| `categories` | `int(11)` | MM counter; relations in `sys_category_record_mm` |
| Standard enableFields | — | `hidden`, `deleted`, `starttime`, `endtime`, `sorting`, timestamps, `sys_language_uid`, `l18n_*` |

## Architecture Constraints

- **No custom category table.** Category taxonomy is handled exclusively via `sys_category`.
  Never add a `tx_mairesource_category` table or a custom MM join.
- **No SCSS.** Styling is owned exclusively by `mai_assets` (compiled SCSS) and `mai_theme`.
  Never add `scssphp/scssphp` as a dependency.
- **No mail dispatch.** `mai_resource` must never send email. All transactional email is
  routed through `mai_mail`.
- **FE group gate is content-driven, not HTTP-driven.** The `fe_groups` column holds raw
  FK references. HTTP-level file protection requires a dedicated middleware or eID script
  (task resource-1). Do not implement file serving in a Fluid template.
- **FAL only.** Files are stored as `sys_file_reference` relations. Never store raw file
  paths in any `tx_mairesource_*` column.
- **Separate semantics.** `tx_mairesource_download` (protected, group-gated, general files)
  and `tx_mairesource_template` (public, document/form templates) serve different use cases
  and must remain separate tables. Do not merge them.
- **`mai_base` dependency.** All TCA is built via `mai_base`'s `Table` / `FieldConfig` API.
  `maispace/mai-base` must remain a `require` dependency.
