# Prompt — paste everything below the line into a new chat

---

I need you to draft three legal pages for an e-commerce website. I will paste the
result into a CMS, so follow the output format at the end exactly.

## The single most important rule

**Do not invent facts.** Everything technical you need is listed below and it is
accurate — it was compiled by auditing the actual code, not from assumptions.

Where a document needs a fact about my *business* that I have not given you (legal
entity name, mailing address, contact email, how long we keep order records, whether
we use a shipping carrier's tracking, which state we're organised in, etc.), insert a
clearly marked placeholder in double square brackets — `[[LEGAL ENTITY NAME]]`,
`[[SUPPORT EMAIL]]`, `[[ORDER RETENTION PERIOD]]` — and keep a running list of every
placeholder at the end of your reply so I know exactly what I still owe you.

Do not pad with boilerplate. Do not copy a generic template you have seen. Do not
claim certifications, frameworks, audits, or "industry-standard encryption" — I have
not told you any of that is true. If you think something material is missing from
what I have given you, say so at the end rather than filling the gap with a guess.

## The site

- E-commerce. **I sell `[[WHAT THE STORE SELLS — ask me]]`.** Ask me this if it
  matters to the wording; otherwise keep product references generic.
- Visitors can browse, add to cart and **check out entirely as guests** — no account
  required. Accounts are optional.
- A logged-in user gets a wishlist, saved to their profile.
- Audience: **primarily United States**, expanding to **Mexico and South America**,
  and **possibly Europe later**. Draft so the documents satisfy US state privacy law
  (California-style opt-out rights, plus the other state acts that follow the same
  shape) **and are already GDPR-ready**, since the consent machinery described below
  is built to the stricter opt-in standard.

## Exactly what the site sets and loads — this is the audited list

### Cookies

Every cookie below is **first-party except `_GRECAPTCHA`**, which is set by
Google and is listed separately after this table.

Strictly necessary — these are set with no consent because the site cannot work
without them:

| Name | Purpose | Lifetime |
|---|---|---|
| `PHPSESSID` (PHP session) | Holds the shopping cart contents for the current visit | Session |
| Couch CMS authentication cookie | Keeps a logged-in user signed in | Session or persistent if "remember me" |
| `couchcms_testcookie` | Set once to check whether the browser accepts cookies at all | Session |
| `ccs_cnst_v` | Records **which version of the policy** the visitor consented to. When the policy changes materially the version is raised and everyone is asked again. | 6 months |
| `ccs_cnst_a` | Records the analytics choice — `1` or `0` | 6 months |
| `ccs_cnst_m` | Records the marketing choice — `1` or `0` | 6 months |
| `ccs_cnst_e` | Records the embedded-media choice — `1` or `0` | 6 months |

The four `ccs_cnst_*` cookies exist **only to record a privacy choice**, which is why
they themselves need no consent — the alternative is asking on every page load
forever. Say this plainly in the cookie policy; people ask about it.

**One third-party cookie, on one page:**

| Name | Set by | Purpose | Lifetime |
|---|---|---|---|
| `_GRECAPTCHA` | **Google** | Account registration page **only**. Lets reCAPTCHA distinguish a person from an automated script, so bots cannot create accounts. | `[[CONFIRM against Google's own documentation — commonly cited as ~6 months. Google controls this, not us.]]` |

Say plainly in the cookie policy that this is the only cookie on the site that is not
ours, that it appears only if you visit the registration form, and that it is treated
as strictly necessary because without it the account system fills with fake sign-ups.
It is **not** in the consent banner, and the reason is worth stating: a bot would
simply decline the banner and walk through an unprotected form.

### Consent categories shown in the banner

Four, and the visitor can accept all, reject all, or choose individually:

1. **Strictly necessary** — always on, shown but not switchable. Sign-in, the cart,
   and remembering the choice itself.
2. **Analytics** — currently **no analytics tag is installed**. There is a gated slot
   ready for one. Write the policy so it describes the category honestly without
   claiming a specific tool is in use.
3. **Marketing** — same situation: a gated slot, nothing installed today.
4. **Embedded media** — YouTube and Vimeo video. Separate from Marketing on purpose,
   so someone can watch a video without also enabling advertising tracking.

### How consent actually behaves — describe this accurately, it is unusual and good

