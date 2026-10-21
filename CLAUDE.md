# customCMSsystems — Project Baseline

CouchCMS-based custom site builder. Primary work: **PageBuilder blocks** and **front-end (inline) editing**.

---

## 0. Working agreement

**Read `Copilote Rules.txt` in this root. It governs.** Summary of the covenant, restated as it applies to me:

- Never predict code. If I haven't read the file, I ask for it or read it. No guessed field names, no guessed tag signatures, no assumed file contents.
- I can reason freely about *concepts, architecture, and why* — never about *actual code* I haven't seen.
- Uncertainty gets stated out loud, not papered over.
- Nested scope is deep here (groups → rows → fields, controllers driving controllers, hidden fields governing logic). If the dependency chain isn't clear, I ask to see the files that build it.

**Additional standing instructions from the user:**

- **There are currently many broken files. Do not fix them unprompted.** Do not "helpfully" repair, refactor, or clean up code I wasn't asked to touch.
- We build PageBuilder blocks **together, methodically** — one step at a time, confirming as we go. Don't run ahead and generate a whole module unasked.
- **Standard CouchCMS protocol is authoritative.** Couch tag syntax, semantics and documented behavior apply here and should be followed — my training and the Couch docs are valid sources for *stock tag behavior*.
- The **exceptions are narrow and named**: the modified `inline` addon (§5), recent PageBuilder work, and the directory renames (§1). In those areas the repo is the source of truth and I read the file instead of assuming.
- **Never cite line numbers as the anchor for a change.** Couch line numbers shift with
  embeds, the panel reports different numbers than the editor, and my own citations go
  stale the moment I insert a comment block. The user has said plainly: *"I've never been
  able to trust the line numbers when given as references."* Quote the **code string** to
  be changed. Line numbers may appear as a hint, never as the identifier.
- My reliable knowledge cutoff is **2026-05-05**. Couch changes after that, or the user's own work from roughly the last quarter, may be outside it. When behavior looks non-standard, read the core in `ccs_vsp_dash/` rather than guess which side of the line it falls on.

---

## 1. Non-standard install facts

| Thing | Reality here |
|---|---|
| Couch core dir | **`ccs_vsp_dash/`** — *not* `couch/`. `K_COUCH_DIR` / `K_ADMIN_URL` resolve here. |
| Addons | `ccs_vsp_dash/addons/` |
| Custom bootstrap | `ccs_vsp_dash/addons/kfunctions.php` |
| Snippets | `snippets/` (`K_SNIPPETS_DIR`) |
| Uploads | `ccs_vsp_dash/uploads/` (gitignored) |
| Dreamweaver artifacts | `_notes/` dirs and `dwsync.xml` are everywhere and are **ignored noise** — skip them. |
| Search scope | When searching the site for our own code, **skip `ccs_vsp_dash/`, `vendors/`, `module-display/`** (core, third-party, and design reference respectively). **Always include `bmenu/`** — it is part of the PageBuilder lifecycle. |

**`bmenu/`** mirrors each dynamic-folder template (`about.php`, `product.php`, `service.php`) and carries its own `cms:section` declarations pointing at the same block masterpages and mosaics. **Rename a block and `bmenu/` must be updated too** — it is easy to miss because it sits outside `blocks/` and `snippets/`.
| Backup convention | Files prefixed `!` are backups (e.g. `!globals_bu.php`, `!inline_ex_dupl-pre-depth.php`). Not live code. |

> Resolved: the old `ccs_admin/` rules in `.gitignore` were stale from the rename and have been corrected to `ccs_vsp_dash/`.

---

## 2. Addon loadout (`ccs_vsp_dash/addons/kfunctions.php`)

Active: `cart`, `csv`, `inline`, `extended-folders`, `extended-users`, `routes`, `jcropthumb`, `page-builder`, `color-picker`, `bootstrap-grid`, `recaptcha`.

Commented out (do not assume available): `extended-comments`, `sub-templates`.

`kfunctions.php` also defines, in-file:

- **`<cms:jit_fields>`** — custom tag. Super-admin only. Injects just-in-time fields into the pages form via the `alter_pages_form_default_fields` event, by re-parsing a synthetic `<cms:config_form_view>` node. This is bespoke; there is no Couch doc for it.
- **`menu_check()` / `bmenu_check()`** — field validators bound to dummy hidden fields, used to force a folder/menu-category selection before save. `bmenu_check` keys off `ccs_abt_ctnt_sbm_opt`.
- **PageBuilder render path overrides** (see §3).
- **"Save and go back"** button injected into the admin page form (`alter_pages_form_page_actions` + `pages_form_custom_action`).
- **Admin sidebar grouping headers**, registered via `register_admin_menuitems`:
  `_mod_bnr_` (100) · `_mod_lst_` (200) · `_mod_prt_` (225) · `_mod_mnl_` (250) · `_mod_glr_` (300) · `_mod_cmp_` (400) · `_mod_frm_` (550) · `_mod_adv_` (600) · `_frame_` (700) · `_global_` (800) · `_site_` (810) · `_donottouch_` (900).
  A new module template's `_menu_parent` must reference one of these.

---

## 3. PageBuilder architecture (custom)

### Render overrides

`kfunctions.php` rebinds two PB renderables away from the addon's own theme dir:

```php
$FUNCS->override_render( 'pb_wrapper', array('template_path'=> K_SITE_DIR . K_SNIPPETS_DIR .'/pb_mods/misc/theme/') );
$FUNCS->override_render( 'pb_tile',    array('template_path'=> K_SITE_DIR . K_SNIPPETS_DIR .'/pb_mods/',
                                             'template_ctx_setter'=>array('MyPB', '_render_pb_tile')) );
```

**Consequence — this is the key mechanism:** when `k_template_type == 'tile'`, `MyPB::_render_pb_tile()` returns the value of the template's own **`k_template__pb_template`** setting. So *each tile template names the snippet that renders it*, resolved relative to `snippets/pb_mods/`. A block's front-end output is wired by that per-template setting, not by a central registry.

`snippets/pb_mods/misc/theme/pb_wrapper.html` is the PB canvas chrome: it embeds the real page frame (`pg_frame/head.htm`, `main-cap.htm`, footer, `tail.htm`) around `<cms:show pb_tile_content />` so tiles preview in true site context.

### A PB tile is a page — stale tiles look like broken code

Each tile is its own `KWebpage` (`page-builder.php:261-282`) holding its own saved
field data. Schema edits change what the *form* offers; they do not touch values
already stored on an existing tile. A tile created before a field was added,
renamed or re-scoped can therefore render nonsense while the template is
perfectly correct.

**When schema changes stop producing sensible output, delete and rebuild the tile
before debugging the code.** This has already cost real time once.

### Block folder convention — `snippets/pb_mods/<block>/`

| Subfolder | Ext | Role |
|---|---|---|
| `embed/` | `.htm` | **Field definitions.** `<cms:editable>` trees embedded into a template. |
| `theme/` | `.html` | **Front-end render.** The actual markup output. |
| `inline/` | `.txt` | **Front-end edit affordances.** Floating `popup_edit` button clusters. |
| `router/` | `.inc` | Routing / dispatch logic (present on `list_1`, `nav_swpr`). |

Not every block has all four. Existing blocks: `bs_mods`, `contact`, `content`, `hero`, `hero_swpr`, `list_1`, `list_2`, `list_3`, `logos_lyot`, `nav_swpr`, `pricing`, `seg_img_scrl`, `tabs`, `portfolio`, `pg_frame` (site frame), `misc` (PB chrome), `DNU_header` (**DNU = Do Not Use**).

`snippets/pb_mod_source/*.htm` — dynamic `opt_values` source pickers (`pb_about_source`, `pb_prd_source`, `pb_svc_source`, `pb_template_source`). They enumerate templates whose `k_template_order` is between **1000 and 1999**, then list each template's folders hierarchically, emitting `Label=tpl_name,folder_name |`. That order range is the marker for "selectable content source."

Other snippet dirs: `dropdowns/` (shared `opt_values` lists), `e_modals/`, `filters/`, `single/`, `utils/`.

### Tile caching — `section_caching`

**PB caching is always on**: `page-builder.php:4` hardcodes `define( 'K_CACHE_PB', '1' )`, cache dir `ccs_vsp_dash/cache/pb/`.

Caching is controlled per tile by an **editable named `section_caching`** — not a tag. Two accepted forms (per KK):

```html
<!-- fixed, as used in all 15 pg_frame nav/footer/utlbar embeds -->
<cms:editable type='hidden' name='section_caching'>never</cms:editable>

<!-- user-configurable -->
<cms:editable name='section_caching' label='Caching' type='dropdown'
    opt_values='Always=always | Skip Posted Forms=skip_post | Never=never' opt_selected='always' />
```

| Value | Behavior | Code |
|---|---|---|
| `always` | Serves and writes cache **even on POST**. | `page-builder.php:275,282` |
| `skip_post` | Cached on GET, bypassed on POST. | falls through — never matched literally |
| `never` | Never written; `_remove_from_cache()` is called. | `edit-page-builder.php:16-21`, `page-builder.php:282` |

**Field absent ⇒ defaults to `always`** (`page-builder.php:272`). Easy to miss.

**The cache key is the tile's page id alone** — `_get_from_cache($pid)` → `_get_cache_name($pid)`. **No query string.** So any block whose output varies by GET parameter (AJAX filtering, category switching, pagination) **must** use `never`. `skip_post` is not sufficient, because those requests are GETs and will still collide on one cache entry.

Rule of thumb (per KK): use `never` when the tile's correct output can't be represented by one cached artifact per page id — cart contents, nonce-bearing edit/login links, GET-parameter-driven output. Use `skip_post` when the tile holds a `<cms:form>`: the cached HTML is fine for *displaying* the form, but the POST must bypass cache so `<cms:form>` actually executes. Cost of not caching is negligible — caching is an optimization, not a requirement.

### The second cache guard — `no_cache` at the call site

The tile cache is **not segmented by user**, and inline-edit clusters embed nonces (`inline.php:108`). Every content-bearing `cms:show_pagebuilder` therefore passes:

```html
no_cache="<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />") >1</cms:if>"
```

See `index.php:52,59`, `contact.php:52,66`, `blog.php:67,86`. **Keep this on any new call site.** The three in `globals.php:657-663` omit it safely because those blocks declare `section_caching = never` in their embeds instead.

---

## 4. Field conventions

Observed in `hero/embed/*.htm` — follow unless told otherwise.

**Naming:** `ccs_<blockabbr>_<subject>_<qualifier>` — e.g. `ccs_hro_bg_img`, `ccs_hro_txtblk_bg_clr`, `ccs_hro_headline_ls`.

Recurring qualifier suffixes: `_grp` (group) · `_row` (row) · `_msg` (message) · `_opt` (dropdown option) · `_clr` (color) · `_sz` (size) · `_wt` (weight) · `_fst` (font-style) · `_ls` (letter-spacing) · `_opc` (opacity) · `_brd`/`_bd` (border) · `_rad` (radius) · `_pd` (padding) · `_halg`/`_valg` (align) · `_alt` (alt text) · `_cstm` (custom).

**Structure:** `type='group'` (with `collapsed='1'`) wraps `type='row'` wraps fields. A `type='message'` field inside the row carries the `<h2>`/`<h4>` explanatory header.

**Every field carries an explicit `order='NNN'`.** Orders are hand-assigned and interleaved across groups — read neighbors before inserting.

**Admin layout** uses Bootstrap classes on the field itself: `class='col-md-5'`, `col-md-4`, `col-md-3`.

**Shared dropdowns:** `opt_values='dropdowns/img-transparency.htm'` + `dynamic='opt_values'`.

### Dropdown house rules — non-negotiable

1. **Never use `opt_selected`.** The default is always whatever sits **first** in the list. Order the file so the intended default is on top.
2. **Anything reusable lives in `snippets/dropdowns/`** — paddings, colors, sizes, alignments, backings. These recur across every PB block. Inline `opt_values` is for prototyping only; promote it to a file before the block is finished.
3. **`checkbox` and `radio` stay inline.** Their labels *are* the control's meaning, so a shared file would make the label wrong wherever it's reused.
4. **Dropdown files emit bare tokens; the prefix goes at the use site.** `light-dark.htm` emits `light`/`dark`, and the consumer writes `bg-`. Guard with `<cms:if field>` so a "None" option doesn't emit an orphan `bg-`.
5. Don't edit a shared file to suit one block — make a variant (e.g. `heading-size_sec.htm` drops `h1` for section blocks, while `heading-size.htm` keeps it for the hero).

### Class-prefix vocabulary (proven in `list_1` and `nav_swpr`)

| Source dropdown | Emits | Prefix at use site |
|---|---|---|
| `solid-colors` / `all-colors` | `primary`, `1200` | `text-` / `bg-` |
| `h-align` | `start` | `text-` |
| `font-family` | `sans-serif` | `font-` |
| `font-weight` | `lighter` | `fw-` |
| `font-style` | `italic` | `fst-` |
| `ltr-spc` | `n1` | `ls-` |
| `line-height` | `f2` | `lh-` |
| `text-size` / `text-size_sml` | `7` | `fs-` |
| `padding` | `3` | `py-` / `my-` (an `<hr>` needs margin, not padding) |
| `radius`, `txt-box_size`, `hr-size` | `rounded-1`, `col-12`, `hr-med` | none — already complete classes |

Build these with `cms:capture` + one `<cms:if field>` per line so an unset value emits nothing rather than a bare prefix. Size scale is modular, each step ×0.75: `fs-6` 2.37rem → `fs-7` 1.78 → `fs-8` 1.33 → `fs-9` 1.00 (= body) → `fs-10` 0.75.

---

## 4a. Conditional fields (`cms:func` + `not_active`)

Verified against `ccs_vsp_dash/conditional-fields.php`. These are **compiled to JavaScript** and run in the browser — the func body is not evaluated server-side into a string.

### The two ordering rules — both must hold

1. **Document order.** The controller must be written above the `cms:func`, and the func above the slave. The func also sits at the **same nesting level as its slave** (top level → top-level slaves; inside a group → slaves in that group; inside a row → slaves in that row).
2. **`order=` is a nested sort.** Parent order is the primary key, child order the secondary. An editable at `order=20` inside a group at `order=5` resolves as (5,20); one at `order=15` inside a group at `order=10` resolves as (10,15). (5,20) comes first — the container's order dominates. A slave never sees a controller sitting in a later container, however low the controller's own number.

Corollary: a row's own `order` does not constrain the numbering of fields inside it. Inner orders only compete with siblings in that row.

### Return vocabulary — `conditional-fields.php:399-408`

```php
case K_NODE_TYPE_TEXT:
    $html = trim( $node->text );
    $ret  = strtolower( $html );
    if( $ret==='1' || $ret==='true' || $ret==='yes' || $ret==='hide' ){ $html='    return 1; //hide'; }
    elseif( $ret==='0' || $ret==='false' || $ret==='no' || $ret==='show' ){ $html='    return 0; //show'; }
```

- Output is **trimmed and lowercased** — indentation and newlines inside the func are harmless.
- `1 | true | yes | hide` → hide. `0 | false | no | show` → show. They are aliases, not two conventions.
- **House style is `show` / `hide`**, for human readability. Numeric `1`/`0` works but is not preferred.
- **`hidden` is always wrong. The token is `hide`.** Same for `shown` — it's `show`.

### Two silent footguns

- Any token outside those eight falls through and is emitted as **raw JavaScript** into the generated function. No error — the conditional just breaks.
- `conditional-fields.php:411-414` — only `cms:if`, `cms:else`, `cms:else_if`, `cms:not`, `cms:arr_val_exists`, `cms:is` compile. In this install the `die()` for unsupported tags is **commented out** and replaced by a bare `return;`, so an unsupported tag produces nothing instead of complaining.

### Reading a `type='checkbox'` field at runtime — the `__` prefix

**House rule: two idioms only, both stock. Do not use a `__` prefixed variable.**

| Checkbox | Runtime idiom |
|---|---|
| Single option (`opt_values='Label=1'`) | `<cms:if ccs_foo_opt='1'>` |
| Multi option (`opt_values='A=a \| B=b'`) | `<cms:each ccs_foo_opt>` + `<cms:if item='a'>` |

The stored value is a **pipe-delimited string**. `cms:each` defaults to `sep='|'` and splits a non-array `var` (`tags.php`, `each()`), which is exactly the storage format — so it walks a multi-select checkbox natively. Single-option compare is proven at `list_1/router/list1_cat_ins.inc:51`.

**Do not use `cms:is` at runtime on a checkbox.** It aliases `cms:arr_val_exists` (`tags.php:7806-7822`) and returns `'1'` only when `is_array($in)`. Whether a checkbox is ever an array is inconsistent — `field.php:387-396` only builds one for `search_type=='text'` fields, and only in the base `KField::get_data()`; there are six other `get_data()` implementations (`field.php` 1054, 1474, 1580, 1715, 1759; `repeatable.php:735`). That inconsistency is why `cms:is` appears to work on some checkboxes and silently fails on others. Avoid it rather than special-casing it.

**Inside a `cms:func`, `cms:is` IS correct** — that code compiles to JavaScript (§4a) where the value comes from `get_selected()` and is genuinely an array. So the same expression is right in a func and wrong at runtime.

**`cms:show_repeatable` cannot read a checkbox** either — `Repeatable::show_handler` resolves via `$CTX->get_object()`, which only repeatable regions register, so the tag silently executes none of its children.

### HTML comments do not protect Couch tags

`<!-- <cms:is .. in=..> -->` is still parsed and will throw `ERROR! ATTRIB_NAME: Invalid char`. Use `<cms:ignore>` to comment out Couch syntax, and never write a `<cms:` sequence inside an HTML comment.

### Popup interaction

This is why `hide_fields` exists — see §5. A slave's controller must be present in the `popup_edit` field list or the conditional can't resolve.

---

## 4b. `cms:set` scope — verified in `ccs_vsp_dash/parser/parser.php`

`KContext` is a **stack** of contexts. `scope` selects a level in that stack. It is *not* a site-wide broadcast.

| `scope=` | Behavior | Code |
|---|---|---|
| *(omitted)* | Sets at the **nearest enclosing scope** — walks up from innermost and writes at the first context that has a `_scope_` array. | `parser.php:187-192` |
| `parent` | Searches **upward for a context where the variable already exists** and updates it there. Falls through to default if never found. | `parser.php:178-185` |
| `global` | Writes to `ctx[0]` — `__ROOT__`. | `parser.php:173-175` |

**Only scope-creating tags have a `_scope_`.** The list is `parser.php:64` — `pages`, `folders`, `capture`, `each`, `repeat`, `while`, `form`, `templates`, `search`, `query`, `comments`, `menu`, … **`cms:if` is not one of them**, so a `cms:set` inside a `cms:if` lands in whichever scoped tag encloses the `if`.

**Reads walk up the stack.** A tile's own `ccs_*` editables are readable inside `cms:pages` / `cms:folders` loops with no aliasing — see `list_1/theme/list1_cat_ins.html:43-60`. So `scope='global'` is *not* needed merely to make a value visible further down.

### When `global` is actually required

Only for values assigned **inside a loop** that must survive the loop's scope popping — e.g. `left_nav_html` accumulated across `cms:folders` iterations, or `initial_load_cat`.

### The cost of over-using it

`__ROOT__` lives for the whole page render, and every PageBuilder tile on a page renders into that same root. A presentational var set `global` in one tile and left unset in the next **persists into the next tile**. This is the mechanism behind the `ltnv_sec_inline_bg` bleed. Prefer default scope for anything computed at a router's top level.

---

## 4c. `custom_field` builds an INNER JOIN — negative tests are unsafe

`tags.php:2595` joins one data table per field named in `custom_field`:

```php
$join .= ' inner join '.$tbl['tbl_name'].' '.$tbl['alias'].' on '.$tbl['alias'].'.page_id = p.id';
```

**A page with no data row for that field is dropped from the result entirely** — it is never evaluated. So:

| Test | Safe? | Why |
|---|---|---|
| `feature=1` | ✅ | no row genuinely means "not featured" |
| `noshow!=1` | ❌ | no row *should* pass, but the join excludes it |

Rows normally exist: `page.php:1548` inserts one per field when a page is created, and `tags.php:1739-1771` backfills existing pages when a field is added to a template. **The backfill is the weak point** — its own comment warns it can time out on large templates, and its resume path (`tags.php:1751`) only runs when a lock file exists at `K_COUCH_DIR.'cache/'`. If that directory is missing, a timed-out backfill leaves permanent gaps and no record of the failure.

Symptom: pages silently vanish from listings that use a `!=` filter. Diagnose by comparing page count against distinct `page_id` count in `couch_data_text` / `couch_data_numeric` for that field. Fix the **data** (backfill the rows), not the query — degrading an indexed `limit='1'` lookup into a full scan to tolerate bad data does not scale.

### Cheap folder guards — no query needed

`cms:folders` already exposes both counts from the in-memory tree (`folder.php:530-531`):

| Variable | Meaning |
|---|---|
| `k_folder_pagecount` | pages **directly** in this folder |
| `k_folder_totalpagecount` | pages in this folder **and all descendants** |

Use these before issuing any `cms:pages` query inside a folder loop — `totalpagecount gt pagecount` answers "does a descendant have pages?" for free, and `pagecount gt '0'` skips folders that could never qualify. A guard inside a folder loop runs once per folder, so anything expensive there multiplies by the size of the tree.

---

## 4d. Hiding a field does not stop its value applying

A `not_active` conditional only hides the **input**. The stored value stays in the
database and the router keeps reading it. Gate the router too, or the user hides a
control and its effect persists — which reads as a bug that cannot be undone.

Both halves are needed:

```html
<!-- embed: hide the input -->
<cms:editable ... not_active=ccs_nswp_cat_typo_chc />
```
```html
<!-- router: and stop emitting its classes -->
<cms:if ccs_nswp_itm_cat_ovr_opt='1'>
    <cms:capture into='nswp_tile_cat_classes' scope='global'>...</cms:capture>
</cms:if>
```

Same reasoning put `nswp_hdg_size` inside the header typography gate: with the size
dropdown moved under the override, leaving the capture outside meant unticking the
override hid the control but kept its `fs-` class on the heading.

## 4e. `cms:func` — multi-controller and checkbox reading

Multiple controllers are supported and used ~138 times across `hero`. Declare each
as a parameter, then combine with `&&` / `||`:

```html
<cms:func _into='ccs_nswp_cat_typo_chc' ccs_nswp_tile_cat_opt='' ccs_nswp_itm_cat_ovr_opt=''>
    <cms:if ("<cms:is '1' in=ccs_nswp_tile_cat_opt />") && ("<cms:is '1' in=ccs_nswp_itm_cat_ovr_opt />")>show<cms:else />hide</cms:if>
</cms:func>
```

**A checkbox controller inside a func is read with `cms:is`** — the func compiles to
JavaScript where the value comes from `get_selected()` and is genuinely an array.
This is the exact opposite of the runtime rule in §4a, and both are correct in their
own context. All existing `nav_swpr` and `hero` funcs follow it.

Chained gates (A reveals B, A+B reveals C) are just two funcs, each sitting at the
same nesting level as its own slave.

---

## 5. Front-end / inline editing — MODIFIED ADDON

`ccs_vsp_dash/addons/inline/` is **not stock**. Build stamp `K_INLINE_BUILD = '20250122i'`.

### Custom `hide_fields` parameter

Added to `cms:popup_edit`, `cms:popup_edit_ex`, `cms:inline_edit`, `cms:inline_link`. **This does not exist in stock Couch — never look it up, read `inline.php`.**

Flow:

1. `inline.php` → `Inline::inline_handler()` accepts `hide_fields`, appends `&hide_fields=<urlencoded>` to the popup URL (skipped when the tag is `inline_link`).
2. `inline_ex.php` → `InlineEx::edit_action()` parses it into `$this->hide_fields` (pipe-delimited).
3. `InlineEx::_set_default_fields()` ORs `in_array($f->name, $this->hide_fields)` into the field's `hide` flag.

**Why it exists — Couch conditional fields.** When a field is a *slave* of a conditional (`not_active=` driven by a `cms:func`), the **controlling field must be present in the `popup_edit` field list** or the slave won't resolve. But exposing the controller in a small focused edit popup is confusing to the end user — and lets them change a setting they didn't come there to change. `hide_fields` makes the controller **available to the logic but neither visible nor editable**.

So the rule of thumb: list every ancestor group/row *and* every conditional controller in `fields`; move the ones the user shouldn't see into `hide_fields`. E.g. `hide_fields='ccs_hro_txtblk_grp'`.

### Popup field ordering — the group must be in `fields`, not `hide_fields`

`inline_ex.php:114` decides nesting:

```php
'group' => ( array_key_exists($f->k_group, $this->arr_fields) ) ? $f->k_group : '',
'order' => $f->k_order,
```

`$arr_fields` is the **`fields` list only** — `hide_fields` is a separate array and
does not register. So:

> **If any of a group's children appear in a popup, the group name MUST be listed
> in `fields`.** Otherwise every child falls into `_custom_fields_` and is sorted
> by its own `order`. The group's own order is lost, nesting collapses, and orders
> that never competed in the admin suddenly do.

This breaks conditionals, not just layout. Real case: `ccs_nswp_nav_ttl_typo_row`
(order 5, inside group order 45) and its controller `ccs_nswp_nav_ovr_opt`
(inside `ccs_nswp_opt_row`, order 20). In the admin the group's 45 dominates, so
the controller sits above the slave. Flattened in the popup, 5 beats 20, the
controller renders *below* its slave, and the conditional silently stops
resolving — the field is simply always visible.

**So the two parameters have different jobs:**

| Want | Where it goes |
|---|---|
| Structural group whose children you are editing | `fields` |
| Conditional controller the user should not see or change | `hide_fields` |
| Ancestor rows | `fields` |

A group in **both** is hidden — `hide` wins — which looks exactly like forgetting it.

### The popup renders in `order`, not in the order you list fields

The `fields` string is a set, not a sequence. `_set_default_fields()` iterates
`$PAGE->fields` (schema order) and hands the renderer each field's own `k_order`.
Rearranging the pipe-delimited list changes nothing on screen. To change what the
user sees first, change `order` in the embed — or restore the nesting so the
parent's order applies again.

### Other local deltas in `inline_ex.php`

- `$AUTH` added to the `global` line in `_set_default_fields()` (stock omitted it; the `deleted`-field access-level check would otherwise misfire).
- `invalidate_cache()` calls `KPageBuilder::_remove_from_cache( $pg->id )` when the page has `tpl__pb_height` and `PB_CACHE_DIR` is defined — PB tiles must be dropped from cache on inline save.
- `!inline_ex_dupl-pre-depth.php` is **byte-identical** to `inline_ex.php` as of this writing, despite the "pre-depth" name. Treat as a stale backup.

### Inline control-cluster pattern (`<block>/inline/*.txt`)

Gate every cluster with:

```
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")>
```

Then a cluster of `<cms:popup_edit>` calls, each with a full pipe-delimited field list, an optional `hide_fields`, and a `link_text` containing a Bootstrap icon button. Sizing via `height='720' width='940'`.

**`position-absolute` / `translateX` is not a rule** — it's just whatever gets the edit button visually adjacent to the element it edits. Placement is per-case; copy the intent (button next to its target), not the exact classes.

Field lists here are long, order-sensitive, and must include every ancestor group/row plus every conditional controller of any field being edited.

---

## 6. Front-end stack

Bootstrap 5 + Font Awesome. Vendors in `vendors/`: bootstrap, swiper, isotope (layout + packery), imagesloaded, bigpicture, rellax, parallax, popper, typed.js, hamburgers, lodash, prism, is.js. Site assets in `assets/`.

Other top-level dirs: `blocks/` (banner, bs-mods, content, frame, lists, mods, portfolio, sliders), `bmenu/`, `module-display/`, `users/`.

`globals.php` (~64KB) holds global site settings; `!globals_bu.php` is its backup.

---

## 6a. CSS traps proven in this theme

**`.bg-holder` carries `z-index: -1`** (a deliberate local change for the hero's
overlay stacking — do not "fix" it). A negative z-index means visibility depends on
which ancestor happens to establish a stacking context: over a plain section the
image shows, over a section with its own background it is painted straight over.
Symptom looks data-driven (worked on one tile, not another) but tracks whether a
section background is set. Fix locally, never in `theme.css`:

```css
.nswp-nav-col { position: relative; z-index: 0; isolation: isolate; }
.nswp-nav-col .bg-holder { z-index: 0; }
.nswp-nav-col > .row > .col-12 { position: relative; z-index: 1; }
```

**An absolutely positioned `.row` keeps its negative side margins.** `.row` carries
`margin-inline: calc(-0.5 * var(--ccs-gutter-x))` to cancel column gutters. Make it
`position:absolute; inset:0` and nothing cancels them any more — the row and any
full-width `.bg-holder` inside it overhang the column by half a gutter on each side
and overlap the neighbour. Zero the margins, and inset the sides by half a gutter if
you want the normal column spacing back.

**`.btn` sets `--ccs-btn-padding-x: 2.5rem`.** Anything using `.btn-link` for looks
inherits 5rem of horizontal padding — brutal inside a narrow column. Override the
variable, not the padding, so the theme's own mechanism and any `padding-left`
indent rules keep working:

```css
.nswp-nav-col .ajax-nav-link { --ccs-btn-padding-x: 0.25rem; }
```

**`bg-opacity-*` works only against the variable form** — `.bg-dark` here is
`rgba(var(--ccs-dark-rgb), var(--ccs-bg-opacity))`, and `.bg-opacity-50` sets that
variable. Same specificity, so the utility must come **later** in the stylesheet;
in this theme it does (~2.6 KB after). A flat-hex override of `.bg-*` would kill
transparency silently.

**Defaults the user must be able to beat go in `:where()`.** `user.php` loads after
`theme.css`, so a plain `.nswp-tile-cat { font-size: … }` ties with `.fs-8` and wins
on order — the dropdown appears to do nothing. `:where(.nswp-tile-cat)` has zero
specificity, so any chosen utility overrides it while the default still applies.
Keep non-controllable declarations (`text-transform`, `opacity`, margins) in the
normal rule.

**Nested `<a>` is invalid.** A tile-wide link plus a category link inside it does not
parse. Split into sibling anchors; give the redundant image link
`aria-hidden="true" tabindex="-1"` so screen readers and the tab order see the
destination once, not twice.

---

## 7. Checklist for adding a new PageBuilder block

> **Third-party iframes: use `data-src`, never `src`.** YouTube, Vimeo, maps, anything
> off-domain. See §18 — this is what keeps consent working without a per-block field and
> without disabling the PB cache.

Confirm each step with the user — do not batch them.

1. Decide block name + field prefix (`ccs_<abbr>_`).
2. `snippets/pb_mods/<block>/embed/<name>.htm` — `<cms:editable>` tree, group→row→fields, explicit `order`, `col-md-*` classes.
3. `snippets/pb_mods/<block>/theme/<name>.html` — front-end markup.
4. Set the tile template's `k_template__pb_template` to the theme path (relative to `snippets/pb_mods/`) so `MyPB::_render_pb_tile` resolves it.
5. Assign the template an admin sidebar group (`_mod_*` from §2) and, if it should be a selectable content source, a `k_template_order` in 1000–1999.
6. `snippets/pb_mods/<block>/inline/<name>.txt` — gated `popup_edit` clusters; include ancestor groups, use `hide_fields` to suppress wrappers.
7. `router/*.inc` only if the block needs dispatch logic (see `list_1`, `nav_swpr`).

---

## 8. `nav_swpr` — reference implementation

The Products left-nav slider (`blocks/sliders/ltnv_sld_blk.php` → `snippets/pb_mods/nav_swpr/`) is the most complete block and the pattern to copy. Its `abt_` and `svc_` embeds are **not built yet** and will be near-copies of `prd_ltnv_sld_blk.htm`.

Decisions baked into it, worth carrying to new blocks:

- **Initialize every conditionally-set router var to `''` at the top.** `scope='global'` writes `__ROOT__`, which survives between tiles on the same page. Uninitialised vars bleed into the next tile.
- **One `cms:folders` loop, attributes resolved up front** (`nav_childof` / `nav_depth` / `nav_hier`) rather than a branch per combination. An empty `childof` is skipped by the tag exactly as if omitted.
- **Guard and renderer must ask the identical question.** If the nav guard tests something narrower or wider than the slider query, links appear that render nothing. Both carry `include_subfolders='0'`.
- **Leaf-only vs roll-up.** `cms:pages` defaults to `include_subfolders='1'`, which makes every ancestor of a page qualify. `nav_swpr` forces `'0'` so one link means exactly one set. Roll-up is the defensible alternative for a paginated grid — it's a bad fit for a 3-at-a-time swiper.
- **Free folder guards before any query** — see §4c.
- **Empty state must be explicit.** A blank `folder=`/`childof=` is *no filter*, not *no results*, so an unguarded empty state silently lists the entire source. `ltnv_has_content` gates the output.
- **One state class for active nav**, `ltnv-active`, defined once in `user.php`. The router emits it on load and the JS toggles the same name on click, so no color is named in two languages.
- **AJAX fetches send `&ajax_block=<tile page id>`** so only the clicked block renders; the others short-circuit before the router runs. Missing param falls back to rendering everything.
- **Nav height is capped at `min(350px, 55vh)` in `.nswp-scroll-container`, not matched to the slider.** An attempt to make it match failed and was reverted. `height: 100%` resolves against the parent's *specified* height; `.col-lg-3` gets its height from flex **stretch**, so its specified height stays `auto` and the whole chain silently fails — the list then grows unbounded instead of scrolling. A flex-only chain has the opposite problem: in a flex row the tallest item sets the row height, so a long nav pushes the section taller rather than capping at the slider. **The approach that should work is absolute-fill**: `.col-lg-3` already carries `position-relative`, so `position:absolute; inset:0` on its inner `.row` takes the nav out of flow (the slider alone then sets row height) *and* gives it a definite height to resolve against. Flex the chain inside that, with `min-height:0` at every level. Guard it to `@media (min-width: 992px)` — below `lg` the columns stack and there is no sibling to match.
- **Per-tile visual values go inline or in a tile-scoped `<style>`** — never into `user.php`, which is fetched once per page with one `?page=&post=` pair and therefore can only express one answer for the whole page.

---

## 9. Front-end edit clusters — the working pattern

Reference: `hero/inline/*.txt` embedded from `hero/theme/*.html`. Live example now in
`nav_swpr/theme/nswp_sld_blk.html` (written inline first; extract to
`nav_swpr/inline/*.txt` once settled — a `.txt` nobody `cms:embed`s does nothing).

**Non-negotiable:** every editable in the embed must be reachable from some button.
Generate the field lists from the embed rather than typing them, and re-run coverage
after any move. `nav_swpr` sits at 116/118 — the two absent are admin-panel divider
messages.

**Shape of a cluster:**

```html
<cms:if (k_user_access_level ge '7') && ("<cms:get_session 'inline_edit_on' />")>
    <div class="position-absolute top-0 start-0 mt-3 ms-2 z-3 d-flex flex-column gap-2">
        <cms:popup_edit height='760' width='940' 'field|field|group|row'
            hide_fields='controller_the_user_should_not_see'
            link_text="<button class='btn btn-sm btn-light shadow-sm rounded-circle' type='button'
                        aria-label='Edit X' title='Edit X'><i class='fa-solid fa-x'></i></button>" />
    </div>
</cms:if>
```

- **Dim, do not remove**, when the target is off:
  `<cms:if <off-test>>btn-outline-secondary bg-white opacity-50<cms:else />btn-light shadow-sm</cms:if>`.
  The header button uses this and must live **outside** the `nswp_show_header` gate —
  otherwise it disappears with the header it exists to bring back.
- **Anchor to the right element.** `.container` is centered with a max-width, so
  `start-0` there is not the section edge; pin section-wide clusters to the
  `<section>` (add `position-relative` to it).
- **Put the button beside what it edits.** Tile *text* controls belong at the bottom
  of the slider column, pushed out with `translateX(±115%)` so they sit beside the
  text rather than over the photo.
- **Mirror with layout.** Clusters that could collide follow `ccs_nswp_nav_pos` so
  they always move to the outer edge.
- **Group related buttons in one flex row** rather than hand-tuned margins, so they
  stay aligned when a button is added or removed.

## 9a. `_tools/cms-balance.py` — tag balance checker (USE THIS ONE)

    python3 _tools/cms-balance.py FILE [FILE...]        exit 0 = clean

**Do not write an ad-hoc regex for this.** One was used throughout the consent work and
reported phantom mismatches on `head.htm`, `globals.php`, `hero_split.html` and
`trns_img_smp.inc` — every one false. Two idioms defeat a regex: a nested tag inside a
QUOTED attribute (`custom_field="tok==<cms:show t />"`), and a nested tag OUTSIDE quotes
(`<cms:if a ge '7' && "<cms:not " <cms:get_session 'x' /> " />" >`), where the inner tag's
`/>` makes the outer look self-closing.

This scanner walks quote state and nesting depth instead, and keeps `cms:ignore` newlines so
reported line numbers match the real file.

## 10. `_tools/check-embed.php` — embed validator

> **GONE as of 2026-08-30.** `_tools/` now contains only `cms-balance.py`. The
> validator described below is not on disk and was never run. Kept as a spec in
> case it is rebuilt; do not tell anyone to run it.

`php _tools/check-embed.php <embed.htm>` or `--all`. Exit 0 = clean. Checks func
nesting vs slave, controller/func/slave document order, the nested-order arithmetic,
the eight legal return tokens, the six tags that compile in a func, messages inside
rows, duplicate names, missing `opt_values` files, tag balance.

**Written but never executed** — there is no PHP in the assistant sandbox. Treat the
first run as a test of the tool.

**Parser gotcha it was built to survive:** `type='message'` and `type='text'` carry
content, so they emit their own `</cms:editable>`. Popping the stack on every closing
tag closes the enclosing group early and hands every later field the wrong parent —
which produces a confident, wrong "all clear". Push every non-self-closing editable;
treat only `group`/`row` as a parent scope.

## 11. Open items on `nav_swpr`

- `ccs_nswp_bg_clr_cstm_two` (Gradient Color) is a slave inside
  `ccs_nswp_bg_clr_cstm_row`, but its func `ccs_nswp_bg_grdt_hide` sits at group
  level — a nesting mismatch of the kind in §4a. Pre-existing, left alone.
- `ccs_nswp_nav_clr_row` (a nav row) is still listed in the Swiper Tile Block popup;
  leftover from debugging, not an ancestor of anything there.
- `abt_nswp_sld_blk.htm` / `svc_nswp_sld_blk.htm` not built. Confirmed a four-line
  diff from the product embed: `source_master`, the `pb_mod_source` file, two labels.
- Field name `ccs_nswp_itm_txt_halg` now governs the whole block, not just tiles.
  Kept deliberately — renaming drops stored values on every existing tile.
- Not verified: color contrast, focus visibility on `.btn-link`,
  `prefers-reduced-motion`, possible double announcement between the custom
  `aria-live` and Swiper's own a11y module.

---

# ADDED 2026-08-20 — sessions since the last baseline

Everything below was learned or built after §11. Where a fact was proven by reading
the core, the file is named; per §0 the **code string** is the anchor, not the line.

---

## 12. Couch runtime facts proven this cycle

These cost real debugging time. Do not re-derive them.

| Fact | Where proven | Consequence |
|---|---|---|
| **`cms:ignore` is a RUNTIME tag** — `function ignore(){ return; }` in `tags.php` | its body is *parsed* first | A `<cms:...>` tag inside prose in an ignore block becomes a real node and can break parsing. **Comment prose must not contain angle-bracketed cms tags.** Write `cms:if field` with no brackets. |
| **`get_data()` falls back to `default_data` whenever `data` is empty** | `field.php`, the `is_numeric` guard | The body of an editable is a real default on *every read*, front end and panel alike. A stored `"0"` survives because of the `is_numeric` guard. This is why the consent banner carries no `cms:else` fallbacks. |
| **`opt_selected` is admin-panel-only** | `field.php` — `get_data()` is consulted first | It only marks which option is drawn selected. Never stored, never reaches the front end. Use a default in the editable body instead. |
| **`not_active` DISCARDS the posted value** | `functions.php` registers `resolve_active_fallback` on `page_prevalidate`; `field.php` — `if( $this->deleted \|\| $this->k_inactive ) return;` | A conditionally-hidden field does not keep its old value — it is never written. Same on the admin form path (`register.php`) and on PB tiles (`edit-mosaic-ex.php`). |
| **`not_active` is a persisted DB column** | `install.php`; written base64-serialized in `tags.php` | Removing it from a template clears the column on re-registration. |
| **`cms:template` body is a SCHEMA block** | `tags.php` gates the whole function on `access_level >= K_ACCESS_LEVEL_SUPER_ADMIN`, then runs children and **discards output** | Fields go inside. Page logic goes **outside**. Putting page body inside a non-self-closing `cms:template` is what broke `users/logout.php`. |
| **`k_` prefix is reserved** | `tags.php` | `<cms:set k_foo …>` dies with *"cannot be set because it begins with 'k_'"*. This is why the nav active-state variable is `ccs_active_page_name`, not `k_current_page_name`. |
| **LOGIC_OP rejects a single `=`** | parser state machine | A lone `=` is tolerated **only** when the LHS is a bare variable in a simple test. Inside `&&`/`\|\|`, **or** when the LHS is a quoted expression, it errors: `ERROR! LOGIC_OP: Invalid char "="`. **Read into a variable first, then compare with `==`.** |
| **`cms:link` returns nothing** for a template with no row in the templates table | `tags.php` | Produces `href=""` silently. Register the template. |
| **`cms:gpc` supports `method='cookie'`** | `tags.php` | Enables server-side consent gating with no custom PHP. |
| **Dynamic `opt_values` snippets are PARSED** | `field.php` — `$parser = new KParser( $html ); … get_HTML()` | A dropdown file can contain `cms:php` and generate its list at render time. All five font dropdowns rely on this. |

---

## 13. Frame files — `snippets/pb_mods/pg_frame/`

### `head.htm`
- `<cms:embed 'utils/consent.htm' />` is on **line 2, before `<html>`**, deliberately —
  everything downstream gates on the globals it sets.
- Canonical is `<cms:show k_page_link />`. It was `k_site_link`, which told search engines
  every page was a duplicate of the home page.
- The Google Fonts `<link>` and both `preconnect`s are **gone** (see §15).
- Header script slot (`ccs_gl_header_scripts`) was declared in globals and rendered
  **nowhere**. Now wired, gated on `ccs_consent_analytics == '1'`.

### `nav/nav_emb.htm` — the active-nav fix
Two captures **before the first `cms:pages` opens**:

```
<cms:set ccs_active_page_master = k_template_name scope='global' />
<cms:set ccs_active_page_name    = k_page_name    scope='global' />
```

**Why here and nowhere else:** every nested `cms:folders` / `cms:pages` calls `set_context`
on its current row, reassigning `k_template_name` / `k_page_name` to the *iterated* page.
By the time a nav theme reaches its comparison, both sides of the test are the loop's own
row. The request's identity has to be snapshotted while intact. `scope='global'` is
required — it writes `__ROOT__`, which survives a loop's scope being popped.

Read by 11 top-level sites and 12 dropdown sites across the three nav themes
(`nav_mdd.html`, `nav_sdd.html`, `nav_xtr.html`). `snippets/tl_if_pb_emb.html` held the
first line already but nothing ever embedded it — now redundant.

### Caching at the two bar call sites
- `ccs_gl_tpb_pb` → `no_cache='1'` **unconditionally**. The cart badge reads
  `$_SESSION['kcart']` and guests can shop without logging in, so anonymous visitors
  share one cache entry. This is also the whole point of `users/logout.php`.
- `ccs_gl_nav_pb` → `no_cache="<cms:if k_logged_in>1</cms:if>"`. Its only per-visitor
  content is the Login/Logout branch; entries can then only be written by an anonymous
  render, which always prints "Login". Keeps the expensive folder-walk cached.

---

## 14. Consent & legal framework

### `snippets/utils/consent.htm` — resolved SERVER-SIDE
Exposes three globals, **all defaulted to `'0'` before the cookie is read**:
`ccs_consent_set`, `ccs_consent_analytics`, `ccs_consent_marketing`.

Three cookies, not one packed value: `ccs_cnst_v` (policy version), `ccs_cnst_a`,
`ccs_cnst_m`. The version cookie is why `ccs_gl_consent_ver` exists — raising it
invalidates every stored answer so a materially changed policy is re-consented.

