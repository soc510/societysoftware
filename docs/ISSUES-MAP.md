# Issue map — 23 reported bugs mapped to code

Survey of the reported issue sheet against the codebase. Purpose is to make
prioritisation possible: right now every row on that sheet looks equally sized,
and they are not.

**Confidence markers**

- ✅ **Verified** — the code was read and the defect confirmed directly.
- ○ **Reported** — found by survey, mechanism looks sound, not yet confirmed by
  running it. Treat as a strong lead, not a fact.

Nothing here has been fixed. No behaviour has been changed.

---

## Start here: three verified one-line-ish fixes

These are small, confirmed, and each one fully explains its reported symptom.

### 1. "Delete Bill Specific Period — only deletes last period" (sheet #7) ✅

`app/Controller/SocietyBillsController.php:10179-10182`

```php
$from_date = isset($postData['MemberBillSummary']['from_date']) ? ... : '';
$to_date   = isset($postData['MemberBillSummary']['to_date'])   ? ... : '';
$from_date = $this->Session->read('date.from_date');   // <-- overwrites
$to_date   = $this->Session->read('date.to_date');     // <-- overwrites
```

The period the user picks is read, then thrown away two lines later and replaced
with the range last *listed* on screen. Deleting the two session lines restores
the selected period.

Related, same action: it deletes from `member_bill_generates`,
`member_bill_summaries`, `member_payments` and `journal_vouchers` but never from
`member_bill_settlements` / `member_bill_summary_settlements`, orphaning
settlement rows. `deleteSettlement()` at `:10264` exists and is simply not called.

### 2. "Bill generation / interest issue" (sheet #1) ✅

`app/Controller/SocietyBillsController.php:5639`

```php
'MemberPayment.member_transfer' => $latestTransferNo,
'MemberPayment.member_transfer' => $this->financialYearId,   // same key
```

One PHP array, the same key twice. The second silently replaces the first, so
the payment lookup that feeds interest filters `member_transfer` by a *financial
year id*. It matches almost nothing, so interest is charged on a principal that
was never reduced by payments.

### 3. "Bill edit worked before, not now" (sheet #2) ✅

`app/Controller/SocietyBillsController.php:8240`

```php
$amountPayable = ... + $jvAdjustment;
```

`$jvAdjustment` is only ever assigned inside `calculateMembersRegularBillByTarity*`
(`:787`, `:1336`, `:2416`, …). It is never assigned inside
`updateMemberBillSummaryById()`. Line `:8236` just above correctly uses
`$jvDebitedAmt` — the intended variable.

This is almost certainly the "worked before" part: on PHP 5/7 an undefined
variable was a notice evaluating to null, so the sum still worked. On PHP 8 it is
a warning/Error and `amount_payable` degrades.

---

## Cross-cutting root causes

Fixing these once clears symptoms across many sheet rows. Worth doing before the
individual reports, or the same bug gets "fixed" five times.

### A. Society settings read without a WHERE clause ✅

CakePHP 2 ignores condition keys that are not wrapped in `'conditions'`. Compare:

```php
// correct - regular bill generation, :130
find('first', array('conditions' => array('SocietyParameter.society_id' => $id)))

// no filter at all - returns whichever row comes back first
find('first', array('SocietyParameter.society_id' => $id))
```

The unwrapped form is live at three sites:

| file:line | function |
|---|---|
| `SocietyBillsController.php:9222` | `calculateSocietyMembersSupplementaryBills` |
| `SocietyBillsController.php:10388` | `upadate_last_member_bill` |
| `SocietyBillsController.php:12540` | `update_member_bills_future` |

On a single-society install this is invisible. On this database — 2,380
societies in one schema — GST percentages, interest rate, interest method,
billing frequency and `gst_limit` are taken from **another society** on those
three paths. This is the highest-severity finding in the survey.

### B. `$options['AND'] = ...` reassignment drops the society filter ○

`SocietysController.php:2661-2672`, `AccountReportsController.php:4772-4782`, and
`SocietyBillComponent.php:1729-1730` all assign `$options['AND']` twice instead of
appending. The second assignment discards the first — usually the `society_id`
condition — so the query runs across all societies.

### C. Financial-year boundary off by one ○

`if ($mon > 4)` treats April as belonging to the previous financial year
(`SocietysAjaxController.php:3009`, `:3497`; `AccountReportsController.php:22961`).
`AccountReportsController.php:22945` additionally hardcodes `return '2022-04-01';`.
A corrected version already exists at `SocietysAjaxController.php:4360` — the
other copies were never updated.

### D. Forked code — the same fix has to land in several files ○

There is no shared bill engine or shared balance sheet. Before editing anything,
confirm which copy the router actually reaches.

| Logic | Live copy | Dead/forked copies |
|---|---|---|
| Regular bill generation | `SocietyBillsController.php:745` | `:1188`, `:2263`, `:3313`, `:4401` |
| Supplementary bills | `:9210` | forked from `:745`, not shared |
| Balance sheet | `AccountReportsController.php:20494` | `:20076`, `:20993`, `ExcelsReportController.php:3471` |

