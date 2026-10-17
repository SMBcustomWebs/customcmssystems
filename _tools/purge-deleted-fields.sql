-- ============================================================================
--  purge-deleted-fields.sql
--  Hard-delete Couch fields that are already soft-deleted (deleted = 1).
--
--  WHY THESE ROWS EXIST
--  When you remove a <cms:editable> from a template, Couch does NOT drop it.
--  ccs_dash/functions.php:255-278 does one of two things:
--
--    - type = group | message | hidden  -> hard-deleted immediately
--                                          (they hold no data)
--    - everything else                  -> UPDATE couch_fields SET deleted='1'
--
--  The soft-deleted row keeps its stored values, which is deliberate: put the
--  editable back in the template and the old content returns. Super-admins keep
--  seeing those fields in the admin form (inline_ex.php hides them only from
--  access levels below super-admin), which is the clutter you want gone.
--
--  THIS SCRIPT IS IRREVERSIBLE. It removes the field definitions AND every
--  stored value for them, on every page of the template. Once run, putting the
--  editable back gives you an empty field.
--
--  Tables are unprefixed here because config.php:55 sets
--  K_DB_TABLES_PREFIX to ''. If that ever changes, prefix each table name.
--
--  BACK UP FIRST. From a shell:
--     mysqldump -u root -p YOUR_DB couch_fields couch_data_text couch_data_numeric > backup_fields.sql
--  Or dump the whole database if you would rather not think about it.
--
--  Run steps 1 and 2 first and READ THE OUTPUT. Only then uncomment step 4.
-- ============================================================================


-- ---------------------------------------------------------------------------
-- STEP 1 - What is soft-deleted, and where does it live?
-- Run this on its own. Nothing is modified.
-- ---------------------------------------------------------------------------
SELECT  t.name          AS template,
        f.id            AS field_id,
        f.name          AS field_name,
        f.label         AS field_label,
        f.k_type        AS field_type
FROM    couch_fields f
JOIN    couch_templates t ON t.id = f.template_id
WHERE   f.deleted = 1
ORDER BY t.name, f.name;


-- ---------------------------------------------------------------------------
-- STEP 2 - How much stored data would go with them?
-- If a count here is large, that field still holds real content on real pages.
-- ---------------------------------------------------------------------------
SELECT  t.name                          AS template,
        f.name                          AS field_name,
        (SELECT COUNT(*) FROM couch_data_text    d WHERE d.field_id = f.id) AS text_rows,
        (SELECT COUNT(*) FROM couch_data_numeric n WHERE n.field_id = f.id) AS numeric_rows
FROM    couch_fields f
JOIN    couch_templates t ON t.id = f.template_id
WHERE   f.deleted = 1
ORDER BY text_rows + numeric_rows DESC, t.name, f.name;


-- ---------------------------------------------------------------------------
-- STEP 3 - Narrow the scope (RECOMMENDED)
--
-- Purging everything at once is the riskiest way to do this. Pick one template,
-- confirm the result, then move on. Replace the template name below and use the
-- same WHERE clause in step 4.
-- ---------------------------------------------------------------------------
SELECT  t.name AS template, f.id AS field_id, f.name AS field_name, f.k_type
FROM    couch_fields f
JOIN    couch_templates t ON t.id = f.template_id
WHERE   f.deleted = 1
  AND   t.name = 'globals.php'          -- <<< CHANGE ME
ORDER BY f.name;


-- ---------------------------------------------------------------------------
-- STEP 4 - The purge.  DESTRUCTIVE.  Uncomment only after reviewing 1-3.
--
-- Order matters and mirrors what Couch itself does: data rows first (they are
-- keyed by field_id and would otherwise be orphaned), field definitions last.
--
-- Couch picks ONE data table per field based on its search_type. Deleting from
-- both is harmless - the other simply matches nothing.
--
-- Keep the t.name filter for a single template, or delete those two lines to
-- purge every soft-deleted field in the install.
-- ---------------------------------------------------------------------------

