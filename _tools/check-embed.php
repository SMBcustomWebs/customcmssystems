<?php
/**
 * check-embed.php - static validator for PageBuilder embed files.
 *
 * Catches the class of mistake that produces NO error in Couch and no visible
 * symptom until a user hits the exact combination that exposes it:
 *
 *   1. cms:func nesting     - a func must sit at the SAME nesting level as its
 *                             slave (top level -> top-level slaves; inside a
 *                             group -> slaves in that group; inside a row ->
 *                             slaves in that row). At the wrong level it simply
 *                             never resolves and the slave is always visible.
 *   2. Document order       - controller above func, func above slave.
 *   3. Nested order         - parent order is the primary sort key, child order
 *                             the secondary. A slave never sees a controller in
 *                             a later container, however low its own number.
 *   4. Return vocabulary    - only 1|true|yes|hide and 0|false|no|show compile.
 *                             Anything else is emitted as RAW JAVASCRIPT into
 *                             the generated function, silently breaking it.
 *   5. Unsupported tags     - only cms:if / cms:else / cms:else_if / cms:not /
 *                             cms:arr_val_exists / cms:is compile inside a func.
 *   6. Messages inside rows - type=message in a row fights the column layout.
 *   7. Duplicate names, unbalanced tags, missing dropdown files.
 *
 * Usage (Git Bash, CMD or PowerShell):
 *     php _tools/check-embed.php snippets/pb_mods/nav_swpr/embed/prd_nswp_sld_blk.htm
 *     php _tools/check-embed.php snippets/pb_mods/hero/embed/*.htm
 *     php _tools/check-embed.php --all
 *
 * A glob spanning EVERY block cannot be written out in this comment. The
 * sequence that would appear in the middle of it closes a docblock, so the
 * example ends the comment early and everything below it is parsed as PHP -
 * which is exactly what happened here, and why this file could not run at
 * all until 2026-09-04. Use --all for that case.
 *
 * Exit code 0 = clean, 1 = problems found.
 */

if (php_sapi_name() !== 'cli') {
    header('HTTP/1.1 403 Forbidden');
    exit('CLI only.');
}

$ROOT = dirname(__DIR__);

// Only these compile to JS - conditional-fields.php:411-414.
$ALLOWED_TAGS = ['if', 'else', 'else_if', 'not', 'arr_val_exists', 'is', 'ignore'];
// conditional-fields.php:399-408. Trimmed and lowercased before comparison.
$ALLOWED_RETURNS = ['1', 'true', 'yes', 'hide', '0', 'false', 'no', 'show'];

$args = array_slice($argv, 1);
if (!$args) {
    fwrite(STDERR, "usage: php _tools/check-embed.php <embed.htm> [more...] | --all\n");
    exit(1);
}

$files = [];
if ($args[0] === '--all') {
    foreach (glob($ROOT . '/snippets/pb_mods/*/embed/*.htm') as $f) $files[] = $f;
} else {
    foreach ($args as $a) {
        $p = (strpos($a, ':') === 1 || $a[0] === '/') ? $a : $ROOT . '/' . ltrim($a, '/\\');
        foreach ((glob($p) ?: [$p]) as $g) $files[] = $g;
    }
}
if (!$files) { fwrite(STDERR, "no files matched\n"); exit(1); }

$totalProblems = 0;