Same story in the view tree: `account_balance_sheet.ctp`,
`..._live_code.ctp`, `..._test.ctp`, plus a stray copy outside any controller
directory.

---

## Per-issue map

| # | Issue | Where | Nature |
|---|---|---|---|
| 1 | Bill / interest | `SocietyBillsController.php:5639`, interest engine `:5609`, `:5446` | ✅ fix |
| 2 | Bill edit | `SocietyBillsController.php:8240` | ✅ fix |
| 3 | Receipt delete/edit → bill update; bill date | `updateMemberBillSummaryById():8141`; date guard `:7039` | ○ fix |
| 4 | Supplementary bill settlement | `deductPaidAmount():8526` vs `settleBillThroughLIFO()` | ○ design |
| 5 | Bank reconciliation | `SocietysAjaxController.php:2979` | ○ fix |
| 6 | Cheque return | `memberChecqueReturn()`; `AccountReportsController.php:17429` | ○ fix |
| 7 | Delete bill by period | `SocietyBillsController.php:10179` | ✅ fix |
| 8 | Balance sheet | `AccountReportsController.php:20494`, view `:151` | ○ fix |
| 9 | GST limit | `SocietyBillsController.php:941` (works) vs `:9550`, `:9392`, `:1005` (missing) | ○ fix |
| 10 | General ledger | `AccountReportsController.php:11031`, sort `:11456` | ○ fix |
| 11 | Bank slip | `AccountReportsController.php:13517` | ○ fix |
| 12 | Closing balance carry-forward | `SocietyBillComponent.php:2112`, `:2020` | ○ fix |
| 13/22 | Journal entries Dr≠Cr | `ReportsController.php:909`; no validation exists | ○ fix |
| 14 | O/s dues, cut-off date | `AccountReportsController.php:5809` | ○ fix |
| 15 | Member online payment | — | **not built** |
| 16 | Member SMS | `SocietySmsController.php:119`, `:337` | ○ fix |
| 18 | Member login, bills date-wise | `SocietysMembersController.php` `dashboard()` | ○ fix |
| 19 | Bill mail format / attachment | `SocietyBillsController.php:13789` | ○ fix |
| 19b | Reminder | — | **not built** |
| 20 | Supplementary bill register | nearest: `account_bill_register():18158` | **not built** |
| 21 | Income & expenditure JV | `AccountReportsController.php:8305` | ○ fix |
| 23 | Balance sheet → Excel | `ExcelsReportController.php:3471` + `.ctp:618` | ○ fix |

### Notable detail on #9 (GST limit)

The feature is **already half-built**, which makes it cheaper than it looks:

- `society_parameters.gst_limit` exists; UI at `society_parameter.ctp:144` saves it
- Applied correctly in regular bill generation, `SocietyBillsController.php:941-949`
- **Not** applied on bill edit (`:9550`), supplementary bills (`:9392`), or
  GST-on-interest (`:1005`)

Current data: of 1,441 `society_parameters` rows only 2 have a limit set —
`7500` and `2000000`. Those match the Indian GST rules for housing societies
(₹7,500/month per member RWA exemption; ₹20 lakh registration threshold), so the
intended behaviour is almost certainly the statutory exemption.

**Open question for the business side:** is the ₹7,500 limit per member per
month, or per ledger head? And at exactly ₹7,500, is GST charged or not? The
code cannot answer this.

---

## Three items are not bugs — they do not exist yet

Scoping these as "fixes" will mislead the estimate.

- **#15 member online payment** — no gateway integration anywhere. Repo-wide
  search for razorpay / payu / ccavenue / paytm / instamojo / billdesk /
  cashfree / easebuzz returns nothing. The only outbound calls are the email API
  and the SMS API. Payments are manual entry only. The only trace is legal copy
  in `app/View/Terms/refund_policy.ctp`.
- **#19 reminder** — no reminder action, view, template, cron or shell exists.
- **#20 supplementary bill register** — no dedicated report; only a bill-type
  dropdown on the regular register.

---

## Security items found while surveying

Not on the sheet, but they surfaced and should not wait.

- **Textlocal API key hardcoded** in `SocietySmsController.php:612`.
- **MySQL user and password hardcoded** in `SocietysMembersController.php`
  (`member_logins()`), against a hardcoded schema name.
- Both are in git history — they need rotating, not just deleting.
- Raw interpolated SQL reachable from user input: `SocietySmsController.php:315`,
  `:369`; `SocietysController.php:3155`.
- `app/Controller/CurlController.php` is a scratch test action left routable; it
  emails a hardcoded bill to a hardcoded gmail address.

Separately: `member_logins()` calls `mysql_connect()`, removed in PHP 7. Member
login creation cannot work on any current PHP.

---

## Landmines when editing

- **CR-only line endings.** Several controllers (`SocietysMembersController.php`,
  `ExcelsReportController.php`) use classic-Mac `\r` line endings and appear as a
  single line to most tools. Read them with `tr '\r' '\n' < file`. Any tool that
  rewrites them to LF can swallow trailing code into a `//` comment.
- **Check which copy is live** before editing — see section D above.
- Bills are financial records for 2,380 societies and 84,099 members. Every
  change here needs a before/after comparison on real data in staging, not just
  "the page loads".