- Consent is resolved **on the server**. A tag the visitor has not agreed to is **not
  printed into the page at all** — it is not present-but-disabled. Nothing to fire.
- **Refusing is exactly as easy as accepting** — same size, same styling, side by
  side, no close button.
- **Declining changes nothing about shopping.** Browsing, cart, checkout and order
  delivery all work identically for someone who rejects everything, because those run
  on strictly-necessary cookies. Make this explicit — people assume rejecting breaks
  the store.
- **Withdrawal is one click**: a persistent **"Cookie Settings"** link in the site
  footer reopens the banner at any time.
- **Videos**: with embedded-media consent, video loads normally. Without it, the video
  does not load and the visitor is shown an in-place notice naming the provider and
  offering a "Load video" button, with an optional "remember this" checkbox. Clicking
  without ticking loads that one video and stores nothing.

### Third parties — this is the complete list

| Who | When it is contacted | Basis |
|---|---|---|
| **Stripe** | **Checkout only**, not sitewide | Strictly necessary to take payment. Name it as a payment processor. |
| **PayPal** | **Checkout only**, and only if the buyer chooses PayPal. The buyer is redirected to PayPal to pay, and PayPal notifies our server directly when the payment completes. | Strictly necessary to take payment. Name it as a payment processor alongside Stripe. |
| **Google (reCAPTCHA)** | **Account registration page only.** Not on browsing, cart or checkout. | Legitimate interest — preventing automated account creation. Google receives the visitor's IP address and interaction signals, and processes them in the **United States**. We receive only a yes/no answer, never the underlying data. |
| **YouTube** (`youtube-nocookie.com`) | Only after embedded-media consent, or a per-video click | Consent |
| **Vimeo** (`player.vimeo.com`, with `dnt=1`) | Only after embedded-media consent, or a per-video click | Consent |

Also true and worth stating positively in the privacy policy, because most sites
cannot say it:

- **Web fonts are self-hosted.** The site does **not** call Google Fonts. No visitor
  IP is disclosed to a font host.
- **No CDN calls on visitor-facing pages, with one exception.** All scripts and styles
  are served from the site's own domain **except** the Google reCAPTCHA script, which
  loads on the account registration page only. State the exception; do not write the
  absolute version of this sentence.
- There is **no advertising network, no social media pixel and no session-recording
  tool** on the site.

### Where personal data is collected, and what each form takes

| Form | Collects |
|---|---|
| Contact form (full) | Name, email address, message |
| Contact form (email only) | Email address |
| Account registration | Email address, password, display name |
| Newsletter sign-up (footer) | Email address |
| Checkout / orders | First name, last name, email address, shipping address (street, city, state, ZIP) and billing address (street, city, state, ZIP). **No telephone number is collected.** If the buyer ticks "billing same as shipping", the shipping values are copied into the billing fields so the order carries a complete record of its own. |

**Card details never reach this server, and that is worth stating positively.** This
has been verified in the code, not assumed:

- **Stripe** card entry happens inside Stripe's own hosted fields. The site receives a
  payment-method reference (`pm_...`) and never the card number, expiry or CVC.
- **PayPal** takes payment entirely on PayPal's own site. The buyer is redirected
  there and returns afterwards.
- The site stores the gateway's **transaction reference** against the order, so a
  payment can be traced, but nothing that could be used to charge a card again.

### What an order record stores, and for how long

Every completed purchase writes two kinds of record. Both are relevant to retention
and to deletion requests, so describe them honestly.

**The order** holds: an order number, its status, the customer's first and last name,
email address, shipping address and billing address, which gateway was used, the
gateway's transaction reference, the date it was paid, and the money figures
(subtotal, shipping, tax, total). If a refund is issued it also records the amount
refunded and the date.

**Each line of the order** holds a snapshot of what was bought: product title, SKU,
the variant chosen (size, colour), quantity, unit price, line total and tax rate.
These are **snapshots taken at purchase time on purpose** — they do not change when a
product's price or name later changes, because an order must remain a true record of
what was actually sold.

If the buyer was signed in, the order also stores their user ID. **Guest checkouts
store no user ID** — the order stands alone.