foreach ($files as $file) {
    $rel = str_replace($ROOT . DIRECTORY_SEPARATOR, '', $file);
    $rel = str_replace('\\', '/', $rel);

    if (!is_file($file)) { report($rel, ["cannot read file"]); $totalProblems++; continue; }
    $src = file_get_contents($file);

    $problems = [];
    $nodes    = [];   // flat list in document order
    $stack    = [];   // open containers

    // Tokenise editables and funcs, tracking nesting. The attribute pattern
    // tolerates quoted '>' inside values.
    $tok = '/<cms:editable\b((?:[^>\'"]|\'[^\']*\'|"[^"]*")*?)(\/?)>'
         . '|<\/cms:editable>'
         . '|<cms:func\b((?:[^>\'"]|\'[^\']*\'|"[^"]*")*?)(\/?)>'
         . '|<\/cms:func>/s';

    preg_match_all($tok, $src, $ms, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

    foreach ($ms as $m) {
        $whole = $m[0][0];
        $pos   = $m[0][1];

        if (strpos($whole, '</cms:editable') === 0 || strpos($whole, '</cms:func') === 0) {
            array_pop($stack);
            continue;
        }

        $isFunc = (strpos($whole, '<cms:func') === 0);
        $attrs  = $isFunc ? ($m[3][0] ?? '') : ($m[1][0] ?? '');
        $self   = $isFunc ? ($m[4][0] ?? '') : ($m[2][0] ?? '');

        // Nearest GROUP or ROW ancestor. Every non-self-closing editable goes on
        // the stack - including type='message' and type='text', which carry
        // content and therefore emit their own </cms:editable>. Popping on those
        // while only pushing containers would close the real container early and
        // hand every later field the wrong parent.
        $parent = 'ROOT';
        for ($k = count($stack) - 1; $k >= 0; $k--) {
            if ($stack[$k][1] === 'group' || $stack[$k][1] === 'row') { $parent = $stack[$k][0]; break; }
        }

        if ($isFunc) {
            $name = attr($attrs, '_into') ?: '?';
            preg_match_all('/(\w+)\s*=\s*(?:\'\'|"")/', $attrs, $pm);
            $nodes[] = [
                'kind' => 'func', 'name' => $name, 'pos' => $pos,
                'parent' => $parent, 'params' => $pm[1],
                'line' => lineOf($src, $pos),
            ];
            if ($self !== '/') $stack[] = [$name, 'func'];
            continue;
        }

        $type = attr($attrs, 'type') ?: '?';
        $name = attr($attrs, 'name') ?: '?';
        preg_match('/not_active\s*=\s*(\w+)/', $attrs, $na);

        $nodes[] = [
            'kind'   => 'ed',
            'type'   => $type,
            'name'   => $name,
            'pos'    => $pos,
            'order'  => attr($attrs, 'order'),
            'slave'  => $na[1] ?? null,
            'parent' => $parent,
            'opts'   => attr($attrs, 'opt_values'),
            'line'   => lineOf($src, $pos),
        ];

        // push EVERY non-self-closing editable so its own closing tag pops itself
        if ($self !== '/') $stack[] = [$name, $type];
    }

    if ($stack) $problems[] = 'unclosed container(s): ' . implode(', ', array_column($stack, 0));

    $byName = []; $funcs = [];
    foreach ($nodes as $n) {
        if ($n['kind'] === 'ed')   $byName[$n['name']] = $n;
        if ($n['kind'] === 'func') $funcs[$n['name']]  = $n;
    }

    // ---- 1/2/3: conditional chains -------------------------------------
    foreach ($nodes as $n) {
        if ($n['kind'] !== 'ed' || !$n['slave']) continue;
        $f = $funcs[$n['slave']] ?? null;

        if (!$f) {
            $problems[] = "line {$n['line']}: {$n['name']} -> func '{$n['slave']}' is not defined";
            continue;
        }
        if ($f['pos'] > $n['pos']) {
            $problems[] = "line {$n['line']}: {$n['name']} -> func '{$n['slave']}' is BELOW its slave (must be above)";
        }
        if ($f['parent'] !== $n['parent']) {
            $problems[] = "line {$n['line']}: {$n['name']} -> func is in '{$f['parent']}' but slave is in '{$n['parent']}'"
                        . " - a func must sit at the same nesting level as its slave, or it never resolves";
        }
        foreach ($f['params'] as $ctl) {
            if (!isset($byName[$ctl])) {
                $problems[] = "line {$f['line']}: func {$f['name']} -> controller '{$ctl}' is not an editable in this file";
            } elseif ($byName[$ctl]['pos'] > $f['pos']) {
                $problems[] = "line {$f['line']}: func {$f['name']} -> controller '{$ctl}' is BELOW the func (must be above)";
            } else {
                // nested order: container order dominates the child's own order
                $co = orderPath($byName[$ctl], $byName);
                $so = orderPath($n, $byName);
                if ($co !== null && $so !== null && cmpPath($co, $so) > 0) {
                    $problems[] = "line {$n['line']}: {$n['name']} -> controller '{$ctl}' resolves at ("
                                . implode(',', $co) . ") but the slave resolves at (" . implode(',', $so) . "). "
                                . "Parent order is the primary sort key, so the slave is evaluated before its controller.";
                }
            }
        }
    }

    // ---- 4/5: func bodies ----------------------------------------------
    if (preg_match_all('/<cms:func\b[^>]*>(.*?)<\/cms:func>/s', $src, $fm, PREG_OFFSET_CAPTURE)) {
        foreach ($fm[1] as $i => $bodyCap) {
            $body = $bodyCap[0];
            $line = lineOf($src, $bodyCap[1]);

            // Strip cms:ignore blocks first. Their prose is not a return value,
            // and leaving it in would flag every documented func as broken.
            $body = preg_replace('/<cms:ignore>.*?<\/cms:ignore>/s', '', $body);
            $body = preg_replace('/<!--.*?-->/s', '', $body);

            if (preg_match_all('/<cms:(\w+)/', $body, $tm)) {
                foreach (array_unique($tm[1]) as $t) {
                    if (!in_array($t, $ALLOWED_TAGS, true)) {
                        $problems[] = "line {$line}: func body uses <cms:{$t}> - not one of the tags that compile "
                                    . "to JS; in this install it produces nothing rather than an error";
                    }
                }
            }
            // every bare text run is a return value
            foreach (preg_split('/<[^>]*>/', $body) as $chunk) {
                $t = strtolower(trim($chunk));
                if ($t === '') continue;
                if (!in_array($t, $ALLOWED_RETURNS, true)) {
                    $problems[] = "line {$line}: func returns '{$t}' - only "
                                . implode(' | ', $ALLOWED_RETURNS)
                                . " compile. Anything else is emitted as raw JavaScript and silently breaks the condition. "
                                . "House style is show / hide.";
                }
            }
        }
    }

    // ---- 6: messages inside rows ---------------------------------------
    foreach ($nodes as $n) {
        if ($n['kind'] === 'ed' && $n['type'] === 'message' && isset($byName[$n['parent']])
            && $byName[$n['parent']]['type'] === 'row') {
            $problems[] = "line {$n['line']}: message '{$n['name']}' is inside row '{$n['parent']}' - messages fight the column layout";
        }
    }

    // ---- 7: duplicates, dropdown files, tag balance ---------------------
    $seen = [];
    foreach ($nodes as $n) {
        if ($n['kind'] !== 'ed') continue;
        if (isset($seen[$n['name']])) $problems[] = "line {$n['line']}: duplicate field name '{$n['name']}' (first at line {$seen[$n['name']]})";
        else $seen[$n['name']] = $n['line'];
    }

    foreach ($nodes as $n) {
        if ($n['kind'] !== 'ed' || !$n['opts']) continue;
        if (!preg_match('/\.htm$/i', $n['opts'])) continue;
        if (!is_file($ROOT . '/snippets/' . $n['opts'])) {
            $problems[] = "line {$n['line']}: {$n['name']} -> opt_values file 'snippets/{$n['opts']}' not found";
        }
    }

    $counts = [];
    if (preg_match_all('/<cms:(\w+)((?:[^>\'"]|\'[^\']*\'|"[^"]*")*?)(\/?)>|<\/cms:(\w+)>/s', $src, $bm, PREG_SET_ORDER)) {
        foreach ($bm as $b) {
            if (!empty($b[4]))            $counts[$b[4]] = ($counts[$b[4]] ?? 0) - 1;
            elseif (($b[3] ?? '') !== '/') $counts[$b[1]] = ($counts[$b[1]] ?? 0) + 1;
        }
    }
    foreach ($counts as $tag => $n) {
        if ($n !== 0) $problems[] = "unbalanced <cms:{$tag}> ({$n} unclosed)";
    }

    report($rel, $problems, count($nodes));
    $totalProblems += count($problems);
}

echo "\n" . ($totalProblems === 0
    ? "All clear.\n"
    : "{$totalProblems} problem(s) found.\n");

exit($totalProblems === 0 ? 0 : 1);


// ---------------------------------------------------------------- helpers

function attr($attrs, $key) {
    if (preg_match("/{$key}\s*=\s*'([^']*)'/", $attrs, $m)) return $m[1];
    if (preg_match("/{$key}\s*=\s*\"([^\"]*)\"/", $attrs, $m)) return $m[1];
    return null;
}

function lineOf($src, $pos) {
    return substr_count(substr($src, 0, $pos), "\n") + 1;
}

/**
 * Full order path, outermost container first, the field's own order last.
 * Couch sorts nested: parent order is the primary key, child order secondary,
 * so a field at order 20 inside a group at order 5 resolves as (5,20) and
 * beats one at order 15 inside a group at order 10, which is (10,15).
 * Comparing whole paths is the only way to model that correctly at any depth.
 */
function orderPath($node, $byName) {
    $path = [];
    $cur  = $node;
    $seen = 0;
    while (true) {
        $path[] = ($cur['order'] === null) ? 0 : (int)$cur['order'];
        if ($cur['parent'] === 'ROOT' || !isset($byName[$cur['parent']])) break;
        $cur = $byName[$cur['parent']];
        if (++$seen > 20) break;   // cycle guard
    }
    return array_reverse($path);
}

/** Lexicographic compare of two order paths. */
function cmpPath($a, $b) {
    $n = max(count($a), count($b));
    for ($i = 0; $i < $n; $i++) {
        $x = $a[$i] ?? -1;
        $y = $b[$i] ?? -1;
        if ($x !== $y) return $x <=> $y;
    }
    return 0;
}

function report($rel, $problems, $fieldCount = null) {
    echo "\n" . str_repeat('=', 70) . "\n{$rel}\n" . str_repeat('=', 70) . "\n";
    if ($fieldCount !== null) echo "{$fieldCount} nodes parsed\n";
    if (!$problems) { echo "  OK\n"; return; }
    foreach ($problems as $p) echo "  FAIL  {$p}\n";
}
