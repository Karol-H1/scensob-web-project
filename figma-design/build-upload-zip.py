"""Package the three sites for upload to a PHP host.

Produces scensob-upload-to-htdocs.zip, whose contents go straight into the
host's web root. The three sites stay as sibling folders because every
cross-site link between them is relative (../it/, ../transport-delivery/);
flattening one of them to the root would break those.

Deliberately excluded:
  config.php          live database credentials -- created on the server
  standalone/         local preview builds, not for a real server
  build-standalone.py dev tooling
  database/           the SQL is served separately, see scensob-all-tables.sql
  *.md                developer notes, no reason to publish them
"""
import io, os, re, zipfile

SRC   = os.path.dirname(os.path.abspath(__file__))
OUT   = os.path.join(SRC, 'scensob-upload-to-htdocs.zip')
SITES = ['group', 'it', 'transport-delivery']
BACKSLASH = chr(92)

EXCLUDE_FILES = {'config.php', 'build-standalone.py'}
EXCLUDE_DIRS  = {'standalone', 'database', '__pycache__', '.git'}
EXCLUDE_EXT   = {'.md', '.pyc'}

def live_credentials():
    """Values that must never appear in a file leaving this machine.

    Read out of the local config.php files at run time rather than written in
    here. Listing them literally would put the very credentials this check
    exists to protect into the repository -- which is exactly what happened
    before, and it went public.
    """
    found = []
    for site in SITES:
        path = os.path.join(SRC, site, 'config.php')
        if not os.path.exists(path):
            continue
        with io.open(path, encoding='utf-8') as fh:
            text = fh.read()
        for value in re.findall(r"'(?:username|password)'\s*=>\s*'([^']+)'", text):
            if value and value != 'CHANGE_ME':
                found.append(value.encode('utf-8'))
    return found


SECRETS = live_credentials()
if not SECRETS:
    print('WARNING: no config.php found, so the archive cannot be checked for '
          'live credentials. Verify by hand before sending it anywhere.')

if os.path.exists(OUT):
    os.remove(OUT)

with zipfile.ZipFile(OUT, 'w', zipfile.ZIP_DEFLATED) as z:
    z.write(os.path.join(SRC, 'deploy-index.html'), 'index.html')
    for site in SITES:
        for dirpath, dirnames, filenames in os.walk(os.path.join(SRC, site)):
            dirnames[:] = [d for d in dirnames if d not in EXCLUDE_DIRS]
            for fn in sorted(filenames):
                if fn in EXCLUDE_FILES or os.path.splitext(fn)[1] in EXCLUDE_EXT:
                    continue
                full = os.path.join(dirpath, fn)
                z.write(full, os.path.relpath(full, SRC).replace(os.sep, '/'))

# Verify rather than assume.
with zipfile.ZipFile(OUT) as z:
    assert z.testzip() is None, 'CRC failure'
    names = z.namelist()
    assert not [n for n in names if BACKSLASH in n], 'backslash in archive path'
    assert not [n for n in names if n.endswith('config.php')], 'config.php leaked'
    for n in names:
        if n.endswith(('.php', '.html', '.js', '.css')):
            body = z.read(n)
            for secret in SECRETS:
                assert secret not in body, 'credential leaked inside %s' % n

print('%d files, CRC OK, no credentials' % len(names))
print('%.1f KB -> %s' % (os.path.getsize(OUT) / 1024, OUT))
