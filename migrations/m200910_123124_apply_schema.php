<?php

use yii\db\Migration;
use yii\db\pgsql\Schema;
use yii\db\ColumnSchemaBuilder;

/**
 * Handles the applying schema.
 */
class m200910_123124_apply_schema extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //No any simple migrations after deploy.
        //The one migration is only for establishing new environment.
        $schemaFile = Yii::$app->basePath . '/schema.sql';
        $schema = file_get_contents($schemaFile);
        $migrationDelimiter = '@migration delimiter@';

        $queries = explode($migrationDelimiter, $schema);

        $transaction = $this->db->beginTransaction();
        try {
            foreach ($queries as $query){
                $this->db->createCommand($query)->execute();
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200910_123124_apply_schema should not be reverted (reverse migrations are potentially dangerous code).\n";

        return false;
    }
}
