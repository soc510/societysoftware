#!/usr/bin/env bash
#
# Runs ON THE SERVER, piped in over SSH by .github/workflows/deploy.yml.
# Can also be run by hand:  bash deploy.sh /path/to/site production
#
set -euo pipefail

DEPLOY_PATH="${1:?deploy path required}"
BRANCH="${2:?branch required}"

cd "$DEPLOY_PATH"

if [ ! -d .git ]; then
	echo "ERROR: $DEPLOY_PATH is not a git checkout."
	echo "Run the one-time setup in docs/DEPLOYMENT.md first."
	exit 1
fi

# --------------------------------------------------------------------------
# Preserve anything edited directly on the server.
#
# This codebase carries a lot of 'SomeFile.php 05-05-2025' style copies, which
# means people have historically edited live files in place. git reset --hard
# below throws such edits away, so snapshot them as a patch first - recovering
# from a patch is easy, recovering from nothing is not.
# --------------------------------------------------------------------------
# --ignore-cr-at-eol: .gitattributes asks for `eol=lf` but many files were
# committed with CRLF, so git reports them as modified on every checkout,
# forever. Without this filter every deploy writes a ~700KB patch of pure
# line-ending noise, and a genuine edit would be invisible inside it.
#
# `git diff HEAD` rather than plain `git diff` so staged-but-uncommitted edits
# are caught too - those would otherwise be destroyed by the reset below with
# nothing saved.
CHANGED_FILES="$(git diff HEAD --ignore-cr-at-eol --name-only)"

if [ -n "$CHANGED_FILES" ]; then
	mkdir -p ../deploy-backups
	PATCH="../deploy-backups/server-edits-$(date +%Y%m%d-%H%M%S).patch"
	# Patch only the genuinely-changed files, so it stays reviewable.
	echo "$CHANGED_FILES" | xargs -d '\n' git diff HEAD -- > "$PATCH"
	echo "WARNING: server had uncommitted edits to tracked files:"
	echo "$CHANGED_FILES" | sed 's/^/           /'
	echo "         Saved to $(cd .. && pwd)/deploy-backups/$(basename "$PATCH")"
	echo "         Apply with: git apply <patch>"
fi

# --------------------------------------------------------------------------
# NEVER add `git clean` here.
#
# Untracked files under app/webroot are live customer data - payment scanner
# images, member photos, uploaded documents. They are not in the repo, so
# git clean would permanently delete them. `git reset --hard` alone leaves
# untracked files alone, which is exactly what we want.
# --------------------------------------------------------------------------
git fetch --prune origin
git reset --hard "origin/$BRANCH"

# core.php and database.php are gitignored, so they live only on the server.
# A missing one means the site will fatal - catch it here, not in the browser.
for f in app/Config/core.php app/Config/database.php; do
	if [ ! -f "$f" ]; then
		echo "ERROR: $f is missing on this server."
		echo "It is gitignored by design - create it from the template and redeploy."
		exit 1
	fi
done

# app/tmp is gitignored, so a fresh checkout has no tmp tree at all.
mkdir -p app/tmp/cache/models app/tmp/cache/persistent app/tmp/cache/views \
         app/tmp/logs app/tmp/sessions app/tmp/tests
chmod -R 775 app/tmp

# With debug = 0 CakePHP 2 caches the table schema on disk. If a deploy adds or
# renames a column and this cache is stale, every query on that table fails with
# a confusing "column not found". Clearing it each deploy avoids that entirely.
rm -rf app/tmp/cache/models/* app/tmp/cache/persistent/*

echo "Deployed $(git rev-parse --short HEAD) ($BRANCH) to $DEPLOY_PATH"
