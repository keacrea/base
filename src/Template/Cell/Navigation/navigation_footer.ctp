<ul class="footer__nav">
    <?php foreach ($navigation_footer as $nav): ?>
        <li>
            <?= $this->Html->link($nav->item, $nav->url); ?>
        </li>
    <?php endforeach; ?>
</ul>