/*
START TRANSACTION;

-- 4a. text data
DELETE d
FROM   couch_data_text d
JOIN   couch_fields f    ON f.id = d.field_id
JOIN   couch_templates t ON t.id = f.template_id
WHERE  f.deleted = 1
  AND  t.name = 'globals.php';          -- <<< CHANGE ME (or remove these 2 lines)

-- 4b. numeric data
DELETE n
FROM   couch_data_numeric n
JOIN   couch_fields f    ON f.id = n.field_id
JOIN   couch_templates t ON t.id = f.template_id
WHERE  f.deleted = 1
  AND  t.name = 'globals.php';          -- <<< CHANGE ME (or remove these 2 lines)

-- 4c. the field definitions
DELETE f
FROM   couch_fields f
JOIN   couch_templates t ON t.id = f.template_id
WHERE  f.deleted = 1
  AND  t.name = 'globals.php';          -- <<< CHANGE ME (or remove these 2 lines)

-- Check the row counts above look sane, THEN:
COMMIT;
-- If anything looks wrong instead:
-- ROLLBACK;
*/


-- ---------------------------------------------------------------------------
-- STEP 5 - Verify
-- ---------------------------------------------------------------------------
SELECT COUNT(*) AS remaining_soft_deleted FROM couch_fields WHERE deleted = 1;

-- Orphaned data rows: should be 0 both before and after. Anything else means
-- data exists for a field_id that no longer has a definition.
SELECT COUNT(*) AS orphan_text
FROM   couch_data_text d
LEFT   JOIN couch_fields f ON f.id = d.field_id
WHERE  f.id IS NULL;

SELECT COUNT(*) AS orphan_numeric
FROM   couch_data_numeric n
LEFT   JOIN couch_fields f ON f.id = n.field_id
WHERE  f.id IS NULL;


-- ============================================================================
--  AFTERWARDS
--
--  - Log out of the admin and back in. Couch caches field definitions per
--    request, but a stale admin tab can still POST against a field id that no
--    longer exists.
--  - Open and save each affected template's page once. That re-syncs
--    couch_fulltext, which is keyed by page_id rather than field_id and so is
--    not touched by the deletes above.
--  - InnoDB only: the transaction in step 4 does nothing on MyISAM tables. Check
--    with:  SHOW TABLE STATUS WHERE Name = 'couch_fields';
--    If it says MyISAM, the deletes commit as they run and the backup is your
--    only way back.
-- ============================================================================


-- ============================================================================
--  STEP 6 - WHOLE DATABASE, NO FILTERING
--  For a test site with a backup, where "deleted = 1" is reason enough to go.
--  Two ways. Pick one.
-- ============================================================================

-- ---------------------------------------------------------------------------
-- 6A. BROWSE-AND-TICK
-- Single table, no join, so phpMyAdmin gives you row checkboxes and a Delete
-- button. Tick what you want, delete, then run 6C to clear the orphans left
-- behind - phpMyAdmin only deletes from couch_fields, never the data tables.
-- (Adding a template-name column here via a JOIN would remove the checkboxes,
-- which is why this query is deliberately plain.)
-- ---------------------------------------------------------------------------
SELECT * FROM couch_fields WHERE deleted = 1 ORDER BY template_id, name;


-- ---------------------------------------------------------------------------
-- 6B. ONE SHOT - everything, data included, in the right order
-- Data first: once the field row is gone there is nothing left to join to.
-- ---------------------------------------------------------------------------
/*
START TRANSACTION;

DELETE d FROM couch_data_text    d JOIN couch_fields f ON f.id = d.field_id WHERE f.deleted = 1;
DELETE n FROM couch_data_numeric n JOIN couch_fields f ON f.id = n.field_id WHERE f.deleted = 1;
DELETE   FROM couch_fields WHERE deleted = 1;

COMMIT;
*/


-- ---------------------------------------------------------------------------
-- 6C. ORPHAN CLEANUP - run after 6A
-- Removes data rows whose field definition no longer exists. Safe to run at any
-- time; it can only match rows that are already unreachable.
-- ---------------------------------------------------------------------------
/*
DELETE d FROM couch_data_text    d LEFT JOIN couch_fields f ON f.id = d.field_id WHERE f.id IS NULL;
DELETE n FROM couch_data_numeric n LEFT JOIN couch_fields f ON f.id = n.field_id WHERE f.id IS NULL;
*/
