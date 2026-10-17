#!/usr/bin/env python3
"""
check-embeds.py - every cms:embed must point at a file that exists.

WHY THIS EXISTS
cms:embed does NOT fail loudly on a missing file. tags.php does
@file_get_contents() and, when that returns false, RETURNS THE STRING
"Error embedding file: <path>" as the tag's output. Rendering carries on.

So a missing snippet is not an error, it is a paragraph of text in the middle
of the page - and every variable that snippet was supposed to set is simply
never set. Downstream, an empty masterpage makes cms:pages fall back to the
CURRENT template, match nothing, and skip its body in silence. That is how a
missing utils/item_tpl.htm turns into stock that never gets deducted.

It bites hardest when porting between sites built from the same framework:
the CALLERS get copied and the CALLEES do not. Run this after any port, and
before trusting a site that has just been forked.

    python3 _tools/check-embeds.py          # scan the site
    python3 _tools/check-embeds.py --quiet  # only print problems

Exit 0 = every reference resolves. Exit 1 = at least one does not.
"""

import os, re, sys

# Couch resolves cms:embed against K_SNIPPETS_DIR, not the calling file.
SNIPPETS_DIR = 'snippets'

# Directories that are not ours to police: Couch core, third-party vendors,
# the design reference, Dreamweaver droppings, and anything staged for delete.
SKIP_DIRS = {
    'ccs_dash', 'ccs_vsp_dash', 'couch', 'vendors', 'module-display',
    'node_modules', '.git', '_notes', '_to_delete',
}

SCAN_EXT = ('.php', '.htm', '.html')

# <cms:embed 'utils/thing.htm' />  and  <cms:embed "utils/thing.htm" />
# Only literal paths can be checked; a computed one is reported separately.
EMBED_LITERAL = re.compile(r"""<cms:embed\s+['"]([^'"<>]+?)['"]""", re.I)
EMBED_ANY     = re.compile(r"""<cms:embed\b""", re.I)


def walk(root):
    for dirpath, dirnames, filenames in os.walk(root):
        dirnames[:] = [d for d in dirnames if d not in SKIP_DIRS]
        for name in filenames:
            if name.lower().endswith(SCAN_EXT):
                yield os.path.join(dirpath, name)


def main():
    quiet = '--quiet' in sys.argv
    root = os.getcwd()
    base = os.path.join(root, SNIPPETS_DIR)

    if not os.path.isdir(base):
        print("no %s/ directory here - run this from the site root" % SNIPPETS_DIR)
        return 2

    broken, checked, computed = [], 0, 0

    for path in walk(root):
        try:
            with open(path, encoding='utf-8', errors='replace') as fh:
                text = fh.read()
        except OSError:
            continue

        rel = os.path.relpath(path, root).replace('\\', '/')

        literals = EMBED_LITERAL.findall(text)
        # An embed whose path is built at runtime cannot be verified here.
        computed += len(EMBED_ANY.findall(text)) - len(literals)

        for target in literals:
            target = target.strip().lstrip('/\\')
            checked += 1
            if not os.path.isfile(os.path.join(base, target)):
                line = text[:text.index(target)].count('\n') + 1
                broken.append((rel, line, target))

    if broken:
        print("BROKEN EMBEDS - the file referenced does not exist:\n")
        width = max(len(r) for r, _, _ in broken)
        for rel, line, target in sorted(set(broken)):
            print("  %-*s :%-5d  %s/%s" % (width, rel, line, SNIPPETS_DIR, target))
        print("\n%d broken, %d checked." % (len(broken), checked))
        print("Remember: these do NOT raise an error at runtime. They print")
        print("'Error embedding file' into the page and leave their variables unset.")
        return 1

    if not quiet:
        print("%d cms:embed references, all resolve." % checked)
        if computed:
            print("%d computed embed path(s) skipped - cannot be checked statically." % computed)
    return 0


if __name__ == '__main__':
    sys.exit(main())
