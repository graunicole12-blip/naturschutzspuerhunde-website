<header class="site-header">
  <div class="site-header-inner">
    <a class="site-logo" href="/index.php">
      <img src="/assets/img/logo.png" alt="Vereinslogo">
      <span>Naturschutzspürhunde</span>
    </a>
    <button class="nav-toggle" id="navToggle" aria-label="Menü öffnen" aria-expanded="false">&#9776;</button>
    <nav class="site-nav" id="siteNav">
      <ul>
        <li><a href="/index.php">Startseite</a></li>
        <li><a href="/ueber-uns.php">Über uns</a></li>
        <li><a href="/naturschutzspuerhunde.php">Naturschutzspürhunde</a></li>
        <li><a href="/projekte.php">Projekte</a></li>
        <li><a href="/unsere-hunde.php">Unsere Hunde</a></li>
        <li><a href="/ausbildung.php">Ausbildung</a></li>
        <li><a href="/unterstuetzen.php">Unterstützen</a></li>
        <li><a href="/news.php">News</a></li>
        <li><a href="/kontakt.php">Kontakt</a></li>
      </ul>
    </nav>
  </div>
</header>
<script>
  document.getElementById('navToggle').addEventListener('click', function () {
    var nav = document.getElementById('siteNav');
    var expanded = nav.classList.toggle('open');
    this.setAttribute('aria-expanded', expanded ? 'true' : 'false');
  });
</script>
