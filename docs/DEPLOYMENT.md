# Deployment

Auto-deploy for this CakePHP 2 app using GitHub Actions.

One repository serves both sites. A branch decides which server a push lands on,
so nothing is ever copied between them by hand.

## Branches

| Branch       | Environment  | Site                             | Server user            | Trigger                 |
| ------------ | ------------ | -------------------------------- | ---------------------- | ----------------------- |
| `dev`        | `testing`    | https://societynew.in            | `societynew1`          | Automatic on push       |
| `production` | `production` | https://easylogicstechnology.com | `easylogicstechnology` | Automatic on push       |
| `main`       | —            | *nothing*                        | —                      | Integration branch only |

The environment name in that table is the GitHub Environment the job runs under
*and* the label its self-hosted runner is registered with. Both are derived from
the branch in `.github/workflows/deploy.yml`; get the label wrong and the job
queues forever instead of failing.

Intended flow:

```
feature branch  ->  dev         (test on the subdomain)
dev             ->  main        (merge + review)
main            ->  production  (release to live)
```

Live only moves when someone deliberately merges into `production`.

## How it works

Each server runs its **own GitHub Actions runner**. The server dials *out* to
GitHub, picks up the job, and runs `.github/scripts/deploy.sh` locally — which
does `git fetch` + `git reset --hard` in the site directory, fixes `app/tmp`
permissions, and clears CakePHP's schema cache. No files are uploaded from
GitHub — **the server pulls from GitHub itself**.

```
GitHub  <--[runner polls out]--  Server  --[key B]-->  GitHub repo
```

Nothing connects *into* the server, so there are no SSH deploy secrets, no host
key to scan, and no inbound port for a firewall or fail2ban to block.

> This replaced an earlier design where a GitHub-hosted runner SSH'd into the
> server. That version failed intermittently — the inbound SSH connection was
> unreliable — and needed four secrets per environment. If you find
> `SSH_HOST` / `SSH_USER` / `SSH_PRIVATE_KEY` still set on an environment, they
> are leftovers and can be deleted.

---

## One-time setup

### 1. One SSH key pair (key B)

The runner talks to GitHub over HTTPS on its own, so the only key you need is
the one the *site checkout* uses to pull from GitHub.

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

> If that instead says `Hi <username>!`, the server is authenticating as a
> **personal account**, not a deploy key — which means the server can *push* to
> the repo, and the access follows that person rather than the machine. Replace
> it with a repo deploy key as above and remove the key from the account.

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

### 3. Install the Actions runner on the server

Do this **once per server**, logged in as the site user — not as root. The
runner inherits that user, and deploy.sh writes into the site directory, so a
root-owned runner would leave root-owned files the web server cannot read.

```bash
ssh societynew1@88.222.245.214        # testing; use the live user for production
whoami                                # must be the site user
```

Go to **Repo → Settings → Actions → Runners → New self-hosted runner**, pick
**Linux / x64**. GitHub shows a download and a `./config.sh` command with a
registration token baked in — use *those* commands, they carry the current
runner version and a token that expires in an hour.

Run the download block as shown, then register with a **label naming the
environment**:

```bash
# testing server
./config.sh --url https://github.com/easylogicstechnology/easylogicstechnology \
            --token <TOKEN-FROM-GITHUB> \
            --name societynew-testing \
            --labels testing \
            --unattended

# production server
./config.sh --url https://github.com/easylogicstechnology/easylogicstechnology \
            --token <TOKEN-FROM-GITHUB> \
            --name easylogics-production \
            --labels production \
            --unattended
```

The label is what `runs-on: [self-hosted, testing|production]` matches. Get it
wrong and the job queues forever instead of failing.

Then keep it running across reboots. With sudo:

```bash
sudo ./svc.sh install "$(whoami)"
sudo ./svc.sh start
sudo ./svc.sh status
```

CloudPanel site users often have no sudo. Without it, use cron:

```bash
(crontab -l 2>/dev/null; echo "@reboot cd ~/actions-runner && ./run.sh >> ~/actions-runner/runner.log 2>&1") | crontab -
cd ~/actions-runner && nohup ./run.sh >> runner.log 2>&1 &
```

Confirm it shows **Idle** under Settings → Actions → Runners before pushing.

### 4. GitHub Environments and variables

**Repo → Settings → Environments** → create `testing` and `production`.

On the `production` environment, add yourself under **Required reviewers** so
live deploys need one approval click.

Add per environment:

| Type     | Name          | Example                                                      |
| -------- | ------------- | ------------------------------------------------------------ |
| Variable | `DEPLOY_PATH` | `/home/easylogicstechnology/htdocs/easylogicstechnology.com` |
| Variable | `SITE_URL`    | `https://easylogicstechnology.com`                            |

The `testing` environment gets the same names with the subdomain's own path and
URL (`/home/societynew1/htdocs/societynew.in`, `https://societynew.in`).

`DEPLOY_PATH` is a *variable*, not a secret — a path is not sensitive, and as a
secret it shows up masked as `***` in the log exactly when you need to read it.
An existing `DEPLOY_PATH` secret still works, so this does not have to be
migrated in a hurry.

> `SSH_HOST`, `SSH_USER`, `SSH_PRIVATE_KEY` and `SSH_PORT` are no longer used.
> Delete them, and remove the Actions public key from the server's
> `~/.ssh/authorized_keys`.

### 5. First deploy

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

**The run sits on "Waiting for a runner to pick up this job"**
No online runner carries the label this job asked for. Check **Settings →
Actions → Runners**: the right one must show **Idle**, not **Offline**, and its
label must be exactly `testing` or `production`. If it is offline, log into that
server and restart it (`sudo ./svc.sh start`, or the `nohup ./run.sh` line from
section 3).

**`<path> is not a git checkout on this runner`**
Either `DEPLOY_PATH` is wrong for that environment, or the runner is registered
on the wrong server — a `production`-labelled runner installed on the testing
box will happily accept live deploys. Check `whoami` and `pwd` on the server
against the environment's `DEPLOY_PATH`.

**`Repository not found` / `Permission denied` during `git fetch` on the server**
Key B is wrong. Run `ssh -T git@github.com` on the server and confirm the deploy
key is registered on the repo.

**Deploy leaves files the site cannot read**
The runner is running as the wrong user — most likely it was installed as root.
`sudo ./svc.sh uninstall`, then reinstall as the site user per section 3.

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
