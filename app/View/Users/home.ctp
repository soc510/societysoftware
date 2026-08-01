<?php
/**
 * Landing page for Easy Logics Technology
 * Society management & GST-compliant accounting software.
 *
 * Self-contained modern redesign. Preserves the CakePHP login flow
 * (form posts to users/login) and the session flash for login errors.
 */
$loginUrl = Router::url(array('controller' => 'users', 'action' => 'login'));
// Capture the flash once (reading it also clears it) so we can both show it
// inside the login modal and decide whether to auto-open the modal.
$flashHtml = $this->Session->flash();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>Easy Logics Technology &mdash; GST-Compliant Society Accounting Software</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="GST-compliant society accounting &amp; management software for managing committees, property managers, accountants and auditors. Billing, ledgers, registers and compliance on one platform.">
        <link rel="icon" type="image/png" href="images/fvcon.png">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
        <style>
        :root{
            --blue:#1e5fa8;
            --blue-dark:#164a85;
            --blue-deep:#0f2f57;
            --green:#7cb342;
            --green-dark:#5f9130;
            --ink:#12263f;
            --slate:#5b6b82;
            --line:#e6ecf3;
            --bg:#f6f9fc;
            --white:#ffffff;
            --shadow:0 18px 40px -18px rgba(15,47,87,.35);
            --shadow-sm:0 8px 24px -12px rgba(15,47,87,.28);
            --radius:16px;
        }
        *{box-sizing:border-box;margin:0;padding:0}
        html{scroll-behavior:smooth}
        body{
            font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;
            color:var(--ink);background:var(--white);line-height:1.6;
            -webkit-font-smoothing:antialiased;overflow-x:hidden;
        }
        h1,h2,h3,h4,.brandfont{font-family:'Poppins',sans-serif;line-height:1.18;color:var(--ink)}
        a{text-decoration:none;color:inherit}
        img{max-width:100%;display:block}
        .wrap{max-width:1180px;margin:0 auto;padding:0 24px}
        .eyebrow{font-family:'Poppins',sans-serif;font-weight:600;font-size:13px;letter-spacing:.14em;
            text-transform:uppercase;color:var(--blue)}
        .accent{color:var(--green)}

        /* Buttons */
        .btn{display:inline-flex;align-items:center;gap:9px;font-family:'Poppins',sans-serif;font-weight:600;
            font-size:15px;padding:13px 26px;border-radius:999px;border:0;cursor:pointer;
            transition:transform .18s ease,box-shadow .18s ease,background .18s ease;white-space:nowrap}
        .btn-primary{background:linear-gradient(135deg,var(--blue),var(--blue-dark));color:#fff;
            box-shadow:0 12px 26px -12px rgba(30,95,168,.75)}
        .btn-primary:hover{transform:translateY(-2px);box-shadow:0 18px 32px -12px rgba(30,95,168,.85)}
        .btn-accent{background:linear-gradient(135deg,var(--green),var(--green-dark));color:#fff;
            box-shadow:0 12px 26px -12px rgba(124,179,66,.7)}
        .btn-accent:hover{transform:translateY(-2px)}
        .btn-ghost{background:rgba(255,255,255,.14);color:#fff;border:1px solid rgba(255,255,255,.4)}
        .btn-ghost:hover{background:rgba(255,255,255,.24)}
        .btn-outline{background:#fff;color:var(--blue);border:1.5px solid var(--line)}
        .btn-outline:hover{border-color:var(--blue);transform:translateY(-2px)}

        /* Top bar */
        .topbar{background:var(--blue-deep);color:#dbe6f3;font-size:13.5px}
        .topbar .wrap{display:flex;justify-content:space-between;align-items:center;gap:16px;
            min-height:40px;flex-wrap:wrap}
        .topbar-info{display:flex;gap:22px;flex-wrap:wrap;align-items:center}
        .topbar-info span{display:inline-flex;align-items:center;gap:7px}
        .topbar-info i{color:var(--green)}
        .topbar-social a{display:inline-flex;width:28px;height:28px;align-items:center;justify-content:center;
            border-radius:50%;background:rgba(255,255,255,.1);margin-left:6px;transition:background .18s,transform .18s}
        .topbar-social a:hover{background:var(--green);transform:translateY(-2px)}

        /* Nav */
        header.nav{position:sticky;top:0;z-index:900;background:rgba(255,255,255,.97);
            backdrop-filter:saturate(160%) blur(12px);border-bottom:1px solid var(--line);
            transition:box-shadow .25s,background .25s}
        header.nav.scrolled{background:#fff;box-shadow:var(--shadow-sm)}
        .nav .wrap{display:flex;align-items:center;justify-content:space-between;min-height:82px;gap:16px}
        .nav-logo img{height:58px;width:auto}
        .nav-links{display:flex;align-items:center;gap:30px;list-style:none}
        .nav-links a{font-family:'Poppins',sans-serif;font-weight:600;font-size:15px;color:var(--ink);
            position:relative;padding:6px 0;transition:color .18s}
        .nav-links a::after{content:"";position:absolute;left:0;bottom:0;height:2px;width:0;
            background:var(--green);transition:width .22s}
        .nav-links a:hover{color:var(--blue)}
        .nav-links a:hover::after{width:100%}
        .nav-cta{display:flex;align-items:center;gap:12px}
        .nav-toggle{display:none;background:none;border:0;font-size:24px;color:var(--ink);cursor:pointer}

        /* Hero */
        .hero{position:relative;color:#eaf2fb;overflow:hidden;
            background-image:
                linear-gradient(100deg,rgba(11,34,64,.96) 0%,rgba(18,58,105,.9) 38%,rgba(23,74,133,.62) 66%,rgba(30,95,168,.32) 100%),
                url('images/society2.jpg');
            background-size:cover;background-position:center right;background-repeat:no-repeat}
        .hero::before{content:"";position:absolute;inset:0;pointer-events:none;
            background:radial-gradient(760px 420px at 88% 12%,rgba(124,179,66,.2),transparent 60%);}
        .hero .wrap{position:relative;min-height:600px;display:flex;flex-direction:column;
            justify-content:center;padding:96px 24px 108px}
        .hero-text{max-width:600px}
        .hero-eyebrow{color:#bfe08a;background:rgba(124,179,66,.16);border:1px solid rgba(124,179,66,.4);
            display:inline-flex;align-items:center;gap:8px;padding:7px 15px;border-radius:999px;
            font-size:12.5px;letter-spacing:.1em;text-transform:uppercase;font-weight:600;
            font-family:'Poppins',sans-serif}
        .hero h1{color:#fff;font-size:47px;font-weight:800;margin:20px 0 18px;letter-spacing:-.5px}
        .hero h1 .accent{color:#a9dd6b}
        .hero p.lead{font-size:18px;color:#cfe0f2;max-width:560px}
        .hero-actions{display:flex;gap:14px;flex-wrap:wrap;margin-top:30px}
        .hero-trust{display:flex;gap:26px;flex-wrap:wrap;margin-top:34px;font-size:14px;color:#cfe0f2}
        .hero-trust span{display:inline-flex;align-items:center;gap:8px}
        .hero-trust i{color:var(--green)}
        .hero-badge{position:absolute;z-index:2;background:rgba(255,255,255,.96);color:var(--ink);
            border-radius:14px;padding:14px 18px;box-shadow:0 20px 45px -18px rgba(0,0,0,.55);
            display:flex;align-items:center;gap:12px;backdrop-filter:blur(4px)}
        .hero-badge i{font-size:22px}
        .hero-badge .hb-num{font-family:'Poppins',sans-serif;font-weight:700;font-size:18px;line-height:1}
        .hero-badge .hb-lbl{font-size:12px;color:var(--slate)}
        .hero-badge.b1{right:40px;bottom:150px}
        .hero-badge.b1 i{color:var(--green)}
        .hero-badge.b2{right:150px;top:150px}
        .hero-badge.b2 i{color:var(--blue)}
        .hero-wave{display:block;width:100%;height:auto;position:relative;margin-top:-1px}

        /* Stats */
        /* At a glance */
        .glance{background:linear-gradient(180deg,#eef4fc 0%,#e7eefb 100%)}
        .glance .section-head{margin-bottom:40px}
        .glance-row{display:grid;grid-template-columns:repeat(6,1fr);background:rgba(255,255,255,.75);
            border:1px solid #dfe8f5;border-radius:22px;box-shadow:0 20px 50px -26px rgba(15,47,87,.35);
            overflow:hidden;backdrop-filter:blur(4px)}
        .glance-item{text-align:center;padding:34px 16px;border-right:1px solid #eaeff7;
            transition:background .2s}
        .glance-item:last-child{border-right:0}
        .glance-item:hover{background:#fff}
        .glance-emoji{font-size:34px;line-height:1;margin-bottom:14px}
        .glance-num{font-family:'Poppins',sans-serif;font-weight:800;font-size:31px;line-height:1;
            background:linear-gradient(135deg,#f6a01c,#ef7d17);-webkit-background-clip:text;
            background-clip:text;-webkit-text-fill-color:transparent}
        .glance-lbl{font-size:13.5px;color:var(--slate);margin-top:9px;line-height:1.4}

        /* Section base */
        section{padding:76px 0}
        .section-head{text-align:center;max-width:680px;margin:0 auto 52px}
        .section-head h2{font-size:34px;font-weight:700;margin:12px 0 12px}
        .section-head p{color:var(--slate);font-size:17px}

        /* Features grid */
        .features{background:var(--bg)}
        .feat-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px}
        .feat-card{background:#fff;border:1px solid var(--line);border-radius:var(--radius);
            padding:30px 26px;transition:transform .2s,box-shadow .2s,border-color .2s}
        .feat-card:hover{transform:translateY(-6px);box-shadow:var(--shadow);border-color:transparent}
        .feat-icon{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;
            justify-content:center;font-size:24px;color:#fff;margin-bottom:18px;
            background:linear-gradient(135deg,var(--blue),var(--blue-dark))}
        .feat-card:nth-child(2) .feat-icon,
        .feat-card:nth-child(4) .feat-icon,
        .feat-card:nth-child(6) .feat-icon{background:linear-gradient(135deg,var(--green),var(--green-dark))}
        .feat-card h3{font-size:19px;font-weight:600;margin-bottom:8px}
        .feat-card p{color:var(--slate);font-size:15px}

        /* Alternating detail rows */
        .detail{border-bottom:1px solid var(--line)}
        .detail .wrap{display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center}
        .detail.rev .detail-text{order:2}
        .detail-media{position:relative}
        .detail-media img{border-radius:var(--radius);border:1px solid var(--line);box-shadow:var(--shadow)}
        .detail-text h2{font-size:30px;font-weight:700;margin:12px 0 16px}
        .detail-list{list-style:none;margin-top:18px;display:grid;gap:14px}
        .detail-list li{display:flex;gap:12px;align-items:flex-start;color:#3a4a60;font-size:15.5px}
        .detail-list i{color:var(--green);margin-top:3px;font-size:16px;flex-shrink:0}

        /* Why band */
        .why{background:var(--bg)}
        .why-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:22px}
        .why-item{text-align:center;padding:8px}
        .why-item .wi-ic{width:64px;height:64px;border-radius:50%;margin:0 auto 16px;
            display:flex;align-items:center;justify-content:center;font-size:26px;color:var(--blue);
            background:rgba(30,95,168,.1)}
        .why-item h3{font-size:17px;font-weight:600;margin-bottom:6px}
        .why-item p{color:var(--slate);font-size:14.5px}

        /* CTA band */
        .cta{background:
            radial-gradient(700px 300px at 85% 0%,rgba(124,179,66,.25),transparent 60%),
            linear-gradient(135deg,var(--blue-dark),var(--blue-deep));color:#fff;
            border-radius:24px;padding:56px 40px;text-align:center;margin:76px auto;max-width:1180px}
        .cta h2{color:#fff;font-size:32px;font-weight:700}
        .cta p{color:#cfe0f2;font-size:17px;margin:12px auto 26px;max-width:560px}
        .cta .hero-actions{justify-content:center}

        /* Footer */
        footer.site{background:var(--blue-deep);color:#c3d2e4;padding:60px 0 0}
        .foot-grid{display:grid;grid-template-columns:1.6fr 1fr 1fr 1.4fr;gap:36px;padding-bottom:40px}
        footer.site h4{color:#fff;font-size:16px;margin-bottom:18px;font-weight:600}
        .foot-logo{background:#fff;padding:12px 18px;border-radius:14px;display:inline-block;margin-bottom:16px}
        .foot-logo img{height:54px}
        footer.site p{font-size:14.5px;color:#a9bcd4}
        .foot-links{list-style:none;display:grid;gap:11px}
        .foot-links a{font-size:14.5px;color:#c3d2e4;transition:color .18s,padding .18s}
        .foot-links a:hover{color:#fff;padding-left:4px}
        .foot-contact li{display:flex;gap:10px;font-size:14.5px;margin-bottom:12px;color:#c3d2e4}
        .foot-contact i{color:var(--green);margin-top:4px}
        .foot-social{margin-top:16px;display:flex;gap:10px}
        .foot-social a{width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.1);
            display:flex;align-items:center;justify-content:center;transition:background .18s,transform .18s}
        .foot-social a:hover{background:var(--green);transform:translateY(-3px)}
        .foot-bottom{border-top:1px solid rgba(255,255,255,.12);padding:20px 0;text-align:center;
            font-size:13.5px;color:#94a9c4}

        /* Modal */
        .modal-ov{position:fixed;inset:0;background:rgba(15,47,87,.55);backdrop-filter:blur(4px);
            z-index:1000;display:none;align-items:center;justify-content:center;padding:20px}
        .modal-ov.open{display:flex}
        .modal-card{background:#fff;border-radius:20px;width:100%;max-width:410px;overflow:hidden;
            box-shadow:0 40px 90px -30px rgba(0,0,0,.6);animation:pop .25s ease}
        @keyframes pop{from{opacity:0;transform:translateY(14px) scale(.97)}to{opacity:1;transform:none}}
        .modal-head{background:linear-gradient(135deg,var(--blue),var(--blue-dark));color:#fff;
            padding:26px 28px;position:relative}
        .modal-head h3{color:#fff;font-size:22px}
        .modal-head p{color:#d5e5f7;font-size:14px;margin-top:4px}
        .modal-close{position:absolute;top:16px;right:18px;background:rgba(255,255,255,.2);border:0;
            color:#fff;width:32px;height:32px;border-radius:50%;font-size:18px;cursor:pointer;line-height:1}
        .modal-close:hover{background:rgba(255,255,255,.35)}
        .modal-body{padding:26px 28px 30px}
        .form-field{margin-bottom:16px}
        .form-field label{display:block;font-size:13px;font-weight:600;color:var(--ink);margin-bottom:6px;
            font-family:'Poppins',sans-serif}
        .input-wrap{position:relative}
        .input-wrap i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--slate)}
        .form-field input[type=text],.form-field input[type=password]{width:100%;padding:12px 14px 12px 40px;
            border:1.5px solid var(--line);border-radius:10px;font-size:15px;font-family:inherit;
            transition:border-color .18s,box-shadow .18s;outline:none}
        .form-field input:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(30,95,168,.15)}
        .form-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;font-size:13.5px}
        .form-row label{display:flex;align-items:center;gap:7px;color:var(--slate);cursor:pointer}
        .form-row a{color:var(--blue);font-weight:600}
        .modal-body .btn-primary{width:100%;justify-content:center}
        .modal-foot{text-align:center;margin-top:18px;font-size:14px;color:var(--slate)}
        .modal-foot a{color:var(--green-dark);font-weight:700}
        .flash-slot .message,.flash-slot .alert{padding:11px 14px;border-radius:10px;font-size:14px;
            margin-bottom:14px}
        .flash-slot .error,.flash-slot .alert-danger,.flash-slot .cake-error{background:#fdecec;color:#b23b3b;
            border:1px solid #f5c6c6}
        .flash-slot .success,.flash-slot .alert-success{background:#eaf7e0;color:#4a7a1f;border:1px solid #cfe9b6}

        /* Reveal on scroll */
        .reveal{opacity:0;transform:translateY(24px);transition:opacity .6s ease,transform .6s ease}
        .reveal.in{opacity:1;transform:none}

        /* Responsive */
        @media(max-width:960px){
            .hero .wrap{min-height:auto;padding:64px 24px 84px}
            .hero h1{font-size:38px}
            .hero-badge{display:none}
            .hero p.lead,.hero h1{max-width:100%}
            .glance-row{grid-template-columns:repeat(3,1fr)}
            .glance-item:nth-child(3n){border-right:0}
            .glance-item:nth-child(-n+3){border-bottom:1px solid #eaeff7}
            .feat-grid{grid-template-columns:repeat(2,1fr)}
            .detail .wrap{grid-template-columns:1fr;gap:32px}
            .detail.rev .detail-text{order:0}
            .detail-media{max-width:560px;margin:0 auto}
            .why-grid{grid-template-columns:repeat(2,1fr)}
            .foot-grid{grid-template-columns:1fr 1fr}
        }
        @media(max-width:680px){
            .nav-links,.topbar-info span.sep{display:none}
            .nav-toggle{display:block}
            .nav .wrap{min-height:66px}
            .nav-logo img{height:46px}
            .nav-links.open{display:flex;position:absolute;top:74px;left:0;right:0;flex-direction:column;
                background:#fff;padding:18px 24px;gap:16px;border-bottom:1px solid var(--line);
                box-shadow:var(--shadow-sm)}
            .hero h1{font-size:31px}
            .hero p.lead{font-size:16px}
            .glance-row{grid-template-columns:repeat(2,1fr)}
            .glance-item{border-right:1px solid #eaeff7}
            .glance-item:nth-child(2n){border-right:0}
            .glance-item:nth-child(-n+4){border-bottom:1px solid #eaeff7}
            .glance-num{font-size:27px}
            .feat-grid{grid-template-columns:1fr}
            .why-grid{grid-template-columns:1fr}
            .foot-grid{grid-template-columns:1fr}
            .section-head h2,.detail-text h2,.cta h2{font-size:26px}
            .hero-badge{display:none}
            .cta{padding:40px 24px;margin:56px 16px}
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
                <a class="nav-logo" href="index.php"><img src="images/easylogistic-7-6.png" alt="Easy Logics Technology"></a>
                <nav>
                    <ul class="nav-links" id="navLinks">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#solutions">Solutions</a></li>
                        <li><a href="#why">Why Us</a></li>
                        <li><a href="/about/about_us.php">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </nav>
                <div class="nav-cta">
                    <a class="btn btn-primary" href="javascript:void(0)" onclick="openLogin()"><i class="fa fa-sign-in"></i> Login</a>
                    <button class="nav-toggle" onclick="toggleNav()" aria-label="Menu"><i class="fa fa-bars"></i></button>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="hero">
            <div class="wrap">
                <div class="hero-text">
                    <span class="hero-eyebrow"><i class="fa fa-shield"></i> GST Compliant &bull; Audit Ready</span>
                    <h1>Society Accounting &amp; Management, <span class="accent">made effortless.</span></h1>
                    <p class="lead">A single, integrated platform for managing committees, property managers, accountants and auditors &mdash; from maintenance billing to ledgers, registers and statutory compliance.</p>
                    <div class="hero-actions">
                        <a class="btn btn-accent" href="javascript:void(0)" onclick="openLogin()"><i class="fa fa-rocket"></i> Get Started</a>
                        <a class="btn btn-ghost" href="#features"><i class="fa fa-play-circle"></i> Explore Features</a>
                    </div>
                    <div class="hero-trust">
                        <span><i class="fa fa-check-circle"></i> Double-entry accounting</span>
                        <span><i class="fa fa-check-circle"></i> Cloud based</span>
                        <span><i class="fa fa-check-circle"></i> Secure &amp; reliable</span>
                    </div>
                </div>
                <div class="hero-badge b1">
                    <i class="fa fa-file-text-o"></i>
                    <div><div class="hb-num">Instant</div><div class="hb-lbl">Maintenance bills</div></div>
                </div>
                <div class="hero-badge b2">
                    <i class="fa fa-balance-scale"></i>
                    <div><div class="hb-num">Trial Balance</div><div class="hb-lbl">Always ties out</div></div>
                </div>
            </div>
            <svg class="hero-wave" viewBox="0 0 1440 70" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path fill="#ffffff" d="M0,40 C360,80 720,0 1080,24 C1260,36 1380,48 1440,40 L1440,70 L0,70 Z"></path>
            </svg>
        </section>

        <!-- At a glance : live figures, same source as the admin dashboard -->
        <?php
            // Marketing-friendly formatter: rounds DOWN to a clean figure and adds "+".
            if (!function_exists('elGlanceNum')) {
                function elGlanceNum($n) {
                    $n = (int)$n;
                    if ($n >= 10000000) { $c = floor($n / 1000000) / 10; return rtrim(rtrim(number_format($c, 1), '0'), '.') . ' Cr+'; }
                    if ($n >= 100000)   { return floor($n / 100000) . ' Lakh+'; }
                    if ($n >= 1000)     { return number_format(floor($n / 100) * 100) . '+'; }
                    if ($n >= 100)      { return (floor($n / 10) * 10) . '+'; }
                    return $n > 0 ? $n . '+' : '0';
                }
            }
            $gs = isset($glanceStats) ? $glanceStats : array();
            $gv = function ($k) use ($gs) { return isset($gs[$k]) ? $gs[$k] : 0; };
        ?>
        <section class="glance">
            <div class="wrap">
                <div class="section-head reveal">
                    <span class="eyebrow">Trusted across India</span>
                    <h2>Easy Logics <span class="accent">at a Glance</span></h2>
                </div>
                <div class="glance-row reveal">
                    <div class="glance-item">
                        <div class="glance-emoji">🧾</div>
                        <div class="glance-num"><?php echo elGlanceNum($gv('bills')); ?></div>
                        <div class="glance-lbl">Bills &amp; receipts<br>generated</div>
                    </div>
                    <div class="glance-item">
                        <div class="glance-emoji">👥</div>
                        <div class="glance-num"><?php echo elGlanceNum($gv('members')); ?></div>
                        <div class="glance-lbl">Members on<br>the platform</div>
                    </div>
                    <div class="glance-item">
                        <div class="glance-emoji">🏢</div>
                        <div class="glance-num"><?php echo elGlanceNum($gv('societies')); ?></div>
                        <div class="glance-lbl">Societies<br>onboarded</div>
                    </div>
                    <div class="glance-item">
                        <div class="glance-emoji">🏬</div>
                        <div class="glance-num"><?php echo elGlanceNum($gv('buildings')); ?></div>
                        <div class="glance-lbl">Buildings<br>managed</div>
                    </div>
                    <div class="glance-item">
                        <div class="glance-emoji">🏘️</div>
                        <div class="glance-num"><?php echo elGlanceNum($gv('wings')); ?></div>
                        <div class="glance-lbl">Wings<br>managed</div>
                    </div>
                    <div class="glance-item">
                        <div class="glance-emoji">🤝</div>
                        <div class="glance-num"><?php echo elGlanceNum($gv('resellers')); ?></div>
                        <div class="glance-lbl">Channel partners<br>&amp; associates</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="features" id="features">
            <div class="wrap">
                <div class="section-head reveal">
                    <span class="eyebrow">Everything you need</span>
                    <h2>One platform to run your <span class="accent">society</span></h2>
                    <p>Purpose-built for managing committees, property managers, accountants and auditors &mdash; seamlessly integrated end to end.</p>
                </div>
                <div class="feat-grid">
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-database"></i></div>
                        <h3>Society Data Management</h3>
                        <p>A single source of truth for members, flats, wings and vehicles &mdash; with ownership, tenant and transfer history in one place.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-calculator"></i></div>
                        <h3>Accounting Management</h3>
                        <p>Full double-entry accounting with ledgers, journal vouchers, receipts, payments and a trial balance that always ties out.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-file-text"></i></div>
                        <h3>Maintenance Billing</h3>
                        <p>Generate periodic maintenance bills and debit notes, apply interest or penalty on late payment, and reward prompt payers with rebates.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-bullhorn"></i></div>
                        <h3>Notices &amp; Circulars</h3>
                        <p>Publish notices and circulars to all members instantly and keep a clean record of every communication you send.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-book"></i></div>
                        <h3>Manage Registers</h3>
                        <p>Maintain statutory registers &mdash; members, share, nomination and property &mdash; accurately and always ready for inspection.</p>
                    </div>
                    <div class="feat-card reveal">
                        <div class="feat-icon"><i class="fa fa-check-square-o"></i></div>
                        <h3>Manage Compliance</h3>
                        <p>Stay audit-ready with GST-compliant records, statutory reports and compliance tracking built into every module.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Detail: Billing -->
        <section class="detail" id="solutions">
            <div class="wrap">
                <div class="detail-text reveal">
                    <span class="eyebrow">Society Billing</span>
                    <h2>Bill members in minutes, not days</h2>
                    <p style="color:var(--slate)">Raise accurate maintenance bills for the whole society or for specific purposes, exactly as your bye-laws require.</p>
                    <ul class="detail-list">
                        <li><i class="fa fa-check-circle"></i> Maintenance charges on a regular periodical basis, or purpose-specific debit notes generated with ease.</li>
                        <li><i class="fa fa-check-circle"></i> Interest and penalty automatically charged on delayed payments.</li>
                        <li><i class="fa fa-check-circle"></i> Rebate for prompt payment, as per the rules set by your society.</li>
                    </ul>
                </div>
                <div class="detail-media reveal"><img src="images/billing.png" alt="Maintenance billing screen"></div>
            </div>
        </section>

        <!-- Detail: Accounting -->
        <section class="detail rev">
            <div class="wrap">
                <div class="detail-text reveal">
                    <span class="eyebrow">Accounting &amp; Ledgers</span>
                    <h2>Books that always balance</h2>
                    <p style="color:var(--slate)">Every transaction flows straight into your books, so your financial position is accurate the moment it happens.</p>
                    <ul class="detail-list">
                        <li><i class="fa fa-check-circle"></i> Ledger heads, journal vouchers and general receipts with proper cash and bank tracking.</li>
                        <li><i class="fa fa-check-circle"></i> Trial balance with clear flags whenever it does not tie, so differences are easy to locate.</li>
                        <li><i class="fa fa-check-circle"></i> Receipts, payments, income &amp; expenditure and balance sheet, ready whenever you need them.</li>
                    </ul>
                </div>
                <div class="detail-media reveal"><img src="images/ledger-heads.png" alt="Ledger and accounting screen"></div>
            </div>
        </section>

        <!-- Detail: Reports & Compliance -->
        <section class="detail">
            <div class="wrap">
                <div class="detail-text reveal">
                    <span class="eyebrow">Reports &amp; Compliance</span>
                    <h2>Audit-ready, all year round</h2>
                    <p style="color:var(--slate)">Give your accountants and auditors exactly what they need &mdash; complete, GST-compliant records at their fingertips.</p>
                    <ul class="detail-list">
                        <li><i class="fa fa-check-circle"></i> Member ledgers covering current and old members, so nothing is ever lost on transfer.</li>
                        <li><i class="fa fa-check-circle"></i> Statutory registers and reports maintained as required by law.</li>
                        <li><i class="fa fa-check-circle"></i> GST-compliant billing and reporting across every module.</li>
                    </ul>
                </div>
                <div class="detail-media reveal"><img src="images/High.jpg" alt="Reports and compliance"
                    onerror="this.onerror=null;this.src='images/billing.png';"></div>
            </div>
        </section>

        <!-- Why -->
        <section class="why" id="why">
            <div class="wrap">
                <div class="section-head reveal">
                    <span class="eyebrow">Why Easy Logics</span>
                    <h2>Built for the way societies actually work</h2>
                </div>
                <div class="why-grid">
                    <div class="why-item reveal">
                        <div class="wi-ic"><i class="fa fa-cubes"></i></div>
                        <h3>Truly integrated</h3>
                        <p>Data, billing, accounting and compliance work together &mdash; no double entry, no reconciliation headaches.</p>
                    </div>
                    <div class="why-item reveal">
                        <div class="wi-ic"><i class="fa fa-file-o"></i></div>
                        <h3>GST compliant</h3>
                        <p>Billing and reporting designed to keep your society compliant out of the box.</p>
                    </div>
                    <div class="why-item reveal">
                        <div class="wi-ic"><i class="fa fa-users"></i></div>
                        <h3>For every role</h3>
                        <p>Committees, property managers, accountants and auditors &mdash; each gets the view they need.</p>
                    </div>
                    <div class="why-item reveal">
                        <div class="wi-ic"><i class="fa fa-headphones"></i></div>
                        <h3>Real support</h3>
                        <p>A responsive team on call and email, ready to help you whenever you need it.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <div class="cta reveal">
            <h2>Ready to simplify your society management?</h2>
            <p>Join the committees and accountants already running billing, accounting and compliance on one seamless platform.</p>
            <div class="hero-actions">
                <a class="btn btn-accent" href="javascript:void(0)" onclick="openLogin()"><i class="fa fa-rocket"></i> Get Started</a>
                <a class="btn btn-ghost" href="#contact"><i class="fa fa-phone"></i> Talk to Us</a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="site" id="contact">
            <div class="wrap">
                <div class="foot-grid">
                    <div>
                        <span class="foot-logo"><img src="images/easylogistic-7-6.png" alt="Easy Logics Technology"></span>
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
                            <li><a href="#features">Features</a></li>
                            <li><a href="#solutions">Solutions</a></li>
                            <li><a href="#why">Why Us</a></li>
                            <li><a href="javascript:void(0)" onclick="openLogin()">Login</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4>Company</h4>
                        <ul class="foot-links">
                            <li><a href="/about/about_us.php">About Us</a></li>
                            <li><a href="/terms/termandcondition">Terms &amp; Conditions</a></li>
                            <li><a href="privacy_policy">Privacy Policy</a></li>
                            <li><a href="#contact">Contact Us</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4>Get in touch</h4>
                        <ul class="foot-contact" style="list-style:none">
                            <li><i class="fa fa-phone"></i><span>+91 8108107496<br>+91 8208902649</span></li>
                            <li><i class="fa fa-envelope"></i><span>info@easylogicstechnology.com</span></li>
                        </ul>
                    </div>
                </div>
                <div class="foot-bottom">
                    &copy; <?php echo date('Y'); ?> Easy Logics Technology. All Rights Reserved.
                </div>
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
                    <div class="flash-slot"><?php echo $flashHtml; ?></div>
                    <form class="form" action="<?php echo $loginUrl; ?>" method="post" id="login_form">
                        <div class="form-field">
                            <label for="username">Username</label>
                            <div class="input-wrap">
                                <i class="fa fa-user"></i>
                                <input type="text" id="username" name="data[User][username]" placeholder="Enter your username" required autocomplete="username">
                            </div>
                        </div>
                        <div class="form-field">
                            <label for="password">Password</label>
                            <div class="input-wrap">
                                <i class="fa fa-lock"></i>
                                <input type="password" id="password" name="data[User][password]" placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                        </div>
                        <div class="form-row">
                            <label><input type="checkbox" name="data[User][remember_me]" value="1"> Keep me logged in</label>
                            <a href="javascript:void(0)">Forgot password?</a>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Sign in</button>
                    </form>
                    <div class="modal-foot">New to Easy Logics? <a href="<?php echo $loginUrl; ?>">Register now</a></div>
                </div>
            </div>
        </div>

        <script>
        function openLogin(){document.getElementById('loginModal').classList.add('open');document.body.style.overflow='hidden';}
        function closeLogin(){document.getElementById('loginModal').classList.remove('open');document.body.style.overflow='';}
        function toggleNav(){document.getElementById('navLinks').classList.toggle('open');}
        document.addEventListener('keydown',function(e){if(e.key==='Escape')closeLogin();});

        // Sticky nav shadow
        var nav=document.getElementById('siteNav');
        window.addEventListener('scroll',function(){
            if(window.scrollY>10)nav.classList.add('scrolled');else nav.classList.remove('scrolled');
        });

        // Close mobile menu on link click
        document.querySelectorAll('#navLinks a').forEach(function(a){
            a.addEventListener('click',function(){document.getElementById('navLinks').classList.remove('open');});
        });

        // Reveal on scroll
        var io=new IntersectionObserver(function(entries){
            entries.forEach(function(en){if(en.isIntersecting){en.target.classList.add('in');io.unobserve(en.target);}});
        },{threshold:.12});
        document.querySelectorAll('.reveal').forEach(function(el){io.observe(el);});

        // If login failed, the server set a flash message -> auto-open the modal
        <?php if (!empty($flashHtml)): ?>
        openLogin();
        <?php endif; ?>
        </script>
    </body>
</html>
