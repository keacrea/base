<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    <url>
        <loc><?= \Cake\Routing\Router::url('/', true); ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <?php foreach ($pages as $page): ?>
        <url>
            <loc><?= \Cake\Routing\Router::url(['controller' => 'Pages', 'action' => 'view', "slug" => $page->slug], true); ?></loc>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>

    <?php endforeach; ?>

    <url>
        <loc><?= \Cake\Routing\Router::url(['controller' => 'Contacts', 'action' => 'index'], true); ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>

</urlset>
