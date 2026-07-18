# Deployment

Auto-deploy for this CakePHP 2 app using GitHub Actions over SSH.

## Branches

| Branch       | Deploys to       | Trigger                    |
| ------------ | ---------------- | -------------------------- |
| `dev`        | Testing subdomain | Automatic on push          |
| `production` | Live site         | Automatic on push          |
| `main`       | *nothing*        | Integration branch only    |

Intended flow:

```
feature branch  ->  dev         (test on the subdomain)
dev             ->  main        (merge + review)
main            ->  production  (release to live)
```

Live only moves when someone deliberately merges into `production`.

## How it works

`.github/workflows/deploy.yml` connects to the server over SSH and pipes in
`.github/scripts/deploy.sh`, which runs `git fetch` + `git reset --hard` in the
site directory, fixes `app/tmp` permissions, and clears CakePHP's schema cache.
No files are uploaded from the runner — **the server pulls from GitHub itself**.

---

## One-time setup

### 1. Two SSH key pairs

This trips people up: there are **two** separate hops, so **two** key pairs.

```
GitHub Actions  --[key A]-->  Server  --[key B]-->  GitHub repo
```

**Key A — Actions logging into the server**

Generate anywhere (your laptop):

```bash
ssh-keygen -t ed25519 -f actions_key -C "github-actions-deploy" -N ""
```

- `actions_key` (private) → GitHub secret `SSH_PRIVATE_KEY` (step 3)
- `actions_key.pub` (public) → append to the server's `~/.ssh/authorized_keys`

In CloudPanel: **Site → SSH/FTP** to get shell access, then:

```bash
mkdir -p ~/.ssh && chmod 700 ~/.ssh
echo "ssh-ed25519 AAAA...  github-actions-deploy" >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

**Key B — the server pulling from GitHub**

The repo is private, so the server needs its own credential. Generate it
**on the server**:

```bash
ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519 -C "server-deploy-key" -N ""
cat ~/.ssh/id_ed25519.pub
```

Add that public key to GitHub: **Repo → Settings → Deploy keys → Add deploy
key**. Leave "Allow write access" **unchecked** — the server only ever reads.

Verify from the server:

```bash
ssh -T git@github.com    # should say: Hi easylogicstechnology/... successfully authenticated
```

### 2. Turn the existing site directory into a git checkout

The site currently got there by unzipping `archive.zip`, so it is not yet a git
repo. Convert it **without losing uploads or config**.

Production path (from CloudPanel):

```
/home/easylogicstechnology/htdocs/easylogicstechnology.com
```

```bash
cd /home/easylogicstechnology/htdocs/easylogicstechnology.com

# 1. Back up first. This is a live site.
tar czf ~/backup-before-git-$(date +%F).tar.gz .

# 2. Point the directory at GitHub
git init
git remote add origin git@github.com:easylogicstechnology/easylogicstechnology.git
git fetch origin production

# 3. Attach to the branch. -f overwrites tracked files with the repo version;
#    untracked files (uploads) are left exactly where they are.
git checkout -f -B production origin/production

# 4. Confirm nothing important vanished
git status
ls app/webroot/img/payment_scanner | head
```

Repeat on the testing server with `dev` instead of `production`.

> `app/Config/core.php`, `app/Config/database.php` and `app/tmp/` are gitignored,
> so the checkout never touches them. The server keeps its own copies with the
> real database credentials — that is intended, do not commit them.

### 3. GitHub Environments and secrets

**Repo → Settings → Environments** → create `testing` and `production`.

On the `production` environment, add yourself under **Required reviewers** so
live deploys need one approval click.

Add per environment:

| Type     | Name              | Example                                                    |
| -------- | ----------------- | ---------------------------------------------------------- |
| Secret   | `SSH_HOST`        | `88.222.245.214`                                            |
| Secret   | `SSH_USER`        | `easylogicstechnology`                                      |
| Secret   | `SSH_PRIVATE_KEY` | contents of `actions_key` (whole file, including header)     |
| Secret   | `DEPLOY_PATH`     | `/home/easylogicstechnology/htdocs/easylogicstechnology.com` |
| Variable | `SITE_URL`        | `https://easylogicstechnology.com`                           |
| Variable | `SSH_PORT`        | only if not `22`                                             |

The `testing` environment gets the same names with the subdomain's own host,
user and path.

### 4. First deploy

```bash
git push origin dev
```

Watch it under the **Actions** tab. Once testing looks right:

```bash
git checkout main && git merge dev && git push origin main
git checkout production && git merge main && git push origin production
```

---

## Daily use

Deploy to testing:

```bash
git push origin dev
```

Release to live:

```bash
git checkout production
git merge main
git push origin production
```

Or use **Actions → Deploy → Run workflow** and pick the environment. The branch
is derived from the environment, so choosing `production` always ships the
`production` branch — never whatever branch you were on.

## Rollback

```bash
# on the server
cd /home/easylogicstechnology/htdocs/easylogicstechnology.com
git log --oneline -10
git reset --hard <good-commit>
rm -rf app/tmp/cache/models/* app/tmp/cache/persistent/*
```

Or revert the commit and push — the workflow redeploys automatically:

```bash
git revert <bad-commit> && git push origin production
```

## Safety rules

**Never run `git clean` in the site directory.** Untracked files under
`app/webroot` are live customer data — payment scanner images, member photos,
uploaded documents. They are not in the repo, so `git clean` deletes them
permanently. `git reset --hard` alone is safe: it only touches tracked files.

**Do not edit files directly on the server.** The deploy overwrites tracked
files. `deploy.sh` saves a patch of any such edits to `../deploy-backups/`
before overwriting, but that is a safety net, not a workflow — the change is
still not in git and will be lost on the next release.

**Never commit `app/Config/database.php` or `core.php`.** They hold live
credentials and are gitignored for that reason.

## Troubleshooting

**`Permission denied (publickey)` in the Actions log**
Key A is wrong. Check `SSH_PRIVATE_KEY` contains the *entire* file including
`-----BEGIN OPENSSH PRIVATE KEY-----`, and that the matching `.pub` is in the
server's `~/.ssh/authorized_keys`.

**`Repository not found` / `Permission denied` during `git fetch` on the server**
Key B is wrong. Run `ssh -T git@github.com` on the server and confirm the deploy
key is registered on the repo.

**Deploy succeeds but the site 500s**
Usually a stale schema cache or `app/tmp` permissions. Check
`app/tmp/logs/error.log` on the server. `deploy.sh` clears the cache and sets
`775` already, but a different web-server user can still break writes.

**"Column not found" right after a deploy that changed the schema**
Stale model cache — clear it:

```bash
rm -rf app/tmp/cache/models/* app/tmp/cache/persistent/*
```

**Deploy did nothing**
`main` does not auto-deploy by design. Push to `dev` or `production`.

## Housekeeping

`archive.zip` is still sitting on the server (~594 MB inside the site directory,
plus ~1.3 GB in `htdocs/`). Those are leftovers from the original manual upload
and are no longer needed once git is the source of truth. Delete them to free
about 1.9 GB.
