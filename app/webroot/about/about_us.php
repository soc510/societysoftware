<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>About Us &mdash; Easy Logics Technology</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="About Easy Logics Technology — GST-compliant society accounting &amp; management software for committees, property managers, accountants and auditors.">
        <link rel="icon" type="image/png" href="/images/fvcon.png">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
        :root{
            --blue:#1e5fa8;--blue-dark:#164a85;--blue-deep:#0f2f57;
            --green:#7cb342;--green-dark:#5f9130;
            --ink:#12263f;--slate:#5b6b82;--line:#e6ecf3;--bg:#f6f9fc;--white:#fff;
            --shadow:0 18px 40px -18px rgba(15,47,87,.35);
            --shadow-sm:0 8px 24px -12px rgba(15,47,87,.28);--radius:16px;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
            color:var(--ink);background:var(--white);line-height:1.6;-webkit-font-smoothing:antialiased;overflow-x:hidden}
        h1,h2,h3,h4{font-family:'Poppins',sans-serif;line-height:1.18;color:var(--ink)}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}
        .wrap{max-width:1180px;margin:0 auto;padding:0 24px}
        .eyebrow{font-family:'Poppins',sans-serif;font-weight:600;font-size:13px;letter-spacing:.14em;
            text-transform:uppercase;color:var(--blue)}
        .accent{color:var(--green)}
        .btn{display:inline-flex;align-items:center;gap:9px;font-family:'Poppins',sans-serif;font-weight:600;
            font-size:15px;padding:13px 26px;border-radius:999px;border:0;cursor:pointer;
            transition:transform .18s ease,box-shadow .18s ease,background .18s ease;white-space:nowrap}
        .btn-primary{background:linear-gradient(135deg,var(--blue),var(--blue-dark));color:#fff;
            box-shadow:0 12px 26px -12px rgba(30,95,168,.75)}
        .btn-primary:hover{transform:translateY(-2px)}
        .btn-accent{background:linear-gradient(135deg,var(--green),var(--green-dark));color:#fff}
        .btn-accent:hover{transform:translateY(-2px)}
        .btn-ghost{background:rgba(255,255,255,.14);color:#fff;border:1px solid rgba(255,255,255,.4)}
        .btn-ghost:hover{background:rgba(255,255,255,.24)}

        /* Top bar */
        .topbar{background:var(--blue-deep);color:#dbe6f3;font-size:13.5px}
        .topbar .wrap{display:flex;justify-content:space-between;align-items:center;gap:16px;min-height:40px;flex-wrap:wrap}
        .topbar-info{display:flex;gap:22px;flex-wrap:wrap;align-items:center}
        .topbar-info span{display:inline-flex;align-items:center;gap:7px}
        .topbar-info i{color:var(--green)}
        .topbar-social a{display:inline-flex;width:28px;height:28px;align-items:center;justify-content:center;
            border-radius:50%;background:rgba(255,255,255,.1);margin-left:6px;transition:background .18s,transform .18s}
        .topbar-social a:hover{background:var(--green);transform:translateY(-2px)}

        /* Nav */
        header.nav{position:sticky;top:0;z-index:900;background:rgba(255,255,255,.9);
            backdrop-filter:saturate(160%) blur(12px);border-bottom:1px solid var(--line);transition:box-shadow .25s}
        header.nav.scrolled{box-shadow:var(--shadow-sm)}
        .nav .wrap{display:flex;align-items:center;justify-content:space-between;min-height:74px;gap:16px}
        .nav-logo img{height:42px;width:auto}
        .nav-links{display:flex;align-items:center;gap:30px;list-style:none}
        .nav-links a{font-family:'Poppins',sans-serif;font-weight:600;font-size:15px;color:var(--ink);
            position:relative;padding:6px 0;transition:color .18s}
        .nav-links a::after{content:"";position:absolute;left:0;bottom:0;height:2px;width:0;background:var(--green);transition:width .22s}
        .nav-links a:hover{color:var(--blue)}
        .nav-links a.active,.nav-links a:hover::after{width:100%}
        .nav-links a.active{color:var(--blue)}
        .nav-links a.active::after{width:100%}
        .nav-cta{display:flex;align-items:center;gap:12px}
        .nav-toggle{display:none;background:none;border:0;font-size:24px;color:var(--ink);cursor:pointer}

        /* Page hero */
        .phero{position:relative;color:#eaf2fb;overflow:hidden;text-align:center;
            background-image:linear-gradient(180deg,rgba(11,34,64,.92),rgba(22,74,133,.86)),url('/images/society2.jpg');
            background-size:cover;background-position:center}
        .phero .wrap{position:relative;padding:70px 24px 84px}
        .phero .eyebrow{color:#bfe08a}
        .phero h1{color:#fff;font-size:44px;font-weight:800;margin:14px 0 14px;letter-spacing:-.5px}
        .phero p{color:#cfe0f2;font-size:18px;max-width:680px;margin:0 auto}
        .crumbs{margin-top:22px;font-size:14px;color:#bcd0e8}
        .crumbs a:hover{color:#fff}
        .phero-wave{display:block;width:100%;height:auto;margin-top:-1px}

        section{padding:74px 0}
        .section-head{text-align:center;max-width:680px;margin:0 auto 50px}
        .section-head h2{font-size:32px;font-weight:700;margin:12px 0 12px}
        .section-head p{color:var(--slate);font-size:17px}

        /* Story */
        .story .wrap{display:grid;grid-template-columns:1.05fr .95fr;gap:56px;align-items:center}
        .story h2{font-size:32px;font-weight:700;margin:12px 0 18px}
        .story p{color:#3a4a60;font-size:16px;margin-bottom:14px}
        .story-media img{border-radius:var(--radius);border:1px solid var(--line);box-shadow:var(--shadow)}
        .story-points{list-style:none;margin-top:20px;display:grid;gap:12px}
        .story-points li{display:flex;gap:12px;align-items:flex-start;color:#3a4a60;font-size:15.5px}
        .story-points i{color:var(--green);margin-top:3px;flex-shrink:0}

        /* Feature chips */
        .whatwedo{background:var(--bg)}
        .feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
        .feat-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:28px 24px;
            transition:transform .2s,box-shadow .2s,border-color .2s}
        .feat-card:hover{transform:translateY(-6px);box-shadow:var(--shadow);border-color:transparent}
        .feat-icon{width:54px;height:54px;border-radius:14px;display:flex;align-items:center;justify-content:center;
            font-size:23px;color:#fff;margin-bottom:16px;background:linear-gradient(135deg,var(--blue),var(--blue-dark))}
        .feat-card:nth-child(2) .feat-icon,.feat-card:nth-child(4) .feat-icon,.feat-card:nth-child(6) .feat-icon{
            background:linear-gradient(135deg,var(--green),var(--green-dark))}
        .feat-card h3{font-size:18px;font-weight:600;margin-bottom:7px}
        .feat-card p{color:var(--slate);font-size:14.5px}

        /* Founder + Contact */
        .connect .wrap{display:grid;grid-template-columns:1fr 1fr;gap:40px;align-items:stretch}
        .card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:var(--shadow-sm);
            padding:34px 32px}
        .founder-badge{display:flex;align-items:center;gap:16px;margin-bottom:22px}
        .founder-avatar{width:66px;height:66px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;
            justify-content:center;font-family:'Poppins',sans-serif;font-weight:700;font-size:24px;color:#fff;
            background:linear-gradient(135deg,var(--blue),var(--green))}
        .founder-badge h3{font-size:20px}
        .founder-badge span{color:var(--slate);font-size:14px}
        .info-list{list-style:none;display:grid;gap:16px;margin-top:8px}
        .info-list li{display:flex;gap:14px;align-items:flex-start}
        .info-ic{width:42px;height:42px;border-radius:11px;flex-shrink:0;display:flex;align-items:center;
            justify-content:center;font-size:17px;color:var(--blue);background:rgba(30,95,168,.1)}
        .info-list .lbl{font-family:'Poppins',sans-serif;font-weight:600;font-size:13px;color:var(--ink)}
        .info-list .val{color:var(--slate);font-size:14.5px}
        .card h2{font-size:24px;font-weight:700;margin-bottom:6px}
        .card>p.sub{color:var(--slate);font-size:15px;margin-bottom:20px}
        .captcha-box{margin-top:6px}
        .captcha-row{display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:16px}
        .captcha-row img{border:1px solid var(--line);border-radius:10px;background:#fff}
        .cap-input{flex:1;min-width:160px;padding:12px 14px;border:1.5px solid var(--line);border-radius:10px;
            font-size:15px;font-family:inherit;outline:none;transition:border-color .18s,box-shadow .18s}
        .cap-input:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(30,95,168,.15)}
        .cap-status{display:inline-block;margin-bottom:14px;font-size:14px;font-weight:600;padding:8px 14px;border-radius:10px}
        .cap-ok{background:#eaf7e0;color:#4a7a1f;border:1px solid #cfe9b6}
        .cap-err{background:#fdecec;color:#b23b3b;border:1px solid #f5c6c6}
        .cap-warn{background:#fff7e6;color:#a06b00;border:1px solid #f3e0b0}

        /* CTA */
        .cta{background:radial-gradient(700px 300px at 85% 0%,rgba(124,179,66,.25),transparent 60%),
            linear-gradient(135deg,var(--blue-dark),var(--blue-deep));color:#fff;border-radius:24px;
            padding:52px 40px;text-align:center;margin:70px auto;max-width:1180px}
        .cta h2{color:#fff;font-size:30px;font-weight:700}
        .cta p{color:#cfe0f2;font-size:17px;margin:12px auto 24px;max-width:560px}
        .cta-actions{display:flex;gap:14px;justify-content:center;flex-wrap:wrap}

        /* Footer */
        footer.site{background:var(--blue-deep);color:#c3d2e4;padding:58px 0 0}
        .foot-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1.4fr;gap:36px;padding-bottom:40px}
        footer.site h4{color:#fff;font-size:16px;margin-bottom:18px;font-weight:600}
        .foot-logo{background:#fff;padding:10px 14px;border-radius:12px;display:inline-block;margin-bottom:16px}
        .foot-logo img{height:38px}
        footer.site p{font-size:14.5px;color:#a9bcd4}
        .foot-links{list-style:none;display:grid;gap:11px}
        .foot-links a{font-size:14.5px;color:#c3d2e4;transition:color .18s,padding .18s}
        .foot-links a:hover{color:#fff;padding-left:4px}
        .foot-contact{list-style:none}
        .foot-contact li{display:flex;gap:10px;font-size:14.5px;margin-bottom:12px;color:#c3d2e4}
        .foot-contact i{color:var(--green);margin-top:4px}
        .foot-social{margin-top:16px;display:flex;gap:10px}
        .foot-social a{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);
            display:flex;align-items:center;justify-content:center;transition:background .18s,transform .18s}
        .foot-social a:hover{background:var(--green);transform:translateY(-3px)}
        .foot-bottom{border-top:1px solid rgba(255,255,255,.12);padding:20px 0;text-align:center;font-size:13.5px;color:#94a9c4}

        /* Modal */
        .modal-ov{position:fixed;inset:0;background:rgba(15,47,87,.55);backdrop-filter:blur(4px);z-index:1000;
            display:none;align-items:center;justify-content:center;padding:20px}
        .modal-ov.open{display:flex}
        .modal-card{background:#fff;border-radius:20px;width:100%;max-width:410px;overflow:hidden;
            box-shadow:0 40px 90px -30px rgba(0,0,0,.6);animation:pop .25s ease}
        @keyframes pop{from{opacity:0;transform:translateY(14px) scale(.97)}to{opacity:1;transform:none}}
        .modal-head{background:linear-gradient(135deg,var(--blue),var(--blue-dark));color:#fff;padding:26px 28px;position:relative}
        .modal-head h3{color:#fff;font-size:22px}
        .modal-head p{color:#d5e5f7;font-size:14px;margin-top:4px}
        .modal-close{position:absolute;top:16px;right:18px;background:rgba(255,255,255,.2);border:0;color:#fff;
            width:32px;height:32px;border-radius:50%;font-size:18px;cursor:pointer;line-height:1}
        .modal-close:hover{background:rgba(255,255,255,.35)}
        .modal-body{padding:26px 28px 30px}
        .form-field{margin-bottom:16px}
        .form-field label{display:block;font-size:13px;font-weight:600;color:var(--ink);margin-bottom:6px;font-family:'Poppins',sans-serif}
        .input-wrap{position:relative}
        .input-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--slate)}
        .form-field input[type=text],.form-field input[type=password]{width:100%;padding:12px 14px 12px 40px;
            border:1.5px solid var(--line);border-radius:10px;font-size:15px;font-family:inherit;outline:none;
            transition:border-color .18s,box-shadow .18s}
        .form-field input:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(30,95,168,.15)}
        .form-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;font-size:13.5px}
        .form-row label{display:flex;align-items:center;gap:7px;color:var(--slate);cursor:pointer}
        .form-row a{color:var(--blue);font-weight:600}
        .modal-body .btn-primary{width:100%;justify-content:center}
        .modal-foot{text-align:center;margin-top:18px;font-size:14px;color:var(--slate)}
        .modal-foot a{color:var(--green-dark);font-weight:700}

        .reveal{opacity:0;transform:translateY(24px);transition:opacity .6s ease,transform .6s ease}
        .reveal.in{opacity:1;transform:none}

        @media(max-width:960px){
            .phero h1{font-size:36px}
            .story .wrap{grid-template-columns:1fr;gap:34px}
            .story-media{max-width:560px;margin:0 auto}
            .feat-grid{grid-template-columns:repeat(2,1fr)}
            .connect .wrap{grid-template-columns:1fr}
            .foot-grid{grid-template-columns:1fr 1fr}
        }
        @media(max-width:680px){
            .nav-links,.topbar-info span.sep{display:none}
            .nav-toggle{display:block}
            .nav-links.open{display:flex;position:absolute;top:74px;left:0;right:0;flex-direction:column;
                background:#fff;padding:18px 24px;gap:16px;border-bottom:1px solid var(--line);box-shadow:var(--shadow-sm)}
            .phero h1{font-size:29px}
            .feat-grid{grid-template-columns:1fr}
            .foot-grid{grid-template-columns:1fr}
            .section-head h2,.story h2,.cta h2{font-size:25px}
            .cta{padding:40px 24px;margin:52px 16px}
        }
        </style>
    </head>
    <body>
        <!-- Top bar -->
        <div class="topbar">
            <div class="wrap">
                <div class="topbar-info">
                    <span><i class="fa fa-phone"></i>+91 8108107496 / +91 8208902649</span>
                    <span class="sep"><i class="fa fa-envelope"></i>info@easylogicstechnology.com</span>
                </div>
                <div class="topbar-social">
                    <a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <header class="nav" id="siteNav">
            <div class="wrap">
                <a class="nav-logo" href="/"><img src="/images/easylogistic-7-6.png" alt="Easy Logics Technology"></a>
                <nav>
                    <ul class="nav-links" id="navLinks">
                        <li><a href="/">Home</a></li>
                        <li><a href="/#features">Features</a></li>
                        <li><a href="/#solutions">Solutions</a></li>
                        <li><a href="about_us.php" class="active">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
                <div class="nav-cta">
                    <a class="btn btn-primary" href="javascript:void(0)" onclick="openLogin()"><i class="fa fa-sign-in"></i> Login</a>
                    <button class="nav-toggle" onclick="toggleNav()" aria-label="Menu"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </header>

        <!-- Page hero -->
        <section class="phero">
            <div class="wrap">
                <span class="eyebrow">About Us</span>
                <h1>Simplifying society management for everyone</h1>
                <p>Easy Logics Technology is a GST-compliant society accounting &amp; management platform for managing committees, property managers, accountants and auditors &mdash; seamlessly integrated end to end.</p>
                <div class="crumbs"><a href="/">Home</a> &nbsp;/&nbsp; About Us</div>
            </div>
            <svg class="phero-wave" viewBox="0 0 1440 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" d="M0,32 C360,68 720,4 1080,20 C1260,28 1380,40 1440,34 L1440,60 L0,60 Z"></path>
            </svg>
        </section>

        <!-- Story -->
        <section class="story">
            <div class="wrap">
                <div class="story-text reveal">
                    <span class="eyebrow">Who we are</span>
                    <h2>Built for the way <span class="accent">societies</span> really run</h2>
                    <p>Managing a housing society means juggling members, billing, accounts, registers and compliance &mdash; often across spreadsheets and paper. We built Easy Logics Technology to bring all of it onto one seamless, integrated platform.</p>
                    <p>From maintenance billing to double-entry accounting, statutory registers and GST-compliant reporting, our software helps committees, property managers, accountants and auditors work faster, stay accurate and remain audit-ready all year round.</p>
                    <ul class="story-points">
                        <li><i class="fa fa-check-circle"></i> One integrated platform &mdash; no double entry, no reconciliation headaches.</li>
                        <li><i class="fa fa-check-circle"></i> GST compliant billing and reporting, out of the box.</li>
                        <li><i class="fa fa-check-circle"></i> Designed with real committees, accountants and auditors.</li>
                    </ul>
                </div>
                <div class="story-media reveal"><img src="/images/society2.jpg" alt="Housing society"></div>
            </div>
        </section>

        <!-- What we do -->
        <section class="whatwedo">
            <div class="wrap">
                <div class="section-head reveal">
                    <span class="eyebrow">What we do</span>
                    <h2>Everything a society needs, in one place</h2>
                    <p>Six tightly integrated modules that cover the full lifecycle of society management.</p>
                </div>
                <div class="feat-grid">
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-database"></i></div>
                        <h3>Society Data Management</h3>
                        <p>A single source of truth for members, flats, wings and vehicles with full ownership history.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-calculator"></i></div>
                        <h3>Accounting Management</h3>
                        <p>Double-entry accounting with ledgers, vouchers and a trial balance that always ties out.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-file-text"></i></div>
                        <h3>Maintenance Billing</h3>
                        <p>Periodic bills and debit notes, with interest, penalty and rebates as per society rules.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-bullhorn"></i></div>
                        <h3>Notices &amp; Circulars</h3>
                        <p>Publish notices to all members instantly and keep a record of every communication.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-book"></i></div>
                        <h3>Manage Registers</h3>
                        <p>Statutory registers &mdash; members, share, nomination and property &mdash; always inspection-ready.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-check-square-o"></i></div>
                        <h3>Manage Compliance</h3>
                        <p>GST-compliant records, statutory reports and compliance tracking built into every module.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Founder + Contact -->
        <section class="connect" id="contact">
            <div class="wrap">
                <!-- Founder / office card -->
                <div class="card reveal">
                    <div class="founder-badge">
                        <div class="founder-avatar">AC</div>
                        <div>
                            <h3>Akhilesh Choudhary</h3>
                            <span>Founder &bull; GNIT, Mumbai</span>
                        </div>
                    </div>
                    <ul class="info-list">
                        <li>
                            <div class="info-ic"><i class="fa fa-map-marker"></i></div>
                            <div><div class="lbl">Office Address</div>
                            <div class="val">Maxventur Office N.19,<br>Near Central Park, Nallasopara (E)</div></div>
                        </li>
                        <li>
                            <div class="info-ic"><i class="fa fa-phone"></i></div>
                            <div><div class="lbl">Phone</div>
                            <div class="val">+91 8108107496 / +91 8208902649</div></div>
                        </li>
                        <li>
                            <div class="info-ic"><i class="fa fa-envelope"></i></div>
                            <div><div class="lbl">Email</div>
                            <div class="val">info@easylogicstechnology.com</div></div>
                        </li>
                    </ul>
                </div>

                <!-- Verify / captcha card -->
                <div class="card reveal">
                    <h2>Verify you're human</h2>
                    <p class="sub">Enter the characters shown below to continue.</p>
                    <?php
                        $capMsg = '';
                        if (isset($_POST['verify'])) {
                            if (!empty($_POST['captcha'])) {
                                if (isset($_SESSION['captcha']) && $_SESSION['captcha'] == $_POST['captcha']) {
                                    $capMsg = "<span class='cap-status cap-ok'><i class='fa fa-check-circle'></i> Captcha validated</span>";
                                } else {
                                    $capMsg = "<span class='cap-status cap-err'><i class='fa fa-times-circle'></i> Invalid captcha!</span>";
                                }
                            } else {
                                $capMsg = "<span class='cap-status cap-warn'><i class='fa fa-exclamation-triangle'></i> Please fill up the required field!</span>";
                            }
                        }
                        echo $capMsg;
                    ?>
                    <form class="captcha-box" action="about_us.php#contact" method="POST">
                        <div class="captcha-row">
                            <img src="captcha.php" alt="Captcha">
                            <input class="cap-input" type="text" name="captcha" placeholder="Enter captcha" autocomplete="off">
                        </div>
                        <button name="verify" value="1" class="btn btn-accent"><i class="fa fa-shield"></i> Verify</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <div class="cta reveal">
            <h2>Ready to simplify your society management?</h2>
            <p>Join the committees and accountants already running billing, accounting and compliance on one seamless platform.</p>
            <div class="cta-actions">
                <a class="btn btn-accent" href="javascript:void(0)" onclick="openLogin()"><i class="fa fa-rocket"></i> Get Started</a>
                <a class="btn btn-ghost" href="/">Back to Home</a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="site">
            <div class="wrap">
                <div class="foot-grid">
                    <div>
                        <span class="foot-logo"><img src="/images/easylogistic-7-6.png" alt="Easy Logics Technology"></span>
                        <p>GST-compliant society accounting &amp; management software for committees, property managers, accountants and auditors &mdash; on one integrated platform.</p>
                        <div class="foot-social">
                            <a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
                            <a href="#" aria-label="Twitter"><i class="fa fa-twitter"></i></a>
                        </div>
                    </div>
                    <div>
                        <h4>Product</h4>
                        <ul class="foot-links">
                            <li><a href="/#features">Features</a></li>
                            <li><a href="/#solutions">Solutions</a></li>
                            <li><a href="/#why">Why Us</a></li>
                            <li><a href="javascript:void(0)" onclick="openLogin()">Login</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4>Company</h4>
                        <ul class="foot-links">
                            <li><a href="about_us.php">About Us</a></li>
                            <li><a href="https://easylogicstechnology.com/terms/termandcondition" target="_blank" rel="noopener">Terms &amp; Conditions</a></li>
                            <li><a href="#contact">Contact Us</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4>Get in touch</h4>
                        <ul class="foot-contact">
                            <li><i class="fa fa-map-marker"></i><span>Maxventur Office N.19, Near Central Park, Nallasopara (E)</span></li>
                            <li><i class="fa fa-phone"></i><span>+91 8108107496 / +91 8208902649</span></li>
                            <li><i class="fa fa-envelope"></i><span>info@easylogicstechnology.com</span></li>
                        </ul>
                    </div>
                </div>
                <div class="foot-bottom">&copy; <?php echo date('Y'); ?> Easy Logics Technology. All Rights Reserved.</div>
            </div>
        </footer>

        <!-- Login modal -->
        <div class="modal-ov" id="loginModal" onclick="if(event.target===this)closeLogin()">
            <div class="modal-card" role="dialog" aria-modal="true" aria-label="Login">
                <div class="modal-head">
                    <button class="modal-close" onclick="closeLogin()" aria-label="Close">&times;</button>
                    <h3>Welcome back</h3>
                    <p>Sign in to your Easy Logics account</p>
                </div>
                <div class="modal-body">
                    <form class="form" action="/users/login" method="post" id="login_form">
                        <div class="form-field">
                            <label for="username">Username</label>
                            <div class="input-wrap"><i class="fa fa-user"></i>
                                <input type="text" id="username" name="data[User][username]" placeholder="Enter your username" required autocomplete="username"></div>
                        </div>
                        <div class="form-field">
                            <label for="password">Password</label>
                            <div class="input-wrap"><i class="fa fa-lock"></i>
                                <input type="password" id="password" name="data[User][password]" placeholder="Enter your password" required autocomplete="current-password"></div>
                        </div>
                        <div class="form-row">
                            <label><input type="checkbox" name="data[User][remember_me]" value="1"> Keep me logged in</label>
                            <a href="javascript:void(0)">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Sign in</button>
                    </form>
                    <div class="modal-foot">New to Easy Logics? <a href="/users/login">Register now</a></div>
                </div>
            </div>
        </div>

        <script>
        function openLogin(){document.getElementById('loginModal').classList.add('open');document.body.style.overflow='hidden';}
        function closeLogin(){document.getElementById('loginModal').classList.remove('open');document.body.style.overflow='';}
        function toggleNav(){document.getElementById('navLinks').classList.toggle('open');}
        document.addEventListener('keydown',function(e){if(e.key==='Escape')closeLogin();});
        var nav=document.getElementById('siteNav');
        window.addEventListener('scroll',function(){if(window.scrollY>10)nav.classList.add('scrolled');else nav.classList.remove('scrolled');});
        document.querySelectorAll('#navLinks a').forEach(function(a){a.addEventListener('click',function(){document.getElementById('navLinks').classList.remove('open');});});
        var io=new IntersectionObserver(function(entries){entries.forEach(function(en){if(en.isIntersecting){en.target.classList.add('in');io.unobserve(en.target);}});},{threshold:.12});
        document.querySelectorAll('.reveal').forEach(function(el){io.observe(el);});
        </script>
    </body>
</html>
