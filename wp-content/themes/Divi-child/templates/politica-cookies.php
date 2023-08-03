<?php /*  Template Name: Politica Cookie  */ ?>
<?php get_header('codru2023live'); ?>

<div class="container termsPage pt-5 pb-5">
    <h1 class="pt-5 pb-4 text-center"><?php echo get_the_title(); ?></h1>
    <div>
        <script>!function(de,cl,ar,at,i,o,n){
        let a=de.createElement(at),b=[/^\/([a-z]{2})([_-][a-z]{2})?(\/.*)?$/g.exec(cl),
        /^([a-z]{2})\./.exec(ar),/\.([a-z]{2,})$/.exec(ar)],c={at:"de",au:"en",ca:"en",cz:
        "cs",dk:"da",ee:"et",no:"nb",se:"sv",uk:"en"},d=de.scripts,e=d[d.length-1],f=0;
        for(f of b){b=f?f[1]:n;if(f)break}b=b.length!=2?o:b;b=c[b]?c[b]:b;a.id=i;a.dataset.
        culture=b;a.src=`https://consent.cookiebot.com/${o}/cd.js`;e.parentNode.
        insertBefore(a,e)}(document,location.pathname,location.hostname,"script",
        "CookieDeclaration","3a0946ec-6993-4f13-ade5-9f5617027b2a","en");</script>
    </div>
</div>


<?php get_footer('codru2023live'); ?>
