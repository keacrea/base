<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SettingSitesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SettingSitesTable Test Case
 */
class SettingSitesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SettingSitesTable
     */
    public $SettingSites;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.setting_sites'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SettingSites') ? [] : ['className' => SettingSitesTable::class];
        $this->SettingSites = TableRegistry::getTableLocator()->get('SettingSites', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SettingSites);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
