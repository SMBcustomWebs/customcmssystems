#!/usr/bin/env python3
"""
_tools/check-font-chain.py

Drift guard for the font chain. Sibling of check-theme-palette.py.

The font families live in TWO places: assets/css/user.php decides what the site
actually loads, and the Type card on globals.php decides what the specimens
claim. Two copies of one table is how this drifted before - the dashboard named
Bitter for autunno when the site loads Merriweather, it named Dancing Script and
Oswald in a branch where the site uses Bradley Hand and Sreda, and it printed
0.875rem as the base size for weeks after user.php had moved to 1.

This parses the cms:if / cms:else_if / cms:else chain out of both files, walks it
for every combination of theme and Apply Theme Typography, and diffs the six
resolved families. Run it after touching either file.

Exit 1 on any mismatch, so it can gate an upload.
"""
import re, sys

TOK = re.compile(r"<cms:if\s+(.*?)\s*/?>|<cms:else_if\s+(.*?)\s*/>|<cms:else\s*/>|</cms:if>|"
                 r"<cms:set\s+([A-Za-z_]\w*)\s*=\s*(\"[^\"]*\"|'[^']*')\s*(?:\"global\"|scope='global')?\s*/>",
                 re.S)

def parse(src):
    """-> list of nodes: ('set',k,v) | ('if',[(cond,body),...],else_body)"""
    pos, out, stack = 0, [], []
    cur = out
    chain = []            # stack of (branches, pending_cond, pending_body, parent)
    for m in TOK.finditer(src):
        if m.group(0).startswith('<cms:if'):
            chain.append((cur, [], m.group(1), []))
            cur = chain[-1][3]
        elif m.group(0).startswith('<cms:else_if'):
            parent, branches, cond, body = chain[-1]
            branches.append((cond, body))
            chain[-1] = (parent, branches, m.group(2), [])
            cur = chain[-1][3]
        elif m.group(0).startswith('<cms:else'):
            parent, branches, cond, body = chain[-1]
            branches.append((cond, body))
            chain[-1] = (parent, branches, None, [])
            cur = chain[-1][3]
        elif m.group(0) == '</cms:if>':
            parent, branches, cond, body = chain.pop()
            if cond is None: els = body
            else: branches.append((cond, body)); els = []
            parent.append(('if', branches, els))
            cur = chain[-1][3] if chain else out
        else:
            cur.append(('set', m.group(3), m.group(4)[1:-1]))
    return out

def ev(cond, env):
    m = re.match(r"([\w]+)\s*==\s*'([^']*)'", cond)
    if m: return env.get(m.group(1),'') == m.group(2)
    return bool(env.get(cond.strip()))

def run(nodes, env, out):
    for n in nodes:
        if n[0]=='set': out[n[1]] = n[2]
        else:
            for cond, body in n[1]:
                if ev(cond, env): run(body, env, out); break
            else: run(n[2], env, out)

def fam(v):                       # "'Lato', sans-serif" / '"Sreda", Arvo' -> Lato / Sreda
    return re.split(r'[,]', v.strip())[0].strip().strip('"').strip("'")

# ---- the two sources ----
u = open('assets/css/user.php',encoding='utf-8').read()
u = re.sub(r'<cms:ignore>.*?</cms:ignore>','',u,flags=re.S)
u = u[u.index('<cms:if ccs_gl_site_custom_font_opt'):]
u = u[:u.index('<cms:set ccs_gl_site_nav_logo_txt_clr')]

g = open('globals.php',encoding='utf-8').read()
g = re.sub(r'<cms:ignore>.*?</cms:ignore>','',g,flags=re.S)
g = g[g.index("<cms:set dash_font_body='Montserrat'"):]
g = g[:g.index('<div class="ccs-dash-row">')]

U, G = parse(u), parse(g)
MAP = [('bff','body'),('fss','sans'),('fsf','serif'),('fmn','mono'),('fcs','cursive'),('fdc','decor')]
THEMES=['light','dark','primavera','estate','autunno','inverno','scuro','notte']

bad=0
print(f"{'Apply Theme Typography':24}{'family':10}{'site (user.php)':22}{'dashboard':22}")
print('-'*78)
for mode in ('off','on'):
    for t in (THEMES if mode=='on' else ['-']):
        env={'ccs_gl_site_thm_opt':t,'ccs_gl_site_custom_font_opt':'',
             'ccs_gl_site_thm_typo_opt':'1' if mode=='on' else ''}
        a={}; run(U,env,a)
        b={}; run(G,env,b)
        for uk,dk in MAP:
            x,y = fam(a.get(uk,'?')), fam(b.get('dash_font_'+dk,'?'))
            if x!=y: bad+=1
            if dk in ('cursive','decor') or x!=y:
                lbl = t if mode=='on' else 'OFF (site standard)'
                print(f"{lbl:24}{dk:10}{x:22}{y:22}{'' if x==y else '  <<< MISMATCH'}")
print('-'*78)
print('MISMATCHES:', bad)

sys.exit(1 if bad else 0)