> **The retention question is a real conflict and you should decide it deliberately.**
> Tax and accounting rules generally require keeping sales records for a period of
> years, and that obligation **overrides** a customer's request to be deleted. So the
> honest answer to "delete everything about me" is usually: the account and marketing
> data go, the order records stay until the legal retention period expires, and then
> they go too. Say that plainly rather than promising deletion you cannot perform.
> Use `[[ORDER RETENTION PERIOD — e.g. 7 years, ask your accountant]]`.

### Refunds and returns — how it actually works

Describe this accurately in the Terms, because it sets customer expectations:

- **A refund is issued by us, by hand**, inside Stripe or PayPal. There is no button
  on the website that a customer can press to trigger a refund, and no automated
  refund path exists in the code.
- When we issue one, the gateway notifies the site and the order is marked refunded
  automatically, with the amount recorded. A partial refund is recorded as such.
- **Returned stock is not automatically put back on sale.** A person inspects the
  returned item first. This is deliberate and is worth a sentence in the Terms,
  because it explains why a return may not immediately free up the last item.

### The newsletter consent record — mention this in the privacy policy

For every newsletter subscriber the site stores, alongside the address: the **date and
time** of sign-up, the **form** they used, and the **exact wording** they agreed to.
That is a deliberate accountability measure. It stores **no IP address**, by choice,
to avoid collecting more personal data than the record needs.

There is **no double opt-in** currently — sign-up is single-step.

## What to write

Three separate documents.

### 1. Privacy Policy
Who the controller is, what is collected and why, the legal basis for each, who it is
shared with (the third parties above and nobody else), how long it is kept, and the
rights people have — including the US opt-out framing and the GDPR rights, kept
readable rather than duplicated into two parallel sections. It **must** include a
clear route for access, correction and deletion requests: an email address
placeholder and what someone can expect after they write.

It must also cover, specifically:

- **Google reCAPTCHA on the registration form** — that Google receives the visitor's
  IP address and interaction signals, processes them in the **United States**, that
  this is an international transfer, that we rely on legitimate interest in preventing
  automated sign-ups, and that we receive only a yes/no answer.
- **Order records and the retention conflict** described above — that a deletion
  request cannot erase sales records we are legally required to keep, and what
  actually happens instead.
- **What an account holds** — display name, email, hashed password, saved shipping and
  billing address, wishlist. Say the password is **stored hashed, never in readable
  form**, and `[[CONFIRM: do you want to state the hashing method? Usually better not
  to name it.]]`
- **Data breach notification** — several US state laws require notifying affected
  people. State what we would do; use `[[NOTIFICATION APPROACH]]` if undecided.
- **Children** — that the store is not intended for children under
  `[[MINIMUM AGE]]` and we do not knowingly collect their data.

### 2. Cookie Policy
The tables above turned into readable prose plus a table. Explain the four categories,
name every cookie, be honest that the analytics and marketing slots are currently
empty, and explain how to change or withdraw a choice via footer → Cookie Settings.

### 3. Terms of Service
Ordinary e-commerce terms: who we are, orders and acceptance, pricing and errors,
payment (via **Stripe or PayPal** — both, not just Stripe), shipping, returns and
refunds, accounts and acceptable use, disclaimers and limitation of liability,
governing law, and how the terms get changed.

Everything in the list below is a **commercial decision I have to make**, not a fact
you can derive from the site. Use a placeholder for each and list them at the end.

**Shipping**
- `[[WHERE WE SHIP TO]]` — US only at first, and which countries after that
- `[[SHIPPING RATES]]` — the cart currently applies **no shipping charge**; if that is
  temporary, say so rather than implying free shipping is a permanent offer
- `[[DISPATCH TIME]]` and `[[DELIVERY ESTIMATE]]`
- `[[CARRIER AND TRACKING]]` — whether tracking is provided
- `[[CUSTOMS AND DUTIES]]` — **important for Mexico and South America.** Who pays
  import duty, and that delivery estimates exclude customs delays
- `[[RISK OF LOSS]]` — when responsibility passes to the buyer
- `[[UNDELIVERABLE / WRONG ADDRESS]]` — what happens and who pays reshipping

