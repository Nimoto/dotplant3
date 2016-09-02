<?php

use app\helpers\PermissionsHelper;
use yii\db\Migration;

class m160902_100019_navigation extends Migration
{

    public static $permissionsConfig = [
        'NavigationAdministrator' => [
            'descr' => 'Backend Menu Administration Role',
            'permits' => [
                'core-navigation-view' => 'View navigation grid',
                'core-navigation-edit' => 'Edit navigation',
                'core-navigation-create' => 'Create navigation',
                'core-navigation-delete' => 'Delete navigation',
            ],
        ],
    ];

    public function up()
    {

        mb_internal_encoding("UTF-8");
        $tableOptions = $this->db->driverName === 'mysql'
            ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
            : null;

        $this->createTable(
            '{{%navigation%}}',
            [
                'id' => $this->primaryKey(),
                'parent_id' => $this->integer()->notNull()->defaultValue(0),
                'url' => $this->string(),
                'structure_id' => $this->integer(),
                'packed_json_params' => $this->text(),
                'css_class' => $this->string(),
                'rbac_check' => $this->string(),
                'sort_order' => $this->integer()->notNull()->defaultValue(0),
                'active' => $this->integer(1)->unsigned()->notNull()->defaultValue(1),
                'is_deleted' => $this->integer(1)->unsigned()->notNull()->defaultValue(0)
            ]
        );

        $this->createIndex(
            'navigation_parent_id',
            '{{%navigation%}}',
            [
                'parent_id',
                'active',
                'is_deleted'
            ]
        );

        // translations
        $this->createTable(
            '{{%navigation_translation}}',
            [
                'model_id' => $this->integer()->notNull(),
                'language_id' => $this->integer()->notNull(),
                'label' => $this->string()->notNull(),
            ],
            $tableOptions
        );

        $this->addForeignKey(
            'fkNavigationT',
            '{{%navigation_translation}}',
            ['model_id'],
            '{{%navigation}}',
            ['id'],
            'CASCADE'
        );
        PermissionsHelper::createPermissions(self::$permissionsConfig);
        $auth = Yii::$app->authManager;
        if (null !== $adminRole = $auth->getRole('CoreAdministrator')) {
            $auth->addChild($adminRole, $auth->getRole('NavigationAdministrator'));
        }
    }

    public function down()
    {
        $this->db->createCommand("SET foreign_key_checks = 0")->execute();
        $this->dropTable('{{%navigation_translation%}}');
        $this->dropTable('{{%navigation%}}');
        $this->db->createCommand("SET foreign_key_checks = 1")->execute();
        PermissionsHelper::removePermissions(self::$permissionsConfig);
    }
}
