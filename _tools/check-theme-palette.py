#!/usr/bin/env python3
"""
check-theme-palette.py - diff the theme colour table against its two homes.

The palette lives in two files, and it has to agree in both:

  assets/css/user.php            the cms:set chain that paints the site when
                                 "Customize Site Colors" is unchecked
  <dash>/addons/kfunctions.php   ccs_theme_palette(), which seeds the fourteen
                                 custom colour fields when that box is ticked

Nothing in Couch links them, so a colour edited in one and not the other means
ticking the box quietly changes the site's colours. This diffs them.

    python3 _tools/check-theme-palette.py

Exit 0 when they agree, 1 when they do not.
"""
import os, re, sys

THEMES = ['light', 'dark', 'primavera', 'estate', 'autunno', 'inverno', 'scuro', 'notte']
KEYS   = ['primary', 'secondary', 'tertiary', 'quaternary', 'success', 'info',
          'warning', 'danger', 'light', 'dark', 'white', 'black', 'body_clr', 'body_bg']

SET   = re.compile(r"<cms:set\s+([A-Za-z_][\w-]*)\s*=\s*[\"']?(#[0-9A-Fa-f]{3,8})[\"']?", re.I)
TAG   = re.compile(r"<cms:if\b|<cms:else_if\b[^>]*?/>|<cms:else\s*/>|</cms:if>", re.I)
THEME_TEST = re.compile(r"ccs_gl_site_thm_opt\s*==\s*'([a-z]+)'", re.I)


def norm(h):
    h = h.strip().strip('"\'').lower()
    if re.fullmatch(r'#[0-9a-f]{3}', h):
        return '#' + ''.join(c * 2 for c in h[1:])
    return h


def split_branches(txt):
    """One cms:if chain -> [(theme_or_None, body)], nested chains left intact."""
    out, depth, start, theme = [], 0, None, None
    for m in TAG.finditer(txt):
        t = m.group(0).lower()
        if t.startswith('<cms:if'):
            if depth == 0:
                start = m.end()
                theme = None
                tail = txt[m.start():m.start() + 200]
                mt = THEME_TEST.search(tail)
                theme = mt.group(1) if mt else None
            depth += 1
        elif t.startswith('</cms:if'):
            depth -= 1
            if depth == 0:
                out.append((theme, txt[start:m.start()]))
                return out, txt[m.end():]
        elif depth == 1:
            out.append((theme, txt[start:m.start()]))
            start = m.end()
            mt = THEME_TEST.search(m.group(0))
            theme = mt.group(1) if mt else None
    return out, ''


def from_user_php(path):
    s = open(path, encoding='utf-8').read()
    i = s.find('ccs_gl_site_custom_color_opt')
    i = s.find('<cms:else />', i)            # the branch used when custom is off
    j = s.find('<cms:set gray-100', i)       # chain ends where the grey ramp starts
    if i < 0 or j < 0:
        sys.exit('check-theme-palette: could not locate the colour chain in %s' % path)
    body = s[i + len('<cms:else />'):j]

    k = body.lower().find('<cms:if')
    base = {m.group(1): norm(m.group(2)) for m in SET.finditer(body[:k])}
    branches, _ = split_branches(body[k:])

    named = {t: b for t, b in branches if t}
    fallback = next((b for t, b in branches if not t), '')

    out = {}
    for theme in THEMES:
        p = dict(base)
        txt = named.get(theme, fallback)
        nested, _ = split_branches(txt[txt.lower().find('<cms:if'):]) if '<cms:if' in txt.lower() else ([], '')
        flat = TAG.sub('', txt) if not nested else txt[:txt.lower().find('<cms:if')]
        p.update({m.group(1): norm(m.group(2)) for m in SET.finditer(flat)})
        for nt, nb in nested:
            # nested tests inside a branch: apply only the one this theme matches,
            # plus any nested test with no theme condition (e.g. thm_clr_opt)
            if nt in (None, theme):
                p.update({m.group(1): norm(m.group(2)) for m in SET.finditer(nb)})
        out[theme] = {k2: p.get(k2, '') for k2 in KEYS}
    return out


def from_kfunctions(path):
    """Read ccs_theme_palette()'s table without running PHP - the function is
    all literals, so it parses. Keeps this runnable anywhere python is."""
    src = open(path, encoding='utf-8').read()
    m = re.search(r'function ccs_theme_palette\(.*?\n\}', src, re.S)
    if not m:
        sys.exit('check-theme-palette: ccs_theme_palette() not found in %s' % path)
    body = m.group(0)

    assign = re.compile(r"\$p\[\s*'([a-z_]+)'\s*\]\s*=\s*'(#[0-9a-fA-F]{3,8})'")
    kv     = re.compile(r"'([a-z_]+)'\s*=>\s*'(#[0-9a-fA-F]{3,8})'")

    first_case = body.find('switch(')
    base = {k: norm(v) for k, v in kv.findall(body[:first_case])}

    cases = list(re.finditer(r"case\s+'([a-z]+)'\s*:", body))
    per = {}
    for n, c in enumerate(cases):
        end = cases[n + 1].start() if n + 1 < len(cases) else len(body)
        per[c.group(1)] = {k: norm(v) for k, v in assign.findall(body[c.end():end])}

    out = {}
    for t in THEMES:
        p = dict(base)
        p.update(per.get(t, {}))
        out[t] = {k: p.get(k, '') for k in KEYS}
    return out


def main():
    root = os.getcwd()
    dash = next((d for d in ('ccs_vsp_dash', 'ccs_dash') if os.path.isdir(os.path.join(root, d))), None)
    if not dash:
        sys.exit('check-theme-palette: run me from the site root (no ccs_vsp_dash/ or ccs_dash/ here)')

    css = from_user_php(os.path.join(root, 'assets/css/user.php'))
    php = from_kfunctions(os.path.join(root, dash, 'addons/kfunctions.php'))

    bad = 0
    for t in THEMES:
        for k in KEYS:
            a, b = css[t].get(k, ''), php[t].get(k, '')
            if a != b:
                bad += 1
                print('  %-10s %-11s user.php=%-9s kfunctions=%-9s' % (t, k, a or '(unset)', b or '(unset)'))
    if bad:
        print('\n%d mismatch(es). Seeded colours would not match what the theme renders.' % bad)
        return 1
    print('theme palette OK - user.php and kfunctions.php agree (%d themes x %d colours)'
          % (len(THEMES), len(KEYS)))
    return 0


sys.exit(main())
