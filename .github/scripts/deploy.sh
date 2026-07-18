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
if [ -n "$(git status --porcelain --untracked-files=no)" ]; then
	mkdir -p ../deploy-backups
	PATCH="../deploy-backups/server-edits-$(date +%Y%m%d-%H%M%S).patch"
	# `git diff HEAD`, not plain `git diff`: the check above also matches staged
	# changes, and plain `git diff` omits those - which would write an empty
	# patch and let the reset destroy them anyway.
	git diff HEAD > "$PATCH"
	echo "WARNING: server had uncommitted edits to tracked files."
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
