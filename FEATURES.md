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

## Planned Rendering Integration

Frontend rendering is split across two pending tasks:

- **resource-1** — Implement HTTP-level download tracking and protected file serving once a
  concrete use case is defined. This will require a middleware or eID script that checks the
  `fe_groups` relation before streaming the file.
- **resource-2** (this document) — Document the data model and infobox / template rendering
  paths as a specification contract.

### Expected infobox rendering path

When implemented, the expected approach for **infoboxes** is an Extbase plugin that:

1. Queries `tx_mairesource_infobox` via an `InfoboxRepository` scoped to `settings.storagePid`.
2. Accepts FlexForm settings for `storagePid` and optional `categoryUid` filter.
3. Passes results (including the resolved FAL attachment references) to a Fluid template in
   `mai_theme`'s template structure:
   - Container variable: `infoboxes` (Extbase `QueryResultInterface`)
   - Per-record variables: `title`, `content` (processed RTE HTML), `attachments` (FAL collection)
4. The Fluid template renders a `<div class="mai-infobox">` with `{infobox.title}`,
   `{infobox.content -> f:format.html()}`, and attachment download links.

### Expected download list rendering path

When implemented, the expected approach for **downloads** is an Extbase plugin that:

1. Queries `tx_mairesource_download` via a `DownloadRepository`.
2. Filters by `settings.categoryUid` (optional) and enforces `fe_groups` visibility using
   TYPO3's record-level access check (`AccessControl` or a custom `f:if` guard).
3. Passes `downloads` to a Fluid template that renders file metadata (title, description,
   file type icon, file size) and a download link via `f:uri.resource` or a direct FAL URL.

### Expected template/form list rendering path

When implemented, the expected approach for **document templates** is similar to the download
list but omits the `fe_groups` check and uses a separate Fluid template with a semantic label
("Template" or "Form") to distinguish template records from generic downloads.

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