**Returns and refunds**
- `[[RETURN WINDOW]]` — how many days from delivery
- `[[CONDITION REQUIRED]]` — unworn, tags attached, original packaging
- `[[NON-RETURNABLE ITEMS]]` — e.g. customised or personalised apparel, final-sale
- `[[WHO PAYS RETURN SHIPPING]]` — differs for a faulty item versus a change of mind
- `[[REFUND METHOD AND TIMING]]` — back to the original payment method, and how long
- `[[EXCHANGES]]` — offered or not
- `[[HOW TO START A RETURN]]` — currently **by email**; there is no returns form on
  the site, so do not describe one

**Pricing and tax**
- Prices are in **US dollars**, and that is the only currency — a buyer in Mexico pays
  in USD and their bank handles conversion. Say this; it prevents disputes.
- Sales tax is calculated at checkout `[[TAX REGISTRATION / NEXUS STATES]]`
- `[[PRICE ERROR POLICY]]` — the right to cancel an order priced in error

**Business and legal**
- `[[LEGAL ENTITY NAME]]` and `[[ENTITY TYPE]]` — sole proprietor, LLC, etc.
- `[[BUSINESS MAILING ADDRESS]]` — required on a returns policy and by several
  consumer-protection rules
- `[[SUPPORT EMAIL]]` and `[[SUPPORT PHONE, if any]]`
- `[[GOVERNING LAW STATE]]` and `[[VENUE FOR DISPUTES]]`
- `[[ARBITRATION CLAUSE — yes or no]]` — a real decision with real consequences; flag
  that it deserves a lawyer's eye
- `[[MINIMUM AGE TO BUY OR HOLD AN ACCOUNT]]`
- `[[INTELLECTUAL PROPERTY]]` — trademarks and image rights, given the brand carries a
  named person's likeness

## Tone

Plain, short sentences. Write for a customer, not for a court. No "we value your
privacy", no "your privacy is important to us", no throat-clearing. Where a legal term
is unavoidable, say what it means in the same sentence. Prefer "we" and "you".

## Output format — follow this exactly

For **each** of the three pages, give me four labelled blocks, because those are the
four fields in my CMS:

1. **Page Title** — plain text, short.
2. **Effective Date** — output the literal placeholder `[[EFFECTIVE DATE]]`. Do not
   put today's date. This is a legal statement about when the terms took effect, not
   a record of when the file was edited.
3. **Short Summary** — one or two plain-language sentences, shown above the formal
   text.
4. **Policy Text** — **valid HTML**, because this goes into a rich-text field. Use
   `<h2>`, `<h3>`, `<p>`, `<ul>`, `<ol>`, `<table>` and `<a>`. Do **not** include
   `<html>`, `<head>`, `<body>`, `<style>`, inline `style=` attributes, classes, or
   scripts — the site's own stylesheet handles appearance.

Finish with:
- the full list of every `[[PLACEHOLDER]]` you used, and
- anything you think is missing or that you would want a lawyer to look at first.

## Known gaps in the site itself — do not describe these as working

Do not write policy text that implies otherwise:

1. **No customer order-history page.** A buyer cannot log in and view past orders;
   the only link they receive is the one-off receipt URL. So a Terms section about
   "reviewing your orders in your account" would be false.

**Updated 2026-08-27 — order confirmation email now EXISTS.** An earlier version of this
file said it did not; that is out of date. `snippets/utils/order_email.htm` sends an HTML
receipt to the customer and a plain-text notification to the shop, both fired from
`order_fulfil.htm` the moment payment is confirmed. Two things to get right when
describing it:

- It is a **transactional** message, not marketing. It is sent because an order was
  placed, needs no consent, and must not be described as something the customer opted
  into or can unsubscribe from.
- It contains the customer's name, email, shipping address, what they bought and what
  they paid, and it travels over ordinary email. Say so plainly in the privacy policy
  rather than implying receipts stay on the site.

The receipt itself makes no promise the site cannot keep — no tracking number, no
shipping notification, no account link. Keep the Terms in step with that: **do not
promise dispatch notifications or order tracking**, because neither is built.

Also currently true and worth knowing while drafting:

- **No double opt-in** on the newsletter, as noted above.
- **No returns form** on the site — returns start by email.
- **Analytics and marketing slots are empty.** Describe the categories, not tools.

## Last thing

You are not a lawyer and neither am I. Draft these as a solid, accurate starting point
that reflects what this site actually does — then tell me plainly that they need a
qualified review before they go live, especially before selling into the EU.
