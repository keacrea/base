<ul class="vertical menu accordion-menu" data-submenu-toggle="true" data-accordion-menu>
    <li>
        <?= $this->Html->link('Accueil', '/');?>
    </li>
    <?= $this->Tree->nav_responsive($navigationResp); ?>
    <li>
        <?= $this->Html->link('FAQ', ['controller' => 'Topics', 'action' => 'index']);?>
    </li>
    <li>
        <?= $this->Html->link('Conseils', ['controller' => 'Posts', 'action' => 'index']);?>
    </li>
</ul>
<div class="nav-resp__btn">
    <?= $this->Html->link('simulez votre prêt', '/simulez-votre-pret-immobilier', ['class' => 'btn']);?>
</div>
