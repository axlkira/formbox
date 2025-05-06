<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TableCreatorService
{
    /**
     * Crea una tabla física en la base de datos según definición de campos avanzada.
     * @param string $tableName
     * @param array $fields
     *   Cada campo debe tener: name, type, required, is_primary, is_foreign, foreign_table, foreign_column, unique, nullable, default, unsigned, index, auto_increment, enum_values, on_delete, on_update
     * @return void
     * @throws \Exception
     */
    public function createTable($tableName, $fields)
    {
        Schema::create($tableName, function (Blueprint $table) use ($fields) {
            $primary = [];
            // Detectar si el usuario ya definió algún campo como llave primaria
            $hasPrimary = false;
            foreach ($fields as $field) {
                if (!empty($field['is_primary'])) {
                    $hasPrimary = true;
                    break;
                }
            }
            // Si NO hay llave primaria definida, agregar id autoincremental
            if (!$hasPrimary) {
                $table->bigIncrements('id');
                $primary[] = 'id';
            }
            foreach ($fields as $field) {
                $type = $field['type'];
                $name = $field['name'];
                $col = null;
                // Tipos soportados
                switch ($type) {
                    case 'string':
                        $col = $table->string($name, $field['length'] ?? 255);
                        break;
                    case 'text':
                        $col = $table->text($name);
                        break;
                    case 'integer':
                        $col = $table->integer($name, $field['unsigned'] ?? false, $field['auto_increment'] ?? false);
                        break;
                    case 'bigInteger':
                        $col = $table->bigInteger($name, $field['unsigned'] ?? false, $field['auto_increment'] ?? false);
                        break;
                    case 'float':
                        $col = $table->float($name);
                        break;
                    case 'double':
                        $col = $table->double($name);
                        break;
                    case 'decimal':
                        $col = $table->decimal($name, $field['precision'] ?? 8, $field['scale'] ?? 2);
                        break;
                    case 'boolean':
                        $col = $table->boolean($name);
                        break;
                    case 'date':
                        $col = $table->date($name);
                        break;
                    case 'datetime':
                        $col = $table->dateTime($name);
                        break;
                    case 'time':
                        $col = $table->time($name);
                        break;
                    case 'timestamp':
                        $col = $table->timestamp($name);
                        break;
                    case 'json':
                        $col = $table->json($name);
                        break;
                    case 'enum':
                        $col = $table->enum($name, $field['enum_values'] ?? []);
                        break;
                    default:
                        $col = $table->string($name);
                }
                // Opciones avanzadas
                if (!empty($field['nullable'])) $col->nullable();
                if (!empty($field['unique'])) $col->unique();
                if (isset($field['default'])) $col->default($field['default']);
                if (!empty($field['unsigned']) && method_exists($col, 'unsigned')) $col->unsigned();
                if (!empty($field['auto_increment']) && method_exists($col, 'autoIncrement')) $col->autoIncrement();
                if (!empty($field['index'])) $col->index();
                // Llave primaria
                if (!empty($field['is_primary'])) $primary[] = $name;
                // Llave foránea
                if (!empty($field['is_foreign']) && !empty($field['foreign_table']) && !empty($field['foreign_column'])) {
                    $table->foreign($name)
                        ->references($field['foreign_column'])
                        ->on($field['foreign_table'])
                        ->onDelete($field['on_delete'] ?? 'cascade')
                        ->onUpdate($field['on_update'] ?? 'cascade');
                }
            }
            if (count($primary)) {
                $table->primary($primary);
            }
            $table->timestamps();
        });
    }
}
