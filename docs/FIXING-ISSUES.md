# How to fix an issue in this codebase

Written after fixing three of the reported billing defects. The steps that
matter here are not the generic ones — they are the traps specific to this
codebase, which has forked files, unusual line endings, and financial data for
2,380 societies in one database.

Companion docs: [ISSUES-MAP.md](ISSUES-MAP.md) (what is broken, and where),
[DEPLOYMENT.md](DEPLOYMENT.md) (how code reaches the servers).

---

## The loop

```
pick one issue  ->  reproduce locally  ->  find the live code  ->  fix
                ->  verify the numbers  ->  dev branch  ->  test on staging
                ->  production branch  ->  spot-check live
```

One issue at a time. These are money calculations; a batch of five fixes that
lands together cannot be un-picked when one of them turns out wrong.

---

## 1. Pin down what "correct" means — before touching code

This is the step that gets skipped, and it is the one that decides whether the
fix is right.

For every issue, write down:

- **Where** — the exact menu path and screen
- **With what** — which society, member, month, financial year
- **Expected vs actual** — the number that should appear, and the number that does

The code cannot tell you the expected value. "Interest is wrong" is not
actionable; "member 1043 in June should be charged ₹412 interest, the bill shows
₹1,180" is.

If you cannot state the expected value, the issue is not ready to fix yet.

## 2. Reproduce it locally

Local runs the full production dataset — 84,099 members, 2.24 million bills —
so almost anything reported can be reproduced without touching a server.

```
https://easylogics-cake.test
```

Turn errors on while you work. In `app/Config/core.php`:

```php
Configure::write('debug', 2);
```

Set it back to `0` when you are done. `debug` 2 also prints the SQL log at the
bottom of every page, which is usually the fastest way to see what a screen
actually queried.

Watch `app/tmp/logs/error.log` in parallel — Cake writes exceptions there even
when the page renders.

## 3. Find the code that actually runs

[ISSUES-MAP.md](ISSUES-MAP.md) has a `file:line` for each reported issue. Two
checks before editing anything:

**a. Confirm you are in the live copy.** This codebase carries many forked
copies. The regular bill engine exists five times; the balance sheet exists four
times. Editing a dead copy produces a fix that changes nothing.

```bash
# Which copies exist?
grep -n "function calculateMembersRegularBillByTariff" app/Controller/SocietyBillsController.php

# Which one does the live action call? Follow the caller, do not guess by name.
```

Names ending `_bk`, `_bkOM`, `_old`, `_test`, or carrying a date are dead. So are
files like `SocietyBillComponent.php 05-03-2025`.

**b. Watch the line endings.** `SocietysMembersController.php` and
`ExcelsReportController.php` use classic-Mac CR-only (`\r`) endings and look like
a single line to most tools. To read them:

```bash
tr '\r' '\n' < app/Controller/SocietysMembersController.php | sed -n '1200,1260p'
```

Do not let an editor rewrite those files to LF wholesale — a `//` comment can
swallow the rest of the file.

## 4. Make the smallest change that fixes the cause

Two habits that paid off in the three fixes already done:

- **Fix the cause, not the symptom.** The "delete only deletes the last period"
  bug looked like two stray lines to delete. Deleting them would have wiped the
  entire financial year, because the delete form posts no dates at all — the
  session read was the only thing feeding it. The real fix was in the form.
- **Leave a note about why.** These files have no tests and no history to
  explain intent. A comment naming the old behaviour is what stops the next
  person from "cleaning up" the fix.

## 5. Verify the numbers, not just the page

For anything touching bills, interest, GST, or balances, "the page loads" proves
nothing. Snapshot the figures before and after.

Pick a member and record the current state:

```sql
SELECT id, bill_no, month, op_due_amount, monthly_bill_amount,
       interest_on_due_amount, tax_total, amount_payable, balance_amount
FROM Neweasylogics.member_bill_summaries
WHERE member_id = <id> AND financial_year_id = <fy>
ORDER BY bill_no;
```

Then run the flow, run the query again, and diff the two.

- If the fix was meant to change amounts (the interest fix), they **must**
  change — and the new number has to be right by step 1's definition.
- If the fix was not meant to change amounts, they **must not** change.

For a wider check, compare totals across a whole society before and after:

```sql
SELECT COUNT(*) AS bills,
       ROUND(SUM(amount_payable),2) AS total_payable,
       ROUND(SUM(interest_on_due_amount),2) AS total_interest
FROM Neweasylogics.member_bill_summaries
WHERE society_id = <id> AND financial_year_id = <fy>;
```

## 6. Ship it to staging

```bash
git add <files>
git commit -m "..."
git push origin dev
```

Deploy runs automatically, about 20 seconds. Then re-test on
`https://societynew.in` — staging has its own `societynew` database, so
experiments there cannot touch live data.

Test the same case from step 1, plus one case the fix should **not** affect.

## 7. Ship it to production

Only after staging looks right:

```bash
git checkout main && git merge dev
git checkout production && git merge main
git push origin main production
git checkout dev
```

Then check the live screen for the same case.

If something is wrong, roll back — do not debug on live:

```bash
git revert <commit> && git push origin production
```

---

## Things that will bite you

**Never change `Security.salt` or `Security.cipherSeed`.** Passwords are
sha1+salt. A new salt invalidates all 7,910 logins at once. The startup notice
asking you to change it is wrong for this app — ignore it.

**`core.php` and `database.php` never deploy.** They are gitignored so live
credentials stay out of git. A fresh checkout has neither, and the site 500s
until they are created on the server by hand.

**Never run git commands on a server** other than the recovery in
DEPLOYMENT.md. The deploy script owns those checkouts. Switching branches there
by hand has already, once, restored deleted files including a publicly reachable
`adminer.php`.

**`app/webroot` holds live customer uploads** — payment scanner images, member
photos. They are untracked. `git clean` deletes them permanently; nothing in the
deploy path may ever run it.

**Clear the schema cache after a schema change.** With `debug` 0, CakePHP caches
table structure on disk, so a new column produces "column not found" until:

```bash
rm -rf app/tmp/cache/models/* app/tmp/cache/persistent/*
```

The deploy script does this on every run.

**Watch for the tenant filter.** Several queries are missing `society_id` or
`financial_year_id`, and a few use `find('first', array('Model.field' => x))`
without the `'conditions' =>` wrapper — which CakePHP silently ignores, giving no
WHERE clause at all. When touching any query, check both filters are present.
See section A of [ISSUES-MAP.md](ISSUES-MAP.md).

---

## Worked example: the delete-by-period fix

1. **Expected**: choosing 01-06 to 30-06 deletes only June. **Actual**: deleted
   whatever range was last listed.
2. Reproduced locally on the generated-bills screen.
3. Map pointed at `SocietyBillsController.php:10179`. Two lines overwrote the
   posted dates with session values.
4. **Nearly got it wrong here.** Removing those two lines looked correct — but
   reading the view showed the delete button submits its own *empty* form. With
   the session read gone, both dates become `''`, `isset('')` is true, and
   `date >= ''` matches every row: the whole year deleted.
5. Real fix: the delete form now carries the selected dates, and an empty range
   is rejected outright instead of being waved through by `isset()`.
6. Verified the page loads and the app boots; the delete itself still needs a
   staging run against real data.

The lesson is step 4. The map tells you where to look, not what to do.
