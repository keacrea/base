

<li>
    <?= $this->Html->link(
        '<i class="fa fa-newspaper-o"></i> Actualités'
        ,'#',
        [
            'escape' => false,
            'class'  => (in_array($this->request->getParam('controller'),['Thematics', 'Posts', 'Diaries', 'Newspapers'])) ? 'active' : ''
        ]);?>
    <ul class="navigation__sublist">
        <li>
            <?php
            echo $this->Html->link(
                'Articles du blog',
                [
                    'controller' => 'Posts',
                    'action'     => 'index',
                    'prefix'      => 'admin',
                    'plugin' => 'Blog'
                ],
                [
                    'escape' => false,
                    'class'  => ($this->request->getParam('controller') == 'Posts') ? 'active' : ''
                ]
            );
            ?>
        </li>
        <li>
            <?= $this->Html->link(
                'Catégories',
                [
                    'controller' => 'Thematics',
                    'action'     => 'index',
                    'prefix'      => 'admin',
                    'plugin' => 'Blog'
                ],
                [
                    'escape' => false,
                    'class'  => (in_array($this->request->getParam('controller'),['Thematics']))  ? 'active' : ''
                ]);?>
        </li>


    </ul>
</li>
