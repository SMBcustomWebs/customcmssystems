#!/usr/bin/env python3
"""cms tag balance checker for CouchCMS templates.

WHY NOT A REGEX. Two Couch idioms defeat one:

    <cms:pages custom_field="tok==<cms:show t />" limit='1'>     nested, quoted
    <cms:if a ge '7' && "<cms:not " <cms:get_session 'x' /> " />" >   unquoted

A pattern that stops at the first '>' lands inside an attribute; adding quote
handling still trips on the second form, where the inner tag's '/>' makes the
outer tag look self-closing. Both produced phantom defects in working files -
and a checker that invents defects is worse than none, because it teaches you
to ignore its output.

So this scans. It walks the file tracking quote state and nesting depth, finds
each tag's real end, and decides self-closing from the character before it.
Content nested inside a tag is skipped wholesale, which is correct: an inner tag
inside an attribute is balanced within that attribute or it is not a tag at all.

Line numbers are the REAL line numbers - cms:ignore blocks are blanked but their
newlines are kept, so the report points where you actually need to look.

Usage:  python3 _tools/cms-balance.py FILE [FILE...]
Exit 0 = every file clean.
"""
import re, sys

NEVER_OPENS = {'else', 'else_if'}          # never open a scope
NAME = re.compile(r'[a-z_]+')

def tag_end(src, i):
    """i points at the '<' of a cms tag. Return the index of its closing '>',
       or -1. Depth-aware, so a nested tag inside the attributes is handled."""
    depth, q, n = 0, None, len(src)
    while i < n:
        c = src[i]
        if q:
            if c == q:
                q = None
            i += 1
            continue
        if c in '"\'':
            q = c
            i += 1
            continue
        if src.startswith('</cms:', i):
            depth += 1
            i += 6
            continue
        if src.startswith('<cms:', i):
            depth += 1
            i += 5
            continue
        if c == '>':
            depth -= 1
            if depth == 0:
                return i
            i += 1
            continue
        i += 1
    return -1

def check(path):
    src = open(path, 'rb').read().decode('utf-8', 'replace').replace('\r\n', '\n')

    # cms:ignore is a RUNTIME tag - Couch PARSES its body first and only then
    # discards the output, so a cms: tag written inside comment prose becomes a
    # real node. A BALANCED example is harmless and is used deliberately for
    # documentation all over this repo. An ORPHAN CLOSING tag is not: it closes
    # whatever the parser had open and the page dies with "closing tag has no
    # matching opening tag". Only the orphan case is reported, so the balanced
    # documentation examples stay quiet.
    problems = []
    for m in re.finditer(r'<cms:ignore>(.*?)</cms:ignore>', src, flags=re.S):
        body = m.group(1)
        for t in re.finditer(r'</cms:([a-zA-Z_]+)', body):
            name = t.group(1)
            if not re.search(r'<cms:' + name + r'\b', body[:t.start()]):
                line = src.count('\n', 0, m.start(1) + t.start()) + 1
                problems.append(
                    'line %d: orphan </cms:%s> inside cms:ignore - Couch parses '
                    'ignore bodies, so write it without angle brackets'
                    % (line, name))

    src = re.sub(r'<cms:ignore>.*?</cms:ignore>',
                 lambda m: '\n' * m.group(0).count('\n'), src, flags=re.S)

    stack = []
    i, n = 0, len(src)
    while i < n:
        if not src.startswith('<cms:', i) and not src.startswith('</cms:', i):
            i += 1
            continue

        closing = src.startswith('</cms:', i)
        j = i + (6 if closing else 5)
        m = NAME.match(src, j)
        if not m:
            i += 1
            continue
        name = m.group(0)
        line = src.count('\n', 0, i) + 1

        if closing:
            end = src.find('>', m.end())
            if not stack:
                problems.append('line %d: </cms:%s> with nothing open' % (line, name))
            elif stack[-1][0] != name:
                problems.append('line %d: </cms:%s> closes <cms:%s> opened on line %d'
                                % (line, name, stack[-1][0], stack[-1][1]))
            else:
                stack.pop()
            i = (end + 1) if end != -1 else (i + 1)
            continue

        end = tag_end(src, i)
        if end == -1:
            problems.append('line %d: <cms:%s ...> is never terminated' % (line, name))
            break
        self_closing = src[end - 1] == '/'
        if not self_closing and name not in NEVER_OPENS:
            stack.append((name, line))
        i = end + 1

    for name, line in stack:
        problems.append('line %d: <cms:%s> never closed' % (line, name))
    return problems

if __name__ == '__main__':
    bad = 0
    for f in sys.argv[1:]:
        p = check(f)
        print('%-58s %s' % (f, 'OK' if not p else 'PROBLEM'))
        for x in p:
            print('    ' + x)
            bad = 1
    sys.exit(bad)