**Why server-side:** under an opt-in regime the requirement is that the tracker is not
*there*, not that it stays quiet. If the tag is in the page and JS decides afterwards, the
request has usually already gone. Reading the cookie in PHP and not printing the tag means
there is nothing to fire.

### `snippets/pb_mods/pg_frame/consent_banner.htm`
Embedded from `tail.htm`. **Lives in the frame, not a PB tile** — PageBuilder caches
rendered tile HTML in one entry shared by every visitor (`page-builder.php`), and a
consent banner is per-visitor by definition. Reject / Choose / Accept are identically
styled; no close button; `role="dialog"`; focus moved once. JS writes the cookies then
`location.reload()` so the **server** decides what the next page contains.

Turning the banner off does not turn tracking on — consent is simply never granted.

### Legal templates
`legal/privacy.php`, `legal/terms.php`, `legal/cookies.php` + `snippets/legal/legal_fields.htm`
and `legal_layout.htm`. Parent `_global_`, order in the 8000 range (user's choice).
**Content is still blank — the user has not decided the wording.**

---

## 15. Self-hosted fonts — `assets/fonts/webfonts/`

Google Fonts is **gone**: no `<link>` to `fonts.googleapis.com`, no `preconnect` to
`fonts.gstatic.com`. Loading a font that way transmits the visitor's IP to a third party
before consent exists; a German court ruled against exactly that in 2022.

### Layout — non-negotiable
```
assets/fonts/webfonts/<slug>/<slug>.css     slug = family, lowercase, spaces to hyphens
assets/fonts/webfonts/<slug>/*.woff2
```
`Playfair Display` → `playfair-display`. Get it wrong and the family is silently never
detected — there is no error. (A `greeat-vibes.css` typo did exactly this.)

**23 families installed, 241 `url()` refs, 0 missing, 0 orphans, 5.9 MB.**
latin subset only, woff2 only, flat folders, no variable-axis files.

### The `$hosted` array is an ALLOWLIST, not just a lookup
`snippets/dropdowns/font-{sans,serif,mono,script,decor}.htm` each hold
`$hosted = array(...)` and offer a family only when **both** the name is in that array
**and** `file_exists($dir.$slug.'/'.$slug.'.css')`.

Nothing on disk records whether a family is a serif or a script, so a pure folder scan
could not sort families into the five roles. **A family in no `$hosted` array is invisible
everywhere, however correctly built, and fails silently.** The dropdowns and
`assets/fonts/webfonts/README.txt` used to claim otherwise; both are now corrected.

`snippets/utils/google_fonts.htm` keeps its historical name (renaming would break embeds)
and links whichever selected families exist locally. A missing family emits **nothing** —
no link, no 404 — and falls through to the system stack in `user.php`.

### Where the default fonts live
Both files, but **`user.php` wins** — `head.htm` loads `theme.css` first, then `user.php`,
which re-declares the same `--ccs-*` custom properties.
- `theme.css` — baseline `--ccs-body-font-family: "Montserrat"…`, `--ccs-font-base`, and a
  stale `--ccs-font-sans-serif: "Droid", serif` (dead face, serif fallback on a sans var;
  harmless because it is overwritten).
- `user.php` — the operative defaults in its `<cms:else />` branch:
  Montserrat (body), Raleway (sans), Playfair Display (serif). **Edit here.**

### The theme chains need `<cms:else />`
Both `user.php` and `google_fonts.htm` had four branches for eight theme values. If they
disagree, `user.php` names a family that `google_fonts.htm` never links. Both now have a
final `<cms:else />`.

---

## 16. The color-cascade bug (root cause, resolved)

Theme colors would not show on the navbar brand text. Four wrong theories were proposed
and corrected by the user before the real cause was found — **the user's facts were right
every time.** Recorded so it is not re-litigated:

- Custom colors are a **complete override** of theme colors, not theme colors.
- **There is no `cache/` directory on the live site**, so PB caching has never actually run.
- `inline_edit_active` **is** read — `ftr_emb.htm`.

**Actual cause:** `user.php` emits
`h6, .h6, … h1, .h1 { color: <body_clr>; font-family: <bff>; }`.
`heading-size.htm` emits `h1`–`h6` as **size classes**, so `.h3` ties `.text-warning` on
specificity (0,1,0) and wins on load order. Only `.text-tertiary` / `.text-quaternary`
carry `!important` in `theme.css`, which is exactly why those two colors worked and the
rest did not.

**Fix:** the `<h1>` brand element was removed (a logo is not a heading — an SEO and a11y
correction in its own right) and the size fields repointed to `heading-size_sec.htm`,
which emits `fs-N`.

---

## 17. Contact block — `blocks/contact/contacts.php`

Two tiles: `cnt_basic_frm_tl`, `cnt_email_frm_tl`. Follows the five-layer convention (§3).

- 7 text defaults + `required='1'` in `cnt_frm.htm`; 12 fabricated theme fallbacks removed.
- Email body collapsed to a single `cms:send_mail` wrapping `<cms:show k_success />`.
  **`k_success` is not a boolean** — `tags.php` builds one `Label: value` line per field,
  which is why every `cms:input` now carries `label=`.
- Six shared dropdowns extracted: `_cls/box-width.htm`, `_cls/field-size.htm`,
  `_cls/label-visibility.htm`, `_val/heading-tag_sec.htm`, `_val/textarea-rows.htm`,
  `_tok/color-bg_solid.htm`.
- **Still open:** CAPTCHA.

---

## 18. OUTSTANDING — third-party leaks that break the privacy claim

Audited 2026-08-20. **The consent framework is sound; these leak around it.** A privacy
policy asserting nothing third-party loads before consent would be a *false statement*
while these stand. Priority order:

1. ~~**`assets/js/theme.js` loaded YouTube on EVERY page.**~~ — **CLOSED 2026-08-21.**
   A presence guard (`!youtubeEmbedElements.length`) and a consent guard
   (`window.ccsConsent && !window.ccsConsent.embed`) sit at the top of `loadVideo`, and
   `tail.htm` now defines `window.ccsConsent`, so both are live. Original defect: `tail.htm` loads theme.js
   sitewide; `bgPlayerInit`'s `loadVideo()` runs unconditionally and injects
   `https://www.youtube.com/iframe_api` **before** checking for `[data-youtube-embed]`
   (that check is inside the `onload` handler). Every visitor, every page, no video needed.
   `[data-youtube-embed]` is genuinely used by 4 tile themes — `hero_single_vid.html` and
   `hero_swiper-slide_{50,75,100}.html` — so the API is needed *sometimes*, never always.
2. ~~**Direct YouTube / Vimeo iframes** in `hero_split.html`~~ — **CLOSED 2026-08-21.**
   All four now ship `data-src` + `data-consent="embed"` with no `src`, on
   `youtube-nocookie.com` and with Vimeo `&dnt=1`. `ccs_consent.js` fills in the `src` for a
   visitor who allowed embedded media and draws a click-to-load notice for one who did not.
3. ~~**flatpickr from `cdn.jsdelivr.net`**~~ — **CLOSED 2026-08-21.** The user removed the
   booking and travel utility bars (carry-overs from another site). Verified: no
   `*booking*`/`*travel*` files remain, no flatpickr on disk, no CDN call anywhere.
   Residual dead flatpickr *style rules* remain in compiled `theme.css`/`theme.min.css` —
   no network call, deliberately not hand-edited.
4. ~~**`theme.js` `formInit`**~~ — **CLOSED 2026-08-21.** Body replaced with a documented
   no-op; `docReady(formInit)` left valid. `node --check` passes.
5. **Stripe** (`checkout.php`) is **fine** — strictly necessary, correctly scoped to
   checkout only, not sitewide. Must be *named* in the privacy policy as a processor.
6. `add-product.php` / `add-about.php` pull Bootstrap + jQuery UI from CDNs, but both are
   gated to `k_user_access_level ge '7'` with editing on. Admin's browser only. Minor.

### Non-third-party gaps
- ~~**No privacy link at any point of collection**~~ — **CLOSED 2026-08-21.**
  `snippets/utils/privacy_notice.htm` (shared, `pn_purpose`-parameterised, self-clearing)
  is embedded at all four: both contact themes, `users/register.php`, and the footer
  newsletter. A fifth, `contact/theme/cntct_map_cntcfrm.html`, was unreferenced demo content
  with a dead form and was moved to `_to_delete/unreferenced-themes/` instead.
- ~~**`subscribers.php` stores one field**~~ — **CLOSED 2026-08-21.** Added
  `subscriber_consent_ts` / `_src` / `_txt`, written by the footer sign-up.
  **RE-REGISTER THE TEMPLATE** or the columns do not exist. No IP by choice — see
  `_WIP-consent.md`. Double opt-in is still not implemented and remains a judgement call.
- **The cookie policy must name what is actually set**: `ccs_cnst_v/a/m/e` (four now — the
  embedded-media category was added in phase 3), Couch's auth cookie and
  `couchcms_testcookie` (`auth/auth.php`), the PHP session, the cart.
- **No data access / deletion request path.**

### The PageBuilder consent trap — read before "fixing" #1 or #2
A server-side `<cms:if ccs_consent_marketing == '1'>` **inside a PB tile theme is unsafe**.
`page-builder.php` hardcodes `K_CACHE_PB = 1` and returns stored HTML keyed by tile id —
not by user, not by session. One visitor's consent state gets baked in and served to
everyone. This is the same reason `consent_banner.htm` lives in the frame (§14).
*(Not biting today: no `cache/` dir exists. Latent, not active.)*

### DECIDED 2026-08-20 — the `data-src` convention. Do not propose a facade again.

A per-block poster/facade was proposed and **the user rejected it**, for a good reason:
many more blocks are coming, several will embed video, and video is to be used "however
YouTube has it". A pattern that needs a new field on every future block does not scale.

**The adopted approach — consent is given once, up front, by category:**

1. **Tiles never emit a live `src` on a third-party iframe.** The convention for every
   block, existing and future, is:

   ```html
   <iframe data-src="https://www.youtube-nocookie.com/embed/..." data-consent="marketing"
           class="ccs-consent-frame" ...></iframe>
   ```

   An iframe with no `src` makes **no request**. The markup is identical for every
   visitor, so there is **no per-visitor branch server-side** and the PB cache entry is
   correct for everyone — the trap above is sidestepped by construction, with no
   `no_cache` and no new fields.

2. **One sitewide script in `tail.htm`** (the frame, where consent is known per-request)
   promotes `data-src` to `src` when `ccs_consent_marketing == '1'`. When it is not, it
   leaves the box empty and writes a short in-place notice with a link that re-opens the
   banner. Frame-level, so it is never cached and never duplicated per block.

3. **The banner names the processors.** Generic "we use cookies" wording is the specific
   thing regulators object to; naming YouTube and Vimeo is what makes the consent
   *informed*, and it is what lets a single up-front answer legitimately cover every video
   on the site — including ones in blocks that do not exist yet. See §14 for the field.

**Why this is compliant and a plain notice is not.** Explaining in the banner is necessary
but not sufficient: the visitor's answer has to actually govern. "By using this site you
may see YouTube videos" as a statement of fact, with the iframes loading regardless, is
notice, not consent — declining has to really prevent the load. Under this design it does.

**Use `youtube-nocookie.com` and Vimeo's `dnt=1`** in the `data-src` regardless. They
reduce what is set once consent *is* given; they are not a substitute for the gate.

**Do NOT use YouTube's thumbnail (`i.ytimg.com/vi/...`)** anywhere as a placeholder —
that is still a Google request on page load and would rebuild the problem being solved.
The empty-box notice needs no image at all.

**Add to the §7 new-block checklist:** any third-party iframe uses `data-src`, never `src`.

### AUDIT LESSON 2026-08-21 — grep for the PROVIDER, not for the embed syntax

Three YouTube/Vimeo triggers shipped ungated because they are built **in JavaScript from a
data attribute** by BigPicture (`theme.js` `lightboxInit`), so there is no URL in the markup.
Scans for `iframe src=` and `[data-youtube-embed]` both missed them.
`grep -rn "youtube\|vimeo\|ytimg" snippets/` finds them all.

Any library that constructs embeds from data attributes is invisible to a markup scan. The
pattern now: third-party sources ship as `data-ccs-bp`, `ccs_consent.js` promotes it to
`data-bigPicture` only with consent, and a local upload keeps its real attribute.

**The diagnostic that found it:** the page made 46 requests with JS on and 29 with JS off,
with YouTube contact only in the first. A plain iframe fires either way — so JS-initiated.

### DECIDED 2026-08-21 — the consent bar is visually ISOLATED from the theme

`assets/css/ccs_consent.css` owns every color, size and font for the bar and the
blocked-video placeholder. **No Bootstrap class, no `var(--ccs-*)`, no site font.** Loaded
last in `head.htm`, `#id`-scoped, `!important` only on contrast-critical properties.

This is not over-engineering and must not be "tidied" back onto theme variables. It is the
one component whose appearance carries legal weight, and theming it would let a settings
screen break compliance silently.

**Reject and Accept share one class and differ only in label.** They did not before: Accept
was `btn-primary` and Reject was `btn-outline-secondary`, while the template's own header
claimed they were identical — the exact dark pattern it said it avoided. Never style one
differently from the other.

Also: no heading element in the bar (an `<h2>` polluted every page's outline;
`aria-labelledby` points at a styled `<p>` instead), explicit focus rings, 44px buttons,
15px text floor, all contrast pairs AAA, and `hidden` rather than `.d-none` for the panel.

### DECIDED 2026-08-21 — consent cookies are written by the SERVER, not JavaScript

`ccs_consent.js` does not touch `document.cookie`. The banner is a real `<form method="post">`
and `utils/consent.htm` records the answer with `cms:set_cookie`. Reason: Safari's ITP caps
script-written cookies at 7 days whatever lifetime is asked for, so an accepted answer was
expiring weekly instead of six-monthly.

Two facts that made it possible, both verified in core: `cms.php:38` calls `ob_start()`, so
`setcookie()` is legal after output has begun; and `parser.php:458` dispatches any public
non-underscore method on `$TAGS`, which is why `cms:set_cookie` works with no `register_tag`
anywhere. `cms:set_cookie` takes **expire as a duration in seconds**, forces **httpOnly**,
and sets **no SameSite**.

Consequences: the banner now works with JavaScript disabled; the policy version is stamped
server-side so the client never sees it (**do not add `ver` back to `window.ccsConsent`**);
and the globals after a POST are set from the posted values, because a cookie written this
request is not in `$_COOKIE` until the next one.

### DECIDED 2026-08-20 — click-to-load IS the second consent path

Background video is an *enhancement*, not the content of a section, and the user has
ruled: **local upload is not mandated, and if a visitor opts out the video simply does not
play.** That is a legitimate outcome — there is no obligation to serve the content to
someone who declined it.

On top of that, the empty box carries a **click-to-load** control that doubles as consent:

- **The click must be informed.** The notice has to say what the click does — that it
  contacts YouTube/Vimeo, that they set cookies, and (if it persists) that video will load
  automatically from then on. A bare "Play" button that silently flips a sitewide consent
  flag is the dark pattern regulators fine. Wording carries the compliance here.
- **Persisting the choice is optional and valid.** Either ask every time (most
  conservative) or offer "remember this" and write the cookie.
- **Write `ccs_cnst_v` as well as the category cookie**, or `consent.htm`'s version check
  treats the visitor as unanswered on the next page and the banner returns.
- **No `location.reload()` on this path** — unlike the banner, nothing server-rendered
  changes; the script just fills in the `src`.

### PARTIALLY BUILT 2026-08-21 — withdrawal must be as easy as consent

The user proposed withdrawal by emailing to be removed. **That is not sufficient** —
GDPR Art. 7(3) requires withdrawal to be as easy as giving consent, and a click cannot be
undone only by composing an email.

The fix is a persistent **"Cookie Settings"** control in the footer that re-opens
`consent_banner.htm`. One click to open, one to reject. This is a blocker for the
click-to-load design, not a nicety.

**Built 2026-08-21** in `footer/legal_links.htm`, embedded by all four footer themes — but
it ships with `hidden` and **phase 3 must remove that attribute** (on the button
`[data-ccs-consent-open]` and its separator `[data-ccs-consent-sep]`) once
`ccs_consent.js` exists. It also needs `consent_banner.htm` made reopenable: today the
banner renders only when `ccs_consent_set != '1'`. Until both land, this requirement is
**not** satisfied.

### RECOMMENDED — give embedded media its own category

Today a click-to-load would have to grant **marketing**, which also switches on ad pixels
— more than the visitor asked for when they only wanted to watch a video. A third category
(`ccs_cnst_e`, "Embedded media") makes the click honest: it grants video and nothing else.
Cost is one cookie, one checkbox, one globals field, one line in the cookie policy. Cheap
now, awkward to retrofit once answers are stored against the current two.

theme.js's `[data-youtube-embed]` DOM check is unaffected by any of this: PB tiles are
rendered server-side into the final HTML before the response ships, so by `DOMContentLoaded`
the attributes are simply in the DOM. Gating the injection on `elements.length` is safe and
PageBuilder-agnostic.

---

# ADDED 2026-08-27 — commerce, receipts, deliverability, store color

Everything below was built or proven after §18. Same rule as §12: where a fact was
proven by reading the core or the compiled CSS, the file is named and the **code
string** is the anchor, never the line.

---

## 19. Commerce — the order flow

One shape, both gateways:

```
submit -> validate stock -> write PENDING order + line items
       -> gateway takes the money
       -> server-to-server confirmation marks paid and deducts stock ONCE
```

**Nothing on `checkout.php` deducts inventory.** `snippets/utils/order_fulfil.htm` is
the only thing that does, and it is reached from three places — `stripe-webhook.php`,
`paypal-ipn.php`, `order-complete.php`. All three are guarded by the same
`order_stock_deducted` flag, so whichever arrives first wins and the others are no-ops.

**The flag is claimed BEFORE the deduction, not after.** That ordering is the whole
guarantee. A crash between claim and deduct loses one deduction; the reverse ordering
would double-deduct on every retry, which is worse and much harder to notice.

| File | Role |
|---|---|
| `checkout.php` | Collects, validates stock, writes the pending order, hands off to the gateway. PayPal's `_cart` form is built **outside** `cms:form` — see §20. |
| `snippets/utils/order_create.htm` | Writes the order + line items. Runs inside checkout's `k_success` so it can read `frm_*`. Sets `ccs_new_order_id`, `ccs_new_order_number` (`VS-<id>`), `ccs_new_order_token`. |
| `snippets/utils/order_fulfil.htm` | The **only** place stock moves. Claims the flag, deducts, then embeds the receipt. Sets `ccs_fulfil_result` = `deducted\|already\|notfound\|notpending`. |
| `snippets/utils/order_refund.htm` | Mirror of fulfil. Stores a **cumulative** refunded total, so a replayed `charge.refunded` is harmless. **Never touches stock** — see below. |
| `snippets/utils/order_email.htm` | Both receipts. See §23. |
| `stripe-webhook.php` / `paypal-ipn.php` | Signature / VERIFIED checks, then embed fulfil or refund. Both answer 200 for any verified event so Stripe does not retry forever. |
| `order-complete.php` | Addressed by `?t=<random page_name>`, not by id. Embeds fulfil **for Stripe only** — the guard is `ocp_gateway == 'stripe'`, so a card customer returning before the webhook lands is covered and a PayPal one is not. **Corrected 2026-08-30**; the entry previously implied both gateways. |
| `orders.php`, `order-items.php` | The stored order. `order-items.php` carries `item_restocked_qty` (a **count**, not a flag). |
| `restock.php` | "Orders & Returns" — per-line-item restock. Ceiling recomputed server-side: `$apply = min( $want, $allowed )`. Dressed in the Couch admin theme, no CDN. |

**Refunds are deliberately half-manual.** The order status updates itself from
`charge.refunded`; **stock is changed by hand** because the condition of returned
apparel is unknown until someone looks at it. This is a decision, not an omission.

**No "Refund this order" button exists in the Couch admin, on purpose.** It would make a
CMS super-admin session able to move real money. Today those privileges are separate —
refunding requires logging into Stripe or PayPal. Do not add one without asking.

**Stripe uses PaymentIntents, not the legacy Charges API.** Charges cannot carry a
bank's 3-D Secure step, so most European and many Mexican cards simply fail on it.
`createPaymentMethod` + `handleNextAction` are the flow.

**Secrets live in `ccs_vsp_dash/config.php` on the LIVE server only.** That file is
gitignored; the local copy has empty placeholders. **Never upload the local one** — it
would wipe the live keys. `ccs_vsp_dash/addons/cart/config.php` is tracked in git and
must stay secret-free.

---

## 20. Couch runtime facts proven this cycle

Extends §12. These cost real debugging time.

| Fact | Where proven | Consequence |
|---|---|---|
| **`db_persist` has its own context scope** | `register_tag('db_persist', …, 1)` sets `$supports_scope=1`; the scope is pushed and popped inside the call | `k_last_insert_id`, `k_success` and `k_error` are **swallowed** — the caller sees nothing and the failure is silent. Use **`db_persist_ex`**, documented in core as *"Same as db_persist above but has no context of its own."* All seven calls in the order flow use `_ex`. |
| **`_auto_title` defaults to `'0'`, not 1** | `db_persist` param defaults | Without `_auto_title='1'` no `page_name` is generated and the save dies with `ERROR: Tag "db_persist" - _page_id required`. Pass it explicitly on every create. |
| **`pp_cart_items` never sets `k_count`** | cart addon | PayPal's `_cart` upload needs `amount_1`, `amount_2`… A manual 1-based counter is required, or every line posts as `amount_` and PayPal answers `AMOUNT_MISSING`. |
| **A nested `<form>` inside `cms:form` does not submit** | browser HTML parsing, not Couch | PayPal hung on "Redirecting". Build the gateway form **after** `</cms:form>`. In JS, find the form with `cardEl.closest('form')` — never `querySelector('form')`, which grabs whichever is first in the document. |
| **`cms:send_mail debug='1'` still SENDS** | `tags.php` — `$rs = $FUNCS->send_mail(…, $debug);` runs first, *then* the log is written | It is a logger, not a dry run. The log records the resolved From/To/headers and `Delivery Success` / `Delivery Failed` — which is the useful part. |
| **`send_mail` is PHP `mail()`** | `functions.php` — `return @mail( $to, $subject, $text, 'From: '.$from.$h );` | No SMTP, no multipart, no `alt_body`. `cms:alt_body` is only consumed by the **phpmailer addon**, which is present in `ccs_vsp_dash/addons/phpmailer/` but **commented out of `kfunctions.php`** — so HTML mail ships HTML-only. See §23. |

---

## 21. Store Appearance — the store color system

Built 2026-08-27 after the user's rule: *"Nothing should ever be hardcoded for color
unless it's theme. But if the bg is colored a theme, we must stay accessible
compliant."*

| Piece | What it is |
|---|---|
| `globals.php` → **Store Appearance** group, order 155 | `ccs_gl_store_panel`, `ccs_gl_store_secondary`. **Re-register after any change here.** |
| `snippets/dropdowns/_tok/color-surface.htm` | The panel list. **Light / Dark / No panel** — three options, not eight. |
| `snippets/utils/store_theme.htm` | Resolves both settings into `ccs_st_panel` and `ccs_st_secondary`. Both can legitimately be **empty** — always print them through `cms:show` inside the class attribute, never concatenate. |

Embedded once near the top of `checkout.php`, `cart.php`, `cart-modal.php`,
`order-complete.php` and `snippets/single/product.html`.

**Why the panel list is only three.** `.text-bg-*` looks like the answer — background
and a contrast-picked foreground in one class. But in this theme only half of it is
dynamic:

```
.text-bg-light { color:#555555 !important;
                 background-color:RGBA(var(--ccs-light-rgb), …) !important }
```

The foreground is a literal baked in at build time; the background reads a variable that
`assets/css/user.php` rewrites at runtime from Site Colors and the named presets
(`primavera`, `estate`, `autunno`, `inverno`, `scuro`, `notte`). **The background moves
and the text color does not.** Under `notte`, primary is `#66FCF1` — a pale cyan —
and `.text-bg-primary` would still paint `#ffffff` on it, about 1.25:1.

Only `light` and `dark` survive every preset, because their *names* constrain them. Plus
"no panel", which cannot be wrong because it paints nothing.

**There is no accent / price color picker, deliberately.** An earlier draft had one. It
was removed once `user.php` was read properly: no fixed hue can be promised readable
against a background that is also moving. Totals use `fw-bold fs-8` — emphasis by weight
and size, which never fails contrast.

### DECIDED 2026-08-30 — on receipts and order pages, a FIXED pair beats a theme token

Refines the rule above rather than contradicting it. The user's words:

> *"The hardcode on text for readability is fine, in fact it's preferred against a
> possible mistake in the theme settings. As long as you have something like a
> text-dark on a text-background-light, then it will be readable. But if you do
> things like primary, secondary, we have no way of knowing if they are readable."*

The test is **can the pair be wrong?**

| Safe | Why |
|---|---|
| A literal foreground on a literal background | Nothing can move it. |
| `light` / `dark` | Their *names* constrain them across every preset. |
| An opacity (`opacity-85`, `ccs_st_secondary`) | Dims the inherited color, so it can only move toward the ground it sits on, never across it. |
| Weight and size (`fw-bold fs-8`) | Carries no color at all. |

| Unsafe on these pages | Why |
|---|---|
| `text-bg-primary`, `bg-secondary`, `text-success` … | The background reads a theme variable the site owner can change; the paired foreground is a build-time literal. The two move independently. |

**This is the one place a hardcode is the careful choice, not the lazy one.** A
receipt or an order page has to stay readable after somebody experiments with Site
Colors, and nobody re-tests a receipt after changing a palette. A status pill that
can turn invisible is worse than no pill.

Applied at `restock.php` `.rs-pill` — a fixed `#a8322a` on `#f6e6e4`, reverted from
`text-bg-danger` once this was stated.

**Secondary text is an opacity, not a color** — see §22 for why `.text-muted` is a trap.
`opacity-85` and `opacity-75` were the two offered; both measure clear of 4.5:1 against
the theme's body color on white. `opacity-50` was dropped for landing near 3:1.

**Left alone on purpose:** `text-danger` on validation messages and `text-primary` on
icons. They are theme colors, not hardcodes, and changing them only in the store would
make it inconsistent with the rest of the site. `text-danger` measures ~3.4:1 against a
light ground — fine for an icon, short of AA for a sentence. Fixing that is a site-wide
theme decision.

`snippets/utils/order_email.htm` keeps literal hex values. **That is correct** — email
clients strip stylesheets and do not support CSS variables.

---

## 22. Theme facts proven this cycle

Extends §6a. All measured against `assets/css/theme.css` as compiled.

**`.text-muted` is a trap.**

```
.text-muted { color: var(--ccs-secondary-color) !important; }
```

which is `#292b2c` — near black — in light mode, and it carries `!important`, so it wins
over whatever the panel handed down. On any dark surface that is black text on a black
ground, and nothing at the use site can override it. An opacity class dims the
*inherited* color, so it can only ever move toward the ground it is sitting on, never
across it.

**`.form-text` fails AA on its own.** `color: #949494` — 2.91:1 against white. Adding
`.text-muted` on top of it was accidentally *improving* it. Do not just delete the
`text-muted` and leave `.form-text`.

**The `fs-*` scale runs BACKWARDS.**

| Class | Size |
|---|---|
| `.fs-10` | 0.750rem |
| `.fs-9` | **1rem** — normal body |
| `.fs-8` | 1.333rem |
| `.fs-7` | 1.777rem |
| `.fs-6` | 2.369rem |
| `.fs-5` | 3.157rem |
| `.fs-1` | 9.969rem |

Bootstrap instincts are wrong here. `fs-5` for "slightly bigger" produces a headline —
this shipped once and had to be walked back to `fs-8`.

**`--ccs-quaternary-bg` has no dark value.** It is defined once, `#fff`, inside
`[data-bs-theme=light]`, and `user.php` never overrides it. `.form-control` pairs it
with a literal `color: #464a4c`. So form inputs are a **white box on every preset**,
including the dark ones. `snippets/single/product.html` copies that rule for
`.cart-form select` and keeps the literal hex on purpose — a theme variable there
follows the preset to near-white text in a white box. There is a comment in the file
saying so. Revisit only if the theme gains a dark surface value.

**NO button variant follows the theme.** Grepped every `.btn-*` rule for
`var(--ccs-primary)` / `var(--ccs-secondary)`: **zero matches.** Every one is a
build-time literal — `.btn-secondary` is `#292b2c`, `.btn-primary` `#007AFF`,
`.btn-danger` `#FF3B30`. `user.php` only emits `.btn-black` / `.btn-outline-black`; it
never regenerates the standard variants. **Changing Site Colors moves `.bg-*`,
`.text-*` and `.text-bg-*`, and moves no button at all.**

The distinction that still matters:

- **Filled** variants set `--ccs-btn-color` **and** `--ccs-btn-bg` together, so the
  label is legible against its own button whatever the page does. `.btn-secondary` is
  `#fff` on `#292b2c` = 14.2:1.
- **Outline** variants set a color and a border only, and borrow the page's ground.
  `.btn-outline-secondary` is `#292b2c` text on a `#292b2c`-ish dark page — the button
  renders perfectly and is invisible. This is what hid the wishlist button.

Every ground-dependent outline button on the store pages was moved to a filled variant
on 2026-08-27. **This is a hardcode-for-a-hardcode trade, not a fix** — see §24.

**`--ccs-gray-1100-rgb` and `--ccs-gray-1200-rgb` are the reverse of
`--ccs-gray-1100` / `--ccs-gray-1200`.** The hex pair says 1100 `#0c151a`, 1200
`#292b2c`; the `-rgb` pair says 1100 `41,43,44`, 1200 `12,21,26`. The `.text-11xx` /
`.text-12xx` utilities read the `-rgb` form. Trust the `-rgb` values.

**Contrast measurements taken this cycle** (against `light` `#fafafa` and `dark`
`#0c151a`, AA needs 4.5:1 for body text, 3:1 for icons and UI):

| | on light | on dark |
|---|---|---|
| `text-success` `#4CD964` | **1.76** | 10.0 |
| `text-info` `#2EB7F5` | **2.18** | 8.1 |
| `text-warning` `#FF9500` | **2.11** | 8.4 |
| `text-danger` `#FF3B30` | **3.40** | 5.2 |
| `text-primary` `#007AFF` | **3.85** | 4.6 |
| `text-secondary` `#292b2c` | 13.6 | **1.30** |

`text-success` on a light panel was the original reported bug — a green total at
1.76:1. The `-emphasis` variants are better where they apply (`text-success-emphasis`
`#318d41` ≈ 4.2:1 on white, `text-danger-emphasis` `#cc2f26` ≈ 5.25:1) and they flip
correctly under `[data-bs-theme]` — but **not** for a dark panel inside a light
document.

---

## 23. Transactional email and deliverability

`snippets/utils/order_email.htm` sends **two** messages: an HTML receipt to the
customer and a plain-text notification to the shop. Plain text for the shop copy is
deliberate — it is a work notification, not a document, and it should read on a phone
lock screen.

**It is embedded from `order_fulfil.htm`, immediately after stock moves.** Two
consequences:

1. The receipt is sent whether or not the customer ever returns to the site. Sending
   from `order-complete.php` would mean a closed tab equals no receipt.
2. It **inherits fulfil's idempotency for free**. A replayed webhook cannot send a
   second receipt — which also means **a webhook resend can never be used to test the
   receipt.** Testing requires a *new* order. This wasted a round trip once.

**Wording rule:** do not promise anything the site cannot do. There is no order
tracking, no shipping notification, and no order-history page a customer can log into.

### Deliverability — resolved 2026-08-27

Order **VS-172**: the shop notification arrived, the customer receipt to a Gmail address
never did — not in spam, not in any tab. Root cause was **not** the code. The shop copy
was local delivery on the same server, so nothing checked it; the Gmail copy had to pass
SPF, DKIM and DMARC, and `valasports.com` had **none of the three**.

Domain is registered at GoDaddy, delegated to `hgns1/hgns2.hostgator.com`. cPanel's
**Email Deliverability** page warned *"this system does not control DNS … if you install
this record, this change will not be effective."* **That warning was wrong** — the three
Install buttons worked, the records went live, and the row went to `Valid`. The PTR,
which had been reading `ip-129-121-64-252.local`, also came back Valid with no support
ticket. Do not send anyone to HostGator support over this again.

| Record | Value now live |
|---|---|
| SPF (`valasports.com`) | `v=spf1 +mx +a +ip4:129.121.64.252 ~all` |
| DKIM (`default._domainkey`) | cPanel-generated key, `v=DKIM1; k=rsa; p=…` |
| DMARC (`_dmarc`) | `v=DMARC1; p=none;` — monitor only |
| PTR (`252.64.121.129.in-addr.arpa`) | `sh00198.hostgator.com` |

Mail leaves from **129.121.64.252** with HELO `sh00198.hostgator.com`. Note the web A
record is a *different* IP, which is why SPF needs the explicit `ip4:`.

**Still true:** mail goes out through PHP `mail()` as HTML-only with no plain-text
alternative, because the phpmailer addon is not loaded (§20). Gmail scores against that.
If receipts still land badly after the retest, enabling phpmailer with authenticated
SMTP is the next move — it would also sidestep shared-IP reputation entirely.

**Diagnostic order for any future mail problem**, cheapest first: cPanel → Email →
**Track Delivery**. Nothing in the log means PHP never sent it and the fault is in the
template. A record with a failure quotes the receiving server's actual refusal. A record
marked delivered means it arrived and was filed somewhere.

---

## 24. OUTSTANDING as of 2026-08-31

Supersedes the 2026-08-27 list. Everything the earlier version called blocking
is resolved; what remains is below.

### Blocking — the money path, in this order

1. **One real order, end to end.** `order_fulfil.htm` changed when the shop chain
   was made template-agnostic (§27) and the stock deduction has not been exercised
   since. A webhook resend cannot test it — fulfil is idempotent (§23), so a receipt
   or a deduction can only be re-tested by a genuinely new order.
2. **Refunds, both gateways.** Untested. `charge.refunded` must first be enabled on
   the Stripe endpoint's event list or nothing reaches the site at all. Then: full,
   partial-then-remainder, a dashboard Resend to prove replay safety, and PayPal.
3. ~~**PayPal partial refunds under-record.**~~ **FIXED 2026-08-31, see §31.**
   `order_refund.htm` now takes a mode: Stripe passes `'total'` (cumulative,
   replaces), PayPal passes `'increment'` (adds, keyed on the refund's own
   `txn_id` in the new `order_refund_txns` field). Verified against a stub;
   still needs a live test on both gateways. **Re-register `orders.php`.**
4. **Delete from the live server:** `_mailtest.php` and `_logs/mail.txt`. That log
   holds customer names, addresses and complete receipt bodies.

### Going live

New live keys, `K_STRIPE_TEST_MODE=0`, `paypal_use_sandbox` off, and a **new webhook
endpoint** at the live URL — the sandbox endpoint's signing secret does not carry over.
The endpoint URL must be the one that actually resolves: Stripe counts any 3xx as a
delivery failure, so a non-www → www redirect silently fails every event. Sandbox
events retry only three times over a few hours; live events retry for three days.

### Diagnostic pages to remove before launch

`_whoami.php` (greeting chain), `_tools/preflight.php`, the `_to_delete/` folder and
the stale `*.bak-*` files. `_domcache.php` is worth keeping while uploads continue —
see §28 for why it exists.

### Known gaps, accepted for now

- ~~The wishlist save button and its POST listener are duplicated.~~ **DONE
  2026-08-31, see §30.** Extracted to `utils/wishlist_listener.htm` and
  `utils/wishlist_button.htm`; both single views now embed them. A new sellable
  template is three lines in its single view and nothing anywhere else. The
  extraction also fixed the missing `variants_display` on services, added an
  ownership check on the posted id, added dedupe, and swapped `db_persist` for
  `db_persist_ex` so a failed save stops reporting success.
- ~~Orders placed before `order_user_id` was written carry the old `k_user_id`.~~
  **NOT AN ISSUE.** The site is live but closed to public orders, so no such orders
  exist. Nothing to backfill.
- No double opt-in on the newsletter.
- `add-product.php` / `add-about.php` image-browser and preview-icon sizing — flagged
  as painful and deferred.
- Mail is HTML-only with no plain-text alternative; phpmailer remains unloaded (§20).
- `.text-tertiary` / `.text-quaternary` resolve correctly (the `-rgb` vars *are*
  defined, at the bottom of `theme.css`) — noted because they look broken on a grep.

### Carried over — verify before assuming done

- Re-register `orders.php` (the Orders & Returns button gained `target="_blank"`,
  and a `type='message'` body only changes on re-registration), `order-items.php`,
  `restock.php`, `users/index.php`, `my-orders.php`, `globals.php`.
- ~~Revert `executable='0'` on `orders.php` / `order-items.php`.~~ **DO NOT.** That
  note predates the hardening and is now wrong. `executable='0'` is deliberate:
  `page.php` refuses to serve a non-executable template in the browser to anyone
  below super admin, so orders cannot be enumerated by URL. It does not block
  `cms:pages`, so `my-orders.php` still works. Reverting it would reopen the hole.
  The rationale is written at the top of `orders.php` itself.

### BOOKMARK — buttons do not follow the theme

*Parked 2026-08-30 at the user's request: not a live problem, revisit only if it
becomes one. Everything needed to pick it up again is here.*

**The problem.** No `.btn-*` rule in the compiled theme reads a theme variable.
Grepped every one for `var(--ccs-primary)` / `var(--ccs-secondary)`: zero matches.
`.btn-primary` is a literal `#007AFF`, `.btn-secondary` a literal `#292b2c`.
So changing Site Colors moves `.bg-*`, `.text-*` and `.text-bg-*`, and moves **no
button at all**. `user.php` emits `.btn-black` / `.btn-outline-black` and never
regenerates the standard variants.

**Why it hasn't bitten.** Filled variants set `--ccs-btn-color` and `--ccs-btn-bg`
together, so the label stays legible against its own button whatever the page does.
The store pages were moved to filled variants for exactly that reason. Outline
variants are the fragile ones — they set a color and a border and borrow the page's
ground, which is what once made the wishlist button invisible.

**The proposed fix, when it matters.** Have `user.php` emit `.btn-*` overrides the
way it already emits `.btn-black` — the pattern is in that file near the
`Button Colors` comment. Two things make it more than a copy-paste:

1. It is a **site-wide theme change**, not a store change. Every button, every page.
2. The foreground problem returns. `--ccs-btn-color: #fff` over a preset where
   secondary is pale (`notte` `#45A29E`, `primavera` `#85D3A9`) fails contrast.
   `user.php` is a Couch template with `cms:php` available, so it *can* compute a
   contrasting label color from the chosen hex. That is the honest version.

**Interim rule that keeps it harmless:** prefer filled variants over outline ones on
anything whose background the theme can move.

# ADDED 2026-08-30 — outgoing mail, root cause and fix

Same rule as §12 and §20: where a fact was proven by reading the core or a server
log, the file is named and the **code string** is the anchor, never the line.

---

## 25. Why site mail never reached external recipients — RESOLVED

**Symptom.** Order receipts to customers were accepted by Exim and never delivered.
Not in spam, not bounced, no trace. The shop's own copy of the same notification,
written by the same code in the same instant, always arrived.

**What masked it for three days:** the shop copy goes to `vala@valasports.com` on the
same server, so Exim delivers it locally (`R=virtual_user T=dovecot_virtual_delivery`)
and it never has to survive an internet hop. It was never a control — it was a
different code path from the message that was failing.

### Two faults. Both had to be fixed; either alone still failed.

**Fault 1 — headers joined with a bare LF.** `functions.php`:

```php
if( strtolower(substr(PHP_OS, 0, 3)) === 'win' ){ $mail_sep = "\r\n"; }
else{ $mail_sep = "\n"; }
...
return @mail( $to, $subject, $text, 'From: '.$from.$h );
```

Mail headers require CRLF. Given bare LF this host's Exim treats each following line
as a **folded continuation** of the one before, so `From:` swallows `MIME-Version`
and `Content-Type` and the message ships with **no valid content type at all**.
Visible in the received headers of a delivered message as leading spaces:

```
From: vala@valasports.com
 MIME-Version: 1.0
 Content-Type: text/plain; charset=utf-8
```

Exim had been logging this on **every send since the site was built**:

```
cwd=/home1/jznrjgte/valasports.com 3 args: /usr/sbin/sendmail -t -i
 Content-Type: may not follow vala@valasports.com
```

That line is in `/var/log/exim_mainlog` and nowhere else. Couch's own log records
`Delivery Success` for the same message.

**Fault 2 — no envelope sender.** No fifth argument to `mail()`, and the server's
`sendmail_path` is `/usr/sbin/sendmail -t -i` with no `-f` either. The envelope
falls back to the cPanel system user, `jznrjgte@sh00198.hostgator.com`.

Exim picks the DKIM key from the **envelope** domain, so site mail was signed
`d=customcmssystems.com` — this cPanel account's *primary* domain — while `From:`
said `valasports.com`. Nothing aligned. Webmail was unaffected because authenticated
submission sets the envelope to the mailbox address.

### The three-way probe that settled it

One page load, three messages, same recipient (`_mailtest.php`, since deleted):

| | header separator | `-f` | result at Gmail |
|---|---|---|---|
| A | LF | no | dropped |
| B | LF | yes | dropped |
| C | **CRLF** | **yes** | **delivered** — `dkim=pass`, `dmarc=pass` |

C's `Authentication-Results` from Google:
`dkim=pass header.i=@valasports.com; spf=softfail; dmarc=pass (p=NONE) header.from=valasports.com`

### The fix — `kfunctions.php`, `alter_send_mail` listener

Core is **not** modified; it is overwritten on upgrade. The listener rebuilds the
header block with `"\r\n"`, passes `-f` when the From address is a bare address, and
**returns 1** — `event.php` `_do_dispatch()` stops on the first truthy return, and
`send_mail()` then does `return $result;` without ever calling `mail()` itself.

Verified before writing it, not assumed:

- `_rsc()` is public and strips CR/LF/tab from keys and values — injection protection preserved.
- Core skips the `Sender` key; the listener does too.
- `$arr_config` (attachments / alt_body) is ignored by core's own `send_mail`, so nothing is lost.
- `tags.php` logs `Delivery Success` from the value `send_mail()` returns, so `debug='1'` still works.
- The `-f` guard rejects a display-name From (`Vala <vala@…>`) and anything carrying a
  shell metacharacter, falling back to sending without `-f` rather than passing it through.

> **The phpmailer addon registers on this same event.** If it is ever enabled in
> `kfunctions.php`, remove this block or phpmailer will never run.

## 26. Mail diagnostics — facts that cost real time

| Fact | Consequence |
|---|---|
| **`cms:send_mail debug='1'` logs to `K_SITE_DIR . 'log.txt'`** — the site root, publicly readable | Always pass `logfile=` into a directory with a deny-all `.htaccess`. The log holds customer names, addresses and full receipt bodies. |
| **`log()` uses `@fopen($file,'a')`** | A path into a directory that does not exist writes nothing and reports nothing. Create the directory first. |
| **`Delivery Success` = `mail()` returned true = the local MTA queued it** | It says nothing about the remote hop. The shop copy logs the identical string and arrives; the customer copy logged it and did not. |
| **The receipt only sends on fulfil's `deducted` branch** | A webhook resend returns `already` and never reaches the mail. **Testing a receipt requires a genuinely new order.** |
| **Outbound relays through `*.smtp.a.cloudfilter.net` (Proofpoint)** | Recipients see cloudfilter's IP, not 129.121.64.252. This server's own PTR is irrelevant to external delivery. |
| **SPF does not cover the relay.** `v=spf1 +mx +a +ip4:129.121.64.252 ~all` covers .244 and .252; mail actually leaves from 35.89.44.x / 44.202.169.x | **SPF softfails on everything this domain sends, webmail included.** DMARC passes on DKIM alignment alone. Adding the relay to SPF is unresolved — ask HostGator. |
| **Reverse DNS for 129.121.64.252 is split and flips.** `ns1`/`ns2.unifiedlayer.com` disagree; the bad value is `ip-129-121-64-252.local` | cPanel's "Valid" reflects whichever nameserver answered that pageload — **do not trust that checkmark**. Real, unfixed, and *not* the cause of this bug. |
| **Two clocks.** Couch's log is UTC−7 (`K_GMT_OFFSET = -7.0`); the server and Exim run America/Chicago | Convert before quoting a time to anyone. |

**HostGator case I-26601376.** Their agent independently identified the bare-LF
header fault from the same Exim log — worth knowing the escalation route works when
the ask names the access level ("someone who can read `/var/log/exim_mainlog`")
rather than the symptom.

### Where the receipt's content lives

Orientation, not a rule. Nothing the customer reads is written into the template
any more. `globals.php` carries an **Order Emails** group (order 157) holding the
sending address, the contact address, and every line of wording; `order_email.htm`
reads them and substitutes placeholders (`{shop}`, `{order}`, `{first_name}`,
`{contact}`, `{total}` …) in `cms:php` — core has no `replace` tag.

Each field's default sits in its own editable body, so an emptied field returns to
the shipped wording rather than printing nothing. The shop's own notification is
assembled in PHP so its line breaks survive; its wording is still in the template.

The point is that a wording change is an admin task on this site, not a code change.

### Confirmed working

**Order VS-185, 2026-08-30 11:51** — receipt delivered to the buyer's Gmail **inbox**,
not spam. HTML rendered correctly and the `View your receipt` link resolved to
`order-complete.php?t=`. The HTML path had never been exercised before this (every
probe was plain text, and `Content-Type: text/html` was never a real header until the
fix), so this order tested it for the first time.

### Still open
- Mail is still HTML-only with no plain-text alternative — phpmailer remains unloaded (§20).
- The plain-text shop copy loses every newline that follows a self-closing `cms` tag;
  newlines after literal text survive. Cosmetic, mechanism unverified in the parser.

---

# ADDED 2026-08-31 — template-agnostic commerce, accounts, and four expensive traps

Same rule as §12, §20 and §25: where a fact was proven by reading the core, the file
is named and the **code string** is the anchor, never the line number alone.

---

## 27. The shop is template-agnostic now

**The idea.** A cart line records a page id. It does not record which template that
page belongs to. Eight places in the shop needed to reopen the item behind a cart or
order line, and every one of them was written `masterpage='product.php'` — a guess,
correct only for as long as products are the only thing sold.

That guess does not raise an error when it is wrong. `cms:pages` matches nothing and
the block inside is skipped in silence, so a service line — or a `shirts.php` line
added later — walked past the oversell guard, took no stock deduction, and returned
no stock on refund, all while every page looked normal.

**The rule.** `snippets/utils/item_tpl.htm` takes `itm_tpl_id` and returns `itm_tpl`,
read out of `couch_pages INNER JOIN couch_templates` — the same join
`functions.php:1842` uses, and the same two steps `KCart::add_item()` takes when it
has only an id. **Read the template, never assume it.** Converted: `order_fulfil.htm`,
`order_create.htm`, `restock.php`, `checkout.php`, `cart.php` (×2), `cart-modal.php`
(×2).

Templates that legitimately name themselves are untouched — `add-product.php` is the
product editor, `snippets/single/product.html` is the product view, the `pb_mod_source`
files are product and service listings.

**What a new sellable template needs.** Nothing beyond what Couch already imposes:
`pp_price` (required — `cart.php` `$arr_pp_fields`), `pp_options` (only if it has
variants; `isset($pp_options)` makes it optional), `pp_requires_shipping`,
`track_inventory` / `in_stock` if stock should be enforced, and a single view whose
cart form carries `id="add-to-cart-form"` — the id `ccs_js.js` scrapes for Move to
Cart, with `form.cart-form` as its fallback.

### Cart → wishlist

`snippets/utils/cart_to_wishlist.htm` saves a whole cart onto the signed-in user's
wishlist; `users/logout.php` offers it rather than throwing the cart away at sign-out.

The interesting part is the variants. `wish_variants` must hold the **raw** cart
control names, because `ccs_js.js` splits that string and injects each pair straight
back into a scraped cart form. The cart itself keeps only the readable label —
`cart.php` `$arr_display_attrs[$opt_name] = $opt_values[$os]['attr']` — and throws
the index away. Two sources recover it:

- **Primary: `raw_variants`.** The `cart_alter_custom_fields` listener in
  `cart_ex.php` already attaches the submitted `on{N}`/`os{N}` pairs to every cart
  line. `order_create.htm` uses the same field for the same reason.
- **Fallback: re-parse.** `KCart::_parse_options()` on the item's own `pp_options`,
  matching the stored label back to its index. It is the identical parse that
  produced the label, so the mapping is exact rather than guessed. Needed only for
  lines that were sitting in a session before that listener shipped.

A line whose options no longer exist is **counted as skipped, not saved**. A wishlist
row whose variants cannot be replayed produces a Move to Cart that fails later, in
front of the customer.

### Guest orders can be claimed

An order placed without signing in has no `order_user_id` and can never appear in
`my-orders.php`. `snippets/utils/order_claim.htm`, offered from `order-complete.php`,
files it against whoever is signed in now.

Authority is the **token already in the URL** — the random `page_name` `db_persist`
generated, not the sequential id. If possession of it is enough to read the receipt,
it is enough to file the order. Two conditions bound it: `order_user_id` must be
**empty**, so an order moves from nobody to somebody and never between people; and
the claimer needs a real `ccs_auth_uid`. The ownership test runs **before** the
elevation to level 10, not inside it — `orders.php` is `access_level='7'` and a
customer is level 2. Same pattern, same reasoning, as `wishlist-modal.php`.

Registration requires email activation, so nothing is carried through it. The token in
the address bar is the whole state: register or sign in, come back to the same URL,
press Save.

---

## 28. Traps that are not guessable from the outside

Each of these cost a full diagnostic cycle. None produces an error message.

### The snippet DOM cache invalidates on `filemtime` alone

`parser.php` `get_cached_HTML()` stores the **parsed tree** of every `cms:embed`'d
snippet and every PageBuilder tile template in `couch_settings`, keyed
`md5('k_dom_cache_' . <absolute path>)`. `K_CACHE_OPCODES` is hard-defined to `'1'` in
`header.php` — it is not a config toggle. Invalidation is one test:

```
filemtime($filepath) > $cache_value['last_mod']
```

An FTP client that **preserves local timestamps** (FileZilla's default) uploads a
changed snippet whose mtime is older than the moment the cache row was written, that
test is false, and the server keeps executing the previous tree indefinitely.

What makes it hard to see: a cached tree still **executes**. Only the markup is
frozen. So dynamic parts of the same file — a badge count, a cart total — keep working
perfectly while new markup never appears. It reads as "my change didn't take", not as
a cache.

Templates in the site root do **not** go through this path, which is why root-level
edits always appear immediately and snippet edits sometimes do not.

`_domcache.php` (level 7) lists the files holding a tree that cannot self-invalidate,
and clears them. Turn off timestamp preservation in the FTP client as well.

### `cms:html_encode` is a BLOCK tag

`tags.php` `html_encode()` encodes `$node->children` and **ignores `$params`
entirely**. Written self-closing — `<cms:html_encode some_var />` — it encodes the
empty string and prints nothing at all, with no error. `Hi <name>!` silently became
`Hi !`.

Worse, it is whitespace-sensitive: an HTML formatter reflowing the block onto several
lines pads the output with encoded whitespace. **A template that cannot survive a
reformat is a trap**, so the site no longer uses the tag anywhere. Escaping is done
once in `cms:php` — `utils/user_name.htm` returns `ccs_user_display_esc` — and printed
with an ordinary self-closing `cms:show`, which no formatter can break.
`.vscode/settings.json` disables HTML format-on-save for this reason;
`cms:capture`, `cms:trim` and `cms:excerpt` are sensitive the same way.

### `assets/js/ccs_js.js` is cache-busted by hand

`tail.htm` loads it as `ccs_js.js?v=N`. That query string is the **only** cache-buster
the file has. Edit the script without moving the number and browsers keep serving the
old one — which is indistinguishable from the change not working. **Bump it on every
JS change**; the version history is in a comment beside the tag.

### `k_user_<field>` does not exist

The extended-users addon sets exactly five `k_user_*` values — id, name, title, email,
access_level (`extended-users.php`, mirrored in `functions.php`
`set_userinfo_in_context()`). **Nothing loops the user page's custom fields into the
context.** Reading `k_user_user_first_name` returns empty every time, silently, which
looks exactly like a customer who never filled the field in. `checkout.php`'s name
prefill did this and always fell through to splitting the display name.

`snippets/utils/user_name.htm` is the one place that resolves a signed-in person's
name. It queries on **`extended_user_id`**, never `k_user_id`: `_get_associated_page()`
returns the page id, or `-1` when no row exists, or nothing when the field is missing —
in which case core's value, the real **user** id, survives. Looking that up as a page
id can land on a different person's page. `extended_user_id` holds `$AUTH->user->id`
with `search_type='integer'`. One value, one meaning.

Outputs `ccs_user_display` (+ `_esc`) for anything the visitor reads about themselves,
and `ccs_user_first` / `ccs_user_last` for forms that need the halves apart.

---

## 29. The wishlist panel and the full page are the same markup

`snippets/e_modals/wishlist_body.html` (offcanvas) and `snippets/single/wishlist.html`
(the `saved-items.php` list) render the same rows, and on `saved-items.php` **both are
in the document at once**. Three consequences, all of which bit:

- **Delegate from `document`, not from `#wishlistOffcanvas`.** The handler was bound to
  the panel, so Remove and Move to Cart worked in the panel and were inert on the full
  page — no error, no request, a dead button.
- **A row id can exist twice.** `getElementById` returns whichever comes first and
  leaves the other on screen. Remove every element with that id.
- **Refresh the panel on `show.bs.offcanvas`.** The cart has always done this
  (`refreshCartModal`) and the wishlist did not, which is the entire reason the cart
  never showed a stale list and the wishlist did. A panel that refreshes when opened
  stops depending on whoever changed the data remembering to tell it.

`window.refreshWishlistModal` and `window.bumpWishlistCount` were both **called and
never defined** — `product.html` and `service.html` guard with
`typeof === 'function'`, so the calls were swallowed in silence. Both now live in
`ccs_js.js`. If a helper is called through a `typeof` guard, grep for its definition
before assuming it runs.

---

## 30. Adding a new sellable template — the whole checklist

The shop no longer cares which template an item comes from. Adding
`shirts.php` (or shoes, or memberships) needs **no edit anywhere else** — no
list to join, no config, no registration beyond the template itself.

**On the template**, only what Couch's cart already requires:

| Field | When |
|---|---|
| `pp_price` | always — `KCart::add_item()` refuses an item without it |
| `pp_options` | only if it has variants (`isset($pp_options)` makes it optional) |
| `pp_requires_shipping` | as needed |
| `track_inventory` / `in_stock` | only if stock should be enforced |

**On its single view**, three lines:

```
<cms:pp_product_form id="add-to-cart-form" ...>   <!-- the id ccs_js.js scrapes -->
<cms:embed 'utils/wishlist_listener.htm' />        <!-- top of the item loop -->
<cms:embed 'utils/wishlist_button.htm' />          <!-- wherever the button goes -->
```

`utils/wishlist_button.htm` takes an optional `wl_btn_class` set before it.
`form.cart-form` works as a fallback for the form id.

**Placement rules that are not cosmetic:**

- The **listener** goes inside the item's `cms:pages` loop, **before any
  markup** — it answers with `cms:abort`, so anything printed first is sent
  as part of the reply — and **outside any stock or availability gate**. An
  out-of-stock item is exactly what people want to save for later; gating it
  made saving fail in silence. `service.html` had it inside the gate and
  `product.html` did not, which is the kind of drift that copies cause.
- The **button** carries element ids (`save-wishlist-btn`,
  `wishlist_feedback`), so embed it **once per page**. `product.html` uses
  `cms:capture` + two `cms:show` because only one of its two placements is
  ever visible at a time. Two genuinely simultaneous buttons would need
  ids per instance.

**Everything downstream already resolves the template from the data:** cart
and order lines, stock deduction and return, the oversell guards and the SKU
snapshot all go through `utils/item_tpl.htm` (§27); wishlist rows carry
`wish_masterpage`, written from `k_template_name` by the listener; Move to
Cart scrapes the item's own page by its link.

### What the extraction fixed on the way

The save button and its handler used to be **copy-pasted** into
`snippets/single/product.html` and `snippets/single/service.html`. Not a
design decision — duplication, and it had already rotted. Pulling it into
`utils/wishlist_listener.htm` + `utils/wishlist_button.htm` also fixed:

- **`service.html` never sent `variants_display`**, so a saved service showed
  no readable options anywhere.
- **No ownership check on the posted id.** The listener now requires
  `product_id` to equal the page actually being viewed. Without it a
  signed-in visitor could post any id and file someone else's item — or a
  non-item — onto their own wishlist.
- **No dedupe.** A second click made a duplicate row that then had to be
  removed twice.
- **`db_persist` instead of `db_persist_ex`**, so a failed save was reported
  to the customer as a success (§28 covers why that tag hides `k_error`).

---

## 31. Refunds — two gateways, two shapes

`utils/order_refund.htm` records refunds for both gateways and is embedded
from `stripe-webhook.php` and `paypal-ipn.php`. It takes a **mode**, and
getting the mode wrong silently corrupts the amount.

| | Stripe | PayPal |
|---|---|---|
| What arrives | `charge.refunded` with `amount_refunded`, **cumulative** | one IPN per refund, **that refund only** |
| `ccs_refund_mode` | `'total'` | `'increment'` |
| Stored figure | **replaces** what is held | **adds** to what is held |
| Replay-safe because | a repeat is not larger, so it is ignored | the refund's `txn_id` is recorded and re-checked |
| `ccs_refund_txn` | not needed | **required** |

**The bug this fixed.** Both were treated as cumulative. On a $100 order a
$30 refund followed by a $70 refund reported 70 — larger than 30, so it
*replaced* it. The record read `$70 refunded, refunded_part` permanently, on
an order refunded in full.

**`order_refund_txns`** on `orders.php` holds the refund transaction ids
already counted, whitespace separated. PayPal's `txn_id` on a refund IPN is
the **refund's own** id: unique per refund, identical across PayPal's retries
of it. `parent_txn_id` is the original payment and is the same for every
refund against it — useless as a key.

An `'increment'` call with **no** `ccs_refund_txn` is **refused, not
applied**. Without an id there is no way to tell a new refund from a replay,
and counting the same money twice is worse than recording nothing.

**Stock is still never touched by a refund.** Money coming back says nothing
about whether goods did, or in what condition. `restock.php` is where a
person decides that.

Verified against a stub before shipping: 30 → 30 `refunded_part`; same IPN
replayed → unchanged; 70 → 100 `refunded`; replayed → unchanged; no txn id →
nothing recorded. Stripe: 30 → 30, replay → 30, 100 → `refunded`.

---

## 32. Front-end password fields have a reveal toggle

`utils/password_reveal.htm`, embedded once per front-end template that asks
for a password — currently `users/login.php`, `users/register.php`,
`users/profile.php`. **Never in the Couch admin.**

No markup change is needed anywhere. It finds `input[type="password"]` at
runtime and wraps each one, which covers both shapes this site uses:
`cms:input type='password'` on login, and `cms:input type='bound'` on the
other two — `extended_user_password` is declared `type='dummy_password'`,
whose `_render()` sets `k_type='password'` before delegating, so it emits a
real password input like any other. A password screen added later gets the
toggle from one embed.

Defaults to hidden, restores to hidden on submit so a revealed password is
not left on screen behind the next page, and never touches the value — only
the input's `type`. Caret position is preserved across the toggle, because
some browsers jump it to the start mid-typing.

The reasoning, since it looks like a small thing: a field nobody can read is
a field people mistype, and the usual answer to mistyping a password twice
is to choose a shorter, worse one.

---

## 33. Two parser rules that fail the whole file

**A quoted tag operand cannot be followed by a comparison operator.**

```
<cms:if "<cms:gpc 'x' method='post' />" eq '1'>     <-- fails
```

`ERROR! LOGIC_OP: Invalid char "e"` — the `e` is the start of `eq`. Couch
treats the quoted expression as a finished operand and then accepts only `&&`
or `||`. Nested quotes are NOT the problem and are used all over this site
(`checkout.php`, `index.php`, `contact.php`, `restock.php`); it is the
comparison *after* one that breaks. Assign, then test:

```
<cms:set x = "<cms:gpc 'x' method='post' />" />
<cms:if x eq '1'>
```

The reported line and char do not reliably point at the offending line, so
grep for the shape instead:

```
grep -rn '"<cms:[^"]*" *\(eq\|ne\|gt\|lt\|ge\|le\|==\|!=\) ' --include=*.php --include=*.htm --include=*.html .
```

**`cms:ignore` discards its body but the PARSER still reads it.** A malformed
tag pasted into a comment as an example fails the file exactly as it would in
live markup. Describe broken syntax in words; never paste it.

# ADDED 2026-09-03 — footer/utility-bar hover, legal links, theme palette seeding

## 34. Link hover colors — footer and utility bar

`ccs_gl_site_nav_hvr_clr` was labeled "Menu Text Color on Mouse Hover" and was
never menu-only: `assets/css/user.php` feeds it into the nav's hover rule AND
into `--ccs-link-hover-color`, the hover color for every ordinary link. It is
now labeled "Link Color on Mouse Hover (menu and site-wide)".

New globals group **Footer & Utility Bar Links** (order 145):

| field | drives |
|---|---|
| `ccs_gl_site_ftr_lnk_hvr_clr` | `.ccs-footer a:hover/:focus` |
| `ccs_gl_site_ubr_lnk_hvr_clr` | `.ccs-utlbar a:hover/:focus` |

Both fall back to `ccs_gl_site_nav_hvr_clr` when left on Default. Mapped in
`user.php` next to `ccs_site_nav_hvr_clr`; rules emitted after the
`.nav-item:hover` block, each wrapped in a blank guard so nothing invalid is
emitted when both are unset.

WHY THOSE SELECTORS. `theme.css` gives every `.link-<color>` a hover rule
pointing at a hardcoded gray ramp (`--ccs-gray-link-hover-N`), declared
`!important` at `theme.css:220-231`. On a dark footer the words vanish under the
pointer. `.link-primary:hover` is specificity (0,2,0); `.ccs-footer a:hover` is
(0,2,1), so it wins among `!important` declarations regardless of load order.
The nav escaped this only because `format_typography` emits `text-<color>`,
which has no hover rule at all.

CLASS HOOKS. `ccs-footer` on all four footer themes, `ccs-utlbar` on every
utility bar theme. Needed because `foot_2col_form-scal.html` uses `<section>`,
not `<footer>`, and `ubr_shop.html`'s `<section>` had no distinctive class.

`link-` ON A CONTAINER IS A BUG. `foot_bsc.html:13` had `link-<color>` on the
`<div class="col-auto">` wrapping the copyright, so hovering anywhere in that
block recolored the plain text too. Containers take `text-`, anchors take
`link-`. Same fix on `ubr_shop.html`'s "Hi {name}!" greeting `<span>`.

FRONT-END EDIT BUTTONS. The two fields live on globals.php, not on the footer's
own template, so a plain `popup_edit` cannot reach them. Wrap it, the way
`nav_mdd.html:190-193` already does:

    <cms:pages masterpage='globals.php' limit='1'>
        <cms:popup_edit 'ccs_gl_site_ftr_msg|ccs_gl_site_ftr_lnk_hvr_clr' ... />
    </cms:pages>

A second badge (`fa-mouse-pointer`) sits beside each theme's existing cog.

## 35. Legal links follow the footer now

`legal_links.htm` styles its links `text-reset` (`color: inherit`), so it takes
everything from the element it sits inside. Three themes put their color and
typography on the root element; **`foot_bsc.html` hung them off an inner
`col-auto` div**, so the legal row inherited from `body` — page text color on
the footer's background. Moved onto its `<footer>`, matching the others.

The row's size was pinned at `fs-9` regardless of the footer's setting, which on
a footer set to `fs-10` made the fine print LARGER than the text above it (the
`fs-*` scale runs backwards — `fs-1` largest, `fs-10` smallest). It now reads
`lgl_fs`, set by each theme from its own size field just before the embed,
defaulting to 9.

Two dead `<cms:show ccs_nav_typography />` calls removed — that variable is set
only inside the nav themes, without global scope, so it rendered empty here.

CCS `foot_3col.html` never embedded `legal_links.htm` at all. Added.

## 36. Theme palettes, and seeding the custom color fields

THE COLOR PICKER'S `color=` DEFAULT IS DEAD AFTER THE FIRST SAVE.
`addons/color-picker/color-picker.php:67`:

    $data = strlen( $this->data ) ? $this->data : $this->color;

It is consulted only while the field has never been saved. The first save of
globals.php writes a value into all fourteen color fields, so no amount of
cleverness in `color=` can make them follow the theme. Seeding has to WRITE.

WHAT THE DEFAULTS WERE. Bootstrap 4's stock palette, not this site's — ten of
fourteen disagreed with `user.php`. White and Black were both `#007AFF`, the
primary color pasted into the wrong two rows, so ticking Customize Colors
turned white and black blue. All fourteen now match `user.php`'s own defaults.

SEEDING. `ccs_theme_palette()` in `<dash>/addons/kfunctions.php` holds the
palette table in PHP. A `page_prevalidate` listener on globals.php keeps the
fourteen custom color fields **tracking the theme for as long as "Customize
Site Colors" is unticked**. Save the theme, and that theme's palette is what is
sitting in those fields.

THE OWNER NEVER SEES A SEEDING STEP. `not_active` on the group is a `cms:func`
with `ccs_gl_site_custom_color_opt` as a declared dependency, and
`conditional-fields.php` compiles that into client-side JS — so ticking the box
reveals the group **immediately, with no save**, already showing the theme's
colors. Pick a theme, save. Later, tick the box, adjust, save. Two ordinary
saves for two ordinary decisions.

ONCE CUSTOM IS ON, NOTHING TOUCHES THOSE FIELDS. Not a re-save, not a change of
base theme. To go back to a theme's palette, untick and save.

THE OFF→ON SAVE IS SEEDED TOO, which matters in exactly one case: changing the
theme and ticking the box in the same sitting, where the revealed swatches still
show the previous theme's colors. Any swatch touched in that same save wins
over the theme — the same `orig_data` vs `data` test, per field.

TWO RUNTIME FACTS THIS RELIES ON. `field.php:236` — `store_posted_changes()`
returns early on an inactive field, so a hidden field's `orig_data` stays null
and `data` holds the stored value; read `orig_data ?? get_data()`, never
`orig_data` alone. `page.php:1033` — persistence is driven by `$f->modified`,
NOT by whether the field was active, so writing an inactive field from this hook
does reach the database. That is what makes the tracking work while the group is
hidden.

Values are six-digit lowercase hex — what the picker stores at 100% opacity
(the alpha pair is dropped at 255, color-picker.php:76-91).

TWO COPIES, ONE CHECKER. The table exists in `user.php` (as a cms:set chain) and
in `kfunctions.php` (as PHP). Nothing in Couch links them, and a color edited
in one and not the other means ticking the box silently changes the site's
colors. `_tools/check-theme-palette.py` diffs all 8 themes x 14 colors. Pure
python, no PHP needed, run from the site root. Run it after touching either
file.

    python3 _tools/check-theme-palette.py

THE SAME DUPLICATION BIT THE FONTS TWICE. `user.php`'s font chain handled four
of eight themes with no `else`, so three font roles silently died on scuro and
notte. The Typography card on the Global Site Settings page had its own copy of
that chain with the same hole, showing blank font names where the site rendered
Montserrat. Both fixed. The card is at `globals.php` ~line 860; the chain it
must track is `user.php:180-232`.

## 37. Known, not fixed — `alpha='0'` does not disable the opacity slider

All fourteen color fields carry `alpha='0'`, which reads as "off" and is not.
The addon does `$attr['alpha'] = trim($attr['alpha'])`, then gates on
`strlen($this->alpha)` — and `strlen('0')` is 1. Only `alpha=''` (or omitting
the parameter) disables it. That is why every swatch shows an "Opacity (100%)"
slider.

NOT CHANGED, DELIBERATELY. With alpha off, `_render` feeds the stored value
straight into `<input type="color">`. Any field holding an 8-digit `#rrggbbaa`
value — which is what gets stored at anything below 100% opacity — would be
rejected by the browser and show black. Check whether any stored color is
8-digit before switching this off.

## 38. The activation email — shape, and why it reads as phishing

`users/register.php` sends it with `cms:send_mail`, plain text (no `html='1'`),
`from=K_EMAIL_FROM` (`vala@valasports.com`, so the From address IS aligned with
the domain).

TWO DEFECTS FIXED 2026-09-04:

1. **The body was indented with the markup**, twelve tabs deep. `cms:send_mail`
   emits its children verbatim; on a plain-text mail it runs `unhtmlentities()`
   then `strip_tags()` (tags.php:5968-5971), and neither touches whitespace. So
   every line arrived behind twelve tabs. `order_email.htm` starts its body at
   column 0 for exactly this reason - line 204 is hard against the left margin,
   deliberately. This one now does the same, and must stay that way.

2. **It was shaped like phishing.** Subject "Account Activation", no site name,
   no reason-for-receipt, no reply path, and a body whose entire content was one
   long URL with a `key=` query string. That is the signature filters are built
   to catch. It now names the site, says why the message arrived, gives the
   24-hour expiry, tells a non-signer to ignore it, and sets `reply_to`.

3. **`k_site_title` does not exist.** Not in core, not in any addon - grep the
   whole of `ccs_vsp_dash` and it appears nowhere. The only `k_site_*` Couch
   sets is `k_site_link` (page.php:1766). This mail signed off "Thanks,"
   followed by a blank line and nobody noticed, because `cms:show` on an unset
   variable prints nothing and raises nothing - same family as `k_user_<field>`
   in sec.28. The site name now comes off globals with
   `<cms:get_field 'ccs_gl_site_name' masterpage='globals.php' />`, with a
   literal fallback so the subject line can never ship half-written.

DIAGNOSING A SPAM FILING - DO THIS FIRST. Track Delivery says "delivered" and
stops there; it cannot tell you why something was filed. The delivered message's
own `Authentication-Results` header can, in one line: open the copy sitting in
the spam folder, View original / Show original, read the spf= dkim= dmarc=
verdicts. Everything else is guesswork until those three words are known.

### SPF softfail - diagnosed from a real header, 2026-09-04

A registration mail to Gmail came back:

    dkim=pass   header.i=@valasports.com header.s=default
    spf=softfail (does not designate 44.202.169.34 as permitted sender)
    dmarc=pass  (p=NONE)

The path, read off the Received chain:

    sh00198.hostgator.com [129.121.64.252]
      -> eig-obgw-5001b.ext.cloudfilter.net
      -> omta035.useast.a.cloudfilter.net [44.202.169.34]  <- Gmail sees THIS

SPF is evaluated against the LAST hop. The record listed only 129.121.64.252,
the machine PHP runs on, which is not the machine Gmail receives from.

**THE FIX - `include:eig.spf.a.cloudfilter.net`.** That record is pure ip4, 45
addresses including `35.89.44.32/29` and `44.202.169.32/29`, and 44.202.169.34
sits inside the second. It costs **one** DNS lookup.

    v=spf1 +mx +a +ip4:129.121.64.252 include:eig.spf.a.cloudfilter.net ~all

Three lookups total. HostGator's own documented record,
`v=spf1 a mx include:websitewelcome.com ~all`, reaches the same ranges but
costs **nine** of the ten RFC 7208 lookups - websitewelcome.com alone is seven,
because it also pulls in Google and Microsoft - and authorising Google and
Microsoft to send as valasports.com is far wider than this site needs. Use the
narrow include; keep the wide one as the fallback only if cPanel insists on
regenerating the record.

**cPanel SAID "VALID" THE WHOLE TIME.** Confirmed 2026-09-04: Email
Deliverability showed a green tick and "SPF is properly configured for this
domain" against `v=spf1 +mx +a +ip4:129.121.64.252 ~all` while Gmail was
softfailing it. That check is syntax only - it does not test whether the record
covers the IP the mail actually leaves from. A green SPF row is not evidence of
anything; the receiving side's Authentication-Results header is.

**`include:cloudfilter.net` IS THE WRONG ONE.** It redirects to
`_spf.cloudmark.com` - Proofpoint and Rackspace ranges, nothing in 44.202.x.
The name is close enough to pick by mistake.

**EDIT THE RECORD FOR valasports.com, NOT customcmssystems.com.** The header
`X-Source-Dir: customcmssystems.com:/valasports.com/users` shows valasports is
an addon domain under the customcmssystems cPanel account, so Email
Deliverability lists both.

DMARC stays `p=none` until SPF has passed for a few weeks; only then is
`p=quarantine` worth considering.


## 39. Scope, from 2026-09-04

**valasports.com only.** customCMSsystems is no longer in scope - do not port
anything to it, do not read from it, do not offer to. Anything below this line
in the history that says "both sites" is history, not instruction.

## 40. Signing in sent admins to the back end — `redirect='1'` is not a flag

Level 7+ logging in from the front end landed on the admin dashboard instead of
the page they came from.

**`auth/auth.php:260` is where it happens:**

```php
$default_dest = ( $this->user->access_level < K_ACCESS_LEVEL_ADMIN )
                ? K_SITE_URL
                : K_ADMIN_URL . K_ADMIN_PAGE;
$dest = $FUNCS->sanitize_url( $dest, $default_dest, 1 );
```

Any time the redirect target is missing or fails the local-only test, an admin
is thrown at the back end. Nothing asked for that — the front end simply failed
to hand over a usable target.

**AND IT FAILED BECAUSE OF `redirect='1'`.** Five call sites had
`<cms:login_link redirect='1' />`. On `cms:login_link` that parameter is **a
URL, not a mode flag**. `login_link_handler` (extended-users.php:703) passes it
to `alter_login_link` (:734), which substitutes `REQUEST_URI` only when it is
**empty**. So `'1'` survived, the link became `?redirect=1`, `sanitize_url`
rejected it as non-local, and the admin default took over.

The confusion is understandable: on `cms:process_login` and `cms:process_logout`
the identically-named `redirect` parameter IS a mode — `'0'` none, `'1'` current
page, `'2'` read the querystring. Same word, two meanings, one addon.

**A bare `<cms:login_link />` is the correct call** and always was — empty means
"come back to REQUEST_URI". All five now omit the parameter: `checkout.php`,
`order-complete.php`, `legal_links.htm`, `nav_mdd.html`, `nav_sdd.html`.

**`users/login.php` now resolves the destination itself** and passes it as
`<cms:process_login redirect=lgn_dest />`, so the core default is never reached.
`lgn_dest` is trusted only if it starts with `K_SITE_URL` or a single `/`, and
never if it points back at login.php or logout.php. The check has to happen
BEFORE `sanitize_url`, not after — sanitize_url's own fallback is the very
dashboard being avoided.

The already-logged-in branch at the top of that file used to redirect level 7+
to `k_admin_link` explicitly. Removed; everyone goes to `lgn_dest`.

**The back end is still reachable** — typing the admin URL works as always, and
an admin bounced to login FROM the dashboard still returns there. Login just
does not volunteer it any more.

### A BARE `login_link` INSIDE A CACHED PAGEBUILDER TILE BAKES ONE URL

`page-builder.php:198` keys the tile cache on the **tile's** page id
(`$page_id = $rows[$x]`), not on the page being viewed. One entry, shared by the
whole site. So `<cms:login_link />` — which embeds `$_SERVER["REQUEST_URI"]` at
render time — freezes whichever page happened to populate the cache and serves
that to everyone. Observed live: the footer's link read `?redirect=%2F` on every
page, because a homepage visit wrote the entry.

`K_CACHE_PB` is hard-defined `'1'` (page-builder.php:4), and both call sites
cache for anonymous visitors — which is exactly who sees a Login link:

- `ccs_gl_nav_pb` → `no_cache="<cms:if k_logged_in>1</cms:if>"`
- `ccs_gl_ftr_pb` → `no_cache` only for an admin with inline edit on

**Consequence:** from the nav or the footer, signing in returns you to the
homepage, not to the page you were on. That is acceptable — the requirement was
"the page they were at OR the homepage, never the back end" — and the dashboard
bounce is gone either way, because `/` is local and sanitize_url passes it.

**Where it DOES return you to the exact page:** `checkout.php` and
`order-complete.php`. Neither is inside a PageBuilder tile, so their links
render per request.

**If the nav and footer ever need the exact page too**, do NOT solve it by
turning caching off — that costs the folder-walk on every anonymous request
(sec.13). Append the location at click time in JS instead; the cached HTML stays
visitor-independent and JS-off visitors simply get the homepage.

## 41. Admin link in the footer — built, switched off

`legal_links.htm` carries an "Admin" link to `k_admin_link`, gated on
`k_logged_in && (k_user_access_level ge '7')`, wrapped in a `cms:ignore` whose
open and close lines are both marked **OFF-SWITCH**. Delete those two lines to
enable it; nothing else needs touching.

**`ftr_emb.htm` was changed at the same time and that is not optional.** Its
`no_cache` used to be `<cms:if k_user_access_level ge '7' && inline_edit_active>`
— bypassing the cache only for an admin with inline edit ON. It is now
`<cms:if k_logged_in>`, matching what `nav_emb.htm` already does for the nav.

Without that, an admin browsing normally still WRITES the shared tile cache, and
`page-builder.php:198` keys that cache on the **tile's** page id, not the page
being viewed — one entry for the whole site. So the admin's copy of the footer,
Admin link included, would be served to every anonymous visitor afterwards.
Exactly the failure sec.13 records for the nav's Logout button.

Cost: logged-in visitors no longer get a cached footer. Negligible — unlike the
nav, the footer does no folder-walk.

`inline_edit_active` is still set at the top of `ftr_emb.htm` but nothing reads
it now; the footer themes gate their overlays on `ccs_gl_edt_ok`.

## 42. globals.php layout — `col-md-*` needs a `type='row'` parent

**Store Appearance's two dropdowns were laid out wrong.** `ccs_gl_store_panel`
and `ccs_gl_store_secondary` carried `col-md-6` but sat loose in the group with
no `<cms:editable type='row'>` around them. `col-md-6` is a Bootstrap grid class
and does nothing without a `.row` parent, so the pair collapsed onto one line and
the Order Emails header drew over the top of them. Wrapped in
`ccs_gl_store_row`; the group renders clean now.

**Both directions of this mistake exist in the file's history.** The reverse -
fields inside a row with NO col class - is what the `cms:ignore` at
`ccs_gl_site_nav_mnu_dd_row` records. Rule: a `col-*` class requires a row
parent, and a field inside a row requires a `col-*` class.

**Auditing for it.** Walk the editable tree and flag any non-group/row/message
field where `has_col != in_row`. Treat `<cms:repeatable>` as its own scope or
you get false positives - its children's `order` values restart, and a naive
sibling check reports them as duplicates of the group's. As of 2026-09-05 the
only two real hits were the pair above. `ccs_gl_site_hglt_row` totalling
col-md-8 is deliberate, not a bug - a row need not fill twelve.

## 43. Order email fields — wiring confirmed 2026-09-05

All ten content fields in the **Order Emails** group are read by
`snippets/utils/order_email.htm` via
`<cms:get_field '<name>' masterpage='globals.php' />`, and nothing else on the
site reads them. The other eight `ccs_gl_ordml_*` names are structural (group,
two messages, five rows).

| globals field | variable | used for |
|---|---|---|
| `ccs_gl_ordml_from` | `om_from_addr` | From header AND `-f` envelope sender |
| `ccs_gl_ordml_contact` | `om_contact` | what `{contact}` resolves to |
| `ccs_gl_ordml_subject` | `om_w_subject` | receipt subject |
| `ccs_gl_ordml_greeting` … `_footer` | `om_w_*` | receipt body lines |

The shop notification is separate and still sends from `K_EMAIL_FROM` with the
customer as `Reply-To`.

**TWO MISSING FALLBACKS ADDED.** `om_admin` and `om_w_subject` were guarded;
`om_from_addr` and `om_contact` were not.

An empty `om_from_addr` is the serious one — it is the From header *and* the
envelope sender, so a blank field means a receipt with no From at all: rejected
by most receivers, with bounces having nowhere to land. It now falls back to
`K_EMAIL_FROM`, which is already domain-aligned. `om_contact` falls back to
`K_EMAIL_TO`, since blank would print "Email  and quote VS-123" to a customer.

## 44. Maintenance mode — wired 2026-09-06

Three parts.

**`maintenance.php`** — the holding page. Not clonable, `hidden='1'`. Embeds
`head.htm`, `main-cap.htm` and `tail.htm` so it carries the theme, fonts and
`user.php`, but **no nav and no footer**: every link in either points at a page
the gate is about to bounce the visitor away from.

It returns **503 with `Retry-After: 3600`**, set in a `cms:php` block before any
output because `head.htm` opens with the doctype on line 1. A holding page
served as 200 tells a crawler "this is the content now" and pages leave the
index.

Carries no editables. Heading, message and two display toggles live in the
Maintenance Mode group in globals.php, so the switch and its wording are edited
in one popup. The dashboard cog on the Site Status card opens all of them.

**`snippets/utils/maintenance_gate.htm`** — embedded as the first line of
`head.htm`, above the doctype.

Passes through for level 7+, and for four templates:

| Template | Why |
|---|---|
| `maintenance.php` | embeds head.htm itself — without this it redirects to itself |
| `users/login.php` | otherwise turning maintenance on from a logged-out browser locks you out |
| `users/logout.php` | end a session without clearing cookies |
| `users/lost-password.php` | the other half of getting back in |

**Everything reached through head.htm is gated; nothing else is.** The Stripe
and PayPal callbacks, `cart-modal.php`, `wishlist-modal.php` and
`categories.php` do not embed it, so a payment in flight still completes and the
AJAX fragments keep answering. That is the reason the gate is in the frame and
not in `kfunctions.php`.

**TWO PARSER RULES THIS OBEYS — both cost time before (sec.33).** The switch is
read into `mnt_on` and compared on the next line; a quoted tag as the left
operand of a comparison fails the whole file with `LOGIC_OP: Invalid char`. And
the four passes are chained `ne` tests, not a `cms:is` list: `cms:is` takes an
ARRAY for `in=` (tags.php:7806, `in_array`) and has no delimiter option — handed
a pipe-delimited string it returns 0 silently, which would have sent
maintenance.php to itself.

**Testing it:** tick the box, then load the site in a private window. Signed in
at level 7 you should see no change at all.

### The redirect loop on the first attempt — two causes, both fixed

`maintenance.php` shipped without `<?php COUCH::invoke(); ?>`. Every other
template on the site ends with it. `cms.php` only calls `ob_start()`; `invoke()`
is what parses the buffer, resolves the template and sets `k_template_name`.
Without it the file was never processed as a Couch page.

The gate's self-exclusion then tested `k_template_name`, which is empty until
invoke() has run — so `'' ne 'maintenance.php'` was true and the holding page
redirected to itself until the browser stopped.

**The exclusion now tests `$CTX->script` instead.** `cms.php:48-50` sets it from
`SCRIPT_FILENAME` relative to `K_SITE_DIR`, before any template is parsed or
registered, so it reads `maintenance.php` regardless of Couch's state. Backslashes
are normalised for Windows — the local WAMP copy hands back
`C:\wamp64\www\...`.

The redirect target is also now `<cms:show k_site_link />maintenance.php` rather
than `cms:link`, which is the form `users/login.php` already uses and needs no
template lookup.

Rule for any future gate of this shape: **never key a self-exclusion on
something that only exists after the page has been resolved.**

## 45. OPEN — `contact.php` is an unported CCS file

Line 1 is `require_once( 'ccs_dash/cms.php' )` — the CCS dash path, which does
not exist on this site. Its five `cms:embed` calls also use the old
`snippets/pg_frame/` layout rather than `pb_mods/pg_frame/`. The page cannot be
working. Not touched, because what it should contain is a separate question from
fixing the paths.


## 46. Dashboard — two sections, no "zones"

The front-end dashboard in `globals.php` was three numbered zones. It is now two
plain headings:

| Was | Is |
|---|---|
| Zone 1: Site Pulse | *gone — see below* |
| Zone 2: Visual Identity | **How the site looks** |
| Zone 3: Architecture & Integrations | **How the site is set up** |

**Zone 1 was three invented numbers and one real control.** Page Views and Form
Leads were hardcoded `--`; Uptime was a hardcoded 99.9%, which cannot be
measured from inside the server at all — if the site is down, this code is not
running to notice. The numbers were removed and the maintenance card moved into
"How the site is set up", full width and first in the row, since it is the only
control on the page that changes what the public can see.

The card shows Open/Closed to the public, the cog that opens the switch plus all
the holding-page wording, and — only while maintenance is on — a line saying
level 7+ still sees the real site, with a link to view the holding page in a new
tab. "Open to the public" means maintenance mode is off. Nothing on this page
pings anything external.

`ccs_gl_shop_on` does NOT exist on valasports — that checkbox was added to CCS
only. Do not reference it here.

## 47. `cms:ignore` bodies are parsed — this bit twice in one session

`LOGIC_OP: Invalid char "="` on uploading `maintenance_gate.htm`. The live code
was correct; the failure was in the comment above it, where the rule being
documented had been written out as a literal broken tag as an example.

`cms:ignore` discards its body but the PARSER STILL READS IT, so a malformed tag
inside a comment fails the whole file exactly as if it were live code. Already
recorded in sec.33, and still made again.

**Describe bad syntax in words. Never paste it, not even inside a comment.**

Scan for it with: find every `<cms:ignore>...</cms:ignore>` body and report any
`<cms:` inside. As of 2026-09-06 the only remaining hits are the two lines of the
deliberately-disabled Admin link in `legal_links.htm` (sec.41), which are
well-formed and meant to be there.


## 48. `.btn-outline-secondary` is a fixed near-black — unusable on dark themes

`theme.css:4281`:

    .btn-outline-secondary { color: #292b2c; border-color: #292b2c; }

A literal hex, not a theme variable, so it does not follow the palette in either
direction. On scuro, notte, or the dark preset it puts a near-black button on an
almost-black card and the control effectively disappears.

Fixed on the dashboard (`globals.php`): the "View the holding page" and
"Advanced Admin" buttons are now `btn-outline-primary`. primary is an accent in
every preset, picked to contrast with that theme's background, so it stays
readable light or dark — and it is what the other action buttons on that page
already use.

**Still using it, unchecked as of 2026-09-07** — fine wherever the surface is
always light, worth a look if any of these render on a dark background:

    add-about.php            snippets/single/product_orig.html
    add-product.php          snippets/single/wishlist.html
    add-service.php          snippets/utils/folder_dialog.htm
    snippets/e_modals/wishlist_body.html
    snippets/pb_mods/hero/theme/hero_container_single.html
    snippets/pb_mods/nav_swpr/theme/nswp_sld_blk.html
    snippets/pb_mods/pg_frame/consent_banner.htm

`btn-body-secondary` and `btn-body-tertiary`, used for the cog buttons on the
dashboard, **do not exist in theme.css at all**. They render as a bare `.btn`
inheriting body color, which happens to be legible in both modes. Not worth
changing, but do not assume they are doing anything.

## 49. `cms:embed` does not open a scope unless it is a tag PAIR

`tags.php:582` pushes `__embed__` onto the context only when the embed has
children. A self-closing `<cms:embed 'file' />` runs in the caller's scope, so
variables set inside the snippet persist afterwards.

That is what lets `maintenance.php` read `bs_theme_mode` for its logo — head.htm
sets it without `scope='global'`, and it survives because the embed is
self-closing.

## 50. Typography card — waterfall specimens

The six font tiles showed one sentence at `fs-6` with `text-truncate`, so every
tile ended in an ellipsis. An ellipsis reads as "click to see the rest" — it
looked like a control and was not one.

Each tile now shows **"Handgloves" at three descending sizes** (1.75rem /
1.05rem / 0.7rem, `line-height: 1.15`), classes `.ccs-spec-lg/-md/-sm` defined
in one `<style>` block above the row.

**Why a waterfall.** It is the standard way to present a typeface: the same word
at successive sizes shows both the letterforms and how the face holds together
as it shrinks. Nothing is cut off, so nothing implies there is more to open.
([Typekit Practice](https://practice.typekit.com/lesson/getting-the-most-out-of-type-specimens/))

**Why "Handgloves".** The traditional type-tester word — ascenders, descenders,
round and straight forms and a distinctive `g`, the letters that actually
separate one face from another, while still reading as a word rather than a
test string.

**The fixed `height: 48%` with `overflow-hidden` on each tile is what forced the
truncation.** Removed; tiles size to their content. Do not put it back.

The Spacing Preview paragraph below the tiles is unchanged and still earns its
place — it is the one place the settings are shown at real usage size.

## 51. Tertiary and Quaternary were missing from two color dropdowns

`snippets/dropdowns/all-colors.htm` listed Primary, Secondary, Light, Dark,
Warning, Success, Info, Danger — and skipped Tertiary and Quaternary. Every
other color list has them. `overlay-colors.htm` had the same gap.

Both fixed 2026-09-07. The CSS was always there — `bg-tertiary` and
`bg-quaternary` are in theme.css, and `user.php:314-318` emits
`--ccs-tertiary`, `--ccs-tertiary-rgb`, `--ccs-quaternary` and
`--ccs-quaternary-rgb`. The overlay path needs the `-rgb` pair specifically,
since `hero_custom_settings.html:89` builds
`rgba(var(--ccs-<token>-rgb), <opacity>)`. Nothing to add there.

**`all-colors.htm` is read by 18 files**, so this was not only the hero: hero
single + all five swiper slides, three content blocks, three list-1 category
inserts, two nav-swiper blocks, the transitions background, and both
`_tok/color-bg.htm` and `_tok/color-link.htm`. All of them gain the two options.

**Three lists still have no Tertiary/Quaternary, all correctly:**

| List | Why |
|---|---|
| `link-colors.htm` | grays only, no theme colors at all |
| `shades_gray.htm` | grays by definition |
| `_tok/color-surface.htm` | light / dark / none only, and its own comment explains that adding tokens there would break the paired foreground color |

**When adding a color list, diff it against `solid-colors.htm`** — that is the
most complete one.

## 52. Typography overhaul, 2026-09-07 — both sites

### `html` is where a site-wide text size has to go

`rem` measures against the ROOT element. The old size control set **`body`**
(`user.php`), so it never scaled anything but unclassed text — `fs-*` stayed
pinned at whatever `html` was, and `html` had no rule at all.

New global **`ccs_gl_site_text_scale`** (Master Theme Controls, order 8) →
`snippets/dropdowns/text-scale.htm` → emitted by user.php as:

    html { font-size: <scale>%; }

Three steps: **Compact 87.5% / Normal 100% / Comfortable 112.5%**. Defaults to
100, so a site that has never saved the field renders exactly as before.

**A percentage, never px.** 100% means the visitor's own browser default. Set
px on `html` and you override someone who raised their default for eyesight
reasons.

**IT STOPS AT 112.5% DELIBERATELY.** theme.css has 2477 rem declarations (only
104 are font-size — the rest is padding, margin, gap), so the whole layout
scales. But all **81 media queries are px** and the container max-widths are
hardcoded 540/720/960/1140. Neither follows the root. At 125% the navbar —
`navbar-expand-lg`, horizontal down to 992px — would be a quarter wider inside a
container that had not grown, and would run out of room before the hamburger
takes over. Adding bigger steps means converting the breakpoints to `em` and the
containers to `rem` first. That is its own piece of work.

### Body 14px → 16px

`bfs` default `0.875` → `1` in both non-custom branches. 16px is the current
floor for body copy; 14px is caption territory. The `fs-*` scale was already
anchored at `fs-9 = 1rem = 16px`, so this aligns body with the scale's own base
rather than sitting a step under it. Also makes `h6` (12px) a clean step below
body instead of an awkward 12-vs-14 near-miss — which is why **h6 was NOT
changed**: 123 uses on CCS, all small-label work.

### Fields and buttons 12px → 16px

`.form-control`, `.form-select`, `.form-label`, `.input-group-text` and
`--ccs-btn-font-size` overridden to `1rem` **in user.php, not theme.css** —
head.htm:175 loads theme.css first and user.php after, so these win on load
order and a theme update cannot wipe them.

iOS Safari zooms the page whenever a focused input is under 16px and does not
zoom back out. On checkout the viewport jumped on every field.
`.form-control-sm/-lg` use `calc(1.5em + …)` and follow on their own; `textarea`
is `font-size: inherit`; `.form-floating` is unused on both sites.

### `Base=-` printed `font-size: -rem`

All four Custom Typography dropdowns lead with `Base=-`. Picked, that stored the
literal `-` and user.php emitted `font-size: -rem` — invalid, dropped by the
browser, body silently inheriting. The dashboard card guarded `'-'` when
DISPLAYING the value; the CSS that renders never did. `bfs`, `bfw` and `blh` are
now guarded to the same defaults as the other branches.

### Size dropdowns relabeled — ROLE NAMES, NEVER PIXELS

`text-size.htm`, `text-size_sml.htm`, `hdng-sz_mods.htm`, `hero-hd-size.htm` now
read "Heading 3", "Body", "Caption - smallest". **Labels only — values untouched,
so no saved page moved.**

**Three generations of this file, two of them wrong:**

1. Originally "small"=fs-8, which is 1.333rem — half again LARGER than body.
   "Medium"=fs-6, an h3. Nothing matched body.
2. Then, briefly, pixel labels: "Body - 16px". Wrong the moment anyone touches
   Site Text Size, since `html { font-size: <scale>% }` moves every step — and
   wrong for any visitor who has raised their own browser font size.
3. Now role names, which are the only labels true at every scale.

**Do not put pixel values in these labels.** Nothing in this system is pixels;
the values are steps on a rem scale whose base is a percentage. A reference
table of the px equivalents at 100% lives in `text-size.htm`'s own header
comment, for developers, where it cannot mislead an editor.

The role names are accurate to the theme, not decorative: h1 is `fs-4`, h2
`fs-5`, h3 `fs-6`, h4 `fs-7`, h5 `fs-8`, body `fs-9`. Picking "Heading 3" really
does match an `h3`. The old `hdng-sz_mods` labels were off by three — it called
`fs-1` "H1" when an actual h1 is `fs-4`.

`hero-hd-size.htm` duplicates are **relabeled, not removed** — `display-1` and
`fs-1` are the same size, now labeled "Massive" and "Massive (same as above)".
Removing a value orphans any hero already saved with it; relabeling cannot.

### The hero's dead size dropdowns

`ccs_hro_headline_sz` and `ccs_hro_txtblk_frm_ttl_sz` were fed by
`heading-size.htm` (values `h1`…`p`) and concatenated as `fs-<value>`, emitting
`fs-h1` — a class that does not exist. Both repointed to `text-size.htm`. Their
stored values (`h1`, `p`) are not in the new list, so those two dropdowns show
unselected until re-saved; nothing renders differently, because the class never
existed. `<h1>` stays hardcoded — one per page, in the hero, is correct, and
Heading Level belongs to the content block below it.

Found by whoever wrote `content/router/img_cntn_blk.inc:98-104`, which names the
bug exactly. It sat documented and unfixed.

### Hardcoded px removed (valasports)

`hero_social.html` 15px → `fs-9`. `order-complete.php` eight inline sizes → `fs-9`
for headings, inherit for body, `fs-10` for fine print. Those 14px values had
been matching the old body size by pinning it, so they would have read small
once body moved.

**Left as px on purpose:** `order_email.htm` (email clients do not support rem)
and `product.php` (admin instruction panels, rendered by Couch's own stylesheet).

## 53. Hero bottom rail sat off the bottom edge — a `.row` cannot be absolutely positioned cleanly

`hero_container_single.html` wrapped the rail in

    <div class="row justify-content-center w-100 position-absolute bottom-0 start-0 m-0 z-3">
      <div class="col-<size> align-self-bottom position-relative px-0">

The nesting was already correct — the rail is a direct child of the hero
`<section>`, which is `position-relative`, so `bottom-0` had the right offset
parent. The problem was the `.row` itself: it is a flex container carrying
`--ccs-gutter-*` and negative margins, and those fight absolute positioning. The
rail rendered short of the section's bottom edge with a strip of hero background
showing beneath it.

**Now a plain absolutely-positioned wrapper**, `bottom-0 start-0 w-100
d-flex justify-content-center`, with the band centered inside it. Nothing between
the rail and the section's bottom edge.

**Width is a computed percentage, not a col- class.** `ccs_hro_bot_rail_ln_sz`
still stores `12` (Full) or `6` (Half) — every saved hero keeps working — but a
`col-` class only means anything inside a `.row`, which is what was removed. A
`cms:php` block turns the stored number into `width: N%`. Note the stored Full
value carries a trailing space (`'12 '`, from `opt_values='Full=12 |Half=6'`),
hence the `trim()`. Verified: `"12 "`→100%, `"6"`→50%, empty/out-of-range→100%.

### STILL OPEN — the clearance spacers are fixed amounts

Lines 3 and 20 add `pb-5 mb-4` and `mb-5 pb-4` to the hero content when the rail
is switched on. Those are hand-tuned constants that know nothing about how tall
the rail actually is. Since the rail's text size became configurable, a large
rail overlaps the content regardless of where the rail is pinned.

Two ways out, neither done:

- Make the section `d-flex flex-column` and let the rail be a real flow item at
  the end. Content can then never overlap it. Costs: the section becomes
  min-vh + rail height, so a `min-vh-100` hero exceeds the viewport.
- Keep it absolute and drive the content's bottom padding from the rail's own
  thickness and text-size settings. Cheaper, but still an approximation.

## 54. Hero bottom rail — REVERTED, do not try the flex-column approach again

**A flex-column rebuild of the hero was tried on 2026-09-07 and broke the layout
badly enough to be reverted immediately.** The section was given
`d-flex flex-column min-vh-<vht>`, the container `flex-grow-1 d-flex`, the
content row `flex-grow-1 w-100`, and the rail was turned into a real flow item.
It looked correct on paper and was not.

Do not repeat it without a way to render-test first. The theory below is sound
and the outcome was still wrong, which is the point worth remembering.

### The theory that did not survive contact

**The structure that was wrong.** `min-vh-<vht>` sat on the content row inside
`hero_container_single.html`, and the rail was `position-absolute bottom-0` over
the top of it. The rail therefore had no height of its own, and the content was
kept clear of it by hand-tuned `pb-5 mb-4` / `mb-5 pb-4` spacers — constants that
knew nothing about how tall the rail actually was. Once the rail's text size
became configurable (`fs-1` reaches 160px) those constants stopped being enough
and the two overlapped.

**THE CURRENT, WORKING STRUCTURE** — restored, and left alone:

    <section  p-0 has-parallax position-relative overflow-hidden>
      .container position-relative
        .row min-vh-<vht> … <if rail>pb-5 mb-4</if>
      rail:  position-absolute bottom-0 start-0 w-100 d-flex justify-content-center

The rail is an overlay pinned to the bottom. The `pb-5 mb-4` and
`mb-5 pb-4` spacers keep the content clear of it. They are fixed constants that
do not know the rail's height, so a large rail text still overlaps the content —
**that is a known, accepted limitation.** Raise `Height of Rail` to match a
larger text size rather than restructuring the hero.

### Two earlier fixes on the way here

**`col-` is not a class.** The rail's width was
`col-<cms:show ccs_hro_bot_rail_ln_sz />`, and that field had never been saved,
so it rendered `class="col-"`. Bootstrap has `.col` and `.col-6` but not `.col-`,
so the browser ignored it and the div fell back to `flex: 0 1 auto` —
shrink-to-fit. The rail hugged its own text instead of spanning the hero.
Width is now computed in PHP with a fallback to 12, so empty means full width.
**Check for `class="foo-<cms:show x />"` anywhere a value can be empty.**

**A `.row` cannot be positioned cleanly.** It is a flex container carrying
gutter variables and negative margins. The rail was wrapped in one purely to get
a `col-` width. Gone now.

### `hero_btm_rail.htm` had an order collision

The message and the SECOND row both carried `order='809'`. Couch has no
tie-break for two siblings on the same order, so the panel drew them in whatever
order it resolved — which is why the rows came out jumbled. The children were
also numbered 811-827 in one flat run across both rows, reading as if they were
siblings of each other.

Now: message `1`, rows `10` and `20`, children `1..n` **local to their own row**.
Child order only has to be unique within its own parent — keep the numbers small
and local so a collision cannot hide.

## 55. Dashboard: dark-mode text, Site Text Size, UI Playground, button overflow — both sites, 2026-09-07

Four fixes to the front-end dashboard in `globals.php`. All four live in one new
`<style>` block plus a handful of markup edits. Both sites are identical.

### The container now carries `.ccs-dash`

`<div class="container py-5 ccs-dash">`. Everything below is scoped to it, so
nothing here can reach the rest of the site.

### 55.1 Text was unreadable on dark themes — `.list-group`

theme.css line 6860:

    .list-group { --ccs-list-group-color: var(--ccs-gray-800); }

`--ccs-gray-800` is `#555555`, declared once in `:root` at line 36 and **never
redefined under `[data-bs-theme=dark]`**. Every list item in the dashboard also
carries `bg-transparent`, so on Scuro (`#222831`) or Notte (`#0B0C10`) that fixed
gray sat on a near-black card at roughly 2:1 contrast.

That is why the complaint was exactly the four cards that use `.list-group` —
Site Architecture, Global Data, Integrations & Legal, Store & Orders — and no
others.

Fix:

    .ccs-dash .list-group { --ccs-list-group-color: var(--ccs-body-color); }

`--ccs-body-color` is what user.php writes per theme, so the text now follows the
theme. On light themes it goes `#555` → `#292b2c`, marginally darker.

**Do not apply this site-wide.** A `.list-group` *without* `bg-transparent` still
gets `--ccs-list-group-bg-global`, which is `var(--ccs-emphasis-bg)` = `#fff` for
every theme (theme.css:234, and there is no dark override). Light text on a white
panel would be worse than the bug being fixed.

Everything else in the dashboard was already dark-aware and was left alone:
`.text-body-secondary` → `--ccs-secondary-color` (redefined at line 146),
`bg-body-tertiary` → `--ccs-tertiary-bg`, `alert-danger` → the `*-bg-subtle` /
`*-text-emphasis` pair (lines 159/167/175). Verified, not assumed.

### 55.2 Text bled out of buttons and badges — a SECOND `.btn` rule

`.btn` is defined twice in theme.css. The one everybody finds is line 3955. The
one that causes this is **line 23055**:

    .btn {
      --ccs-btn-border-width: var(--ccs-border-width);
      letter-spacing: 0.01em;
      white-space: nowrap;
      text-align: center;
    }

`white-space: nowrap` means a label wider than its button runs straight out
through the border instead of wrapping. `.badge` (line 6649) has the same, which
is what broke the multi-line "Only First Listed Will show." badge. Both got worse
when form controls and buttons moved to 1rem (§52).

    .ccs-dash .badge { white-space: normal; }
    .ccs-dash .btn   { white-space: normal; overflow-wrap: break-word; height: auto; }

`.ccs-dash .btn` is (0,2,0) against `.btn`'s (0,1,0), so it wins on specificity
and needs no `!important`. `height: auto` is there so nothing can pin a height;
the round 32px icon buttons keep theirs because an inline `style` beats any
stylesheet rule.

The worst offender also got `text-wrap py-2` on the element itself — `.text-wrap`
is `white-space: normal !important`, belt and braces — and a shorter label:

    Edit Text Highlight/Select Colors  ->  Edit Highlight & Selection Colors

### 55.3 Site Text Size moved to the Text settings

`ccs_gl_site_text_scale` was reachable from nowhere on the front end. It is now
first in the Typography card's `popup_edit` list, and the card header shows the
current value as a badge (`100%` when the field is blank).

The editable itself stays in `ccs_gl_master_theme_grp` at order 8. It is not
moved into `ccs_gl_site_site_fonts`: that group is `not_active=ccs_gl_site_fonts_custom`
and would hide the control unless Customize Fonts is ticked.

### 55.4 UI Playground removed

The whole `col-lg-4` card. Brand Assets and Theme & Colors went `col-lg-4` →
`col-lg-6` so the row still fills 12. Div balance re-checked: the dashboard
region opens and closes 131 `<div>`s and ends at depth 0 on both sites.

### Noted, not changed

`<div class="card-body" data-bs-theme="<cms:show ccs_gl_site_thm_opt />">` in the
Theme & Colors card emits values like `notte` and `primavera`. Only
`[data-bs-theme=dark]` and `[data-bs-theme=light]` exist in theme.css, so the
attribute matches nothing and the card simply inherits from `<html>`, which
head.htm already sets correctly. Inert, not harmful.

On CCS only, the Advanced Admin button was `btn-outline-secondary`, which
hardcodes `#292b2c` for color and border (theme.css:4281) and disappears on dark
themes. Changed to `btn-outline-primary` to match valasports.

## 56. Navbar: menu text invisible on dark themes, and two edit buttons that could not be clicked — both sites, 2026-09-08

### 56.1 "Default" navbar text color was hardcoded to the dark brand color

`assets/css/user.php` set, unconditionally:

    <cms:set ccs_site_nav_mnu_ttl_clr="dark" "global" />

which is read in exactly one place, the `.navbar-nav` rule:

    --ccs-nav-link-color: var(--ccs-<cms:show ccs_site_nav_mnu_ttl_clr />);

So every site, on every theme, emitted `var(--ccs-dark)` for top-level menu
items. On Notte and Scuro `--ccs-dark` is `#050505`: black text on a black bar.

It is not a fallback. No field is read there at all — it is a literal. The
per-navbar **Navbar Text Color** dropdown at Default emits nothing and falls
through to this value, which is why setting that dropdown to Default looked like
it was doing nothing.

Changed to `body-color`, so the rule emits `var(--ccs-body-color)` — the color
user.php already wrote for the active theme.

Why that is the right default: a navbar whose own Background Color is Default
renders `class="... bg- ..."`, which is not a real class, so the bar is
transparent and the **site background** is what sits behind the menu. Matching
the body color is therefore correct on light and dark alike. On light themes
this moves the menu from `#050505` to `#292b2c` — not a visible change.

A navbar given an explicit background that fights the body color still has the
Navbar Text Color dropdown to set it deliberately.

### 56.2 The palette and float-over edit buttons were dead

The inline-edit clusters are wrapped in

    <div class="position-absolute ..." style="width: max-content; pointer-events: none; ...">

so the empty space around the badges does not swallow clicks meant for the nav
underneath. `pointer-events` is inherited, so each badge has to switch it back
on. Every other badge in the codebase already does:

    style='width: 26px; height: 26px; pointer-events: auto;'

The two navbar color badges — `fa-palette` "Format dropdown menu colors" and
`fa-mouse-pointer` "Format floatover colors" — were the only ones missing it, in
both `nav_mdd.html` and `nav_sdd.html`. Added. The click lands on the span and
bubbles to the `popup_edit` anchor as usual; an ancestor's `pointer-events: none`
does not stop bubbling.

There are six such wrappers across the nav and footer themes. All were audited:
these four badges were the only ones affected, and a check now walks every
`pointer-events: none` div in the tree and confirms each `popup_edit` badge
inside it carries `auto`.

### Related, not changed

`ccs_site_nav_actv_clr` and `ccs_site_nav_hvr_clr` are interpolated the same way
— `var(--ccs-<value>)` — and emit `var(--ccs-)` when the field is blank. That is
an invalid reference, so the declaration resolves to `unset` and the color is
inherited rather than applied. Harmless in appearance (the item simply keeps the
inherited color) but it means "Default" for active and hover states is a no-op,
not a sensible value. Same one-line fix shape as 56.1 if it ever needs doing.

### Files

    assets/css/user.php
    snippets/pb_mods/pg_frame/nav/theme/nav_mdd.html
    snippets/pb_mods/pg_frame/nav/theme/nav_sdd.html

## 57. `var(--ccs-)` — the whole family, fixed at source — both sites, 2026-09-08

Follow-on from 56. `assets/css/user.php` builds token names by interpolation:

    var(--ccs-<cms:show SOME_SETTING />)

When the setting is on Default the stored value is empty, so the reference is to
a property named `--ccs-`, which nothing defines and which has no fallback. CSS
calls that **invalid at computed-value time**: the custom property is dropped and
any property reading it computes to `unset`.

What `unset` does depends on the property, which is why this hid for so long:

- **inherited** properties (`color`) fall back to `inherit`. Wrong value, but
  something readable, so nobody filed a bug.
- **non-inherited** properties (`background-color`) fall back to `initial`, which
  is `transparent`. Visibly broken.

And several of these carried `!important`, so an empty setting did not merely
fail to apply — it beat theme.css with a value that then evaporated.

### What was actually broken

- **Dropdown menus were transparent** whenever Navbar Dropdown Background was on
  Default. `.dropdown-menu { background-color: var(--ccs-) !important; }`.
- **Text selection did nothing.** Both Text Selection Colors default to blank, so
  an untouched site emitted `::selection { background-color: var(--ccs-)
  !important; color: var(--ccs-) !important; }`. Transparent highlight, and
  `!important` stopped the browser's own blue from coming back. Dragging across
  text looked like a no-op.
- **Active and hover menu colors were no-ops** on Default rather than sensible
  values.

### The fix

Emit nothing when the field is empty. theme.css already carries a real value for
every one of these, and every one of them is theme-aware:

    --ccs-navbar-active-color        rgba(emphasis, .9)        theme.css:5545
    --ccs-link-hover-color           #0062cc / #85bfff         :108 / :180
    --ccs-dropdown-color             var(--ccs-body-color)     :4927
    --ccs-dropdown-bg                var(--ccs-body-bg)        :4928
    --ccs-dropdown-link-color        var(--ccs-body-color)     :4936
    --ccs-dropdown-link-hover-color  var(--ccs-body-color)     :4937
    --ccs-dropdown-link-hover-bg     var(--ccs-tertiary-bg)    :4938

So Default now means "inherit the theme", which is what it always read as.

Twelve sites guarded: seven `:root` custom properties, the `.navbar` rule, and
the five `!important` rules (`.dropdown-menu`, `.dropdown-item-wrapper:hover`,
`.dropdown-item:hover`, `.nav-link.active`, `.nav-item:hover/.nav-link:hover`),
plus `::selection`. Where a rule had two independently-settable declarations
(`.dropdown-item:hover`, `::selection`) each one is tested separately, so setting
one color still leaves the other alone.

### Standing rule

**Never interpolate a stored value into the middle of a token name without
testing it first.** The failure is silent, and half the time it looks like
nothing happened. A checker for this is one grep:

    grep -n "var(--ccs-<cms:show" assets/css/user.php

Every hit must sit inside a `cms:if` that tests the same variable. The single
legitimate exception is `--ccs-nav-link-color`, whose source is a literal set
earlier in the file and can never be empty.

The same shape appears in the HTML as `text-<cms:show ... />`, `fs-<cms:show ... />`
and `bg-<cms:show ... />`. Those are safer by accident: an empty value produces
`fs-` or `bg-`, which is simply not a class, so the element falls through to
whatever it inherits. That is usually the desired outcome, which is exactly why
the CSS version was never suspected.

## 58. Fluid headings, and the Global Settings page rebuilt — both sites, 2026-09-08

### 58.1 Fluid headings

theme.css sizes every heading as one fixed number: `h1` is `4.2087rem` (67px) and
`.display-1` is `9.9688rem` (160px) at every viewport width. There is no media
query anywhere in that file that steps a heading down, no `clamp()` and no `vw` —
all three greps return zero. On a 375px phone a `display-1` headline fits about
two characters on a line.

**This is the framework's own default, switched off.** Stock Bootstrap 5.3 ships
RFS enabled:

    h1, .h1 { font-size: calc(1.375rem + 1.5vw); }
    @media (min-width: 1200px) { h1, .h1 { font-size: 2.5rem; } }

theme.css was compiled with `$enable-rfs: false`. 19 rules added to user.php —
`.fs-1`–`.fs-8`, `.display-1`–`.display-6`, `h1`–`h5` — each

    clamp( size at 375px , the line between , TODAY'S EXACT VALUE )

Anchors are 375px and 1200px. 1200 is Bootstrap's own RFS cap and this site's xl
breakpoint, so type stops growing exactly where the 1140px container does, and
**nothing changes above 1200px** — every maximum is byte-for-byte theme.css.

    display-1  160px -> 50px    h1  67px -> 30px    h2  51px -> 26px

`fs-9`, `fs-10` and `h6` are deliberately excluded: body and caption, 16px and
12px. Body must not shrink on a phone, and 16px is the iOS Safari zoom threshold.

**The rem term is positive on all eight steps, on purpose.** A pure-`vw` middle
term ignores a visitor who raised their browser's default font size without
zooming — `vw` does not respond to that. A positive rem component keeps their
setting in play. That constraint is why `display-1` floors at 50px, not 40: the
minimum has to be at least ~31% of the maximum or the intercept goes negative.

Composes with Site Text Size — that sets the root, these measure against it.

### 58.2 The settings page

Rebuilt from 8 inconsistent cards to 8 uniform ones. 643 lines became 577.

**One row primitive**, `.ccs-dash-row`: icon, label, current value, action — in
that order, every time. `min-width:0` on the middle column is what lets a long
value ellipsis instead of shoving the button out of the card; `flex:0 0 auto` on
the action is what stops the button shrinking or wrapping. The old page had five
different row treatments (tinted panels, list groups, swatch grids, specimen
tiles, a full-width status block).

**Cards are visible now.** They were `border-0` on a transparent background,
which on a dark theme is no card at all. Every color in the new style block
derives from `--ccs-body-color-rgb`, which user.php writes per theme, so a card
reads correctly on Notte, on Inverno, and on anything added later. No hex, no
fixed gray — that is what caused the unreadable text in the first place.

**Grid is `col-12 col-md-6 col-xxl-4`** — never four across. The old
`col-lg-6 col-xl-3` squeezed four cards into ~190px, wrapping "Site Architecture"
and "Integrations & Legal" onto two lines.

**Every row shows its current value**, which is the real usability win: the
theme name, the base font, the text scale, the sender address on order emails,
the form target, whether the consent bar is on, how many navbars are staged.

**The palette comes from one source.** The swatches were a 60-line per-theme
`cms:if` chain copied out of user.php — a second copy of the same table, which is
exactly how the font chain drifted. It now calls `ccs_theme_palette()` from the
dash's kfunctions.php, which `_tools/check-theme-palette.py` already diffs
against user.php. Customize Colors and Disable Theme Bg/Text are applied after,
since the function does not know about either.

**Six font specimens became one line.** The chain was also corrected against
user.php: autunno is Open Sans/Merriweather (the card said Bitter), inverno is
Roboto/Lora, and scuro and notte have no curated pairing at all — they fall to
stock Montserrat/Playfair Display. Adding branches for them would invent fonts
the stylesheet never loads.

### 58.3 Three settings that had no way in

- `ccs_gl_site_nav_mnu_grp` — Navigation Menu Settings. Real group, saved values
  driving live CSS, reachable only from the back end.
- `ccs_gl_site_ftr_grp` — Footer & Utility Bar Links. Added earlier this month
  and never wired to a button.
- `ccs_gl_site_desc` — the SEO meta description. In the Brand group, but the
  Brand popup listed the name and tagline fields and not this one.

All three now have rows.

### 58.4 A phantom field, and an unbuilt feature behind it

The old "Micro-Branding — Favicon & Social Share" button opened
`ccs_gl_site_favicon|ccs_gl_site_social_share`. **`ccs_gl_site_social_share` is
declared nowhere** — not in this template, not anywhere on either site. One
reference, zero definitions. The popup could only ever show the favicon.

The feature behind it is unbuilt as well: the Open Graph tags in
`snippets/pb_mods/pg_frame/head.htm` lines 82-87 are still boilerplate, so every
page ships `og:title` empty and `og:image` set to the literal string
`LINK TO THE IMAGE FILE`. Any link to the site shared anywhere shows nothing.

The row now says "Favicon" and edits only the favicon. Building the real thing —
an image editable plus real values in those six meta tags — is its own job and is
still outstanding.

### Files

    assets/css/user.php      fluid headings
    globals.php              page rebuilt

## 59. Type card: waterfall specimens restored, and letter spacing finally wired — both sites, 2026-09-08

Amends 58.2. The Type card is now `col-12` full width and Brand and Theme &
color are `col-12 col-md-6`, so the Look-and-feel row fills at every breakpoint
with no empty column.

### 59.1 All six waterfalls are back

Six tiles at `col-12 col-sm-6 col-xl-4` — Base/Body, Sans-Serif, Headings
(Serif), Monospace, Cursive/Script, Decorative — each the three-line
"Handgloves" waterfall with its family label and the resolved font name.
Compressing them to one line lost the thing they exist for.

The chain was rebuilt against user.php rather than copied from the old card,
and two things came out of that:

**Cursive and decorative are NOT the same in both branches.** With Apply Theme
Typography ON they are Dancing Script and Oswald (user.php:48-50). With it OFF
they are Bradley Hand and Sreda (its else branch). Monospace is SFMono-Regular
either way.

**Only four themes have a curated pairing** — primavera, estate, autunno,
inverno. dark, light, scuro and notte fall to stock Montserrat / Raleway /
Playfair Display. Defaults are set first and then overridden, so a branch that
sets three of six leaves the rest correct instead of blank.

### 59.2 The settings sentence

A real paragraph carrying all four base settings as inline style, under a label
naming them. A specimen shows a letterform; only a block of running text shows
what the numbers do to it.

**It said `0.875rem`.** user.php has used `1` since the typography overhaul, so
the preview printed a size the site had not used for weeks. Now `1`.

No arithmetic for Site Text Size: `font-size` is in rem, rem measures against the
root, and user.php sets the root to that percentage — so the browser does it and
the paragraph is the true rendered size. The label appends the scale when it is
not 100%.

### 59.3 Letter spacing was a dropdown attached to nothing

`ccs_gl_site_letter_space_cust` has been in Custom Typography with seven real em
values the whole time, and **user.php never read it** — there was no
`letter-spacing` declaration in the body rule at all. Picking "Wider" changed
nothing on the site, and the dashboard preview printed the chosen value as though
it had.

Wired now: `bls` alongside `bfs`/`bfw`/`blh`, read in the custom branch with the
same `'-'`/empty guard, set to `normal` in both non-custom branches, and emitted
as `--ccs-body-letter-spacing` plus a real `letter-spacing` on `body`.

`normal` rather than `0em` when unset — they render the same for most faces, but
`normal` lets a font's own kerning table stand while `0em` overrides it.

**Worth a sweep.** This is the third setting found this week that stored a value
nothing consumed, after the two unreachable groups in 58.3. A dropdown in the
admin is not evidence that a stylesheet reads it. The check is one grep per
field name across `assets/css/user.php` and `snippets/`.

## 60. Verifying the font chain, and a spelling convention — both sites, 2026-09-08

### 60.1 `_tools/check-font-chain.py`

The cursive/decorative question got answered by a tool, not by reading. It parses
the `cms:if` / `cms:else_if` / `cms:else` chain out of BOTH `assets/css/user.php`
and the Type card in `globals.php`, walks it for every combination of theme and
Apply Theme Typography, and diffs the six resolved families. Exit 1 on any
mismatch, so it can gate an upload.

Current state: **18 scenarios, 0 mismatches.**

    fonts OFF        cursive Bradley Hand    decorative Sreda
    theme typo ON    cursive Dancing Script  decorative Oswald   (all 8 themes)

The custom-fonts branch is checked separately by field name — both files read the
same six `ccs_gl_site_font_*_cust` fields and the same four metric fields.

**Two false alarms on the way, both in the checker, neither in the template.**
The first split the branch on `</cms:if>` and took the last part, which is the
text after the OUTER close, so it never saw the `fcs`/`fdc` sets that sit between
the inner and outer closes and apply to every theme. The second looked up
`body` instead of `dash_font_body` and got `?` for all 54 cells. A checker that
reports a mismatch is a claim about two files, and it is worth confirming the
checker itself before believing it.

Sibling of `check-theme-palette.py`. Run both after touching user.php.

### 60.2 American spellings

House style is American English: color, gray, behavior, center, labeled,
canceled, analyze, recognize, while, among. This applies to UI text, field
labels, descs and comments alike.

A sweep over the files written in this session changed 9 per site. Two traps
worth knowing:

**`aria-labelledby` is a real HTML attribute and must survive.** The rule matches
whole words only, refusing to match across a hyphen or underscore, which is
exactly what protects it. Prefixed forms (`uncoloured`, `recoloured`,
`relabelled`) and hyphen-adjacent ones (`colour-cascade`, `.link-COLOUR`) are
therefore missed by that rule and were fixed in a second, explicit pass.

**Nothing in the codebase's identifiers was ever British.** CSS is `color` and
`gray` already, and every custom property and field name follows. So the sweep
touches prose and labels only — verified by diffing every changed line that
contains `cms:`, `class=`, `--ccs-` or `var(`.

## 61. Footers and utility bars — audit and consolidation — both sites, 2026-09-08

24 files on valasports (4 footers, 6 utility bars, 2 navbars, plus embeds); CCS
carries three more blocks — `ubr_booking`, `ubr_travel`, `nav_xtr`.

### 61.1 What was already sound

Every embed opens with `<cms:editable type='hidden' name='section_caching'>never</cms:editable>`,
so the whole page frame is uncached by one consistent mechanism. Worth knowing:
that makes the `no_cache` reasoning in `nav_emb.htm` and `ftr_emb.htm`
belt-and-braces on top of something already off — `page-builder.php:284` only
writes the cache when `$caching!='never'`.

No `col-*` outside a row anywhere. Every block gates its edit controls on
`ccs_gl_edt_ok`. All 24 files balance.

### 61.2 Shared `order=` values are NOT bugs

Every embed reuses order numbers among siblings. `page.php:316` sorts
`ORDER BY k_group, k_order, id`, so ties break by field id — the order the
fields were first registered, i.e. declaration order. Everything renders as
written.

It is load-bearing by accident, though. `id` is assigned at first registration,
so a field added later gets a higher one and lands **after** both of its
order-mates rather than between them. That is exactly what went wrong in
`hero_btm_rail` (§ earlier). Give a new field its own order rather than copying
a neighbour's.

### 61.3 The real inconsistency: floating edit controls with nothing to anchor to

Not "two idioms" — one idiom, applied to blocks that could not support it.

Every block floats its edit controls with `position-absolute`: a cluster of 26px
badges top-left, and the yellow `btn-warning` swap pill at `top-50 end-0`. That
only works if an ancestor is positioned. **Eight of fifteen root elements had no
`position-relative`:**

    foot_links, ubr_directory              no position-relative, never sticky
                                           -> NEVER anchored
    ubr_ctct, ubr_menu, ubr_sentnc,
    ubr_shop, ubr_booking, ubr_travel      sticky-top only
                                           -> anchored ONLY while Sticky is ticked

`sticky-top` sets `position: sticky`, which does establish a containing block —
so those six worked whenever the admin happened to have Sticky on, and threw
their badges up the page when it was off. A conditional bug, which is why it
never got pinned down.

Fixed by adding `position-relative` to all eight roots. It is inert on a static
element — it establishes a containing block and changes nothing else — so there
is no visual risk. 12/12 anchored on valasports, 15/15 on CCS.

**Rule: a block that floats an edit control must carry `position-relative` on
its own root.** Never rely on `sticky-top` for it; that is an admin setting, not
a layout guarantee.

### 61.4 `ubr_shop` brought onto the badge idiom

The only file still using bracketed text links — `| [EDIT SHOP BAR] |` in
`bg-800 link-warning` — where the other eleven use the dark circle plus a glyph.
Same three popups, same field lists; only the control changed. `fa-cog` for the
block's own settings, `fa-link` for its link list, `fa-mouse-pointer` for the
site-wide hover color, matching `ubr_menu`.

The `container-fluid pt-2` that held them went with them: padding that existed
only for a logged-in admin, so the bar sat taller in edit mode than it ships.

### 61.5 The newsletter form

Wired correctly end to end: POST → duplicate check → `db_persist` into
`subscribers.php` → confirmation mail with an unsubscribe token. Token is
`bin2hex(random_bytes(16))` generated before the row is written; the URL carries
the token, never the address. `subscribers.php` declares all seven fields, every
field `db_persist` writes exists, and it is locked with `executable='0'` and
`access_level='7'` — no subscriber list reachable at a URL. Cache cannot poison
it: `section_caching='never'`, and POST bypasses the cache regardless.

Two fixes:

**The confirmation email had no From.** It read `ccs_gl_site_email`, declared
nowhere on either site — the third phantom field after `ccs_gl_site_social_share`
and this one's twin in `unsubscribe.php`. `functions.php:2365` ends at
`@mail($to,$subject,$text,'From: '.$from.$h)`, so the header shipped as
`From: ` and the MTA substituted its own sender — unaligned with SPF, the same
failure that put registration mail in spam. Now `K_EMAIL_FROM` with
`reply_to=K_EMAIL_TO`, matching `users/register.php` and `users/lost-password.php`.

Deliberately not an editable: an admin field there invites an address the SPF
record does not cover, breaking deliverability silently and site-wide.

**Button Color had no default.** The only rendered field in that footer without
one. Empty emits `btn-`, not a class, so the sign-up button fell back to bare
`.btn` — transparent, no border, `#6a6a6a` text. Now `default='primary'`.

### 61.6 `unsubscribe.php`

`ccs_gl_site_email` → `ccs_gl_hdqt_email` (Public Email, Contact Settings), so
the "email us" fallback names a real address instead of always dropping to the
vaguer branch.

Added an operational section to the header covering what a subscriber actually
does, how to build a per-subscriber link in a bulk send, and the three things
that matter in that loop: filter on `subscriber_status` (an unsubscribed row
keeps its token but has a cleared address), send from `K_EMAIL_FROM`, and set a
`List-Unsubscribe` header carrying the same URL so Gmail and Outlook show their
own one-click control — people who cannot find the link press the spam button
instead, and that costs the whole domain's reputation.

### Still open

`subscribers.php` needs to have been visited in admin once to register. The user
confirms it has been.


# ADDED 2026-10-17 — deployment to staging over GitHub Actions

## 62. The deploy workflow — what goes to the server, and what never does

`.github/workflows/deploy.yml`. Fires on every push to `main`, and a merge is a
push. Also runnable by hand from the Actions tab.

A `workflow_dispatch` workflow only appears in the Actions UI if the file exists
on the DEFAULT branch. Editing it on a feature branch and wondering where the
button went is the first mistake everyone makes with this.

### Three buckets, and where each one is decided

Every file is in exactly one of three states, decided in three different places.
Mixing them up is how credentials reach a web server.

1. **Not in GitHub at all** — decided by `.gitignore`. `ccs_dash/config.php`,
   `ccs_dash/uploads/` (1.1 GB), `ccs_dash/cache/`, the 63 MB `.sql` dump,
   `.vscode/`, and `assets/images|logos|videos|media/`. Media lives on the front
   end and is uploaded through the CMS, never through the repo.
2. **In GitHub, not on the site** — decided by the `EXCL` regex in the workflow.
   `.github/`, `.vscode/`, `_tools/`, `_merge_backup/`, `module-display/`,
   `ccs_dash/includes/ckeditor_old/`, the Font Awesome source trees,
   `snippets/pb_mods/DNU_header/`, every `*.md`, every `*.map`, root `*.txt`.
3. **Both** — everything else. 1,784 files as of 2026-10-21.

The file list comes from `git ls-files`, not a directory walk. Anything
gitignored therefore cannot reach the server by construction, even if someone
later adds a wrong pattern to `EXCL`. Keep that ordering.

### How it decides what to send

The server holds `.deploy-state`:

    sha=<commit of the last successful deploy>
    excl=<md5 of the EXCL regex that deploy used>

Each run reads it and asks GIT what changed since that commit. Git compares file
CONTENT, so nothing depends on timestamps.

Timestamps are a trap here, and this is why the obvious approach was rejected.
`actions/checkout` stamps every file with the checkout time, so everything always
looks new. The fix for that is restoring mtimes from commit dates - but if the
server does not honour `SITE UTIME` the remote keeps its upload time, and a file
whose commit is OLDER than the last deploy is then silently skipped. Merge a
branch on Friday that you committed on Monday, after a Wednesday deploy, and the
change never ships, with no error anywhere.

A FULL RESYNC happens when, and only when:

- `.deploy-state` is missing (first run, or deleted to force one)
- its `sha` is not in this repo's history (force-push, rewritten history)
- its `excl` hash differs from this run's - the EXCLUSION LIST CHANGED, and git
  would not report newly-included files as edits
- the state file is in the old bare-sha format with no `excl` line

It keys on the EXCLUSION LIST, not on "did `deploy.yml` change". Editing a
comment or a timeout costs nothing. **If you change the manifest LOGIC without
changing `EXCL`, force a resync by hand:** delete `.deploy-state` in cPanel and
run the workflow.

`.deploy-state` is written only AFTER every transfer succeeds, so a failed deploy
leaves the recorded commit where it was and the next run re-sends everything
outstanding. A failure can never make the next run think files shipped.

### Two transports, and why

**Incremental uses `curl`** - one file per invocation, `--ftp-create-dirs`.
**A full resync uses `lftp mirror`.**

`mirror` compares a source tree to a target tree, which means enumerating the
remote side first. With 1,784 files on the server that took **9m30s to deliver
one changed file** - the transfer itself was two log lines at the very end. The
same one-file deploy via curl takes **under a second**. For a full resync the
enumeration does not matter, because everything is being sent anyway.

`mirror` also logs `Removing old file` before each transfer: it deletes the
remote file, then writes the new one. On a live page that is a window where the
file 404s. curl overwrites in place.

Credentials go to curl on STDIN via `-K -`, so the password never appears in the
process list or on disk. lftp uses `--env-password` for the same reason. Do not
put them in the URL - the FTP username contains an `@`.

### No empty files, ever

**A zero-byte file drops the whole FTP session on this host.** Three failed
deploys were caused by this before it was spotted; each died on the first empty
file its step reached.

33 empty `embed/*.htm` stubs caused it. They could not simply be skipped -
`tags.php:573`:

    $html = @file_get_contents( $filepath );
    if( $html===FALSE ){ return 'Error embedding file: ' . ... }
    if( $html ){ ...parse... }

A MISSING target prints `Error embedding file:` onto the page. An EMPTY one
returns `''`, which is falsy, so it renders nothing. Empty stubs are correct
couch - they are placeholders for tiles whose field cluster is not built yet,
and `blocks/` does `cms:embed` them. They have to exist. They just cannot be
transferred.

Each now carries a `cms:ignore` block naming its tile, label, template and the
`blocks/` line that embeds it. The parser strips it, so page output is unchanged.

The build step hard-fails if the payload contains any zero-byte file. **If a
deploy fails that check, FILL the file - do not delete it and do not exclude it.**

### No spaces in filenames

`snippets/pb_mods/transns/embed/trnsmp_c2a _full.htm` carried a space for months.
`mirror` handled it silently, so nothing ever broke and nothing ever flagged it -
right up until the move to per-file `curl` made it a live hazard. Renamed
2026-10-21.

The only tracked path with a space now is `Copilote Rules.txt`, which never
deploys. Keep it that way: no spaces, no quotes, no non-ASCII in any path under
version control.

### Deletions do not propagate

A file deleted from the repo STAYS ON THE SERVER. Nothing here carries
`--delete` and nothing should: `ccs_dash/uploads` is 1.1 GB and exists in no
repository. The run prints a warning listing what you deleted; remove those by
hand in cPanel. The same applies to renames - git sees a rename as a delete plus
an add, and only the add ships.

### config.php is placed by hand, once per environment

Gitignored, so a fresh deploy lands on CouchCMS's setup screen:

    "config.php" not found. Perhaps you forgot to rename the
    "config.example.php" file to "config.php" after editing it?

That message means the deploy worked. Put `config.php` in that environment's
`ccs_dash/` with its OWN database - staging must not point at production - and
its own `K_SITE_URL`, e.g. `https://staging.customcmssystems.com/`.

### Branching — main is the deployed state

- Work on a branch. Pushing to a branch deploys nothing and runs nothing.
- Merge into `main`. That merge deploys.
- `main` is always what is on staging. If the two disagree, `main` is right and
  the server is stale - never the other way round.

A merge touching only `*.md`, `.vscode/`, `_tools/`, `_merge_backup/`,
`module-display/` or a root `*.txt` does not spend a deploy. GitHub skips the run
only when EVERY changed file matches, so a commit mixing a doc and a template
still deploys.

`concurrency` is set with `cancel-in-progress: false`. Killing a half-finished
upload would leave the server mixed, so a second merge queues behind the first
rather than racing it on one FTP account.

### Still open

`ssl:verify-certificate no` / curl `-k`. This is parity, not a regression: the
old action's `security` input defaulted to `loose`, documented as "allow
connection even when the domain is not certificate", so the certificate was never
verified. Worth turning on to see whether HostGator's cert passes.

No production workflow exists yet. When one is written it needs its own FTP
account, its own secrets, and its own `config.php` - it must not share staging's.
